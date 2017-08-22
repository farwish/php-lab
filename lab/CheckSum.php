<?php
/**
 * CheckSum
 *
 * 这里的Client和Server都是服务器级别，非网页/App存储密钥。
 *
 * @farwish
 */

// Client:

// 当前时间戳
$curtime = time();

// 应用密钥，可在应用的服务器端存储和使用
$app_secret = 'b9514c52-5363-4364-b73f-a2ec93ae6b34';

/**
 * 取得校验和
 *
 * @param $request_url string 请求地址
 * @param $app_secret  string 密钥
 * @param $encode      bool   获取sign为true/验证sign为false
 * @param $algo        string 校验算法
 *
 * @return string check_sum
 */
function getSign(string $request_url, string $app_secret, bool $encode = true, string $algo = 'sha1')
{
    parse_str( $request_url, $arr );

    if (! $encode ) {
        unset($arr['sign']);
    }

    // 1. 参数按首字母排序
    ksort($arr, SORT_REGULAR);

    $str = http_build_query($arr);

    // 校验和
    $check_sum = hash( $algo, $app_secret . $arr['nonce'] . $arr['curtime'] );

    // 后面属于加深复杂性.

    // 2. 参数字符串拼接校验码
    $new_str = $str . '&' . $check_sum;

    // 3. 生成新sign
    $sign = openssl_encrypt($new_str, 'AES-128-CBC', $app_secret, OPENSSL_RAW_DATA, substr($app_secret, 0, 16));

    return md5($sign);
}


// 模拟获取随机token的api，返回 token/nonce（uuid）
// token 设置有效期，失效重新获取
function getToken($token = '')
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    if ($token) {
        // 检测有效性    
        if ($redis->get($token)) {
            return true;
        } else {
            return false;
        }
    } else {
        // 生成新token
        $nonce = mt_rand(1, 1000);
        $redis->setEx($nonce, 60, true);
        return $nonce;
    }
}

$nonce = getToken();

echo 'token : ' . $nonce . PHP_EOL;


// 模拟 POST 应用 $request_url 接口获取 CheckSum

// -----------------------

if ( getToken($nonce) ) {
    echo "token 有效.\n";
} else {
    echo "token 无效.\n";
    die;
}

$request_url = "b=v1&a=v2&c=v3&nonce={$nonce}&curtime={$curtime}";

$sign = getSign( $request_url, $app_secret, true );

// -----------------------

sleep(2);

// 请求 api 带上 check_sum

$request_url = $request_url . "&sign={$sign}";

$decode_sign = getSign( $request_url, $app_secret, false );

if ( $sign == $decode_sign ) {
    echo "合法请求\n";
} else {
    echo "非法请求\n";
}


