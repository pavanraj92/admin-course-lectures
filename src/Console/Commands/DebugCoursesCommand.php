<?php

namespace admin\courses\Console\Commands;

use Illuminate\Console\Command;

class DebugCoursesCommand extends Command
{
    protected $signature = 'courses:debug';
    protected $description = 'Debug Courses module configuration and view resolution';

    public function handle()
    {
        $this->info('Debugging Courses module...');

        // Check view paths
        $this->line('View paths checked in order:');
        $this->line('1. ' . base_path('Modules/Courses/resources/views'));
        $this->line('2. ' . resource_path('views/admin/course'));
        $this->line('3. ' . base_path('packages/admin/courses/resources/views'));

        // Check if views exist
        $viewPaths = [
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/course'),
            base_path('packages/admin/courses/resources/views'),
        ];

        foreach ($viewPaths as $index => $path) {
            $exists = is_dir($path);
            $this->line('Path ' . ($index + 1) . ': ' . ($exists ? '✓ Exists' : '✗ Missing'));
        }

        $this->info('Debug completed.');
    }
}
