<?php
/**
 * PHPModernizr
 * Makes most of last released built-in PHP functions works on old PHP versions.
 *
 * @author  Geoffray Warnants
 * @version 1.0.20130819
 * @see     https://github.com/gwarnants/PHPModernizr
 */


// ----------------------------------------------------------------------------
//
// array
//
// ----------------------------------------------------------------------------


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
// directories
//
// ----------------------------------------------------------------------------


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
