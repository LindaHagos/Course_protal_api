<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Resources\CourseResource;//
use Illuminate\Support\Facades\Validator;//
use Illuminate\Http\Request;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $course = Course::all();
        return response([ 'courses' => CourseResource::collection($course), 'message' => 'Retrieved successfully'], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'course_name' => 'required|max:255',
            'course_number' => 'required|max:255',
            'course_description' => 'required|max:255'

        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $course = Course::create($data);

        return response([ 'course' => new CourseResource($course), 'message' => 'Created successfully'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return response([ 'course' => new CourseResource($course), 'message' => 'Retrieved successfully'], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'course_name' => 'required|max:255',
            'course_number' => 'required|max:255',
            'course_description' => 'required|max:255'

        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $course->course_name =$request->course_name;
        $course->course_number =$request->course_number;
        $course->course_description=$request->course_description;
        $course->save();
        //$course-update( $request->all());


        return response([ 'course' => new CourseResource($course), 'message' => 'Created successfully'], 200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response(['message' => 'Deleted']);

    }
}
