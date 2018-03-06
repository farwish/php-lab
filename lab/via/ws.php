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

$has_hand_shake = false;

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
    ->setSelectTimeout(5)
    // option, default is 60
    ->setAcceptTimeout(10)

    // Event callback.
    //
    // option, when client connected with server, callback trigger.
    ->onConnection(function($connection) {
        echo "New client connected." . PHP_EOL;
    })
    // option, when client send message to server, callback trigger.if
    ->onMessage(function($connection) use ($has_hand_shake) {

        if (! $has_hand_shake) {

            $method = '';
            $url = '';
            $protocol_version = '';

            $request_header = [];
            $content_type = 'text/html; charset=utf-8';
            $content_length = 0;
            $request_body = '';
            $end_of_header = false;

            $buffer = fread($connection, 8192);

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

                        if (empty($line)) {
                            $end_of_header = true;
                        } else {
                            // Header.
                            //
                            if (false === strstr($line, ': ')) {
                                $array = explode(' ', $line);

                                // Request line.
                                if (count($array) === 3) {
                                    $method = $array[0];
                                    $url = $array[1];
                                    $protocol_version = $array[2];
                                }
                            } else {
                                $array = explode(': ', $line);

                                // Request header.
                                list ($key, $value) = $array;
                                $request_header[$key] = $value;

                                if (strtolower($key) === strtolower('Content-type')) {
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

            // Protocol handshake: https://en.wikipedia.org/wiki/WebSocket#Overview
            // Do handshake.
            $response_header = "HTTP/1.1 101 Switching Protocols\r\n";
            $response_header .= "Upgrade: websocket\r\n";
            $response_header .= "Connection: Upgrade\r\n";
            $response_header .= "Sec-WebSocket-Accept: " .
                                base64_encode(sha1($request_header['Sec-WebSocket-Key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)) . "\r\n";
            // TODO custom server and other appended header.
            $response_header .= "Server: Via\r\n";
            $response_header .= "\r\n";

            if (false !== fwrite($connection, $response_header, strlen($response_header))) {
                echo "Hand Shake Success\n";
            }
        }

//        print_r($request_header);

        if ( $buffer = fread($connection, 8192) ) {

            $len = $masks = $data = $decoded = null;
            $len = ord($buffer[1]) & 127;
            if ($len === 126) {
                $masks = substr($buffer, 4, 4);
                $data = substr($buffer, 8);

            }
            else if ($len === 127) {
                $masks = substr($buffer, 10, 4);
                $data = substr($buffer, 14);
            }
            else {
                $masks = substr($buffer, 2, 4);
                $data = substr($buffer, 6);
            }
            //
            for ($index = 0; $index < strlen($data); $index++) {
                $decoded .= $data[$index] ^ $masks[$index % 4];
            }
            echo "Recv from client: {$decoded}\n";
        }

        while (true) {
            $s = rand();

            $a = str_split($s, 125);
            //添加头文件信息，不然前台无法接受
            if (count($a) == 1) {
                $ns = "\x81" . chr(strlen($a[0])) . $a[0];
            } else {
                $ns = "";
                foreach ($a as $o) {
                    $ns .= "\x81" . chr(strlen($o)) . $o;
                }
            }

            // errno=32 Broken pipe: http://www.php.net/manual/fr/function.fwrite.php#96951
            // sleep 1 before fwrite.
            if (is_resource($connection) && feof($connection)) {
                fclose($connection);
            } else {

                // TODO: must sleep reason.
                sleep(1);

                // If client browser refresh, will cause SIGPIPE problem and strace will see infinite loop!
                $length = @fwrite($connection, $ns, 8192);

                if ((false !== $length) && ($length === strlen($ns))) {
                    echo 111 . ' ' . PHP_EOL;
                }
            }
        }
    })

    // Start server.
    //
    ->start();

