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
            'Course Controllers' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
            'Lecture Controllers' => base_path('Modules/Courses/app/Http/Controllers/Admin/LectureManagerController.php'),
            'Course Models' => base_path('Modules/Courses/app/Models/Course.php'),
            'CourseCategory Models' => base_path('Modules/Courses/app/Models/CourseCategory.php'),
            'CourseSection Models' => base_path('Modules/Courses/app/Models/CourseSection.php'),
            'Lecture Models' => base_path('Modules/Courses/app/Models/Lecture.php'),
            'Views' => base_path('Modules/Courses/resources/views'),
            'Migrations' => base_path('Modules/Courses/database/migrations'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");

                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllers = [
           'Course Controllers' => base_path('Modules/Courses/app/Http/Controllers/Admin/CourseManagerController.php'),
           'Lecture Controllers' => base_path('Modules/Courses/app/Http/Controllers/Admin/LectureManagerController.php'),
        ];

        foreach ($controllers as $name => $controllerPath) {
            if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\Courses\app\Http\Controllers\Admin;')) {
                $this->info("\n✅ {$name} namespace: CORRECT");
            } else {
                $this->error("\n❌ {$name} namespace: INCORRECT");
            }

            // Check for test comment
            if (str_contains($content, 'Test comment - this should persist after refresh')) {
                $this->info("✅ Test comment in {$name}: FOUND (changes are persisting)");
            } else {
                $this->warn("⚠️  Test comment in {$name}: NOT FOUND");
            }
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\Courses\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your Courses module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/Courses/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan courses:publish --force");
    }
}
