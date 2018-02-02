<?php
/**
 * DomCrawler - Provides methods query and manipulate HTML documents.
 *
 * Crawler's implementation is based on the DOM extension.
 *
 * @see https://symfony.com/doc/current/components/dom_crawler.html
 * @author <farwish@foxmail.com>
 */

use Symfony\Component\DomCrawler\Crawler;

include __DIR__ . '/../../../vendor/autoload.php';

$html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
        <span class="btn">Hello php.</span>
        <a href="https://symfony.com/doc/current/components/dom_crawler.html">DomCrawler</a>
    </body>
</html>
HTML;

$crawler = new Crawler($html);

foreach ($crawler as $domElement) {
    var_dump($domElement->nodeName);    // html
}

// Need CssSelector component.
$text = $crawler->filter('body > p')->eq(0)->text();
var_dump($text);                        // Hello World!

// All traversal methods return a new Crawler instance.
$crawler_siblings = $crawler->filter('body > p')->last()->nextAll()->eq(0);

/* @var Crawler $crawler_siblings */
foreach ($crawler_siblings as $domElement) {
    var_dump("tagName: " . $domElement->tagName);
    var_dump("nodeName: " . $domElement->nodeName);
    var_dump("nodeType: " . $domElement->nodeType);
    var_dump("nodeValue: " . $domElement->nodeValue);
    var_dump("textContent: " . $domElement->textContent);
    var_dump("baseURI: " . $domElement->baseURI);
    var_dump("getLineNo: " . $domElement->getLineNo());
    var_dump("getNodePath: " . $domElement->getNodePath());
    var_dump("namespaceURI: " . $domElement->namespaceURI);
    var_dump("getAttribute: " . $domElement->getAttribute('class'));
}

$link_crawler = $crawler->selectLink('DomCrawler')->eq(0);

var_dump($link_crawler->attr('href'));

/*

string(13) "tagName: span"
string(14) "nodeName: span"
string(11) "nodeType: 1"
string(21) "nodeValue: Hello php."
string(23) "textContent: Hello php."
string(9) "baseURI: "
string(12) "getLineNo: 6"
string(28) "getNodePath: /html/body/span"
string(14) "namespaceURI: "
string(17) "getAttribute: btn"

*/
