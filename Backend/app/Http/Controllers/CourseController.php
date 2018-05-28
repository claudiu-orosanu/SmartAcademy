<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Course;
use App\Document;
use App\Exam;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ReviewResource;
use App\Question;
use App\Section;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Create a new CourseController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CourseCollection
     */
    public function index(Request $request)
    {
        //return CourseResource::collection(Course::all());

        // initialize course model
        $courseModel = new Course();

        // filter by category
        if($request->query('categories') !== null){
            $courseModel = $courseModel->whereIn('category', $request->query('categories'));
        }

        // search by name
        if($request->query('search') !== null){
            $searchTerm = '%'. $request->query('search') . '%';
            $courseModel = $courseModel->where('name', 'ILIKE', $searchTerm);
        }

        // find and return paginated courses
        $paginatedCourses = $courseModel->paginate(
                $request->query('itemsPerPage'),
                ['*'],
                'page',
                $request->query('page'));

        return new CourseCollection($paginatedCourses);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // is user teacher?
        if($user->hasRole('student')) {
            return response([
                'error' => 'Only teachers can create courses.'
            ], 400);
        }

        // validate request
        $maxSize = (int)ini_get('upload_max_filesize') * 1000;

        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'description' => 'required|min:3|max:255',
            'category' => 'required',
            'price' => 'required',
            'image' => 'required|file|image|max:' . $maxSize
        ]);

        // store course image in public folder
        $imageFile = $request->file('image');
        $imagePath = Storage::disk('public')->putFile('courseImages', $imageFile);
        $imageUrl = Storage::url($imagePath);

        // create course
        $course = Course::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'category' => $request->input('category'),
            'image_url' => $imageUrl,
            'teacher_id' => $user->id,
        ]);

        // create sections for this course
        $numberOfSections = count($request->file('videos'));
        for($i = 0; $i < $numberOfSections; $i++) {

            // create section
            $section = Section::create([
                'order_number' => $i + 1,
                'name' => $request->input('sectionNames')[$i],
                'description' => $request->input('sectionDescriptions')[$i],
                'course_id' => $course->id,
            ]);

            $this->createVideoForSection($request, $i, $section);
            $this->createDocumentForSection($request, $i, $course, $section);
            $this->createExamForSection($request, $i, $section);

        }

        $this->createFinalExam($course);

        // automatically enroll teacher in course
        DB::table('enrollments')->insert([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        return response($course);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return CourseResource
     */
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }

    /**
     * Handles test submission.
     *
     * @param Request $request
     * @param  \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function submitTest(Request $request, Course $course)
    {
        $user = auth()->user();
        $examId = $request->input('examId');
        $sectionNumber = (int) $request->query('sectionNumber');
        $isFinalExam = $sectionNumber === 0;

        // check if sections exists

        $testData = json_decode($request->getContent(), true);

        // get question from db
        if($isFinalExam) {
            $questions = $course->exam->questions->all();
        } else {
            $questions = $course->sections->where('order_number', $sectionNumber)->first()->exams->first()->questions->all();
        }

        // check if all questions are answered
        if(count($questions) != count($testData)) {

            return response([
                'error' => 'You must answer all questions'
            ], 400);
        }

        // check answers
        $testResults = $this->checkAndStoreAnswers($questions, $testData, $user, $examId, $isFinalExam);

        $correctAnswers = array_reduce($testResults, function ($carry, $answer) {
            if($answer['correct']) {
                $carry += 1;
            }
            return $carry;
        });
        $score = round($correctAnswers / count($testData), 2);

        if($isFinalExam){
            DB::table('enrollments')->update([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'grade' => $score
            ]);
        }

        return response([
            'testResults' => $testResults,
            'score' => $score
        ]);
    }

    /**
     * Check user answers and store them in database
     *
     * @param $questions
     * @param $testData
     * @param $user
     * @param $examId
     * @param $isFinalExam
     * @return mixed
     */
    private function checkAndStoreAnswers($questions, $testData, $user, $examId, $isFinalExam)
    {
        $testResults = [];

        foreach ($questions as $index => $question) {
            $answer = $question->answers->find($testData[$question->id]);

            $testResults[$question->id] = [
                'correct' => $answer->is_correct,
            ];

            $query = DB::table('exam_results')
                ->where('user_id', $user->id)
                ->where('exam_id', $examId)
                ->where('question_id', $question->id);

            $storedResult = $query->get();

            $resultToStore = [
                'user_id' => $user->id,
                'exam_id' => $examId,
                'question_id' => $question->id,
                'answer_id' => $answer->id,
            ];

            // store answers for user
            if ($storedResult->isNotEmpty()) {
                $query->update($resultToStore);
            } else {
                DB::table('exam_results')->insert($resultToStore);
            }

            if ($isFinalExam) {
                $relatedSection = $question->exams->where('section_id', '<>', '')->first()->section;
                $testResults[$question->id]['relatedSection'] = $relatedSection;
            }
        }
        return $testResults;
    }

    /**
     * Handles course enrollment.
     *
     * @param  \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function enroll(Course $course)
    {
        $user = auth()->user();

        // check if user is already enrolled
        if($user->courses->where('id', $course->id)->first()) {
            return response([
                'error' => 'User is already enrolled in this course'
            ], 400);
        }

        $user->courses()->attach($course->id, [
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ]);

        return response([
            'message' => 'You have successfully enrolled in this course!'
        ]);
    }

    /**
     * Handles course review.
     *
     * @param Request $request
     * @param  \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function reviewCourse(Request $request, Course $course)
    {
        $user = auth()->user();

        if($course->teacher_id === $user->id){
            return response([
                'error' => 'You cannot review your own course.'
            ], 400);
        }

        // check if user has already reviewed
        if($user->reviewedCourses->where('id', $course->id)->first()) {
            return response([
                'error' => 'User has already reviewed this course'
            ], 400);
        }

        $user->reviewedCourses()->attach($course->id, [
            'score' => $request->input('score'),
            'text' => $request->input('text'),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ]);

        $user->load('reviewedCourses');

        return response([
            'message' => 'You have successfully reviewed this course!',
            'data' => new ReviewResource($user->reviewedCourses->last()->pivot)
        ]);
    }

    /**
     * @param Request $request
     * @param $i
     * @param $section
     */
    private function createVideoForSection(Request $request, $i, $section)
    {
        // store video for section
        $videoFile = $request->file('videos')[$i];
        $videoPath = Storage::disk('public')->putFile('videos', $videoFile);
        $videoUrl = Storage::url($videoPath);

        // create video for section
        Video::create([
            'name' => $videoFile->getClientOriginalName(),
            'url' => $videoUrl,
            'section_id' => $section->id,
        ]);
    }

    /**
     * @param Request $request
     * @param $i
     * @param $course
     * @param $section
     */
    private function createDocumentForSection(Request $request, $i, $course, $section): void
    {
        // store document for section
        $docFile = $request->file('documents')[$i];
        $docPath = Storage::disk('public')->putFileAs('documents/' . $course->id, $docFile, $docFile->getClientOriginalName());
        $docUrl = Storage::url($docPath);

        // create document for section
        Document::create([
            'name' => $docFile->getClientOriginalName(),
            'url' => $docUrl,
            'section_id' => $section->id,
        ]);
    }

    /**
     * @param Request $request
     * @param $i
     * @param $section
     */
    private function createExamForSection(Request $request, $i, $section)
    {
        $examData = json_decode($request->input('exams')[$i]);

        // create exam for this section
        $exam = Exam::create([
            'section_id' => $section->id,
            'course_id' => null
        ]);

        // create questions for exam
        foreach ($examData->questions as $q) {
            $question = Question::create([
                'text' => $q->text,
            ]);

            $exam->questions()->attach($question->id);

            // create answers for this question
            foreach (get_object_vars($q->answers) as $index => $ans) {

                $answerIsCorrect = $q->correctAnswer === $index;

                Answer::create([
                    'order_number' => $index,
                    'text' => $ans->text,
                    'is_correct' => $answerIsCorrect,
                    'question_id' => $question->id
                ]);
            }
        }
    }

    /**
     * @param $course
     */
    private function createFinalExam($course)
    {
        $allQuestions = [];

        foreach ($course->sections as $section) {
            $examQuestions = $section->exams->first()->questions->all();
            foreach ($examQuestions as $examQuestion) {
                $allQuestions[] = $examQuestion;
            }
        }
        
        if(count($allQuestions) > 30) {
            $allQuestions = array_random($allQuestions, 30);
        } else {
            shuffle($allQuestions);
        }

        // create exam for this section
        $exam = Exam::create([
            'section_id' => null,
            'course_id' => $course->id
        ]);

        // create questions for exam
        foreach ($allQuestions as $q) {
            $exam->questions()->attach($q->id);
        }
    }
}
