<?php

namespace Kjos\ParameterMapper\Support;

class ParameterMapper
{
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
}
