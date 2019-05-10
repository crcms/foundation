<?php

namespace CrCms\Foundation\Helpers;

/**
 * @param $expression
 * @param bool $return
 *
 * @return string|string[]|null
 */
function var_export($expression, $return = FALSE)
{
    $export = var_export($expression, TRUE);
    $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    $array = preg_split("/\r\n|\n|\r/", $export);
    $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
    $export = join(PHP_EOL, array_filter(["["] + $array));
    if ((bool)$return) return $export; else echo $export;
}

/**
 * @param array
 * @param array
 * @return array
 */
function array_merge_recursive_distinct(): array
{
    $arrays = func_get_args();
    $base = array_shift($arrays);
    if (! is_array($base)) {
        $base = empty($base) ? [] : [$base];
    }
    foreach ($arrays as $append) {
        if (! is_array($append)) {
            $append = [$append];
        }
        foreach ($append as $key => $value) {
            if (! array_key_exists($key, $base) and ! is_numeric($key)) {
                $base[$key] = $append[$key];
                continue;
            }
            if (is_array($value) or is_array($base[$key])) {
                $base[$key] = array_merge_recursive_distinct($base[$key], $append[$key]);
            } elseif (is_numeric($key)) {
                if (! in_array($value, $base)) {
                    $base[] = $value;
                }
            } else {
                $base[$key] = $value;
            }
        }
    }

    return $base;
}


/**
 * @param array
 * @param array
 * @return array
 */
function array_merge_recursive_adv(): array
{
    if (func_num_args() < 2) {
        trigger_error(__FUNCTION__.' needs two or more array arguments', E_USER_WARNING);

        return [];
    }
    $arrays = func_get_args();
    $merged = [];
    while ($arrays) {
        $array = array_shift($arrays);
        if (! is_array($array)) {
            trigger_error(__FUNCTION__.' encountered a non array argument', E_USER_WARNING);

            return [];
        }
        if (! $array) {
            continue;
        }
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                    $merged[$key] = call_user_func(__FUNCTION__, $merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            } else {
                $merged[] = $value;
            }
        }
    }

    return $merged;
}

