<?php

namespace Sfue\PayPalExpressCheckoutBundle\Model;

/**
 * Class PayPalCheckoutOrderInterface
 * @package Sfue\PayPalExpressCheckoutBundle\Model
 */
interface PayPalCheckoutOrderInterface
{
    /**
     * @return bool
     */
    public function getPaid();

    /**
     * @return bool
     */
    public function isPaid();

    /**
     * @param bool $paid
     * @return void
     */
    public function setPaid($paid);

    /**
     * @return \DateTime
     */
    public function getPaidAt();

    /**
     * @param \DateTime $paidAt
     * @return void
     */
    public function setPaidAt(\DateTime $paidAt);

    /**
     * @return string
     */
    public function getPaypalCheckoutToken();

    /**
     * @param string $token
     * @return void
     */
    public function setPaypalCheckoutToken($token);

    /**
     * @return string
     */
    public function getPaypalPayerId();

    /**
     * @param string $payerId
     * @return string
     */
    public function setPaypalPayerId($payerId);

    /**
     * @return string
     */
    public function getPaypalTransactionId();

    /**
     * @param string $transactionId
     * @return void
     */
    public function setPaypalTransactionId($transactionId);
}