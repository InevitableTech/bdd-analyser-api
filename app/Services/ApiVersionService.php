<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ApiVersionService
{
    private static $versions = ['v1'];

    public const PLACEHOLDER = '{api-version}';

    public static function get(Request $request): ?string
    {
        return $request->header('Accept-Version');
    }

    public static function isValid(string $version): bool
    {
        if (in_array($version, self::$versions)) {
            return true;
        }

        return false;
    }

    public static function setVersionedControllerInRoute(Route $route, string $version): void
    {
        $version = ucfirst($version);
        $action = $route->getAction();

        $action['namespace'] = str_replace(self::PLACEHOLDER, $version, $action['namespace']);
        $action['uses'] = str_replace(self::PLACEHOLDER, $version, $action['uses']);
        $action['controller'] = str_replace(self::PLACEHOLDER, $version, $action['controller']);

        $route->setAction($action);
    }
}
