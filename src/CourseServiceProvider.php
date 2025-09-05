<?php

namespace admin\courses;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CourseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'), // Published module views first
            resource_path('views/admin/course'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'course');

        $this->mergeConfigFrom(__DIR__ . '/../config/course.php', 'course.constants');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Courses/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Courses/resources/views'), 'courses-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Courses/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Courses/database/migrations'));
        }

        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/Courses/config/courses.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Courses/config/courses.php'), 'course.constants');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan courses:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();

        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Courses/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Courses/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Courses/resources/views/'),
        ], 'course');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();

        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Courses/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Courses/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\courses\Console\Commands\PublishCoursesModuleCommand::class,
                \admin\courses\Console\Commands\CheckModuleStatusCommand::class,
                \admin\courses\Console\Commands\DebugCoursesCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/CourseManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            __DIR__ . '/../src/Controllers/LectureManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/LectureManagerController.php'),

            // Models
            __DIR__ . '/../src/Models/Course.php' => base_path('Modules/Courses/app/Models/Course.php'),
            __DIR__ . '/../src/Models/CourseCategory.php' => base_path('Modules/Courses/app/Models/CourseCategory.php'),
            __DIR__ . '/../src/Models/CourseSection.php' => base_path('Modules/Courses/app/Models/CourseSection.php'),
            __DIR__ . '/../src/Models/Lecture.php' => base_path('Modules/Courses/app/Models/Lecture.php'),

            // Requests
            __DIR__ . '/../src/Requests/Course/CourseCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Course/CourseCreateRequest.php'),
            __DIR__ . '/../src/Requests/Course/CourseUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Course/CourseUpdateRequest.php'),
            __DIR__ . '/../src/Requests/Lecture/LectureCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Lecture/LectureCreateRequest.php'),
            __DIR__ . '/../src/Requests/Lecture/LectureUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/Lecture/LectureUpdateRequest.php'),

            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Courses/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
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
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
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
            'use admin\\courses\\Requests\\Course\\CourseCreateRequest;',
            'use Modules\\Courses\\app\\Http\\Requests\\Course\\CourseCreateRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\courses\\Requests\\Course\\CourseUpdateRequest;',
            'use Modules\\Courses\\app\\Http\\Requests\\CourseUpdateRequest;',
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

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\courses\\Controllers\\CourseManagerController',
            'Modules\\Courses\\app\\Http\\Controllers\\Admin\\CourseManagerController',
            $content
        );
        $content = str_replace(
            'admin\\courses\\Controllers\\LectureManagerController',
            'Modules\\Courses\\app\\Http\\Controllers\\Admin\\LectureManagerController',
            $content
        );

        return $content;
    }
}