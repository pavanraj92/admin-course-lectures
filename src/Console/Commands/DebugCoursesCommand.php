<?php

namespace admin\courses\Console\Commands;

use Illuminate\Console\Command;

class DebugCoursesCommand extends Command
{
    protected $signature = 'courses:debug';
    protected $description = 'Debug Courses module configuration and view resolution';

   public function handle()
    {
        $this->info('🔍 Debugging Courses Module...');

        // Check route file loading
        $this->info("\n📍 Route Files:");
        $moduleRoutes = base_path('Modules/Courses/routes/web.php');
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }

        $packageRoutes = base_path('packages/admin/courses/src/routes/web.php');
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Courses/resources/views'),
            'Published views' => resource_path('views/admin/courses'),
            'Package views' => base_path('packages/admin/courses/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
       $controllers = [
            'CourseManagerController' => 'Modules\\Courses\\app\\Http\\Controllers\\Admin\\CourseManagerController',
            'LectureManagerController' => 'Modules\\Courses\\app\\Http\\Controllers\\Admin\\LectureManagerController',
        ];

         foreach ($controllers as $label => $controllerClass) {
            $this->info("Checking {$label}: {$controllerClass}");
            if (class_exists($controllerClass)) {
            $this->info("✅ Controller class found: {$controllerClass}");
            $reflection = new \ReflectionClass($controllerClass);
            $this->info("   File: " . $reflection->getFileName());
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
            } else {
            $this->error("❌ Controller class not found: {$controllerClass}");
            }
        }

       // Show current routes
        $this->info("\n🛣️  Current Routes:");
        $routes = Route::getRoutes();
        $courseRoutes = [];

        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller'])) {
            if (
                str_contains($action['controller'], 'CourseManagerController') ||
                str_contains($action['controller'], 'LectureManagerController')
            ) {
                $courseRoutes[] = [
                'uri' => $route->uri(),
                'methods' => implode('|', $route->methods()),
                'controller' => $action['controller'],
                'name' => $route->getName(),
                ];
            }
            }
        }
        
        if (!empty($courseRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $courseRoutes);
        } else {
            $this->warn("No shipping routes found.");
        }
    }
}
