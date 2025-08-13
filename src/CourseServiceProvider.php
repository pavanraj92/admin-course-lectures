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
        $this->registerViewNamespaces();
        $this->registerMigrations();
        $this->registerConfigs();
        $this->registerPublishables();
        $this->registerAdminRoutes();
    }

    protected function registerViewNamespaces()
    {
        // Generic course views
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/course'),
            __DIR__ . '/../resources/views'
        ], 'course');

        // Lecture views
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/lecture'),
            __DIR__ . '/../resources/views'
        ], 'lecture');

        // Purchase views
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/purchase'),
            __DIR__ . '/../resources/views'
        ], 'purchase');

        // Report views
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/report'),
            __DIR__ . '/../resources/views'
        ], 'report');

        // Transaction views
        $this->loadViewsFrom([
            base_path('Modules/Courses/resources/views'),
            resource_path('views/admin/transaction'),
            __DIR__ . '/../resources/views'
        ], 'transaction');

        // Extra namespace for explicit usage
        if (is_dir(base_path('Modules/Courses/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Courses/resources/views'), 'courses-module');
        }
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $publishedMigrations = base_path('Modules/Courses/database/migrations');
        if (is_dir($publishedMigrations)) {
            $this->loadMigrationsFrom($publishedMigrations);
        }
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/course.php', 'course.constants');
        $this->mergeConfigFrom(__DIR__ . '/../config/course.php', 'courses.config');

        $publishedConfig = base_path('Modules/Courses/config/course.php');
        if (file_exists($publishedConfig)) {
            $this->mergeConfigFrom($publishedConfig, 'courses.config');
        }
    }

    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/Courses/database/migrations'),
            __DIR__ . '/../resources/views'     => base_path('Modules/Courses/resources/views/'),
            __DIR__ . '/../config/' => base_path('Modules/Courses/config/'),
        ], 'course');

        $this->publishWithNamespaceTransformation();
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
            ->prefix("{$slug}/admin")
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
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
                \admin\courses\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        $moduleBase = base_path('Modules/Courses');
        $srcBase = __DIR__ . '/../src';

        // Define the files that need namespace transformation
        $filesWithNamespaces = [

            // Controllers
            "$srcBase/Controllers/CourseManagerController.php"          => "$moduleBase/app/Http/Controllers/Admin/CourseManagerController.php",
            "$srcBase/Controllers/LectureManagerController.php"         => "$moduleBase/app/Http/Controllers/Admin/LectureManagerController.php",
            "$srcBase/Controllers/CoursePurchaseManagerController.php"  => "$moduleBase/app/Http/Controllers/Admin/CoursePurchaseManagerController.php",
            "$srcBase/Controllers/TransactionManagerController.php"     => "$moduleBase/app/Http/Controllers/Admin/TransactionManagerController.php",
            "$srcBase/Controllers/ReportManagerController.php"          => "$moduleBase/app/Http/Controllers/Admin/ReportManagerController.php",

            // Models
            "$srcBase/Models/Course.php"            => "$moduleBase/app/Models/Course.php",
            "$srcBase/Models/CourseCategory.php"    => "$moduleBase/app/Models/CourseCategory.php",
            "$srcBase/Models/CoursePurchase.php"    => "$moduleBase/app/Models/CoursePurchase.php",
            "$srcBase/Models/CourseSection.php"     => "$moduleBase/app/Models/CourseSection.php",
            "$srcBase/Models/CourseTag.php"         => "$moduleBase/app/Models/CourseTag.php",
            "$srcBase/Models/Lecture.php"           => "$moduleBase/app/Models/Lecture.php",
            "$srcBase/Models/Transaction.php"       => "$moduleBase/app/Models/Transaction.php",

            // Requests
            "$srcBase/Requests/Course/CourseCreateRequest.php"      => "$moduleBase/app/Http/Requests/Course/CourseCreateRequest.php",
            "$srcBase/Requests/Course/CourseUpdateRequest.php"      => "$moduleBase/app/Http/Requests/Course/CourseUpdateRequest.php",
            "$srcBase/Requests/Lecture/LectureCreateRequest.php"    => "$moduleBase/app/Http/Requests/Lecture/LectureCreateRequest.php",
            "$srcBase/Requests/Lecture/LectureUpdateRequest.php"    => "$moduleBase/app/Http/Requests/Lecture/LectureUpdateRequest.php",


            // Routes
            "$srcBase/routes/web.php" => "$moduleBase/routes/web.php",
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
            'namespace admin\\courses\\Controllers;'    => 'namespace Modules\\Courses\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\courses\\Models;'         => 'namespace Modules\\Courses\\app\\Models;',
            'namespace admin\\courses\\Requests;'       => 'namespace Modules\\Courses\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\courses\\Controllers\\'         => 'use Modules\\Courses\\app\\Http\\Controllers\\Admin\\',
            'use admin\\courses\\Models\\'              => 'use Modules\\Courses\\app\\Models\\',
            'use admin\\courses\\Requests\\'            => 'use Modules\\Courses\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\courses\\Controllers\\CourseManagerController' => 'Modules\\Courses\\app\\Http\\Controllers\\Admin\\CourseManagerController',
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
            'use admin\\courses\\Requests\\Course\\CourseCreateRequest;',
            'use Modules\\Courses\\app\\Http\\Requests\\Course\\CourseCreateRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\courses\\Requests\\Course\\CourseUpdateRequest;',
            'use Modules\\Courses\\app\\Http\\Requests\\Course\\CourseUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        return str_replace(
            'namespace admin\\courses\\Models;',
            'namespace Modules\\Courses\\app\\Models;',
            $content
        );
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        return str_replace(
            'namespace admin\\courses\\Requests;',
            'namespace Modules\\Courses\\app\\Http\\Requests;',
            $content
        );
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

        return $content;
    }
}
