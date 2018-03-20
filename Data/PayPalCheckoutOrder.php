<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;

/**
 * Class PayPalCheckoutOrder
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutOrder
{
    /** @var array */
    protected $items = [];
    /** @var string */
    protected $returnUrl;
    /** @var string */
    protected $cancelUrl;
    /** @var string */
    protected $notificationUrl;
    /** @var PayPalCheckoutOrderAddress */
    protected $address;
    /** @var string */
    protected $currency;

    /**
     * PayPalCheckoutOrder constructor.
     * @param $defaultCurrency
     */
    public function __construct($defaultCurrency) {
        $this->address = new PayPalCheckoutOrderAddress();
        $this->currency = $defaultCurrency;
    }

    /**
     * @param null $name
     * @param float $totalGross
     * @param int $tax
     * @param int $quantity
     * @param string $category
     */
    public function addItem($name = null, $totalGross = 0.0, $tax = 0, $quantity = 1, $category = 'physical') {
        $this->items[] = new PayPalCheckoutOrderItem($name, $totalGross, $tax, $quantity, $category);
    }

    /**
     * @return float|int
     */
    public function getTotalGross() {
        $total = 0;

        /** @var PayPalCheckoutOrderItem $item */
        foreach($this->items as $item) {
            $total += $item->getTotalGross();
        }

        return $total;
    }

    /**
     * @return float|int
     */
    public function getTotalTax() {
        $total = 0;

        /** @var PayPalCheckoutOrderItem $item */
        foreach($this->items as $item) {
            $total += $item->getTaxTotalValue();
        }

        return $total;
    }

    /**
     * @return float|int
     */
    public function getItemTotalWithoutTax() {
        $total = 0;

        /** @var PayPalCheckoutOrderItem $item */
        foreach($this->items as $item) {
            $total += $item->getNetValue() * $item->getQuantity();
        }

        return $total;
    }

    /**
     * @return string
     */
    public function getReturnUrl() {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl($returnUrl) {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return string
     */
    public function getCancelUrl() {
        return $this->cancelUrl;
    }

    /**
     * @param string $cancelUrl
     */
    public function setCancelUrl($cancelUrl) {
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * @return string
     */
    public function getNotificationUrl() {
        return $this->notificationUrl;
    }

    /**
     * @param string $notificationUrl
     */
    public function setNotificationUrl($notificationUrl) {
        $this->notificationUrl = $notificationUrl;
    }

    /**
     * @return PayPalCheckoutOrderAddress
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param PayPalCheckoutOrderAddress $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items) {
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency) {
        $this->currency = \strtoupper($currency);
    }
}
