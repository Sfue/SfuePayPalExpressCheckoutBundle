<?php

namespace Sfue\PayPalExpressCheckoutBundle\Data;
use PayPal\PayPalAPI\SetExpressCheckoutResponseType;

/**
 * Class PayPalCheckoutRequest
 * @package Sfue\PayPalExpressCheckoutBundle\Data
 */
class PayPalCheckoutRequest
{
    /** @var string */
    protected $token;

    /** @var \DateTime */
    protected $timestamp;

    /** @var string */
    protected $ack;

    /** @var string */
    protected $correlationID;

    /** @var string */
    protected $errors;

    /** @var string */
    protected $version;

    /** @var string */
    protected $build;

    /** @var bool */
    protected $sandbox;

    /** @var string */
    protected $apiLiveUrl = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';

    /** @var string */
    protected $apiSandboxUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';

    /**
     * PayPalCheckoutRequest constructor.
     * @param SetExpressCheckoutResponseType $responseType
     */
    public function __construct(SetExpressCheckoutResponseType $responseType, $sandbox) {
        $this->token = $responseType->Token;
        $this->timestamp = new \DateTime($responseType->Timestamp);
        $this->ack = $responseType->Ack;
        $this->correlationID = $responseType->CorrelationID;
        $this->errors = $responseType->Errors;
        $this->version = $responseType->Version;
        $this->build = $responseType->Build;

        $this->sandbox = $sandbox;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getAck() {
        return $this->ack;
    }

    /**
     * @return mixed
     */
    public function getCorrelationID() {
        return $this->correlationID;
    }

    /**
     * @return mixed
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getBuild() {
        return $this->build;
    }

    /**
     * @return string
     */
    public function getApiLiveUrl() {
        return $this->apiLiveUrl;
    }

    /**
     * @return string
     */
    public function getApiSandboxUrl() {
        return $this->apiSandboxUrl;
    }

    /**
     * @return string
     */
    public function getCheckoutUrl() {
        if($this->sandbox) {
            return $this->getApiSandboxUrl() . $this->getToken();
        } else {
            return $this->getApiLiveUrl() . $this->getToken();
        }
    }
}
