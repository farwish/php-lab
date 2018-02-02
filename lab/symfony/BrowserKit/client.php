<?php
/**
 * BrowserKit - Make internal requests to your application.
 *
 * If you need to make requests to external sites and applications, consider using Goutte.
 *
 * Request   是一个简单包装请求中的各部分信息的容器，以提供存取。
 * Response  是一个简单包装 content, status, headers 的对象，仅仅用于返回，return new Response()。
 * Cookie    是一个 cookie 信息的容器，操作逻辑和原生cookie函数基本一致，可以理解为只在程序中传递的 cookie，不是在浏览器中真实设置的 cookie。
 * CookieJar 是所有不重复未过期 Cookie对象 的容器，内部设置原理是 $this->cookieJar[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;
 * History   是记录每个 Request对象 的容器，以提供存取，内部设置原理是 $this->stack[] = clone $request; $this->position = count($this->stack) - 1;
 * Client    一个模拟浏览器的客户端，内部组合应用 Request, Cookie, CookieJar, History 以及 DomCrawler, Process 组件，Process 实际请求脚本，DomCrawler 实际处理 HTML 文档。
 *
 * 以上容器的概念等同于对象，提供OOP的操作。
 *
 * @see https://symfony.com/doc/current/components/browser_kit.html
 * @author <farwish@foxmail.com>
 */

use Symfony\Component\BrowserKit\Client as BaseClient;
use Symfony\Component\BrowserKit\Response;

include __DIR__ . '/../../../vendor/autoload.php';

// Creating a Client.
class Client extends BaseClient
{
    protected function doRequest($request)
    {
        return new Response();
    }
}

// Making Requests.
$client = new Client();
$crawler = $client->request('GET', '/');

print_r($crawler);