<?php

Class ComplainListener extends \Symfony\Component\EventDispatcher\Event
{
    private $stock;

    public function __construct(int $stock)
    {
        $this->stock = $stock;
    }

    public function refundAction()
    {
        echo "Refund action.\n";

        $this->stock++;
    }

    public function getStock()
    {
        return $this->stock;
    }
}
