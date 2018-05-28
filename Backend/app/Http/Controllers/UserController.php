<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CourseCollection
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Response
     */
    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        // is user authenticated?


        if($authUser->id !== $user->id) {
            return response([
                'error' => 'You cannot modify other users accounts'
            ]);
        }

        // validate request
        $maxSize = (int)ini_get('upload_max_filesize') * 1000;

        $this->validate($request, [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'about_me' => 'min:3|max:512'
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->about_me = $request->input('about_me');

        // store course image in public folder and save it to model
        $imageFile = $request->file('image');

        if($imageFile) {
            $this->validate($request, [
                'image' => 'file|image|max:' . $maxSize
            ]);
            $imagePath = Storage::disk('public')->putFile('userAvatars', $imageFile);
            $imageUrl = Storage::url($imagePath);
            $user->image_url = $imageUrl;
        }

        $user->save();

        return response(new UserResource($user));
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Response
     */
    public function changePassword(Request $request)
    {
        $authUser = auth()->user();

        // is user authenticated?

        $this->validate($request, [
            'password' => 'required|min:5|max:255'
        ]);

        $password = $request->input('password');
        $oldPassword = $request->input('oldPassword');
        if(!Hash::check($oldPassword, $authUser->password)){
            return response([
                'error' => 'The old password is not correct!'
            ], 400);
        }

        $user = User::find($authUser->id);
        $user->password = Hash::make($password);
        $user->save();

        return response([
            'message' => 'Password updated.'
        ]);
    }
}
