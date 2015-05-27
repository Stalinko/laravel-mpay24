<?php

namespace LaravelMPay24;

use LaravelMPay24\Models\Exception;

class Shop extends MPay24Shop {
    /** @var Transaction */
    private $transaction;
    /** @var ORDER */
    private $order;

    private $callbacks = [
        'success' => '',
        'error' => '',
        'confirmation' => '',
    ];

    function __construct($merchantID, $soapPassword, $test, $proxyHost=null, $proxyPort=null, $debug=false)
    {
        $this->callbacks = [
            'success' => config('services.mpay24.successUrl'),
            'error' => config('services.mpay24.errorUrl'),
            'confirmation' => config('services.mpay24.confirmationUrl'),
        ];

        parent::MPay24Shop($merchantID, $soapPassword, $test, $proxyHost=null, $proxyPort=null, $debug=false);
    }

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

    /**
     * @return Transaction
     * @throws Exception
     */
    public function createTransaction()
    {
        if(!$this->transaction) {
            throw new Exception('Transaction is not set');
        }
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     * @return ORDER
     * @throws Exception
     */
    public function createMDXI($transaction)
    {
        if(!$this->order) {
            throw new Exception('Order is not set');
        }
        $this->prepareOrderUrls($this->order);

        return $this->order;
    }

    /**
     * @param ORDER $order
     */
    private function prepareOrderUrls(ORDER $order)
    {
        if(empty($order->Order->URL->Success) && !empty($this->callbacks['success'])) {
            $order->Order->URL->Success = action($this->callbacks['success']);
        }

        if(empty($order->Order->URL->Error) && !empty($this->callbacks['error'])) {
            $order->Order->URL->Error = action($this->callbacks['error']);
        }

        if(empty($order->Order->URL->Confirmation) && !empty($this->callbacks['confirmation'])) {
            $order->Order->URL->Confirmation = action($this->callbacks['confirmation']);
        }
    }

    /** Required stubs */

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