<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRolesAndAdmin();
//        $this->createCourses();

    }

    private function createRolesAndAdmin()
    {
        // create admin role
        $adminRole = new Role([
            'name' => 'admin'
        ]);
        $adminRole->save();

        // create teacher role
        $teacherRole = new Role([
            'name' => 'teacher'
        ]);
        $teacherRole->save();

        // create student role
        $studentRole = new Role([
            'name' => 'student'
        ]);
        $studentRole->save();

        // create admin user
        $adminUser = new User([
            'first_name' => 'Admin',
            'last_name' => 'Global',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'is_verified' => true,
            'remember_token' => str_random(10)
        ]);
        $adminUser->save();
        $adminUser->attachRole($adminRole);
    }

    private function createCourses()
    {
        // create the courses
        factory(\App\Course::class, 5)->create();
        $courseIds = \App\Course::all()->pluck('id')->toArray();

        // create a section for each course
        foreach ($courseIds as $courseId) {
            factory(\App\Section::class)->create([
                'course_id' => $courseId
            ]);
        }

        // create one video and one document for each section
        $sectionIds = \App\Section::all()->pluck('id')->toArray();
        foreach ($sectionIds as $sectionId) {
            factory(\App\Video::class)->create([
                'section_id' => $sectionId
            ]);
            factory(\App\Document::class)->create([
                'section_id' => $sectionId
            ]);
        }
    }
}
