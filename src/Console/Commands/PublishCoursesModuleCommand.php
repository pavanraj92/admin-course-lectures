<?php

namespace admin\courses\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishCoursesModuleCommand extends Command
{
    protected $signature = 'courses:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Courses module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Courses module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Courses');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'course',
            '--force' => $this->option('force')
        ]);

        $this->info('Courses module files published successfully!');
    }

    private function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../../Controllers/CourseManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            
            // Models
            __DIR__ . '/../../Models/Course.php' => base_path('Modules/Courses/app/Models/Course.php'),
            
            // Requests
            __DIR__ . '/../../Requests/CourseCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/CourseCreateRequest.php'),
            __DIR__ . '/../../Requests/CourseUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/CourseUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/../routes/web.php' => base_path('Modules/Courses/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $from => $to) {
            if (File::exists($from)) {
                // Ensure the destination directory exists
                $destinationDir = dirname($to);
                if (!File::isDirectory($destinationDir)) {
                    File::makeDirectory($destinationDir, 0755, true);
                }

                // Read the source file
                $content = File::get($from);

                // Transform namespaces based on file type
                if (str_contains($to, '/Controllers/')) {
                    $content = str_replace('namespace admin\courses\Controllers;', 'namespace Modules\Courses\app\Http\Controllers\Admin;', $content);
                    $content = str_replace('use admin\courses\Requests\\', 'use Modules\Courses\app\Http\Requests\\', $content);
                    $content = str_replace('use admin\courses\Models\\', 'use Modules\Courses\app\Models\\', $content);
                } elseif (str_contains($to, '/Models/')) {
                    $content = str_replace('namespace admin\courses\Models;', 'namespace Modules\Courses\app\Models;', $content);
                } elseif (str_contains($to, '/Requests/')) {
                    $content = str_replace('namespace admin\courses\Requests;', 'namespace Modules\Courses\app\Http\Requests;', $content);
                } elseif (str_contains($to, '/routes/')) {
                    $content = str_replace('use admin\courses\Controllers\\', 'use Modules\Courses\app\Http\Controllers\Admin\\', $content);
                }

                // Write the transformed content
                File::put($to, $content);
                $this->line("Published: {$to}");
            }
        }
    }
}
