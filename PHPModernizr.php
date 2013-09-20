<?php
/**
 * PHPModernizr
 * Makes most of last released built-in PHP functions works on old PHP versions.
 *
 * @author  Geoffray Warnants
 * @version 1.2.20130920
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
            trigger_error(__FUNCTION__.'() expects parameter 1 to be array, '.gettype($input).' given in', E_USER_WARNING);
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
            || (preg_match('/^WIN/i', PHP_OS) && ($tmp_dir=(is_dir('C:\Windows\Temp')?'C:\Windows\Temp':'')) != '')
            || ($tmp_dir=ini_get('upload_tmp_dir')) != ''
            || ($tmp_dir=ini_get('session.save_path')) != '');
        return $tmp_dir;
    }
}


// ----------------------------------------------------------------------------
//
// string
//
// ----------------------------------------------------------------------------


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

        $prefix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789_'), 0, preg_match('/^WIN/i', PHP_OS) ? 3 : 8);

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


// ----------------------------------------------------------------------------
//
// error handling
//
// ----------------------------------------------------------------------------


// PHP 5
if (!defined('E_STRICT')) {
    define('E_STRICT', 2048);
}
// PHP 5.2.0
if (!defined('E_RECOVERABLE_ERROR')) {
    define('E_RECOVERABLE_ERROR', 4096);
}
// PHP 5.3.0
if (!defined('E_DEPRECATED')) {
    define('E_DEPRECATED', 8192);
}
if (!defined('E_USER_DEPRECATED')) {
    define('E_USER_DEPRECATED', 16384);
}


// ----------------------------------------------------------------------------
//
// network
//
// ----------------------------------------------------------------------------


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
            trigger_error(__FUNCTION__.'() expects parameter 1 to be mysqli_result, '.gettype($result).' given in', E_USER_WARNING);
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
// other
//
// ----------------------------------------------------------------------------


// PHP 5.0.3
if (!defined('UPLOAD_ERR_NO_TMP_DIR')) {
    define('UPLOAD_ERR_NO_TMP_DIR', 6);
}
// PHP 5.1
if (!defined('UPLOAD_ERR_CANT_WRITE')) {
    define('UPLOAD_ERR_CANT_WRITE', 7);
}
// PHP 5.2
if (!defined('UPLOAD_ERR_EXTENSION')) {
    define('UPLOAD_ERR_EXTENSION', 8);
}
if (!defined('M_SQRTPI')) {
    define('M_SQRTPI', sqrt(M_PI));
}
if (!defined('PHP_QUERY_RFC1738')) {
    define('PHP_QUERY_RFC1738', 1);
}
if (!defined('PHP_QUERY_RFC3986')) {
    define('PHP_QUERY_RFC3986', 2);
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
