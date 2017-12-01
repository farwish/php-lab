<?php

class Kernel
{
    public $precreateCommonParams = ['a' => 'b'];
    public $refundCommonParams = ['c' => 'd'];

    public function getRootDir($trade_type)
    {
        $r = new \ReflectionObject($this);
        $this->rootDir = dirname($r->getFileName()); 
        echo $this->rootDir;

        switch ($trade_type) {
            case 'precreate':
                $params_name = 'precreateCommonParams';
                break;
            case 'refund':
                $params_name = 'refundCommonParams';
                break;
            default:
                return;
                break;
        }  
    
        echo $this->{$params_name}['a'];
    }
}

$k = new Kernel();

$k->getRootDir('precreate');
