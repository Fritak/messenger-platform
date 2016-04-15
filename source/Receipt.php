<?php

namespace fritak\MessengerPlatform;


/**
 * Receipt template.
 * 
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#request
 */
class Receipt
{
    /** @var string Recipient's Name */
    protected $recipientName;
    
    /** @var string Order number, must be unique. */
    protected $orderNumber;

    /** @var string Currency for order. */
    protected $currency;
    
    /** @var string Payment method details. This can be a custom string. Ex: 'Visa 1234'. */
    protected $paymentMethod;
    
    /** @var string Timestamp of order. */
    protected $timestamp;
    
    /** @var string URL of order. */
    protected $orderUrl;
    
    public function __construct($recipientName, $orderNumber, $currency, $payment_method, $elements, Summary $summary, 
                                Address $address = NULL, $adjustments = NULL, $timestamp = NULL, $orderUrl = NULL)
    {
        $this->recipientName    = $recipientName;
        $this->orderNumber      = $orderNumber;
        $this->currency         = $currency;
        $this->paymentMethod   = $payment_method;
        $this->elements         = $elements;
        $this->summary          = $summary;
        $this->address          = $address;
        $this->adjustments      = $adjustments;
        $this->timestamp        = $timestamp;
        $this->orderUrl         = $orderUrl;
    }
    
    public function getDataForCall()
    {
        $payload = [];
        $payload['recipient_name']  = $this->recipientName;
        $payload['order_number']    = $this->orderNumber;
        $payload['currency']        = $this->currency;
        $payload['payment_method']  = $this->paymentMethod;
        $payload['order_url']       = $this->orderUrl;
        $payload['timestamp']       = $this->timestamp;
        $payload['elements']        = [];
        
        
        if(!empty($this->address))
        {
            $payload['address'] = $this->address->getDataForCall();
        }
        
        if(!empty($this->summary))
        {
            $payload['summary'] = $this->summary->getDataForCall();
        }

        foreach ($this->elements as $element) 
        {
            $payload['elements'][] = $element->getDataForCall();
        }

        if(!empty($this->adjustments))
        {
            $payload['adjustments'] = [];
            foreach ($this->adjustments as $adjustments) 
            {
                $payload['adjustments'][] = $adjustments->getDataForCall();
            }
        }
        
        return $payload;
    }
}

/**
 * @property string $street_1 Street Address, line 1 <strong>required</strong>
 * @property string $street_2 Street Address, line 2
 * @property string $city City <strong>required</strong>
 * @property string $postal_code US Postal Code <strong>required</strong>
 * @property string $state Two-letter state abbrevation (US) <strong>required</strong>
 * @property string $country Two-letter country abbreviation <strong>required</strong>
 */
class Address extends BaseObject {}

/**
 * @property string $name Name of adjustment
 * @property float $amount Adjusted amount
 */
class Adjustment extends BaseObject  {}

/**
 * @property string $title Title of item <strong>required</strong>
 * @property string $subtitle Subtitle of item
 * @property int $quantity Quantity of item
 * @property float $price Item price  <strong>required</strong>
 * @property string $currency Currency of price
 * @property string $image_url Image URL of item
 */
class ReceiptElement extends BaseObject  {}

/**
 * @property type $subtotal Subtotal
 * @property type $shippingCost Cost of shipping
 * @property type $total_tax Total tax
 * @property type $total_cost Total cost <strong>required</strong>
 */
class Summary extends BaseObject  {}