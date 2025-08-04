<?php

namespace admin\courses\Console\Commands;

use Illuminate\Console\Command;

class TestViewResolutionCommand extends Command
{
    protected $signature = 'courses:test-views';
    protected $description = 'Test view resolution for Courses module';

    public function handle()
    {
        $this->info('Testing view resolution for Courses module...');

        try {
            // Test if views can be resolved
            $views = [
                'course::admin.index',
                'course::admin.createOrEdit', 
                'course::admin.show'
            ];

            foreach ($views as $view) {
                try {
                    if (view()->exists($view)) {
                        $this->line("✓ {$view} - Found");
                    } else {
                        $this->line("✗ {$view} - Not found");
                    }
                } catch (\Exception $e) {
                    $this->line("✗ {$view} - Error: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            $this->error('Error testing views: ' . $e->getMessage());
        }

        $this->info('View resolution test completed.');
    }
}
