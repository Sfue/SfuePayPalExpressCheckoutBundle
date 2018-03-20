<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;

/**
 * Class PayPalCheckoutOrderAddress
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 *
 * @todo add validation method
 * @todo add property constraint exceptions
 */
class PayPalCheckoutOrderAddress
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $street;
    /** @var string */
    protected $city;
    /** @var string */
    protected $zip;
    /** @var string */
    protected $countryCode;

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street) {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip) {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode) {
        $this->countryCode = \strtolower($countryCode);
    }
}