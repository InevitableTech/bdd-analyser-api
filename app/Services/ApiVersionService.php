<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ApiVersionService
{
    private static $versions = ['v1'];

    public static $placeholder = '{api-version}';

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

    public static function getPlaceholder(): string
    {
        return self::$placeholder;
    }

    public static function setVersionedControllerInRoute(Route $route, string $version): void
    {
        $version = ucfirst($version);
        $action = $route->getAction();

        $action['namespace'] = str_replace(self::$placeholder, $version, $action['namespace']);
        $action['uses'] = str_replace(self::$placeholder, $version, $action['uses']);
        $action['controller'] = str_replace(self::$placeholder, $version, $action['controller']);

        $route->setAction($action);
    }
}
