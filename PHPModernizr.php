<?php
/**
 * PHPModernizr
 * Makes most of last released built-in PHP functions works on old PHP versions.
 *
 * @author  Geoffray Warnants
 * @version 1.0.20131104
 * @see     https://github.com/gwarnants/PHPModernizr
 */


// ----------------------------------------------------------------------------
//
// array
//
// ----------------------------------------------------------------------------


if (!function_exists('array_column')) {
    /**
     * Return the values from a single column in the input array
     *
     * @param   array $input
     * @param   mixed $column_key
     * @param   mixed $index_key
     * @return  array
     * @since   PHP 5.5.0
     * @see     http://php.net/manual/en/function.array-column.php
     */
    function array_column($input, $column_key, $index_key=null) {
        if (!is_array($input)) {
            trigger_error(__FUNCTION__.'() Argument #1 is not an array', E_USER_WARNING);
            return;
        }
        $array = array();
        foreach ($input as $k => $v) {
            if ($index_key !== null && array_key_exists($index_key, $v)) {
                $array[$v[$index_key]] = ($column_key===null) ? $v
                                       : (isset($v[$column_key]) ? $v[$column_key]
                                       : null);
            } else {
                $array[] = ($column_key===null) ? $v
                         : (isset($v[$column_key]) ? $v[$column_key]
                         : null);
            }
        }
        return $array;
    }
}

if (!function_exists('array_combine')) {
    /**
     * Creates an array by using one array for keys and another for its values
     *
     * @param   array   $keys
     * @param   array   $values
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-combine.php
     */
    function array_combine($keys, $values) {
        if (!is_array($keys)) {
            trigger_error(__FUNCTION__.'() expects parameter 1 to be array, '.gettype($keys).' given', E_USER_WARNING);
            return;
        }
        if (!is_array($values)) {
            trigger_error(__FUNCTION__.'() expects parameter 2 to be array, '.gettype($values).' given', E_USER_WARNING);
            return;
        }
        if (count($keys) == count($values)) {
            $combined = array();
            foreach ($keys as $k) {
                $combined[$k] = current($values);
                next($values);
            }
            return $combined;
        }
        return false;
    }
}

if (!function_exists('array_diff_uassoc')) {
    /**
     * Computes the difference of arrays with additional index check which is performed by a user supplied callback function
     *
     * @param   array    $array1
     * @param   array    $array2
     * @param   array    ...
     * @param   callable $data_compare_func
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-diff-uassoc.php
     */
    function array_diff_uassoc($array1, $array2=null, $data_compare_func=null) {
        if (($n=func_num_args()) < 3) {
            trigger_error(__FUNCTION__.'() : at least 3 parameters are required, '.$n.' given', E_USER_WARNING);
            return;
        } elseif (!is_callable($data_compare_func=func_get_arg($n-1))) {
            trigger_error(__FUNCTION__.'() expects parameter '.$n.' to be a valid callback', E_USER_WARNING);
            return;
        } elseif (!is_array(func_get_arg(0))) {
            trigger_error(__FUNCTION__.' : Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        $diff = array();
        foreach (func_get_arg(0) as $key => $value) {
            for ($i=1, $found=false; $i<$n-1 && !$found; $i++) {
                if (is_array($arg=func_get_arg($i))) {
                    foreach ($arg as $k => $v) {
                        if ($value == $arg[$key] && $data_compare_func($key, $k) == 0) {
                            continue 2;
                        }
                    }
                } else {
                    trigger_error(__FUNCTION__.' : Argument #'.($i+1).' is not an array', E_USER_WARNING);
                    return;
                }
            }
            if (!$found) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }
}

if (!function_exists('array_fill_keys')) {
    /**
     * Fill an array with values, specifying keys
     *
     * @param   array   $keys
     * @param   array   $value
     * @return  array
     * @since   PHP 5.2.0
     * @see     http://php.net/manual/en/function.array-fill-keys.php
     */
    function array_fill_keys($keys, $value) {
        $filled = array();
        foreach ($keys as $k) {
            $filled[$k] = $value;
        }
        return $value;
    }
}

if (!function_exists('array_replace')) {
    /**
     * Replaces elements from passed arrays into the first array
     *
     * @param   array   $array
     * @param   array   ...
     * @return  array
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.array-replace.php
     */
    function array_replace($array, $array1) {
        if (($num_args=func_num_args()) == 0) {
            trigger_error(__FUNCTION__.'() expects at least 1 parameter, 0 given', E_USER_WARNING);
            return;
        } elseif (!is_array($array)) {
            trigger_error(__FUNCTION__.'() Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        for ($i=1; $i<$num_args; $i++) {
            foreach (func_get_arg($i) as $k => $v) {
                $array[$k] = $v;
            }
        }

        return $array;
    }
}

if (!function_exists('array_replace_recursive')) {
    /**
     * Replaces elements from passed arrays into the first array recursively
     *
     * @param   array   $array
     * @param   array   ...
     * @return  array
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.array-replace-recursive.php
     */
    function array_replace_recursive($array, $array1) {
        if (($num_args=func_num_args()) == 0) {
            trigger_error(__FUNCTION__.'() expects at least 1 parameter, 0 given', E_USER_WARNING);
            return;
        } elseif (!is_array($array)) {
            trigger_error(__FUNCTION__.'() Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        for ($i=1; $i<$num_args; $i++) {
            foreach (func_get_arg($i) as $k => $v) {
                if (isset($array[$k]) && is_array($array[$k]) && is_array($v)) {
                    $array[$k] = array_replace_recursive($array[$k], $v);
                } else {
                    $array[$k] = $v;
                }
            }
        }

        return $array;
    }
}

if (!function_exists('array_udiff')) {
    /**
     * Computes the difference of arrays by using a callback function for data comparison
     *
     * @param   array    $array1
     * @param   array    $array2
     * @param   array    ...
     * @param   callable $data_compare_func
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-udiff.php
     */
    function array_udiff($array1, $array2=null, $data_compare_func=null) {
        if (($n=func_num_args()) < 3) {
            trigger_error(__FUNCTION__.'() : at least 3 parameters are required, '.$n.' given', E_USER_WARNING);
            return;
        } elseif (!is_callable($data_compare_func=func_get_arg($n-1))) {
            trigger_error(__FUNCTION__.'() expects parameter '.$n.' to be a valid callback', E_USER_WARNING);
            return;
        } elseif (!is_array(func_get_arg(0))) {
            trigger_error(__FUNCTION__.' : Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        $diff = array();
        foreach (func_get_arg(0) as $key => $value) {
            for ($i=1; $i<$n-1; $i++) {
                if (is_array($arg=func_get_arg($i))) {
                    foreach ($arg as $k => $v) {
                        if ($data_compare_func($value, $v) == 0) {
                            continue 2;
                        }
                    }
                    $diff[$key] = $value;
                } else {
                    trigger_error(__FUNCTION__.' : Argument #'.($i+1).' is not an array', E_USER_WARNING);
                    return;
                }
            }
        }

        return $diff;
    }
}

if (!function_exists('array_udiff_assoc')) {
    /**
     * Computes the difference of arrays with additional index check, compares data by a callback function
     *
     * @param   array    $array1
     * @param   array    $array2
     * @param   array    ...
     * @param   callable $data_compare_func
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-udiff-assoc.php
     */
    function array_udiff_assoc($array1, $array2=null, $data_compare_func=null) {
        if (($n=func_num_args()) < 3) {
            trigger_error(__FUNCTION__.'() : at least 3 parameters are required, '.$n.' given', E_USER_WARNING);
            return;
        } elseif (!is_callable($data_compare_func=func_get_arg($n-1))) {
            trigger_error(__FUNCTION__.'() expects parameter '.$n.' to be a valid callback', E_USER_WARNING);
            return;
        } elseif (!is_array(func_get_arg(0))) {
            trigger_error(__FUNCTION__.' : Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        $diff = array();
        foreach (func_get_arg(0) as $key => $value) {
            for ($i=1, $found=false; $i<$n-1 && !$found; $i++) {
                if (is_array($arg=func_get_arg($i))) {
                    if (isset($arg[$key]) && $data_compare_func($value, $arg[$key]) == 0) {
                        continue 2;
                    }
                } else {
                    trigger_error(__FUNCTION__.' : Argument #'.($i+1).' is not an array', E_USER_WARNING);
                    return;
                }
            }
            if (!$found) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }
}

if (!function_exists('array_udiff_uassoc')) {
    /**
     * Computes the difference of arrays with additional index check, compares data and indexes by a callback function
     *
     * @param   array    $array1
     * @param   array    $array2
     * @param   array    ...
     * @param   callable $data_compare_func
     * @param   callable $key_compare_func
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-udiff-uassoc.php
     */
    function array_udiff_uassoc($array1, $array2=null, $data_compare_func=null, $key_compare_func=null) {
        if (($n=func_num_args()) < 4) {
            trigger_error(__FUNCTION__.'() : at least 4 parameters are required, '.$n.' given', E_USER_WARNING);
            return;
        } elseif (!is_callable($data_compare_func=func_get_arg($n-2))) {
            trigger_error(__FUNCTION__.'() expects parameter '.($n-1).' to be a valid callback', E_USER_WARNING);
            return;
        } elseif (!is_callable($data_compare_func=func_get_arg($n-1))) {
            trigger_error(__FUNCTION__.'() expects parameter '.$n.' to be a valid callback', E_USER_WARNING);
            return;
        } elseif (!is_array(func_get_arg(0))) {
            trigger_error(__FUNCTION__.' : Argument #1 is not an array', E_USER_WARNING);
            return;
        }

        $diff = array();
        foreach (func_get_arg(0) as $key => $value) {
            for ($i=1, $found=false; $i<$n-2 && !$found; $i++) {
                if (is_array($arg=func_get_arg($i))) {
                    foreach ($arg as $k => $v) {
                        if ($key_compare_func($key, $k) == 0 && $data_compare_func($value, $arg[$key]) == 0) {
                            continue 2;
                        }
                    }
                } else {
                    trigger_error(__FUNCTION__.' : Argument #'.($i+1).' is not an array', E_USER_WARNING);
                    return;
                }
            }
            if (!$found) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }
}

if (!function_exists('array_walk_recursive')) {
    /**
     * Apply a user function recursively to every member of an array
     *
     * @param   array       $input
     * @param   callback    $funcname
     * @param   mixed       $userdata
     * @return  bool
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.array-walk-recursive.php
     */
    function array_walk_recursive(&$input, $funcname, $userdata=null) {
        if (($num_args = func_num_args()) < 2) {
            trigger_error(__FUNCTION__.'() expects at least 2 parameters, '.$num_args.' given', E_USER_WARNING);
            return;
        } elseif (!is_array($input)) {
            trigger_error(__FUNCTION__.'() expects parameter 1 to be array, '.gettype($input).' given', E_USER_WARNING);
            return;
        } elseif (!is_callable($funcname)) {
            trigger_error(__FUNCTION__.'() parameter 2 to be a valid callback', E_USER_WARNING);
            return;
        }

        foreach ($input as $k => $v) {
            if ($num_args == 2) {
                is_array($v) ? array_walk_recursive($v, $funcname) : $funcname($v, $k);
            } else {
                is_array($v) ? array_walk_recursive($v, $funcname, $userdata) : $funcname($v, $k, $userdata);
            }
            $input[$k] = $v;
        }

        return true;
    }
}


// ----------------------------------------------------------------------------
//
// ctype
//
// Before PHP 4.2.0 ctype functions were not enabled by default.
// PHP had to be compiled with option --enable-ctype
//
// ----------------------------------------------------------------------------


if (!function_exists('ctype_alnum')) {
    /**
     * Check for alphanumeric character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/fr/function.ctype-alnum.php
     */
    function ctype_alnum($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ((int)preg_match('/^[[:alnum:]]+$/', chr($text)) > 0);
        } elseif (is_int($text) || is_string($text)) {
            return ((int)preg_match('/^[[:alnum:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_alpha')) {
    /**
     * Check for alphabetic character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-alpha.php
     */
    function ctype_alpha($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ((int)preg_match('/^[[:alpha:]]+$/', chr($text)) > 0);
        } elseif (is_int($text) || is_string($text)) {
            return ((int)preg_match('/^[[:alpha:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_cntrl')) {
    /**
     * Check for control character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-cntrl.php
     */
    function ctype_cntrl($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return (($text >= 0 && $text < 32) || $text == 127);
        } elseif (is_int($text) || is_string($text)) {
            return ((int)preg_match('/^[[:cntrl:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_digit')) {
    /**
     * Check for numeric character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-digit.php
     */
    function ctype_digit($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ($text >= ord('0') && $text <= ord('9'));
        } elseif (is_int($text) || is_string($text)) {
            return ((int)preg_match('/^[0-9]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_graph')) {
    /**
     * Check for any printable character(s) except space
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-graph.php
     */
    function ctype_graph($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ($text >= 33 && $text <= 126);
        } elseif (is_string($text) || is_int($text)) {
            return ((int)preg_match('/^[[:alnum:][:punct:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_lower')) {
    /**
     * Check for lowercase character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-lower.php
     */
    function ctype_lower($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ($text >= ord('a') && $text <= ord('z'));
        } else {
            return ((int)preg_match('/^[a-z]+$/', (string)$text) > 0);
        }
    }
}

if (!function_exists('ctype_print')) {
    /**
     * Check for printable character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-print.php
     */
    function ctype_print($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ($text >= 32 && $text <= 126);
        } elseif (is_string($text) || is_int($text)) {
            return ((int)preg_match('/^[[:print:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_punct')) {
    /**
     * Check for any printable character which is not whitespace or an alphanumeric character
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-punct.php
     */
    function ctype_punct($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ((int)preg_match('/^[[:punct:]]+$/', chr($text)) > 0);
        } elseif (is_string($text) || is_int($text)) {
            return ((int)preg_match('/^[[:punct:]]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}

if (!function_exists('ctype_space')) {
    /**
     * Check for whitespace character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-space.php
     */
    function ctype_space($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return $text==32 || ($text >=9 && $text <=13);
        } else {
            return ((int)preg_match('/^[\s\v]+$/', (string)$text) > 0);
        }
    }
}

if (!function_exists('ctype_upper')) {
    /**
     * Check for uppercase character(s)
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-upper.php
     */
    function ctype_upper($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ($text >= ord('A') && $text <= ord('Z'));
        } else {
            return ((int)preg_match('/^[A-Z]+$/', (string)$text) > 0);
        }
    }
}

if (!function_exists('ctype_xdigit')) {
    /**
     * Check for character(s) representing a hexadecimal digit
     *
     * @param   string  $text
     * @return  bool
     * @since   PHP 4.0.4
     * @see     http://php.net/manual/en/function.ctype-xdigit.php
     */
    function ctype_xdigit($text) {
        if (is_int($text) && $text >= -128 && $text <= 255) {
            if ($text < 0) {
                $text += 256;
            }
            return ((int)preg_match('/^[0-9A-Fa-f]+$/', chr($text)) > 0);
        } elseif (is_string($text) || is_int($text)) {
            return ((int)preg_match('/^[0-9A-Fa-f]+$/', (string)$text) > 0);
        } else {
            return false;
        }
    }
}


// ----------------------------------------------------------------------------
//
// filesystem
//
// ----------------------------------------------------------------------------


if (!function_exists('file_get_contents')) {
    /**
     * Reads entire file into a string
     *
     * @param   string      $filename
     * @param   bool        $use_include_path
     * @param   resource    $context
     * @param   int         $offset
     * @param   int         $maxlen
     * @return  string
     * @since   PHP 4.3
     * @see     http://php.net/manual/en/function.file-get-contents.php
     */
    function file_get_contents($filename, $use_include_path=false, $context=null, $offset=-1, $maxlen=-1) {
        $fopen_args = array(
            $filename,
            'r',
            $use_include_path
        );
        if (is_resource($context)) {
            $fopen_args[] = $context;
        }
        if (($fd = call_user_func_array('fopen', $fopen_args)) !== false) {
            if ($offset > 0) {
                fseek($fd, $offset);
            }
            $buffer = '';
            while (!feof($fd) && ($maxlen < 0 || ($r=$maxlen-strlen($buffer)) > 0)) {
                if (($data = fread($fd, ($maxlen < 0 || $r > 8192) ? 8192 : $r%8192)) === false) {
                    fclose($fd);
                    return false;
                }
                $buffer .= $data;
            }
            fclose($fd);
            return $buffer;
        }
        return false;
    }
}

if (!function_exists('file_put_contents')) {
    /**
     * Write a string to a file
     *
     * @param   string      $filename
     * @param   mixed       $data
     * @param   int         $flags
     * @param   resource    $context
     * @return  int
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.file-put-contents.php
     */
    function file_put_contents($filename, $data, $flags=0, $context=null) {
        $fopen_args = array(
            $filename,
            (($flags&FILE_APPEND) == FILE_APPEND) ? 'a' : 'w',
            (($flags&FILE_USE_INCLUDE_PATH) == FILE_USE_INCLUDE_PATH)
        );
        if (is_resource($context)) {
            $fopen_args[] = $context;
        }
        if (($fd = call_user_func_array('fopen', $fopen_args)) !== false) {
            if (($flags&LOCK_EX) == LOCK_EX && !flock($fd, LOCK_EX)) {
                fclose($fd);
                return false;
            }
            for ($written=0, $l=strlen($data); $written < $l; $written += $nb) {
                if (($nb = fwrite($fd, substr($data, $written))) === false) {
                    if (($flags&LOCK_EX) == LOCK_EX) {
                        flock($fd, LOCK_UN);
                    }
                    fclose($fd);
                    return false;
                }
            }
            if (($flags&LOCK_EX) == LOCK_EX) {
                flock($fd, LOCK_UN);
            }
            fclose($fd);
            return $written;
        }
        return false;
    }
}

// PHP 5.4
if (!defined('SCANDIR_SORT_ASCENDING')) {
    define('SCANDIR_SORT_ASCENDING', 0);
}
if (!defined('SCANDIR_SORT_DESCENDING')) {
    define('SCANDIR_SORT_DESCENDING', 1);
}
if (!defined('SCANDIR_SORT_NONE')) {
    define('SCANDIR_SORT_NONE', 2);
}

if (!function_exists('scandir')) {
    /**
     * List files and directories inside the specified path
     *
     * @param   string      $directory
     * @param   int         $sorting_order
     * @param   resource    $context
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.scandir.php
     */
    function scandir($directory, $sorting_order=SCANDIR_SORT_ASCENDING, $context=null) {
        $files = array();
        if (($fd = call_user_func_array('opendir', (is_resource($context)) ? array($directory, $context) : array($directory))) !== false) {
            while (($filename = readdir($fd)) !== false) {
                $files[] = $filename;
            }
            closedir($fd);
            if ($sorting_order == SCANDIR_SORT_ASCENDING) {
                sort($files);
            } elseif ($sorting_order == SCANDIR_SORT_DESCENDING) {
                rsort($files);
            }
        }
        return $files;
    }
}

if (!function_exists('stream_resolve_include_path')) {
    /**
     * Resolve filename against the include path
     *
     * @param   string
     * @return  string
     * @since   PHP 5.3.2
     * @see     http://php.net/manual/en/function.stream-resolve-include-path.php
     */
    function stream_resolve_include_path($filename) {
        foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $path) {
            if (file_exists(($file=rtrim($path, '/\\').DIRECTORY_SEPARATOR.$filename))) {
                return $file;
            }
        }
        return file_exists(($file=dirname(__FILE__).DIRECTORY_SEPARATOR.$filename)) ? $file : false;
    }
}

if (!function_exists('sys_get_temp_dir')) {
    /**
     * Returns directory path used for temporary files
     *
     * @return  string
     * @since   PHP 5.2.1
     * @see     http://php.net/manual/en/function.sys-get-temp-dir.php
     */
    function sys_get_temp_dir() {
        (($tmp_dir=(empty($_ENV['TMP']) ? '' : $_ENV['TMP'])) != ''
            || ($tmp_dir=(empty($_ENV['TMPDIR']) ? '' : $_ENV['TMPDIR'])) != ''
            || ($tmp_dir=(empty($_ENV['TEMP']) ? '' : $_ENV['TEMP'])) != ''
            || (stripos(PHP_OS, 'WIN')===0 && ($tmp_dir=(is_dir('C:\Windows\Temp')?'C:\Windows\Temp':'')) != '')
            || ($tmp_dir=ini_get('upload_tmp_dir')) != ''
            || ($tmp_dir=ini_get('session.save_path')) != '');
        return $tmp_dir;
    }
}


// ----------------------------------------------------------------------------
//
// gd
//
// ----------------------------------------------------------------------------


if (!defined('IMAGETYPE_GIF')) {
    define('IMAGETYPE_GIF', 1);
}
if (!defined('IMAGETYPE_JPEG')) {
    define('IMAGETYPE_JPEG', 2);
}
if (!defined('IMAGETYPE_PNG')) {
    define('IMAGETYPE_PNG', 3);
}
if (!defined('IMAGETYPE_SWF')) {
    define('IMAGETYPE_SWF', 4);
}
if (!defined('IMAGETYPE_PSD')) {
    define('IMAGETYPE_PSD', 5);
}
if (!defined('IMAGETYPE_BMP')) {
    define('IMAGETYPE_BMP', 6);
}
if (!defined('IMAGETYPE_TIFF_II')) {
    define('IMAGETYPE_TIFF_II', 7);
}
if (!defined('IMAGETYPE_TIFF_MM')) {
    define('IMAGETYPE_TIFF_MM', 8);
}
if (!defined('IMAGETYPE_JPC')) {
    define('IMAGETYPE_JPC', 9);
}
if (!defined('IMAGETYPE_JP2')) {
    define('IMAGETYPE_JP2', 10);
}
if (!defined('IMAGETYPE_JPX')) {
    define('IMAGETYPE_JPX', 11);
}
if (!defined('IMAGETYPE_JB2')) {
    define('IMAGETYPE_JB2', 12);
}
if (!defined('IMAGETYPE_SWC')) {
    define('IMAGETYPE_SWC', 13);
}
if (!defined('IMAGETYPE_IFF')) {
    define('IMAGETYPE_IFF', 14);
}
if (!defined('IMAGETYPE_WBMP')) {
    define('IMAGETYPE_WBMP', 15);
}
if (!defined('IMAGETYPE_XBM7')) {
    define('IMAGETYPE_XBM7', 16);
}
if (!defined('IMG_FLIP_HORIZONTAL')) {
    define('IMG_FLIP_HORIZONTAL', 1);
}
if (!defined('IMG_FLIP_VERTICAL')) {
    define('IMG_FLIP_VERTICAL', 2);
}
if (!defined('IMG_FLIP_BOTH')) {
    define('IMG_FLIP_BOTH', IMG_FLIP_HORIZONTAL|IMG_FLIP_VERTICAL);
}

if (!function_exists('image_type_to_extension')) {
    /**
     * Get file extension for image type
     *
     * @param   int     $imagetype
     * @param   bool    $include_dot
     * @return  string
     * @since   PHP 5.2
     * @see     http://php.net/manual/en/function.image-type-to-extension.php
     */
    function image_type_to_extension($imagetype, $include_dot=true) {
        $map = array (
            IMAGETYPE_GIF     => 'gif',
            IMAGETYPE_JPEG    => 'jpg',
            IMAGETYPE_PNG     => 'png',
            IMAGETYPE_SWF     => 'swf',
            IMAGETYPE_PSD     => 'psd',
            IMAGETYPE_BMP     => 'bmp',
            IMAGETYPE_TIFF_II => 'tiff',
            IMAGETYPE_TIFF_MM => 'tiff',
            IMAGETYPE_JPC     => 'jpc',
            IMAGETYPE_JP2     => 'jp2',
            IMAGETYPE_JPX     => 'jpx',
            IMAGETYPE_JB2     => 'jb2',
            IMAGETYPE_SWC     => 'swc',
            IMAGETYPE_IFF     => 'aiff',
            IMAGETYPE_WBMP    => 'wbmp',
            IMAGETYPE_XBM7    => 'xbm'
        );
        return isset($map[$imagetype]) ? ($include_dot?'.':'').$map[$imagetype] : false;
    }
}

if (!function_exists('imageflip')) {
    /**
     * Flips an image using a given mode
     *
     * @param   resource    $image
     * @param   int         $mode
     * @return  bool
     * @since   PHP 5.5.0
     * @see     http://php.net/manual/en/function.imageflip.php
     */
    function imageflip(&$image, $mode) {
        $w=imagesx($image);
        $h=imagesy($image);
        if (($mode&IMG_FLIP_HORIZONTAL) == IMG_FLIP_HORIZONTAL) {
            for ($y=0; $y<$h; $y++) {
                for ($x=0; $x<=floor($w/2); $x++) {
                    $tmp = imagecolorat($image, $w-$x-1, $y);
                    imagesetpixel($image, $w-$x, $y,imagecolorat($image, $x, $y));
                    imagesetpixel($image, $x, $y, $tmp);
                }
            }
        }
        if (($mode&IMG_FLIP_VERTICAL) == IMG_FLIP_VERTICAL) {
            for ($x=0; $x<$w; $x++) {
                for ($y=0; $y<=floor($h/2); $y++) {
                    $tmp = imagecolorat($image, $x, $h-$y-1);
                    imagesetpixel($image, $x, $h-$y, imagecolorat($image, $x, $y));
                    imagesetpixel($image, $x, $y, $tmp);
                }
            }
        }
        return true;
    }
}


// ----------------------------------------------------------------------------
//
// json
//
// ----------------------------------------------------------------------------


if (!defined('JSON_HEX_TAG')) {
    define('JSON_HEX_TAG', 1<<0);
}
if (!defined('JSON_HEX_AMP')) {
    define('JSON_HEX_AMP', 1<<1);
}
if (!defined('JSON_HEX_APOS')) {
    define('JSON_HEX_APOS', 1<<2);
}
if (!defined('JSON_HEX_QUOT')) {
    define('JSON_HEX_QUOT', 1<<3);
}
if (!defined('JSON_FORCE_OBJECT')) {
    define('JSON_FORCE_OBJECT', 1<<4);
}
if (!defined('JSON_NUMERIC_CHECK')) {
    define('JSON_NUMERIC_CHECK', 1<<5);
}
if (!defined('JSON_UNESCAPED_SLASHES')) {
    define('JSON_UNESCAPED_SLASHES', 1<<6);
}
if (!defined('JSON_PRETTY_PRINT')) {
    define('JSON_PRETTY_PRINT', 1<<7);
}
if (!defined('JSON_UNESCAPED_UNICODE')) {
    define('JSON_UNESCAPED_UNICODE', 1<<8);
}
if (!defined('JSON_PARTIAL_OUTPUT_ON_ERROR')) {
    define('JSON_PARTIAL_OUTPUT_ON_ERROR', 1<<9);
}
if (!defined('JSON_OBJECT_AS_ARRAY')) {
    define('JSON_OBJECT_AS_ARRAY', 1<<0);
}
if (!defined('JSON_BIGINT_AS_STRING')) {
    define('JSON_BIGINT_AS_STRING', 1<<1);
}

if (!function_exists('json_encode')) {
    /**
     * Returns the JSON representation of a value
     *
     * @param   mixed   $value
     * @param   int     $options
     * @param   int     $depth
     * @return  string
     * @since   PHP 5.2.0
     * @see     http://php.net/manual/en/function.json-encode.php
     *
     * @todo    use JSON_UNESCAPED_UNICODE
     */
    function json_encode($value, $options=0, $depth=512) {
        if (is_string($value)) {

            if (($options & JSON_NUMERIC_CHECK) == JSON_NUMERIC_CHECK && is_numeric($value)) {
                return (string)(float)$value;
            }

            $replace = array();
            if (($options & JSON_UNESCAPED_SLASHES) != JSON_UNESCAPED_SLASHES) {
                $replace['\\'] = '\\\\';
                $replace['/']  = '\/';
            }
            if (($options & JSON_HEX_TAG) == JSON_HEX_TAG) {
                $replace['<'] = '\u003C';
                $replace['>'] = '\u003E';
            }
            if (($options & JSON_HEX_AMP) == JSON_HEX_AMP) {
                $replace['&'] = '\u0026';
            }
            if (($options & JSON_HEX_APOS) == JSON_HEX_APOS) {
                $replace['\''] = '\u0027';
            }
            $replace['"']  = (($options & JSON_HEX_QUOT) == JSON_HEX_QUOT) ? '\u0022' : '\"';

            return '"'.str_replace(array_keys($replace), array_values($replace), $value).'"';
        } elseif (is_numeric($value)) {
            return (string)$value;
        } elseif (is_bool($value)) {
            return ($value) ? 'true' : 'false';
        } elseif ($value === null) {
            return 'null';
        } elseif (is_array($value) && array_keys($value)===range(0,count($value)-1) && ($options & JSON_FORCE_OBJECT) != JSON_FORCE_OBJECT) { // sequential array
            $a = array();
            foreach ($value as $v) {
                $a[] = json_encode($v, $options, $depth);
            }
            return '['.implode(',', $a).']';
        } elseif (is_object($value) || is_array($value)) { // object or non-sequential array
            $a = array();
            foreach ($value as $k => $v) {
                $o = (($options & JSON_FORCE_OBJECT) == JSON_FORCE_OBJECT && !is_array($v)) ? $options^JSON_FORCE_OBJECT : $options;
                $a[] = json_encode((string)$k, $o, $depth).':'.json_encode($v, $o, $depth);
            }
            return '{'.implode(',', $a).'}';
        } else {
            trigger_error('[json] (php_json_encode) type is unsupported, encoded as null', E_USER_WARNING);
            return 'null';
        }
    }
}


// ----------------------------------------------------------------------------
//
// string
//
// ----------------------------------------------------------------------------


if (!function_exists('hex2bin')) {
    /**
     * Decodes a hexadecimally encoded binary string
     *
     * @param   string  $data
     * @return  string
     * @since   PHP 5.4.0
     * @see     http://php.net/manual/en/function.hex2bin.php
     */
    function hex2bin($data) {
        return pack('H*', $data);
    }
}

if (!function_exists('lcfirst')) {
    /**
     * Make a string's first character lowercase
     *
     * @param   string  $str
     * @return  string
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.lcfirst.php
     */
    function lcfirst($str) {
        return isset($str[0]) ? strtolower($str[0]).substr($str, 1) : '';
    }
}

if (!function_exists('parse_ini_string')) {
    /**
     * Parse a configuration string
     *
     * @param   string  $ini
     * @param   bool    $process_sections
     * @param   int     $scanner_mode
     * @return  array
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.parse-ini-string.php
     */
    function parse_ini_string($ini, $process_sections=false, $scanner_mode=INI_SCANNER_NORMAL) {

        $prefix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789_'), 0, stripos(PHP_OS, 'WIN')===0 ? 3 : 8);

        if (($tempfile = tempnam(sys_get_temp_dir(), $prefix)) !== false) {
            if (($fd=fopen($tempfile, 'w')) !== false) {
                fwrite($fd, $ini);
                fclose($fd);
                return parse_ini_file($tempfile, $process_sections, $scanner_mode);
            }
            unlink($tempfile);
        }

        return false;
    }
}

if (!function_exists('str_getcsv')) {
    /**
     * Parse a CSV string into an array
     *
     * @param   string  $input
     * @param   string  $delimiter
     * @param   string  $enclosure
     * @param   string  $escape
     * @return  array
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.str-getcsv.php
     */
    function str_getcsv($input, $delimiter=',', $enclosure='"', $escape='\\') {
        $csv = false;
        if (version_compare(PHP_VERSION, '5.1.0') >= 0 && ($fd = fopen('php://temp', 'r+')) !== false) {
            if (fwrite($fd, $input) > 0 && fseek($fd, 0)==0) {
                $csv = fgetcsv($fd, strlen($input), $delimiter, $enclosure); // $escape parameter only added since PHP 5.3.0
            }
            fclose($fd);
        } elseif (($fd=tmpfile()) !== false) {
            if (fwrite($fd, $input) > 0 && fseek($fd, 0)==0) {
                $csv = fgetcsv($fd, strlen($input), $delimiter, $enclosure);
            }
            fclose($fd);
        }
        return is_array($csv) ? $csv : array($input);
    }
}

if (!function_exists('str_shuffle')) {
    /**
     * Randomly shuffles a string
     *
     * @param   string  $str
     * @return  string
     * @since   PHP 4.3.0
     * @see     http://php.net/manual/en/function.str-shuffle.php
     */
    function str_shuffle($str) {
        $array = str_split($str);
        shuffle($array);
        return implode('', $array);
    }
}

if (!function_exists('str_split')) {
    /**
     * Convert a string to an array
     *
     * @param   string  $string
     * @param   int     $split_length
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.str-split.php
     */
    function str_split($string, $split_length=1) {
        if ($string == '') {
            return array('');
        }
        $split = array();
        for ($i=0, $l=strlen($string); $i < $l; $i += $split_length) {
            $split[] = substr($string, $i, $split_length);
        }
        return $split;
    }
}

if (!function_exists('str_word_count')) {
    /**
     * Return information about words used in a string
     *
     * @param   string  $string
     * @param   int     $format
     * @param   string  $charlist
     * @return  mixed
     * @since   PHP 4.3.0
     * @see     http://php.net/manual/en/function.str-word-count.php
     */
    function str_word_count($string, $format=0, $charlist='') {
        if (!is_numeric($format)) {
            trigger_error(__FUNCTION__.'() expects parameter 2 to be long, '.gettype($string).' given', E_USER_WARNING);
            return;
        } elseif (($format=(int)$format) < 0 || $format > 2 ) {
            trigger_error(__FUNCTION__.'() Invalid format value '.$format, E_USER_WARNING);
            return false;
        }

        $offset = 0;
        if (($ltrim = preg_replace('/^[\'-]/', '', $string)) !== $string) {
            $string = $ltrim;
            $offset = 1;
        }

        if (preg_match_all('/[a-z-\''.preg_quote($charlist).']+/i', preg_replace('/[\'-]$/', '', $string), $match, ($format == 2) ? PREG_OFFSET_CAPTURE : PREG_PATTERN_ORDER) && isset($match[0])) {

            if ($format == 0) {
                return count($match[0]);
            } elseif ($format == 1) {
                return $match[0];
            } else {
                $result = array();
                foreach ($match[0] as $v) {
                    $result[$v[1]+$ltrim] = $v[0];
                }
                return $result;
            }

        } else {
            return ($format==0) ? 0 : array();
        }
    }
}

if (!function_exists('stripos')) {
    /**
     * Find the position of the first occurrence of a case-insensitive substring in a string
     *
     * @param   string  $haystack
     * @param   string  $needle
     * @param   int     $offset
     * @return  int
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.stripos.php
     */
    function stripos($haystack, $needle, $offset=0) {
        if (($n=func_num_args()) < 2) {
            trigger_error(__FUNCTION__.'() expects at least 2 parameters, '.$n.' given', E_USER_WARNING);
            return;
        }
        if (!is_string($haystack)) {
            trigger_error(__FUNCTION__.'() expects parameter 1 to be string, '.gettype($offset).' given', E_USER_WARNING);
            return;
        }
        if (!is_string($needle) && !is_numeric($needle)) {
            trigger_error(__FUNCTION__.'() needle is not a string or an integer', E_USER_WARNING);
            return;
        }
        if (!is_numeric($offset)) {
            trigger_error(__FUNCTION__.'() expects parameter 3 to be long, '.gettype($offset).' given', E_USER_WARNING);
            return;
        }
        if ($needle != '' && preg_match('/'.preg_quote($needle).'/i', $haystack, $match, PREG_OFFSET_CAPTURE, $offset) > 0 && isset($match[0][1])) {
            return $match[0][1];
        }
        return false;
    }
}

if (!function_exists('strpbrk')) {
    /**
     * Search a string for any of a set of characters
     *
     * @param   string  $haystack
     * @param   string  $char_list
     * @return  string
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.strpbrk.php
     */
    function strpbrk($haystack, $char_list) {
        for ($i=0, $length=strlen($char_list), $offset=strlen($haystack)+1; $i<$length; $i++) {
            if (($pos=strpos($haystack, $char_list[$i])) !== false && $pos < $offset) {
                $offset = $pos;
            }
        }
        return substr($haystack, $offset);
    }
}

if (!function_exists('substr_compare')) {
    /**
     * Binary safe comparison of two strings from an offset, up to length characters
     *
     * @param   string  $main_str
     * @param   string  $str
     * @param   int     $offset
     * @param   int     $length
     * @param   bool    $case_insensitivity
     * @return  int
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.substr-compare.php
     */
    function substr_compare($main_str, $str, $offset, $length=null, $case_insensitivity=false) {
        if ($offset >= strlen($main_str)) {
            trigger_error(__FUNCTION__.'() : The start position cannot exceed initial string length', E_USER_WARNING);
            return false;
        }
        if (is_int($length)) {
            $sub = substr($main_str, $offset, $length);
            $str_cmp = substr($str, 0, $length);

        } else {
            $sub = substr($main_str, $offset);
            $str_cmp = $str;
        }

        return $case_insensitivity ? strcasecmp($sub, $str_cmp) : strcmp($sub, $str_cmp);
    }
}


// ----------------------------------------------------------------------------
//
// error handling
//
// ----------------------------------------------------------------------------


/** @since PHP 5 */
if (!defined('E_STRICT')) {
    define('E_STRICT', 2048);
}
/** @since PHP 5.2.0 */
if (!defined('E_RECOVERABLE_ERROR')) {
    define('E_RECOVERABLE_ERROR', 4096);
}
/** @since PHP 5.3.0 */
if (!defined('E_DEPRECATED')) {
    define('E_DEPRECATED', 8192);
}
/** @since PHP 5.3.0 */
if (!defined('E_USER_DEPRECATED')) {
    define('E_USER_DEPRECATED', 16384);
}


// ----------------------------------------------------------------------------
//
// network
//
// ----------------------------------------------------------------------------


if (!function_exists('apache_response_headers')) {
    /**
     * Fetch all HTTP response headers
     *
     * @return  array
     * @since   PHP 4.3.0 (but may not exists on != Apache webservers)
     * @see     http://php.net/manual/en/function.apache-response-headers.php
     */
    function apache_response_headers() {
        $all_functions = get_defined_functions();
        if (in_array('headers_list', $all_functions['internal'])) {
            $headers = array();
            foreach (headers_list() as $header) {
                $split = explode(':', $header, 2);
                $headers[$split[0]] = ltrim($split[1]);
            }
            return $headers;
        } else {
            return false;
        }
    }
}

if (!function_exists('gethostname')) {
    /**
     * Gets the host name
     *
     * @return  string
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.gethostname.php
     */
    function gethostname() {
        return php_uname('n');
    }
}

if (!function_exists('header_remove')) {
    /**
     * Remove previously set headers
     *
     * @param   string  $name
     * @return  void
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/function.header-remove.php
     */
    function header_remove($name='') {
        if ($name != '') {
            header($name.':');
        } else {
            foreach (array_keys(apache_response_headers()) as $n) {
                header($n.':');
            }
        }
    }
}

if (!function_exists('headers_list')) {
    /**
     * Returns a list of response headers sent (or ready to send)
     *
     * @return  array
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.headers-list.php
     */
    function headers_list() {
        $all_functions = get_defined_functions();
        if (in_array('apache_response_headers', $all_functions['internal'])) {
            $headers = array();
            foreach (apache_response_headers() as $name => $header) {
                $headers[] = $name.': '.$header;
            }
            return $headers;
        } else {
            return array();
        }
    }
}


// ----------------------------------------------------------------------------
//
// mysqli
//
// ----------------------------------------------------------------------------


if (!function_exists('mysqli_fetch_all') && extension_loaded('mysqli')) {
    /**
     * Fetches all result rows as an associative array, a numeric array, or both
     *
     * @param   mysqli_result   $result
     * @param   int             $resulttype
     * @return  array
     * @since   PHP 5.3.0
     * @see     http://php.net/manual/en/mysqli-result.fetch-all.php
     */
    function mysqli_fetch_all($result, $resulttype=MYSQLI_NUM) {
        if (!is_object($result) || get_class($result) != 'mysqli_result') {
            trigger_error(__FUNCTION__.'() expects parameter 1 to be mysqli_result, '.gettype($result).' given', E_USER_WARNING);
            return;
        }
        $fetch = array();
        while ($row = mysqli_fetch_array($result, $resulttype)) {
            $fetch[] = $row;
        }
        return $fetch;
    }
}


// ----------------------------------------------------------------------------
//
// url
//
// ----------------------------------------------------------------------------


if (!defined('PHP_QUERY_RFC1738')) {
    define('PHP_QUERY_RFC1738', 1);
}
if (!defined('PHP_QUERY_RFC3986')) {
    define('PHP_QUERY_RFC3986', 2);
}

if (!function_exists('http_build_query')) {
    /**
     * Generate URL-encoded query string
     *
     * @param   mixed   $query_data
     * @param   string  $numeric_prefix
     * @param   string  $arg_separator
     * @param   int     $enc_type
     * @return  string
     * @since   PHP 5
     * @see     http://php.net/manual/en/function.http-build-query.php
     */
    function http_build_query($query_data, $numeric_prefix='', $arg_separator=null, $enc_type=PHP_QUERY_RFC1738)
    {
        if (!is_array($query_data) && !is_object($query_data)) {
            trigger_error(__FUNCTION__.'() : Parameter 1 expected to be Array or Object. Incorrect value given', E_USER_WARNING);
            return;
        }
        $encode = ($enc_type==PHP_QUERY_RFC3986) ? 'rawurlencode' : 'urlencode';

        if ($arg_separator === null) {
            $arg_separator = ini_get('arg_separator.output');
        }

        $query = '';
        foreach ($query_data as $k => $v) {

            if (is_array($v) || is_object($v)) {
                $args2 = array();
                foreach ($v as $k2 => $v2) {
                    $args2[$k.'['.$k2.']'] = $v2;
                }
                $query .= $arg_separator.http_build_query($args2, '', $arg_separator, $enc_type);
            } else {
                $query .= $arg_separator.(is_numeric($k)?$numeric_prefix:'').$encode($k).'='.$encode($v);
            }
        }

        return ($query != '') ? substr($query, strlen($arg_separator)) : '';
    }
}


// ----------------------------------------------------------------------------
//
// other
//
// ----------------------------------------------------------------------------


/** @since PHP 5.0.3 */
if (!defined('UPLOAD_ERR_NO_TMP_DIR')) {
    define('UPLOAD_ERR_NO_TMP_DIR', 6);
}
/** @since PHP 5.1 */
if (!defined('UPLOAD_ERR_CANT_WRITE')) {
    define('UPLOAD_ERR_CANT_WRITE', 7);
}
/** @since PHP 5.2 */
if (!defined('UPLOAD_ERR_EXTENSION')) {
    define('UPLOAD_ERR_EXTENSION', 8);
}
/** @since PHP 4.0.2 */
if (!defined('M_SQRTPI')) {
    define('M_SQRTPI', sqrt(M_PI));
}

/** @since PHP 4.3.0 */
if (!defined('PATH_SEPARATOR')) {
    define('PATH_SEPARATOR', (stripos(PHP_OS, 'WIN')===0) ? ';' : ':');
}

/** @since PHP 4.3.10 / 5.0.2 */
if (!defined('PHP_EOL')) {
    switch (stripos(PHP_OS, 'WIN')===0) {
        case 'WIN':
            define('PHP_EOL', "\r\n"); break;
        case 'DAR':
            define('PHP_EOL', "\r"); break;
        default:
            define('PHP_EOL', "\n");
    }
}

/** @since PHP 4.4.0 / 5.0.5  */
if (!defined('PHP_INT_MAX')) {
    if (is_int(9223372036854775807)) {  // 64bits
        define('PHP_INT_MAX', 9223372036854775807);
    } elseif (is_int(2147483647)) {   // 32bits
        define('PHP_INT_MAX', 2147483647);
    } else {    // 16 bits
        define('PHP_INT_MAX', 32767);
    }
}

/** @since PHP 4.2.0 */
if (!defined('PHP_SAPI') && function_exists('php_sapi_name')) {
    define('PHP_SAPI', php_sapi_name());
}

/** @since PHP 5.2.7 */
if (!defined('PHP_MAJOR_VERSION')) {
    $split = explode('.', PHP_VERSION, 1);
    define('PHP_MAJOR_VERSION', (int)$split[0]);
    unset($split);
}

/** @since PHP 5.2.7 */
if (!defined('PHP_MINOR_VERSION')) {
    $split = explode('.', PHP_VERSION, 2);
    define('PHP_MINOR_VERSION', (int)$split[1]);
    unset($split);
}

/** @since PHP 5.2.7 */
if (!defined('PHP_RELEASE_VERSION')) {
    $split = explode('.', PHP_VERSION, 3);
    define('PHP_RELEASE_VERSION', (int)$split[2]);
    unset($split);
}

/** @since PHP 5.2.7 */
if (!defined('PHP_EXTRA_VERSION')) {
    define('PHP_EXTRA_VERSION', (($p=strpos(PHP_VERSION, '-')) !== false) ? substr(PHP_VERSION, $p) : '');
    unset($p);
}

/** @since PHP 5.2.7 */
if (!defined('PHP_VERSION_ID')) {
    define('PHP_VERSION_ID', (10000*PHP_MAJOR_VERSION + 100*PHP_MINOR_VERSION + PHP_RELEASE_VERSION));
}

/**
 * Get the boolean value of a variable
 *
 * @param   mixed   $var
 * @return  bool
 * @since   PHP 5.5.0
 * @see     http://php.net/manual/en/function.boolval.php
 */
if (!function_exists('boolval')) {
    function boolval($var) {
        return (bool)$var;
    }
}
