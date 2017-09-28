<?php
/**
 * Simulate signin.
 *
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 *
 * @param string $url
 * @param array  $options 
    [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content'=> http_build_query([
                'username' => 'user',
                'password' => 'user',
            ]),
            'max_redirects' => 0, # Ignore redirects.
            'ignore_errors' => 1, # Fetch the content even on failure status codes.
        ],
    ];
 * @see http://php.net/manual/en/context.http.php
 */
function simulate_signin($url, $options)
{
    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);

    return $result;
}

$url = 'https://www.baidu.com';

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content'=> http_build_query([
            'token' => '7633dd1b8e8d5fe20643e40d702230dfa7c8cf42',

            'title' => '测试',
            'start_time' => '2017-10-01 23:23',
            'start_place' => '花海',
            'type' => 1,
            'placard' => '2.jpg',
            'details' => '详情...',

            //'activity_id' => 10,
            //'apply_name' => 'aaa',
            //'mobile' => '14398989897',

            //'username' => 'lg',
            //'password' => '123456',
            //'time' => 1505998131,
            //'sign' => '20e8db5d2e5f6f5497ffd3540b7aa13a4c1d773e',
        ]),
//        'max_redirects' => 0, // Ignore redirects.
//        'ignore_errors' => 1, // Fetch the content even on failure status codes.
    ],
];

print_r( simulate_signin($url, $options) );
