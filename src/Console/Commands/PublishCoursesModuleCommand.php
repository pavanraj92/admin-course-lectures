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

        $this->transformBladeFilesNamespaces();
        $this->updateComposerAutoload();

        $this->info('Courses module files published successfully!');
    }

    private function publishWithNamespaceTransformation()
    {
         $basePath = dirname(dirname(__DIR__));
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/CourseManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            $basePath . '/Controllers/LectureManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/LectureManagerController.php'),

            // Models
            $basePath . '/Models/Course.php' => base_path('Modules/Courses/app/Models/Course.php'),
            $basePath . '/Models/CourseCategory.php' => base_path('Modules/Courses/app/Models/CourseCategory.php'),
            $basePath . '/Models/CourseSection.php' => base_path('Modules/Courses/app/Models/CourseSection.php'),
            $basePath . '/Models/Lecture.php' => base_path('Modules/Courses/app/Models/Lecture.php'),

            // Requests
            $basePath . '/Requests/Course/CourseCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Course/CourseCreateRequest.php'),
            $basePath . '/Requests/Course/CourseUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Course/CourseUpdateRequest.php'),
            $basePath . '/Requests/Lecture/LectureCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Lecture/LectureCreateRequest.php'),
            $basePath . '/Requests/Lecture/LectureUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Lecture/LectureUpdateRequest.php'),

            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Courses/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\courses\\Controllers;' => 'namespace Modules\\Courses\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\courses\\Models;' => 'namespace Modules\\Courses\\app\\Models;',
            'namespace admin\\courses\\Requests\\Course;' => 'namespace Modules\\Courses\\app\\Http\\Requests\\Course;',
            'namespace admin\\courses\\Requests\\Lecture;' => 'namespace Modules\\Courses\\app\\Http\\Requests\\Lecture;',

            // Use statements transformations
            'use admin\\courses\\Controllers\\' => 'use Modules\\Courses\\app\\Http\\Controllers\\Admin\\',
            'use admin\\courses\\Models\\' => 'use Modules\\Courses\\app\\Models\\',
            'use admin\\courses\\Requests\\Course\\' => 'use Modules\\Courses\\app\\Http\\Requests\\Course\\',
            'use admin\\courses\\Requests\\Lecture\\' => 'use Modules\\Courses\\app\\Http\\Requests\\Lecture\\',


            // Class references in routes
            'admin\\courses\\Controllers\\CourseManagerController' => 'Modules\\Courses\\app\\Http\\Controllers\\Admin\\CourseManagerController',
            'admin\\courses\\Controllers\\LectureManagerController' => 'Modules\\Courses\\app\\Http\\Controllers\\Admin\\LectureManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace(
                'use admin\\courses\\Models\\Course;',
                'use Modules\\Courses\\app\\Models\\Course;',
                $content
            );
            $content = str_replace(
                'use admin\\courses\\Models\\CourseCategory;',
                'use Modules\\Courses\\app\\Models\\CourseCategory;',
                $content
            );
            $content = str_replace(
                'use admin\\courses\\Models\\OrderAddress;',
                'use Modules\\Courses\\app\\Models\\OrderAddress;',
                $content
            );
            $content = str_replace(
                'use admin\\courses\\Models\\CourseSection;',
                'use Modules\\Courses\\app\\Models\\CourseSection;',
                $content
            );
            $content = str_replace(
                'use admin\\courses\\Models\\Lecture;',
                'use Modules\\Courses\\app\\Models\\Lecture;',
                $content
            );

            $content = str_replace(
                'use admin\\categories\\Models\\Category;',
                'use Modules\\Categories\\app\\Models\\Category;',
                $content
            );

            $content = str_replace(
                'use admin\\tags\\Models\\Tag;',
                'use Modules\\Tags\\app\\Models\\Tag;',
                $content
            );

            $content = str_replace(
                'use admin\\tags\\Models\\CourseTag;',
                'use Modules\\Tags\\app\\Models\\CourseTag;',
                $content
            );

            $content = str_replace(
                'use admin\admin_auth\Services\ImageService;',
                'use Modules\\AdminAuth\\app\\Services\\ImageService;',
                $content
            );
           
            $content = str_replace(
                'use admin\\courses\\Requests\\Course\\CourseCreateRequest;',
                'use Modules\\Courses\\app\\Http\\Requests\\Course\\CourseCreateRequest;',
                $content
            );

            $content = str_replace(
                'use admin\\courses\\Requests\\Course\\CourseUpdateRequest;',
                'use Modules\\Courses\\app\\Http\\Requests\\Course\\CourseUpdateRequest;',
                $content
            );
            $content = str_replace(
                'use admin\\courses\\Requests\\Lecture\\LectureCreateRequest;',
                'use Modules\\Courses\\app\\Http\\Requests\\Lecture\\LectureCreateRequest;',
                $content
            );

            $content = str_replace(
                'use admin\\courses\\Requests\\Lecture\\LectureUpdateRequest;',
                'use Modules\\Courses\\app\\Http\\Requests\\Lecture\\LectureUpdateRequest;',
                $content
            );

        }elseif (str_contains($sourceFile, 'Models')) {
            $content = str_replace(
                'use admin\\tags\\Models\\Tag;',
                'use Modules\\Tags\\app\\Models\\Tag;',
                $content
            );

            $content = str_replace(
                'use admin\\tags\\Models\\CourseTag;',
                'use Modules\\Tags\\app\\Models\\CourseTag;',
                $content
            );
        }

        return $content;
    }

    protected function transformBladeFilesNamespaces()
    {
        $pathsToScan = [
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/course'),
        ];

        foreach ($pathsToScan as $path) {
            if (File::exists($path)) {
                $this->transformBladeNamespacesInDirectory($path);
            }
        }
    }

    protected function transformBladeNamespacesInDirectory($directory)
    {
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            // Process only Blade/PHP view files
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getRealPath());

            $replacements = [
                'admin\\courses\\Models\\Course' => 'Modules\\Courses\\app\\Models\\Course',
            ];

            $updated = $content;
            foreach ($replacements as $search => $replace) {
                $updated = str_replace($search, $replace, $updated);
            }

            if ($updated !== $content) {
                File::put($file->getRealPath(), $updated);
                $this->info('Updated blade namespace: ' . $file->getRelativePathname());
            }
        }
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Courses\\'])) {
            $composer['autoload']['psr-4']['Modules\\Courses\\'] = 'Modules/Courses/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}