<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SyncRoutesCommand extends Command
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
    public function handle(): void
    {
        $routes = Route::getRoutes();
        $currentRouteNames = [];

        foreach ($routes as $route) {
            $routeName = $route->getName();

            if (!$this->isValidRoute($routeName)) {
                continue;
            }

            $currentRouteNames[] = $routeName;

            $existingRoute = Permission::firstOrCreate(
                ['name' => $routeName]
            );

            if ($existingRoute->wasRecentlyCreated) {
                $this->info("Added new route: {$routeName}");
            }
        }

        $removedRoutes = Permission::whereNotIn('name', $currentRouteNames)->delete();

        $this->info("Route Sync Complete.");
    }

    /**
     * Determine if the route is an admin or user or api route
     *
     * @param string $routeName
     * @return bool
     */
    protected function isValidRoute($routeName)
    {
        // Patterns to match admin and user routes
        $allowedPatterns = [
            '/^admin\./',           // Routes starting with 'admin.'
            '/^user\./',            // Routes starting with 'user.'
            '/^api.admin\./', // Matches 'api.admin.something'
            '/^api.user\./', // Matches 'api.user.something'
        ];

        foreach ($allowedPatterns as $pattern) {
            if (preg_match($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }
}
