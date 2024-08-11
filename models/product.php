<?php

class Product {
    public $productId;
    public $amount;
    public function __construct(string $_productId, int $_amount) {
        $this->productId = $_productId;
        $this->amount = $_amount;
    }
}