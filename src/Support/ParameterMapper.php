<?php

namespace Kjos\ParameterMapper\Support;

class ParameterMapper
{
    /**
     * Applies the parameter mapping to the given input array.
     * 
     * @param array<string, mixed> $input The input array to apply the mapping to.
     * @return array<string, mixed> The input array with the parameter mapping applied.
     */
    public static function apply(array $input): array
    {
        $map = config('parameter-mapper.map', []);
        $valuesToMap = $map['values-to-map'] ?? [];
        $arrayKeysToMap = $map['array-keys-to-map'] ?? [];

        // Mapper les clés simples
        foreach ($map as $front => $backend) {
            if (in_array($front, ['values-to-map','array-keys-to-map'])) continue;

            if (array_key_exists($front, $input)) {
                $input[$backend] = $input[$front];
                unset($input[$front]);
            }
        }

        // Mapper les valeurs de certains paramètres (ex: search=id_us => search=user_id)
        foreach ($valuesToMap as $param) {
            if (isset($input[$param]) && isset($map[$input[$param]])) {
                $input[$param] = $map[$input[$param]];
            }
        }

        // Mapper les clés dans des tableaux (ex: sort[id_us] => sort[user_id])
        foreach ($arrayKeysToMap as $param) {
            if (isset($input[$param]) && is_array($input[$param])) {
                foreach ($input[$param] as $key => $value) {
                    if (isset($map[$key])) {
                        $input[$param][$map[$key]] = $value;
                        unset($input[$param][$key]);
                    }
                }
            }
        }

        return $input;
    }

    /**
     * Inverse the parameter mapping.
     *
     * @param array<string, mixed> $input The input parameters to inverse map.
     *
     * @return array<string, mixed> The inverse mapped parameters.
     */
     public static function reverse(array $input): array
    {
        $map = config('parameter-mapper.map', []);

        $valuesToMap = $map['values-to-map'] ?? [];
        $arrayKeysToMap = $map['array-keys-to-map'] ?? [];

        // Inverser le mapping
        $inverseMap = [];
        foreach ($map as $front => $back) {
            if (is_string($back)) {
                $inverseMap[$back] = $front;
            }
        }

        $mapped = [];

        foreach ($input as $key => $value) {
            // Inverser clé simple
            $mappedKey = $inverseMap[$key] ?? $key;
            $mapped[$mappedKey] = $value;

            // Mapper valeurs pour certains champs (ex: search)
            foreach ($valuesToMap as $searchField) {
                if ($key === $searchField && isset($inverseMap[$value])) {
                    $mapped[$key] = $inverseMap[$value];
                }
            }

            // Mapper les clés d'un tableau (ex: sort[user_id] => sort[id_us])
            foreach ($arrayKeysToMap as $arrayField) {
                if (is_array($value) && $key === $arrayField) {
                    $newArr = [];
                    foreach ($value as $k => $v) {
                        $newArr[$inverseMap[$k] ?? $k] = $v;
                    }
                    $mapped[$key] = $newArr;
                }
            }
        }

        return $mapped;
    }
}
