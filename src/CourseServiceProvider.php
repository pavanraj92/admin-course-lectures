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

        $this->mergeConfigFrom(__DIR__.'/../config/course.php', 'course.constants');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Courses/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Courses/resources/views'), 'courses-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Courses/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Courses/database/migrations'));
        }

        // Standard publishing for non-PHP files
        $this->publishes([
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
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
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
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/CourseManagerController.php' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            
            // Models
            __DIR__ . '/../src/Models/Course.php' => base_path('Modules/Courses/app/Models/Course.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/CourseCreateRequest.php' => base_path('Modules/Courses/app/Http/Requests/CourseCreateRequest.php'),
            __DIR__ . '/../src/Requests/CourseUpdateRequest.php' => base_path('Modules/Courses/app/Http/Requests/CourseUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Courses/routes/web.php'),
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
}
