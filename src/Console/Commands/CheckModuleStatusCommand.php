<?php

namespace admin\courses\Console\Commands;

use Illuminate\Console\Command;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'courses:status';
    protected $description = 'Check the status of Courses module installation';

    public function handle()
    {
        $this->info('Checking Courses module status...');

        // Check if package is installed
        $packagePath = base_path('vendor/admin/courses');
        $this->line('Package installed: ' . (is_dir($packagePath) ? '✓ Yes' : '✗ No'));

        // Check if module files are published
        $moduleFiles = [
            'Controllers' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            'Models' => base_path('Modules/Courses/app/Models/Course.php'),
            'Views' => base_path('Modules/Courses/resources/views'),
            'Migrations' => base_path('Modules/Courses/database/migrations'),
        ];

        foreach ($moduleFiles as $type => $path) {
            $exists = file_exists($path) || is_dir($path);
            $this->line("{$type}: " . ($exists ? '✓ Published' : '✗ Not published'));
        }

        $this->info('Status check completed.');
    }
}
