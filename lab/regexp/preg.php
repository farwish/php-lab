<?php

$name = '智能三星变频';

if ( preg_match('/三星/', $name, $matches) ) {
    print_r( $matches );
}
