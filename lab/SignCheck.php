<?php
/**
 * SignCheck : session key 对称式 加密.
 *
 * 注：公私钥模式可以避免私钥被窃取.
 *
 * @farwish
 */

// Client:

$time = time();
$url = "b=v1&a=v2&c=v3&time={$time}";
// Client和Server通用私钥.
$uuid = 'b9514c52-5363-4364-b73f-a2ec93ae6b34';

function getSign($url, $uuid, $encode = true)
{
    parse_str( $url, $arr );

    if (! $encode ) {
        unset($arr['sign']);
    }

    // 1. 参数按首字母排序
    ksort($arr, SORT_REGULAR);

    $str = http_build_query($arr);

    // 2. 参数字符串拼接私钥
    $new_str = $str . '&' . $uuid;

    // 3. 生成新sign
    $sign = openssl_encrypt($new_str, 'AES-128-CBC', $uuid, OPENSSL_RAW_DATA, substr($uuid, 0, 16));

    return md5($sign);
}

// 4. 参数拼接sign进行请求
$client_sign = getSign($url, $uuid);
$request_url = $url . "&sign={$client_sign}";

// Server:

// 去除sign重新校验，并检查time有效期
$server_sign = getSign($request_url, $uuid, false);

//sleep(4);

if ( ($client_sign == $server_sign) &&
    ( (time() - $time) < 5 ) 
) {
    echo "{$server_sign} 有效,且在有效期内.\n";
} else {
    echo "无效请求.\n";
}

