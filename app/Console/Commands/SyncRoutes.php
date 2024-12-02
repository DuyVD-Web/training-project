<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SyncRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize application route names with database route permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all registered routes
        $routes = Route::getRoutes();

        // Track changes
        $added = 0;
        $skipped = 0;

        foreach ($routes as $route) {
            // Skip routes without a name
            $routeName = $route->getName();
            if (!$routeName) {
                $skipped++;
                continue;
            }

            // Filter for admin and user routes
            if (!$this->isAdminOrUserRoute($routeName)) {
                $skipped++;
                continue;
            }

            // Check if route exists in database
            $existingRoute = Permission::where('name', $routeName)->first();

            if (!$existingRoute) {
                // Create new route permission entry
                Permission::create([
                    'name' => $routeName,
                ]);
                $added++;
                $this->info("Added new route: {$routeName}");
            }
        }

        // Output summary
        $this->info("Route Sync Complete:");
        $this->info("Added: {$added}");
        $this->info("Skipped: {$skipped}");
    }

    /**
     * Determine if the route is an admin or user route
     *
     * @param string $routeName
     * @return bool
     */
    protected function isAdminOrUserRoute($routeName)
    {
        // Patterns to match admin and user routes
        $allowedPatterns = [
            '/^admin\./',    // Routes starting with 'admin.'
            '/^user\./',     // Routes starting with 'user.'
        ];

        foreach ($allowedPatterns as $pattern) {
            if (preg_match($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }
}
