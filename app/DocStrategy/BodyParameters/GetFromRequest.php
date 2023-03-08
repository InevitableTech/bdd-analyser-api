<?php

namespace App\DocStrategy\BodyParameters;

use Knuckles\Scribe\Extracting\Strategies\Strategy;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Extracting\ParsesValidationRules;

class GetFromRequest extends Strategy
{
    use ParsesValidationRules;

    public ?ExtractedEndpointData $endpointData;

    /**
     * @param ExtractedEndpointData $endpointData
     * @param array $routeRules Array of rules for the ruleset which this route belongs to.
     *
     * @return array|null
     */
    public function __invoke(ExtractedEndpointData $endpointData, array $routeRules = []): ?array
    {
        $rulesMethod = null;

        foreach ($endpointData->httpMethods as $method) {
            switch (strtoupper($method)) {
                case 'POST':
                    $rulesMethod = 'createInputRules';
                    break;
                case 'PUT':
                    $rulesMethod = 'updateInputRules';
                    break;
            }
        }

        if ($rulesMethod) {
            $action = $endpointData->route->action['controller'];
            $class = $this->getController($action);

            if (method_exists($class, $rulesMethod)) {
                $inputs = $class::$rulesMethod();

                if ($inputs) {
                    $bodyParametersFromValidationRules = $this->getParametersFromValidationRules($inputs);
                    return $this->normaliseArrayAndObjectParameters($bodyParametersFromValidationRules);
                }
            }
        }

        return [];
    }

    private function getController(string $controller)
    {
        return explode('@', $controller)[0];
    }

    private function getAction(string $controller)
    {
        return explode('@', $controller)[1];
    }
}
