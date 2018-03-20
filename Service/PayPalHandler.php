<?php

namespace Sfue\PayPalExpressCheckoutBundle\Service;

use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutDetails;
use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutFinalizeResponse;
use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutOrder;
use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutOrderAddress;
use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutOrderItem;
use Sfue\PayPalExpressCheckoutBundle\Data\PayPalCheckoutRequest;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

/**
 * Class PayPalhandler
 * @package Sfue\PayPalExpressCheckoutBundle\Service
 */
class PayPalHandler
{
    /** @var bool */
    protected $sandbox;
    /** @var string */
    protected $userName;
    /** @var string */
    protected $password;
    /** @var string */
    protected $signature;
    /** @var string */
    protected $merchantEmail;
    /** @var string */
    protected $defaultCurrency;
    /** @var  PayPalAPIInterfaceServiceService */
    protected $apiInterface;

    /**
     * PayPal constructor.
     * @param string $merchantEmail
     * @param string $userName
     * @param string $password
     * @param string $signature
     * @param bool $sandbox
     * @param $defaultCurrency
     */
    public function __construct($merchantEmail,
                                $userName,
                                $password,
                                $signature,
                                $sandbox = false,
                                $defaultCurrency) {
        $this->merchantEmail = $merchantEmail;
        $this->userName = $userName;
        $this->password = $password;
        $this->signature = $signature;
        $this->sandbox = $sandbox;
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @return PayPalCheckoutOrder
     */
    public function generateCheckoutOrder() {
        return new PayPalCheckoutOrder($this->defaultCurrency);
    }

    public function generateCheckoutRequest(PayPalCheckoutOrder $order) {
        // @todo verify order data

        // PayPal payment details object
        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->PaymentAction = 'Sale';
        $paymentDetails->NotifyURL = $order->getNotificationUrl();

        $paymentDetails->ShipToAddress = $this->mapAddressToAddressType($order->getAddress());
        $paymentDetails->OrderTotal = new BasicAmountType($order->getCurrency(), $order->getTotalGross());
        $paymentDetails->TaxTotal = new BasicAmountType($order->getCurrency(), $order->getTotalTax());
        $paymentDetails->ItemTotal = new BasicAmountType($order->getCurrency(), $order->getItemTotalWithoutTax());
        // @todo add shipping if available

        /** @var PayPalCheckoutOrderItem $item */
        foreach($order->getItems() as $item) {
            $itemDetail = new PaymentDetailsItemType();
            $itemDetail->Name = $item->getName();
            $itemDetail->Amount = new BasicAmountType($order->getCurrency(), $item->getNetValue());
            $itemDetail->Quantity = $item->getQuantity();
            $itemDetail->Tax = new BasicAmountType($order->getCurrency(), $item->getTaxValue());
            $itemDetail->ItemCategory = $item->getCategory();

            $paymentDetails->PaymentDetailsItem[] = $itemDetail;
        }

        $ecCheckoutDetails = new SetExpressCheckoutRequestDetailsType();
        $ecCheckoutDetails->PaymentDetails[0] = $paymentDetails;
        $ecCheckoutDetails->CancelURL = $order->getCancelUrl();
        $ecCheckoutDetails->ReturnURL = $order->getReturnUrl();

        $ecRequest = new SetExpressCheckoutReq();
        $ecRequest->SetExpressCheckoutRequest = new SetExpressCheckoutRequestType($ecCheckoutDetails);

        $paypalApiInterface = $this->getApiInterface();

        $setECResponse = $paypalApiInterface->SetExpressCheckout($ecRequest);

        return new PayPalCheckoutRequest($setECResponse, $this->sandbox);
    }

    /**
     * @param string $token
     * @param null $invoiceId
     * @throws \Exception
     */
    public function finalizeCheckout($token, $invoiceId = null) {
        $checkoutDetails = $this->getCheckoutDetails($token);

        if($checkoutDetails->getDetails()->GetExpressCheckoutDetailsResponseDetails->CheckoutStatus == 'PaymentActionNotInitiated') {
            if($invoiceId) {
                $checkoutDetails->getDetails()->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->InvoiceID = $invoiceId;
            }

            $finalCheckoutDetails = new DoExpressCheckoutPaymentRequestDetailsType();
            $finalCheckoutDetails->PayerID = $checkoutDetails->getDetails()->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
            $finalCheckoutDetails->Token = $checkoutDetails->getDetails()->GetExpressCheckoutDetailsResponseDetails->Token;
            $finalCheckoutDetails->PaymentAction = 'Sale';
            $finalCheckoutDetails->PaymentDetails[0] = $checkoutDetails->getDetails()->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0];

            $ecFinalRequest = new DoExpressCheckoutPaymentRequestType($finalCheckoutDetails);

            $DoECReq = new DoExpressCheckoutPaymentReq();
            $DoECReq->DoExpressCheckoutPaymentRequest = $ecFinalRequest;

            $paypalApiInterface = $this->getApiInterface();

            $paypalResponse = $paypalApiInterface->DoExpressCheckoutPayment($DoECReq);
            dump($paypalResponse);
            $response = new PayPalCheckoutFinalizeResponse($paypalResponse->DoExpressCheckoutPaymentResponseDetails->Token, $paypalResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID);

            // @todo add error
            return $response;
        } else {
            $response = new PayPalCheckoutFinalizeResponse($checkoutDetails->getToken(), $checkoutDetails->getTransactionId());

            // @todo add error
            return $response;
        }
    }

    /**
     * @param $token
     * @return PayPalCheckoutDetails
     * @throws \Exception
     */
    public function getCheckoutDetails($token) {
        $paypalApiInterface = $this->getApiInterface();

        $expressCheckoutRequest = new GetExpressCheckoutDetailsReq();
        $expressCheckoutRequest->GetExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);

        $checkoutDetails = $paypalApiInterface->GetExpressCheckoutDetails($expressCheckoutRequest);

        return new PayPalCheckoutDetails($checkoutDetails);
    }

    /**
     * @return PayPalAPIInterfaceServiceService
     */
    protected function getApiInterface() {
        if(!$this->apiInterface) {
            $this->apiInterface = new PayPalAPIInterfaceServiceService(array(
                "mode" => ($this->sandbox) ? 'sandbox' : 'live',
                "acct1.UserName" => $this->userName,
                "acct1.Password" => $this->password,
                "acct1.Signature" => $this->signature
            ));
        }

        return $this->apiInterface;
    }

    /**
     * @param PayPalCheckoutOrderAddress $address
     * @return AddressType
     */
    protected function mapAddressToAddressType(PayPalCheckoutOrderAddress $address) {
        $addressType = new AddressType();
        $addressType->Name = $address->getName();
        $addressType->Street1 = $address->getStreet();
        $addressType->PostalCode = $address->getZip();
        $addressType->CityName = $address->getCity();
        $addressType->Country = $address->getCountryCode();

        return $addressType;
    }
}