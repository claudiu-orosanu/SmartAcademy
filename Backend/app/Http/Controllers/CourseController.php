<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
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
        //
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
}
