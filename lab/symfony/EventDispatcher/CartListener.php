<?php

class CartListener extends \Symfony\Component\EventDispatcher\Event
{
    private $stock;

    public function __construct(int $stock)
    {
        $this->stock = $stock;
    }

    public function payAction()
    {
        echo "Pay action.\n";

        $this->stock--;
    }

    public function smsAction()
    {
        echo "Sms action.\n";
    }

    public function getStock()
    {
        return $this->stock;
    }
}
