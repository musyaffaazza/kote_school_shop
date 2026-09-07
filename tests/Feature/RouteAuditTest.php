<?php

use Illuminate\Support\Facades\Route;

test('all named routes called in blade templates exist', function () {
    $registeredRoutes = array_keys(Route::getRoutes()->getRoutesByName());

    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));
    $missingRoutes = [];

    foreach ($it as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
            $content = file_get_contents($file->getPathname());

            if (preg_match_all('/route\(\s*[\'"]([a-zA-Z0-9_\.\-]+)[\'"]/', $content, $matches)) {
                foreach ($matches[1] as $routeName) {
                    if (! in_array($routeName, $registeredRoutes, true)) {
                        $missingRoutes[] = [
                            'file' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname()),
                            'route' => $routeName,
                        ];
                    }
                }
            }
        }
    }

    expect($missingRoutes)->toBeEmpty();
});

test('all named routes called in php controllers and requests exist', function () {
    $registeredRoutes = array_keys(Route::getRoutes()->getRoutesByName());

    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path()));
    $missingRoutes = [];

    foreach ($it as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
            $content = file_get_contents($file->getPathname());

            if (preg_match_all('/route\(\s*[\'"]([a-zA-Z0-9_\.\-]+)[\'"]/', $content, $matches)) {
                foreach ($matches[1] as $routeName) {
                    if (! in_array($routeName, $registeredRoutes, true)) {
                        $missingRoutes[] = [
                            'file' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname()),
                            'route' => $routeName,
                        ];
                    }
                }
            }
        }
    }

    expect($missingRoutes)->toBeEmpty();
});
