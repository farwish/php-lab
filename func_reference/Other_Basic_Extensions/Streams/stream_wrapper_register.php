<?php

/**
 * Wrapper 相关
 * 
 * stream_wrapper_register()
 * stream_wrapper_restore()
 * stream_wrapper_unregister()
 *
 * @farwish
 */

/**
 * streamWrapper implement.
 *
 * @link php.net/manual/en/class.streamwrapper.php
 *
 */
class WcStream
{
    public $position;

    public $varname;

    function stream_open($path, $mode, $options, &$opend_path)
    {
        $url = parse_url($path);

        $this->varname = $url['host'];
        $this->position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_write($data)
    {
        $left = substr($GLOBALS[$this->varname], 0, $this->position);
        $right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
        $GLOBALS[$this->varname] = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return (bool)$this->position = strlen($GLOBALS[$this->varname]);
    }

    function stream_seek($offset, $whence)
    {
        switch ($whence)
        {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->varname]) && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_END:
                if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
                    $this->position = strlen($GLOBALS[$this->varname]) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }
}

/**
 * 自定义包装类例子
 *
 * @link php.net/manual/en/stream.streamwrapper.example-1.php
 */

$existed = in_array( 'var', stream_get_wrappers() );

if ($existed) {
    stream_wrapper_unregister('var');
}

$bool = stream_wrapper_register('var', 'WcStream');

$myvar = '';

if ($bool) {

    $fp = fopen("var://myvar", "r+");

    fwrite($fp, "line1\n");
    fwrite($fp, "line2\n");
    fwrite($fp, "line3\n");

    rewind($fp);

    while (! feof($fp)) {
        echo fgets($fp);
    }

    fclose($fp);

    echo $myvar;

    if ($existed) {
        stream_wrapper_restore();
    }
}

print_r(stream_get_wrappers());
