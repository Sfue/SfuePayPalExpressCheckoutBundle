<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;

/**
 * Class PayPalCheckoutFinalizeResponse
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutFinalizeResponse
{
    /** @var string */
    protected $token;
    /** @var string */
    protected $transactionId;

    /**
     * PayPalCheckoutFinalizeResponse constructor.
     * @param string $token
     * @param string $transactionId
     */
    public function __construct($token, $transactionId) {
        $this->token = $token;
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->transactionId;
    }
}