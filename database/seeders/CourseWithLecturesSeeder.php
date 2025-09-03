<?php

namespace Admin\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use admin\courses\Models\Course;

class CourseWithLecturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();


        $courses = [
            [
                'title' => 'Laravel Basics',
                'short_description' => 'Introduction to Laravel framework.',
                'description'       => 'This course covers the fundamentals of Laravel including routes, controllers, and views.',
                'language'          => 'en',
                'level'             => 'beginner',
                'duration'          => 30,
                'price'             => 0,
                'max_students'      => 100,
                'start_date'        => $now,
                'end_date'          => $now->copy()->addDays(30),
                'is_highlight'      => true,
                'status'            => 'approved',
                'slug'              => Str::slug('Laravel Basics'), 
                'category_ids'      => [1],
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'title' => 'Advanced Laravel',
                'short_description' => 'Deep dive into Laravel.',
                'description' => 'Covers Eloquent, middleware, and testing.',
                'language' => 'en',
                'level' => 'advanced',
                'duration' => 45,
                'price' => 50,
                'max_students' => 50,
                'start_date' => $now,
                'end_date' => $now->copy()->addDays(45),
                'is_highlight' => false,
                'status' => 'approved',
                'slug' => Str::slug('Advanced Laravel'),
                'category_ids' => [1],
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        foreach ($courses as $data) {
            DB::beginTransaction();
            try {
                $course = Course::updateOrCreate(
                    ['title' => $data['title']],
                    [
                        'short_description' => $data['short_description'] ?? null,
                        'description'       => $data['description'] ?? null,
                        'language'          => $data['language'] ?? null,
                        'level'             => $data['level'] ?? null,
                        'duration'          => $data['duration'] ?? null,
                        'price'             => $data['price'] ?? null,
                        'max_students'      => $data['max_students'] ?? null,
                        'start_date'        => $data['start_date'] ?? null,
                        'end_date'          => $data['end_date'] ?? null,
                        'is_highlight'      => $data['is_highlight'] ?? null,
                        'status'            => $data['status'] ?? 'draft',
                        'slug'              => $data['slug'] ?? null,
                    ]
                );

                // Categories
                if (!empty($data['category_ids'])) {
                    $course->categories()->sync($data['category_ids']);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Failed seeding course '.$data['title'].': '.$e->getMessage());
            }
        }
        // Get the ID of the created course
        $courseId = DB::table('courses')->where('title', 'Laravel Basics')->value('id');

        // Ensure a section exists for this course
        $sectionId = DB::table('course_sections')->updateOrInsert(
            ['title' => 'Getting Started', 'course_id' => $courseId],
            [
                'slug'       => Str::slug('Getting Started'),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $sectionId = DB::table('course_sections')->where('title', 'Getting Started')->value('id');

        // Seed 3 lectures for this course
        $lectures = [
            [
                'course_id'        => $courseId,
                'section_id'       => $sectionId,
                'title'            => 'Introduction to Laravel',
                'short_description'=> 'Overview of Laravel features.',
                'description'      => 'Learn the basics of Laravel and why it is so popular.',
                'type'             => 'video',
                'video'            => 'lecture/videos/intro.mp4',
                'duration'         => 15,
                'order'            => 1,
                'is_preview'       => true,
                'is_highlight'     => false,
                'status'           => 'published',
                'slug'             => Str::slug('Introduction to Laravel'),
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $courseId,
                'section_id'       => $sectionId,
                'title'            => 'Routing in Laravel',
                'short_description'=> 'Basics of Laravel routing.',
                'description'      => 'Understand GET, POST and resource routes.',
                'type'             => 'video',
                'video'            => 'lecture/videos/routing.mp4',
                'duration'         => 20,
                'order'            => 2,
                'is_preview'       => false,
                'is_highlight'     => true,
                'status'           => 'published',
                'slug'             => Str::slug('Routing in Laravel'),
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $courseId,
                'section_id'       => $sectionId,
                'title'            => 'Blade Templates',
                'short_description'=> 'Learn Laravel Blade.',
                'description'      => 'Covers Blade syntax and template inheritance.',
                'type'             => 'audio',
                'audio'            => 'lecture/audios/blade.mp3',
                'duration'         => 10,
                'order'            => 3,
                'is_preview'       => false,
                'is_highlight'     => false,
                'status'           => 'draft',
                'slug'             => Str::slug('Blade Templates'),
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        foreach ($lectures as $lecture) {
            DB::table('lectures')->updateOrInsert(
                ['title' => $lecture['title']],
                $lecture
            );
        }
    }
}
