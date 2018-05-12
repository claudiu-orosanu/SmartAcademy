<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Course;
use App\Document;
use App\Exam;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Question;
use App\Section;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{

    private $validVideoExtensions = ['mp4', 'mpeg'];
    private $validDocumentExtensions = ['pdf'];

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
        // is user authenticated?

        // is user teacher?

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
        ]);

        // create sections for this course
        $numberOfSections = count($request->file('videos'));
        for($i = 0; $i < $numberOfSections; $i++) {

            // create section
            $section = Section::create([
                'order_number' => $i + 1,
                'name' => $request->input('sectionNames')[$i],
                'course_id' => $course->id,
            ]);

            $this->createVideoForSection($request, $i, $section);
            $this->createDocumentForSection($request, $i, $course, $section);
            $this->createExamForSection($request, $i, $section);

        }

        // TODO create final exam for course

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
                'exam_id' => $exam->id
            ]);

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
}
