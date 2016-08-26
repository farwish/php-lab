<?php
/*
 * php.ini: phar.readonly=0
 *
 *
 * @farwish
 *
 */

/*
try {

	$p = new Phar('/home/www/59admin_test.phar', 0, '59admin_test.phar');

	$arr = $p->buildFromDirectory('/home/www/59admin_test');

	$p->compressFiles(Phar::GZ);

	$p->setStub($p->createDefaultStub('index.php'));

} catch (PharException $pe) {
	echo $pe->getMessage();
}

 */

/**
 * unPhar
 *
 * @farwish
 */

/*
$phar = new Phar('my.phar');

if (! is_dir('/my')) {
	mkdir('/my');
}

$phar->extractTo('/my');

echo "Done.\n";
*/
