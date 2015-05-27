<?php

namespace LaravelMPay24;

class Shop extends MPay24Api {
    public $tid = "My first order";
    public $price = 123.45;

    public function createTransaction()
    {
        $transaction = new Transaction($this->tid);
        $transaction->PRICE = $this->price;

        return $transaction;
    }

    public function createMDXI($transaction)
    {
        $mdxi = new ORDER();

        $mdxi->Order->Tid   = $transaction->TID;
        $mdxi->Order->Price = $transaction->PRICE;

        return $mdxi;
    }

    public function updateTransaction($tid, $args, $shippingConfirmed)
    {

    }

    public function getTransaction($tid)
    {

    }

    public function createProfileOrder($tid)
    {

    }

    public function createExpressCheckoutOrder($tid)
    {

    }

    public function createFinishExpressCheckoutOrder($tid, $shippingCosts, $amount, $cancel)
    {

    }

    public function write_log($operation, $info_to_log)
    {

    }

    public function createSecret($tid, $amount, $currency, $timeStamp)
    {

    }

    public function getSecret($tid)
    {

    }
}