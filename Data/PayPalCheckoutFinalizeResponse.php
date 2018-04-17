<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;
use PayPal\EBLBaseComponents\PayerInfoType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentResponseType;

/**
 * Class PayPalCheckoutFinalizeResponse
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutFinalizeResponse
{
    /** @var DoExpressCheckoutPaymentResponseType */
    protected $response;

    /** @var PayerInfoType */
    protected $payerInfo;

    /** @var \DateTime */
    protected $date;

    /**
     * PayPalCheckoutFinalizeResponse constructor.
     * @param DoExpressCheckoutPaymentResponseType $response
     * @param PayerInfoType $payerInfo
     */
    public function __construct(DoExpressCheckoutPaymentResponseType $response, PayerInfoType $payerInfo) {
        $this->response = $response;
        $this->payerInfo = $payerInfo;

        $this->date = new \DateTime($response->Timestamp);
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->response->DoExpressCheckoutPaymentResponseDetails->Token;
    }

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->response->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID;
    }

    /**
     * @return string
     */
    public function getPayerId() {
        return $this->payerInfo->PayerID;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }
}