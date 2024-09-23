<?php
/**
 * DirectDebitOrder
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * finAPI Web Form 2.0
 *
 * The following pages give you some general information on how to use our APIs.<br/>The actual API services documentation then follows further below. You can use the menu to jump between API sections.<br/><br/>This page has a built-in HTTP(S) client, so you can test the services directly from within this page, by filling in the request parameters and/or body in the respective services, and then hitting the TRY button. Note that you need to be authorized to make a successful API call. To authorize, refer to the '<a target='_blank' href='https://docs.finapi.io/?product=access#tag--Authorization'>Authorization</a>' section of Access, or in case you already have a valid user token, just use the QUICK AUTH on the left.<br/>Please also remember that all user management functions should be looked up in <a target='_blank' href='https://docs.finapi.io/?product=access'>Access</a>.<br/><br/>You should also check out the <a target='_blank' href='https://documentation.finapi.io/webform/'>Web Form 2.0 Public Documentation</a> as well as <a target='_blank' href='https://documentation.finapi.io/access/'>Access Public Documentation</a> for more information. If you need any help with the API, contact <a href='mailto:support@finapi.io'>support@finapi.io</a>.<br/><h2 id=\"general-information\">General information</h2><h3 id=\"general-request-ids\"><strong>Request IDs</strong></h3>With any API call, you can pass a request ID via a header with name \"X-Request-Id\". The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error.<br/><br/>If you don't pass a request ID for a call, finAPI will generate a random ID internally.<br/><br/>The request ID is always returned back in the response of a service, as a header with name \"X-Request-Id\".<br/><br/>We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response(especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster.<h3 id=\"type-coercion\"><strong>Type Coercion</strong></h3>In order to ease the integration for some languages, which do not natively support high precision number representations, Web Form 2.0 API supports relax type binding for the openAPI type <code>number</code>, which is used for money amount fields. If you use one of those languages, to avoid precision errors that can appear from <code>float</code> values, you can pass the amount as a <code>string</code>.<h3 id=\"general-faq\"><strong>FAQ</strong></h3><strong>Is there a finAPI SDK?</strong><br/>Currently we do not offer a native SDK, but there is the option to generate an SDKfor almost any target language via OpenAPI. Use the 'Download SDK' button on this page for SDK generation.<br/><br/><strong>Why do I need to keep authorizing when calling services on this page?</strong><br/>This page is a \"one-page-app\". Reloading the page resets the OAuth authorization context. There is generally no need to reload the page, so just don't do it and your authorization will persist.
 *
 * The version of the OpenAPI document: 2.779.0
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.5.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace FinApi\Client\Model;

use \ArrayAccess;
use \FinApi\Client\ObjectSerializer;

/**
 * DirectDebitOrder Class Doc Comment
 *
 * @category Class
 * @description Direct debit order
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class DirectDebitOrder implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'DirectDebitOrder';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'payer' => '\FinApi\Client\Model\PaymentPayer',
        'amount' => '\FinApi\Client\Model\Amount',
        'purpose' => 'string',
        'sepa_purpose_code' => 'string',
        'end_to_end_id' => 'string',
        'mandate_id' => 'string',
        'mandate_date' => '\DateTime',
        'creditor_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'payer' => null,
        'amount' => null,
        'purpose' => null,
        'sepa_purpose_code' => null,
        'end_to_end_id' => null,
        'mandate_id' => null,
        'mandate_date' => 'date',
        'creditor_id' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'payer' => false,
		'amount' => false,
		'purpose' => true,
		'sepa_purpose_code' => true,
		'end_to_end_id' => true,
		'mandate_id' => false,
		'mandate_date' => false,
		'creditor_id' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'payer' => 'payer',
        'amount' => 'amount',
        'purpose' => 'purpose',
        'sepa_purpose_code' => 'sepaPurposeCode',
        'end_to_end_id' => 'endToEndId',
        'mandate_id' => 'mandateId',
        'mandate_date' => 'mandateDate',
        'creditor_id' => 'creditorId'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'payer' => 'setPayer',
        'amount' => 'setAmount',
        'purpose' => 'setPurpose',
        'sepa_purpose_code' => 'setSepaPurposeCode',
        'end_to_end_id' => 'setEndToEndId',
        'mandate_id' => 'setMandateId',
        'mandate_date' => 'setMandateDate',
        'creditor_id' => 'setCreditorId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'payer' => 'getPayer',
        'amount' => 'getAmount',
        'purpose' => 'getPurpose',
        'sepa_purpose_code' => 'getSepaPurposeCode',
        'end_to_end_id' => 'getEndToEndId',
        'mandate_id' => 'getMandateId',
        'mandate_date' => 'getMandateDate',
        'creditor_id' => 'getCreditorId'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('payer', $data ?? [], null);
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('purpose', $data ?? [], null);
        $this->setIfExists('sepa_purpose_code', $data ?? [], null);
        $this->setIfExists('end_to_end_id', $data ?? [], null);
        $this->setIfExists('mandate_id', $data ?? [], null);
        $this->setIfExists('mandate_date', $data ?? [], null);
        $this->setIfExists('creditor_id', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['payer'] === null) {
            $invalidProperties[] = "'payer' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if (!is_null($this->container['purpose']) && (mb_strlen($this->container['purpose']) > 2000)) {
            $invalidProperties[] = "invalid value for 'purpose', the character length must be smaller than or equal to 2000.";
        }

        if (!is_null($this->container['purpose']) && (mb_strlen($this->container['purpose']) < 1)) {
            $invalidProperties[] = "invalid value for 'purpose', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['sepa_purpose_code']) && !preg_match("/^[a-zA-Z0-9]{4}$/", $this->container['sepa_purpose_code'])) {
            $invalidProperties[] = "invalid value for 'sepa_purpose_code', must be conform to the pattern /^[a-zA-Z0-9]{4}$/.";
        }

        if (!is_null($this->container['end_to_end_id']) && (mb_strlen($this->container['end_to_end_id']) > 35)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be smaller than or equal to 35.";
        }

        if (!is_null($this->container['end_to_end_id']) && (mb_strlen($this->container['end_to_end_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['mandate_id'] === null) {
            $invalidProperties[] = "'mandate_id' can't be null";
        }
        if ((mb_strlen($this->container['mandate_id']) > 270)) {
            $invalidProperties[] = "invalid value for 'mandate_id', the character length must be smaller than or equal to 270.";
        }

        if ((mb_strlen($this->container['mandate_id']) < 0)) {
            $invalidProperties[] = "invalid value for 'mandate_id', the character length must be bigger than or equal to 0.";
        }

        if ($this->container['mandate_date'] === null) {
            $invalidProperties[] = "'mandate_date' can't be null";
        }
        if ($this->container['creditor_id'] === null) {
            $invalidProperties[] = "'creditor_id' can't be null";
        }
        if ((mb_strlen($this->container['creditor_id']) > 35)) {
            $invalidProperties[] = "invalid value for 'creditor_id', the character length must be smaller than or equal to 35.";
        }

        if ((mb_strlen($this->container['creditor_id']) < 0)) {
            $invalidProperties[] = "invalid value for 'creditor_id', the character length must be bigger than or equal to 0.";
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets payer
     *
     * @return \FinApi\Client\Model\PaymentPayer
     */
    public function getPayer()
    {
        return $this->container['payer'];
    }

    /**
     * Sets payer
     *
     * @param \FinApi\Client\Model\PaymentPayer $payer payer
     *
     * @return self
     */
    public function setPayer($payer)
    {
        if (is_null($payer)) {
            throw new \InvalidArgumentException('non-nullable payer cannot be null');
        }
        $this->container['payer'] = $payer;

        return $this;
    }

    /**
     * Gets amount
     *
     * @return \FinApi\Client\Model\Amount
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param \FinApi\Client\Model\Amount $amount amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        if (is_null($amount)) {
            throw new \InvalidArgumentException('non-nullable amount cannot be null');
        }
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets purpose
     *
     * @return string|null
     */
    public function getPurpose()
    {
        return $this->container['purpose'];
    }

    /**
     * Sets purpose
     *
     * @param string|null $purpose The purpose of the transfer transaction
     *
     * @return self
     */
    public function setPurpose($purpose)
    {
        if (is_null($purpose)) {
            array_push($this->openAPINullablesSetToNull, 'purpose');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('purpose', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        if (!is_null($purpose) && (mb_strlen($purpose) > 2000)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling DirectDebitOrder., must be smaller than or equal to 2000.');
        }
        if (!is_null($purpose) && (mb_strlen($purpose) < 1)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling DirectDebitOrder., must be bigger than or equal to 1.');
        }

        $this->container['purpose'] = $purpose;

        return $this;
    }

    /**
     * Gets sepa_purpose_code
     *
     * @return string|null
     */
    public function getSepaPurposeCode()
    {
        return $this->container['sepa_purpose_code'];
    }

    /**
     * Sets sepa_purpose_code
     *
     * @param string|null $sepa_purpose_code SEPA purpose code, according to ISO 20022, external codes set.<br/>Please note that the SEPA purpose code may be ignored by some banks.
     *
     * @return self
     */
    public function setSepaPurposeCode($sepa_purpose_code)
    {
        if (is_null($sepa_purpose_code)) {
            array_push($this->openAPINullablesSetToNull, 'sepa_purpose_code');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('sepa_purpose_code', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        if (!is_null($sepa_purpose_code) && (!preg_match("/^[a-zA-Z0-9]{4}$/", $sepa_purpose_code))) {
            throw new \InvalidArgumentException("invalid value for \$sepa_purpose_code when calling DirectDebitOrder., must conform to the pattern /^[a-zA-Z0-9]{4}$/.");
        }

        $this->container['sepa_purpose_code'] = $sepa_purpose_code;

        return $this;
    }

    /**
     * Gets end_to_end_id
     *
     * @return string|null
     */
    public function getEndToEndId()
    {
        return $this->container['end_to_end_id'];
    }

    /**
     * Sets end_to_end_id
     *
     * @param string|null $end_to_end_id End-To-End ID for the transfer transaction.
     *
     * @return self
     */
    public function setEndToEndId($end_to_end_id)
    {
        if (is_null($end_to_end_id)) {
            array_push($this->openAPINullablesSetToNull, 'end_to_end_id');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('end_to_end_id', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        if (!is_null($end_to_end_id) && (mb_strlen($end_to_end_id) > 35)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling DirectDebitOrder., must be smaller than or equal to 35.');
        }
        if (!is_null($end_to_end_id) && (mb_strlen($end_to_end_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling DirectDebitOrder., must be bigger than or equal to 1.');
        }

        $this->container['end_to_end_id'] = $end_to_end_id;

        return $this;
    }

    /**
     * Gets mandate_id
     *
     * @return string
     */
    public function getMandateId()
    {
        return $this->container['mandate_id'];
    }

    /**
     * Sets mandate_id
     *
     * @param string $mandate_id Mandate ID that this direct debit order is based on.
     *
     * @return self
     */
    public function setMandateId($mandate_id)
    {
        if (is_null($mandate_id)) {
            throw new \InvalidArgumentException('non-nullable mandate_id cannot be null');
        }
        if ((mb_strlen($mandate_id) > 270)) {
            throw new \InvalidArgumentException('invalid length for $mandate_id when calling DirectDebitOrder., must be smaller than or equal to 270.');
        }
        if ((mb_strlen($mandate_id) < 0)) {
            throw new \InvalidArgumentException('invalid length for $mandate_id when calling DirectDebitOrder., must be bigger than or equal to 0.');
        }

        $this->container['mandate_id'] = $mandate_id;

        return $this;
    }

    /**
     * Gets mandate_date
     *
     * @return \DateTime
     */
    public function getMandateDate()
    {
        return $this->container['mandate_date'];
    }

    /**
     * Sets mandate_date
     *
     * @param \DateTime $mandate_date Date of the mandate that this direct debit order is based on, in the format <code>YYYY-MM-DD</code>
     *
     * @return self
     */
    public function setMandateDate($mandate_date)
    {
        if (is_null($mandate_date)) {
            throw new \InvalidArgumentException('non-nullable mandate_date cannot be null');
        }
        $this->container['mandate_date'] = $mandate_date;

        return $this;
    }

    /**
     * Gets creditor_id
     *
     * @return string
     */
    public function getCreditorId()
    {
        return $this->container['creditor_id'];
    }

    /**
     * Sets creditor_id
     *
     * @param string $creditor_id Creditor ID of the source account's holder
     *
     * @return self
     */
    public function setCreditorId($creditor_id)
    {
        if (is_null($creditor_id)) {
            throw new \InvalidArgumentException('non-nullable creditor_id cannot be null');
        }
        if ((mb_strlen($creditor_id) > 35)) {
            throw new \InvalidArgumentException('invalid length for $creditor_id when calling DirectDebitOrder., must be smaller than or equal to 35.');
        }
        if ((mb_strlen($creditor_id) < 0)) {
            throw new \InvalidArgumentException('invalid length for $creditor_id when calling DirectDebitOrder., must be bigger than or equal to 0.');
        }

        $this->container['creditor_id'] = $creditor_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


