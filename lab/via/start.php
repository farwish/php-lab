<?php
/**
 * Via package.
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

include 'Container.php';

use Via\Container;

$socket = 'tcp://0.0.0.0:8080';

$con = new Container();

$con
    // Parameter.
    //
    // option, default is 1
    ->setCount(3)
    // option, can also be in constructor
    ->setSocket($socket)
    // option, default is Via
    ->setTitle('Via')
    // option, default is 100
    ->setBacklog(100)
    // option, default is 30
    ->setSelectTimeout(20)
    // option, default is 60
    ->setAcceptTimeout(30)

    // Event callback.
    //
    // option, when client connected with server, callback trigger.
    ->onConnection(function($connection) {
        echo "New client connected." . PHP_EOL;
    })
    // option, when client send message to server, callback trigger.if
    ->onMessage(function($client) {
        $method             = '';
        $url                = '';
        $protocol_version   = '';

        $request_header     = [];
        $content_type       = 'text/html; charset=utf-8';
        $content_length     = 0;
        $request_body       = '';
        $end_of_header      = false;

        $buffer = fread($client, 1024);

        if (false !== $buffer) {

            // Http request format check.
            if (false !== strstr($buffer, "\r\n")) {
                $list = explode("\r\n", $buffer);
            }

            if ($list) {
                foreach ($list as $line) {
                    if ($end_of_header) {
                        if (strlen($line) === $content_length) {
                            $request_body = $line;
                        } else {
                            throw new \Exception("Content-Length {$content_length} not match request body length " . strlen($line) . "\n");
                        }
                        break;
                    }

                    if ( empty($line) ) {
                        $end_of_header = true;
                    } else {
                        // Header.
                        //
                        if (false === strstr($line, ': ')) {
                            $array = explode(' ', $line);

                            // Request line.
                            if (count($array) === 3) {
                                $method           = $array[0];
                                $url              = $array[1];
                                $protocol_version = $array[2];
                            }
                        } else {
                            $array = explode(': ', $line);

                            // Request header.
                            list ($key, $value) = $array;
                            $request_header[$key] = $value;

                            if ( strtolower($key) === strtolower('Content-type') ) {
                                $content_type = $value;
                            }

                            // Have request body.
                            if ($key === 'Content-Length') {
                                $content_length = $value;
                            }
                        }
                    }
                }
            }
        }

        // No request body, show buffer from read.
        $response_body = $request_body ?: $buffer;
        $response_header = "HTTP/1.1 200 OK\r\n";
        $response_header .= "Content-type: {$content_type}\r\n";
        if (empty($content_length) && (strlen($response_body) > 0)) {
            $response_header .= "Content-Length: " . strlen($response_body) . "\r\n";
        }
        foreach ($request_header as $k => $v) {
            $response_header .= "{$k}: {$v}\r\n";
        }
        $response_header .= "\r\n";
        fwrite($client, $response_header . $response_body);
        fclose($client);


        /* websocket request header
            GET / HTTP/1.1
            Host: 10.0.2.15:8080
            Connection: Upgrade
            Pragma: no-cache
            Cache-Control: no-cache
            Upgrade: websocket
            Origin: http://127.0.0.1
            Sec-WebSocket-Version: 13
            User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/63.0.3239.84 Chrome/63.0.3239.84 Safari/537.36
            Accept-Encoding: gzip, deflate
            Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7
            Sec-WebSocket-Key: tefTZ4qXPIuVDHW08jzLJg==
            Sec-WebSocket-Extensions: permessage-deflate; client_max_window_bits
         */

        /*
        $buffer = '';
        $sec_websocket_key = '';
        while ( !preg_match('/\r?\n\r?\n/', $buffer) && ($buffer = fgets($client, 4096)) !== false ) {
            if ( preg_match('/Sec-WebSocket-Key/', $buffer) ) {
                $sec_websocket_key = substr($buffer, strpos($buffer, ' ') + 1);
            }
        }

        // server need response.
        $header = "HTTP/1.1 101 Switching Protocols\r\n";
        $header .= "Upgrade: websocket\r\n";
        $header .= "Connection: Upgrade\r\n";
        $header .= "Sec-WebSocket-Accept: " . base64_encode(sha1($sec_websocket_key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)) . "\r\n";
        $header .= "Sec-WebSocket-Version: 13\r\n";
         */

        /*
        $body = 'AAAA';
        $header = "HTTP/1.1 200 OK\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-Length: " . strlen($body) . "\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";
        $header .= "Server: via\r\n";
        $header .= "X-Powered-By: PHP/8\r\n";
        $header .= "\r\n";
        fwrite($client, $header . $body);
        fclose($client);
         */
    })

    // Start server.
    //
    ->start();

