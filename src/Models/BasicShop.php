<?php

namespace LaravelMPay24\Models;

use LaravelMPay24\ORDER;
use LaravelMPay24\Transaction;

/**
 * A simple class demonstrating use of AbstractShop
 *
 * Class BasicShop
 * @package LaravelMPay24\Models
 */
class BasicShop extends AbstractShop {
    /** @var Transaction */
    private $transaction;
    /** @var ORDER */
    private $order;

    /**
     * @param Transaction $transaction
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param ORDER $order
     */
    public function setOrder(ORDER $order)
    {
        $this->order = $order;
    }

    public function createTransaction()
    {
        return $this->transaction;
    }

    public function createMDXI($transaction)
    {
        return $this->order;
    }
}