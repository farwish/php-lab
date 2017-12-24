<?php

$html = <<<HTML
<!doctype html>
<html>
    <meta charset=utf-8>
    <body>
    <a href="./ws.html">ws.html</a>
    </body>
    <script>
//
// @doc https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_client_applications
// @author farwish
//

    try {

        // 注意WebSocket对象的第二个可选参数表示子协议，支持字符串或数组;
        // 这样单个server就能实现多个 websocket 子协议，即一个服务可以处理不同类型协议的交互.
        // 构造函数在连接遇到阻塞时，将抛出异常;
        // 连接错误则触发 error 事件, 随后是触发 close 事件，表明连接关闭.
        var ws = new WebSocket('ws://192.168.157.140:8081');

        ws.onopen = function(event) {

            ws.send('hello server');
        };

        ws.onmessage = function(event) {
            alert(event.data);
        };

        ws.onclose = function() {
            console.log('server closed');
        };

        ws.onerror = function() {
            console.log('server error');
        };

    } catch (e) {
        alert(e.message);
    }

    </script>
</html>
HTML;

echo $html;
