<?php

define ('BASEPATH', __DIR__);

require BASEPATH . '/src/Core/Loader.php';

spl_autoload_register('\src\Core\Loader::autoload');

//\src\Lib\Demo::index();

//(new \App\Controller\Blog\Index)->show();

print_r(\src\Lib\Factory::create('\src\Lib\Demo'));
