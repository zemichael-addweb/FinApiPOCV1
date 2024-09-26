<?php

namespace App\Services;

use Exception;

class OpenApiEnumModelService
{
    /**
     * Get a valid enum value or return a default value.
     *
     * @param string $modelClass The enum model class (e.g., ISO3166Alpha2Codes::class)
     * @param string $value The value to check against the model's allowable enum values
     * @param string|null $default The default value to return if the provided value is invalid (optional)
     *
     * @return mixed The valid enum value or the default
     * @throws Exception If the model does not implement getAllowableEnumValues method
     */
    public static function getEnumValue(string $modelClass, ?string $value = null, ?string $default = null)
    {
        if (!method_exists($modelClass, 'getAllowableEnumValues')) {
            throw new Exception("The model class {$modelClass} must have a 'getAllowableEnumValues' method.");
        }

        $validValues = $modelClass::getAllowableEnumValues();

        if (in_array($value, $validValues)) {
            return $value;
        }

        return $default ?? $validValues[0];
    }
}
