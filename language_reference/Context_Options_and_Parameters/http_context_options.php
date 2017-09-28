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
        'content'=> '',
//        'max_redirects' => 0, // Ignore redirects.
//        'ignore_errors' => 1, // Fetch the content even on failure status codes.
    ],
];

print_r( simulate_signin($url, $options) );
