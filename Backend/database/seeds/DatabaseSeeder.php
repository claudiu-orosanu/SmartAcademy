<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

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
