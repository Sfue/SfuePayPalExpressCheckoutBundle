<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;

use PayPal\PayPalAPI\GetExpressCheckoutDetailsResponseType;

/**
 * Class PayPalCheckoutRequest
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutDetails
{
    /** @var GetExpressCheckoutDetailsResponseType */
    protected $checkoutDetails;

    /**
     * PayPalCheckoutDetails constructor.
     * @param GetExpressCheckoutDetailsResponseType $detail
     */
    public function __construct(GetExpressCheckoutDetailsResponseType $detail) {
        $this->checkoutDetails = $detail;
    }

    /**
     * @return GetExpressCheckoutDetailsResponseType
     */
    public function getDetails() {
        return $this->checkoutDetails;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->checkoutDetails->GetExpressCheckoutDetailsResponseDetails->Token;
    }

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->checkoutDetails->GetExpressCheckoutDetailsResponseDetails->PaymentRequestInfo[0]->TransactionId;
    }
}