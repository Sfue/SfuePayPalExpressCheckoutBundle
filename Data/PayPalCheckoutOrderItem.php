<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;

/**
 * Class PayPalCheckoutOrderItem
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutOrderItem
{
    /** @var string */
    protected $name;

    /** @var int */
    protected $quantity;

    /** @var string */
    protected $category;

    /** @var int */
    protected $tax;

    /** @var float */
    protected $singlePrice;

    /** @var float */
    protected $totalGross;

    /** @var float */
    protected $taxValue;

    /** @var float */
    protected $taxTotalValue;

    /** @var float */
    protected $netValue;

    /**
     * PayPalCheckoutOrderItem constructor.
     * @param null $name
     * @param float $singlePrice
     * @param int $tax
     * @param int $quantity
     * @param string $category
     */
    public function __construct($name = null, $singlePrice = 0.0, $tax = 0, $quantity = 1, $category = 'Physical') {
        // @todo add additional item information properties
        // @todo add validation exception
        $this->setName($name);
        $this->setSinglePrice($singlePrice);
        $this->setQuantity($quantity);
        $this->setCategory($category);
        $this->setTax($tax);

        $this->calculateTotalGross();
        $this->calculateTaxValue();
        $this->calculateTaxTotalValue();
        $this->calculateNetValue();
    }

    /**
     * Calculates the item tax value
     */
    protected function calculateTaxValue() {
        $this->taxValue = \round($this->getSinglePrice() - ($this->getSinglePrice() / (1 + $this->getTax() / 100)), 2);
    }

    /**
     * Calculates the item tax total value
     */
    protected function calculateTaxTotalValue() {
        $this->taxTotalValue = \round(($this->getSinglePrice() * $this->getQuantity()) - ($this->getSinglePrice() * $this->getQuantity() / (1 + $this->getTax() / 100)), 2);
    }

    /**
     * Calculates the item total gross
     */
    protected function calculateTotalGross() {
        $this->totalGross = \round($this->getSinglePrice() * $this->getQuantity(), 2);
    }

    /**
     * Calculates the item net value
     */
    protected function calculateNetValue() {
        $this->netValue = \round(($this->getSinglePrice() / (1 + $this->getTax() / 100)), 2);
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName($name) {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getTotalGross() {
        return $this->totalGross;
    }

    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    protected function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param string $category
     */
    protected function setCategory($category) {
        $this->category = \ucfirst(\strtolower($category));
    }

    /**
     * @return int
     */
    public function getTax() {
        return $this->tax;
    }

    /**
     * @param int $tax
     */
    protected function setTax($tax) {
        $this->tax = $tax;
    }

    /**
     * @return float
     */
    public function getSinglePrice() {
        return $this->singlePrice;
    }

    /**
     * @param float $singlePrice
     */
    protected function setSinglePrice($singlePrice) {
        $this->singlePrice = $singlePrice;
    }

    /**
     * @return float
     */
    public function getTaxValue() {
        return $this->taxValue;
    }

    /**
     * @return float
     */
    public function getNetValue() {
        return $this->netValue;
    }

    /**
     * @return float
     */
    public function getTaxTotalValue() {
        return $this->taxTotalValue;
    }
}