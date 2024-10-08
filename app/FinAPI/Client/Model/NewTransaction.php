<?php
/**
 * NewTransaction
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * finAPI Access V2
 *
 * <strong>RESTful API for Account Information Services (AIS) and Payment Initiation Services (PIS)</strong> <br/> <strong>Application Version:</strong> 2.48.1 <br/>  The following pages give you some general information on how to use our APIs.<br/> The actual API services documentation then follows further below. You can use the menu to jump between API sections. <br/> <br/> This page has a built-in HTTP(S) client, so you can test the services directly from within this page, by filling in the request parameters and/or body in the respective services, and then hitting the TRY button. Note that you need to be authorized to make a successful API call. To authorize, refer to the 'Authorization' section of the API, or just use the OAUTH button that can be found near the TRY button. <br/>  <h2 id=\"general-information\">General information</h2>  <h3 id=\"general-error-responses\"><strong>Error Responses</strong></h3> When an API call returns with an error, then in general it has the structure shown in the following example:  <pre> {   \"errors\": [     {       \"message\": \"Interface 'FINTS_SERVER' is not supported for this operation.\",       \"code\": \"BAD_REQUEST\",       \"type\": \"TECHNICAL\"     }   ],   \"date\": \"2020-11-19T16:54:06.854+01:00\",   \"requestId\": \"selfgen-312042e7-df55-47e4-bffd-956a68ef37b5\",   \"endpoint\": \"POST /api/v2/bankConnections/import\",   \"authContext\": \"1/21\",   \"bank\": \"DEMO0002 - finAPI Test Redirect Bank (id: 280002, location: none)\" } </pre>  If an API call requires an additional authentication by the user, HTTP code 510 is returned and the error response contains the additional \"multiStepAuthentication\" object, see the following example:  <pre> {   \"errors\": [     {       \"message\": \"An additional authentication is required. Please enter the following code: 123456\",       \"code\": \"ADDITIONAL_AUTHENTICATION_REQUIRED\",       \"type\": \"BUSINESS\",       \"multiStepAuthentication\": {         \"hash\": \"678b13f4be9ed7d981a840af8131223a\",         \"status\": \"CHALLENGE_RESPONSE_REQUIRED\",         \"challengeMessage\": \"An additional authentication is required. Please enter the following code: 123456\",         \"answerFieldLabel\": \"TAN\",         \"redirectUrl\": null,         \"redirectContext\": null,         \"redirectContextField\": null,         \"twoStepProcedures\": null,         \"photoTanMimeType\": null,         \"photoTanData\": null,         \"opticalData\": null,         \"opticalDataAsReinerSct\": false       }     }   ],   \"date\": \"2019-11-29T09:51:55.931+01:00\",   \"requestId\": \"selfgen-45059c99-1b14-4df7-9bd3-9d5f126df294\",   \"endpoint\": \"POST /api/v2/bankConnections/import\",   \"authContext\": \"1/18\",   \"bank\": \"DEMO0001 - finAPI Test Bank\" } </pre>  An exception to this error format are API authentication errors, where the following structure is returned:  <pre> {   \"error\": \"invalid_token\",   \"error_description\": \"Invalid access token: cccbce46-xxxx-xxxx-xxxx-xxxxxxxxxx\" } </pre>  <h3 id=\"general-paging\"><strong>Paging</strong></h3> API services that may potentially return a lot of data implement paging. They return a limited number of entries within a \"page\". Further entries must be fetched with subsequent calls. <br/><br/> Any API service that implements paging provides the following input parameters:<br/> &bull; \"page\": the number of the page to be retrieved (starting with 1).<br/> &bull; \"perPage\": the number of entries within a page. The default and maximum value is stated in the documentation of the respective services.  A paged response contains an additional \"paging\" object with the following structure:  <pre> {   ...   ,   \"paging\": {     \"page\": 1,     \"perPage\": 20,     \"pageCount\": 234,     \"totalCount\": 4662   } } </pre>  <h3 id=\"general-internationalization\"><strong>Internationalization</strong></h3> The finAPI services support internationalization which means you can define the language you prefer for API service responses. <br/><br/> The following languages are available: German, English, Czech, Slovak. <br/><br/> The preferred language can be defined by providing the official HTTP <strong>Accept-Language</strong> header. <br/><br/> finAPI reacts on the official iso language codes &quot;de&quot;, &quot;en&quot;, &quot;cs&quot; and &quot;sk&quot; for the named languages. Additional subtags supported by the Accept-Language header may be provided, e.g. &quot;en-US&quot;, but are ignored. <br/> If no Accept-Language header is given, German is used as the default language. <br/><br/> Exceptions:<br/> &bull; Bank login hints and login fields are only available in the language of the bank and not being translated.<br/> &bull; Direct messages from the bank systems typically returned as BUSINESS errors will not be translated.<br/> &bull; BUSINESS errors created by finAPI directly are available in German and English.<br/> &bull; TECHNICAL errors messages meant for developers are mostly in English, but also may be translated.  <h3 id=\"general-request-ids\"><strong>Request IDs</strong></h3> With any API call, you can pass a request ID via a header with name \"X-Request-Id\". The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. <br/><br/> If you don't pass a request ID for a call, finAPI will generate a random ID internally. <br/><br/> The request ID is always returned back in the response of a service, as a header with name \"X-Request-Id\". <br/><br/> We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster.  <h3 id=\"general-overriding-http-methods\"><strong>Overriding HTTP methods</strong></h3> Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with a special HTTP header indicating the originally intended HTTP method. <br/><br/> The header's name is <strong>X-HTTP-Method-Override</strong>. Set its value to either <strong>PATCH</strong> or <strong>DELETE</strong>. POST Requests having this header set will be treated either as PATCH or DELETE by the finAPI servers. <br/><br/> Example: <br/><br/> <strong>X-HTTP-Method-Override: PATCH</strong><br/> POST /api/v2/label/51<br/> {\"name\": \"changed label\"}<br/><br/> will be interpreted by finAPI as:<br/><br/> PATCH /api/v2/label/51<br/> {\"name\": \"changed label\"}<br/>  <h3 id=\"general-user-metadata\"><strong>User metadata</strong></h3> With the migration to PSD2 APIs, a new term called \"User metadata\" (also known as \"PSU metadata\") has been introduced to the API. This user metadata aims to inform the banking API if there was a real end-user behind an HTTP request or if the request was triggered by a system (e.g. by an automatic batch update). In the latter case, the bank may apply some restrictions such as limiting the number of HTTP requests for a single consent. Also, some operations may be forbidden entirely by the banking API. For example, some banks do not allow issuing a new consent without the end-user being involved. Therefore, it is certainly necessary and obligatory for the customer to provide the PSU metadata for such operations. <br/><br/> As finAPI does not have direct interaction with the end-user, it is the client application's responsibility to provide all the necessary information about the end-user. This must be done by sending additional headers with every request triggered on behalf of the end-user. <br/><br/> At the moment, the following headers are supported by the API:<br/> &bull; \"PSU-IP-Address\" - the IP address of the user's device. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback.<br/> &bull; \"PSU-Device-OS\" - the user's device and/or operating system identification.<br/> &bull; \"PSU-User-Agent\" - the user's web browser or other client device identification.  <h3 id=\"general-faq\"><strong>FAQ</strong></h3> <strong>Is there a finAPI SDK?</strong> <br/> Currently we do not offer a native SDK, but there is the option to generate an SDK for almost any target language via OpenAPI. Use the 'Download SDK' button on this page for SDK generation. <br/> <br/> <strong>How can I enable finAPI's automatic batch update?</strong> <br/> Currently there is no way to set up the batch update via the API. Please contact support@finapi.io for this. <br/> <br/> <strong>Why do I need to keep authorizing when calling services on this page?</strong> <br/> This page is a \"one-page-app\". Reloading the page resets the OAuth authorization context. There is generally no need to reload the page, so just don't do it and your authorization will persist.
 *
 * The version of the OpenAPI document: 2024.38.2
 * Contact: kontakt@finapi.io
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.5.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace FinAPI\Client\Model;

use \ArrayAccess;
use \FinAPI\Client\ObjectSerializer;

/**
 * NewTransaction Class Doc Comment
 *
 * @category Class
 * @description Mock transaction data
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class NewTransaction implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'NewTransaction';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'amount' => 'float',
        'currency' => '\FinAPI\Client\Model\Currency',
        'original_amount' => 'float',
        'original_currency' => '\FinAPI\Client\Model\Currency',
        'purpose' => 'string',
        'counterpart' => 'string',
        'counterpart_iban' => 'string',
        'counterpart_blz' => 'string',
        'counterpart_bic' => 'string',
        'counterpart_account_number' => 'string',
        'booking_date' => '\DateTime',
        'value_date' => '\DateTime',
        'type_id' => 'int',
        'counterpart_mandate_reference' => 'string',
        'counterpart_creditor_id' => 'string',
        'counterpart_customer_reference' => 'string',
        'counterpart_debitor_id' => 'string',
        'type' => 'string',
        'type_code_swift' => 'string',
        'sepa_purpose_code' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'amount' => null,
        'currency' => null,
        'original_amount' => null,
        'original_currency' => null,
        'purpose' => null,
        'counterpart' => null,
        'counterpart_iban' => null,
        'counterpart_blz' => null,
        'counterpart_bic' => null,
        'counterpart_account_number' => null,
        'booking_date' => 'date',
        'value_date' => 'date',
        'type_id' => 'int32',
        'counterpart_mandate_reference' => null,
        'counterpart_creditor_id' => null,
        'counterpart_customer_reference' => null,
        'counterpart_debitor_id' => null,
        'type' => null,
        'type_code_swift' => null,
        'sepa_purpose_code' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'amount' => false,
		'currency' => false,
		'original_amount' => false,
		'original_currency' => false,
		'purpose' => false,
		'counterpart' => false,
		'counterpart_iban' => false,
		'counterpart_blz' => false,
		'counterpart_bic' => false,
		'counterpart_account_number' => false,
		'booking_date' => false,
		'value_date' => false,
		'type_id' => false,
		'counterpart_mandate_reference' => false,
		'counterpart_creditor_id' => false,
		'counterpart_customer_reference' => false,
		'counterpart_debitor_id' => false,
		'type' => false,
		'type_code_swift' => false,
		'sepa_purpose_code' => false
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
        'amount' => 'amount',
        'currency' => 'currency',
        'original_amount' => 'originalAmount',
        'original_currency' => 'originalCurrency',
        'purpose' => 'purpose',
        'counterpart' => 'counterpart',
        'counterpart_iban' => 'counterpartIban',
        'counterpart_blz' => 'counterpartBlz',
        'counterpart_bic' => 'counterpartBic',
        'counterpart_account_number' => 'counterpartAccountNumber',
        'booking_date' => 'bookingDate',
        'value_date' => 'valueDate',
        'type_id' => 'typeId',
        'counterpart_mandate_reference' => 'counterpartMandateReference',
        'counterpart_creditor_id' => 'counterpartCreditorId',
        'counterpart_customer_reference' => 'counterpartCustomerReference',
        'counterpart_debitor_id' => 'counterpartDebitorId',
        'type' => 'type',
        'type_code_swift' => 'typeCodeSwift',
        'sepa_purpose_code' => 'sepaPurposeCode'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'amount' => 'setAmount',
        'currency' => 'setCurrency',
        'original_amount' => 'setOriginalAmount',
        'original_currency' => 'setOriginalCurrency',
        'purpose' => 'setPurpose',
        'counterpart' => 'setCounterpart',
        'counterpart_iban' => 'setCounterpartIban',
        'counterpart_blz' => 'setCounterpartBlz',
        'counterpart_bic' => 'setCounterpartBic',
        'counterpart_account_number' => 'setCounterpartAccountNumber',
        'booking_date' => 'setBookingDate',
        'value_date' => 'setValueDate',
        'type_id' => 'setTypeId',
        'counterpart_mandate_reference' => 'setCounterpartMandateReference',
        'counterpart_creditor_id' => 'setCounterpartCreditorId',
        'counterpart_customer_reference' => 'setCounterpartCustomerReference',
        'counterpart_debitor_id' => 'setCounterpartDebitorId',
        'type' => 'setType',
        'type_code_swift' => 'setTypeCodeSwift',
        'sepa_purpose_code' => 'setSepaPurposeCode'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'amount' => 'getAmount',
        'currency' => 'getCurrency',
        'original_amount' => 'getOriginalAmount',
        'original_currency' => 'getOriginalCurrency',
        'purpose' => 'getPurpose',
        'counterpart' => 'getCounterpart',
        'counterpart_iban' => 'getCounterpartIban',
        'counterpart_blz' => 'getCounterpartBlz',
        'counterpart_bic' => 'getCounterpartBic',
        'counterpart_account_number' => 'getCounterpartAccountNumber',
        'booking_date' => 'getBookingDate',
        'value_date' => 'getValueDate',
        'type_id' => 'getTypeId',
        'counterpart_mandate_reference' => 'getCounterpartMandateReference',
        'counterpart_creditor_id' => 'getCounterpartCreditorId',
        'counterpart_customer_reference' => 'getCounterpartCustomerReference',
        'counterpart_debitor_id' => 'getCounterpartDebitorId',
        'type' => 'getType',
        'type_code_swift' => 'getTypeCodeSwift',
        'sepa_purpose_code' => 'getSepaPurposeCode'
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
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('currency', $data ?? [], null);
        $this->setIfExists('original_amount', $data ?? [], null);
        $this->setIfExists('original_currency', $data ?? [], null);
        $this->setIfExists('purpose', $data ?? [], null);
        $this->setIfExists('counterpart', $data ?? [], null);
        $this->setIfExists('counterpart_iban', $data ?? [], null);
        $this->setIfExists('counterpart_blz', $data ?? [], null);
        $this->setIfExists('counterpart_bic', $data ?? [], null);
        $this->setIfExists('counterpart_account_number', $data ?? [], null);
        $this->setIfExists('booking_date', $data ?? [], null);
        $this->setIfExists('value_date', $data ?? [], null);
        $this->setIfExists('type_id', $data ?? [], null);
        $this->setIfExists('counterpart_mandate_reference', $data ?? [], null);
        $this->setIfExists('counterpart_creditor_id', $data ?? [], null);
        $this->setIfExists('counterpart_customer_reference', $data ?? [], null);
        $this->setIfExists('counterpart_debitor_id', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('type_code_swift', $data ?? [], null);
        $this->setIfExists('sepa_purpose_code', $data ?? [], null);
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

        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if (!is_null($this->container['purpose']) && (mb_strlen($this->container['purpose']) > 2000)) {
            $invalidProperties[] = "invalid value for 'purpose', the character length must be smaller than or equal to 2000.";
        }

        if (!is_null($this->container['purpose']) && (mb_strlen($this->container['purpose']) < 1)) {
            $invalidProperties[] = "invalid value for 'purpose', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['counterpart']) && (mb_strlen($this->container['counterpart']) > 80)) {
            $invalidProperties[] = "invalid value for 'counterpart', the character length must be smaller than or equal to 80.";
        }

        if (!is_null($this->container['counterpart']) && (mb_strlen($this->container['counterpart']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['counterpart_mandate_reference']) && (mb_strlen($this->container['counterpart_mandate_reference']) > 270)) {
            $invalidProperties[] = "invalid value for 'counterpart_mandate_reference', the character length must be smaller than or equal to 270.";
        }

        if (!is_null($this->container['counterpart_mandate_reference']) && (mb_strlen($this->container['counterpart_mandate_reference']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_mandate_reference', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['counterpart_creditor_id']) && (mb_strlen($this->container['counterpart_creditor_id']) > 270)) {
            $invalidProperties[] = "invalid value for 'counterpart_creditor_id', the character length must be smaller than or equal to 270.";
        }

        if (!is_null($this->container['counterpart_creditor_id']) && (mb_strlen($this->container['counterpart_creditor_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_creditor_id', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['counterpart_customer_reference']) && (mb_strlen($this->container['counterpart_customer_reference']) > 270)) {
            $invalidProperties[] = "invalid value for 'counterpart_customer_reference', the character length must be smaller than or equal to 270.";
        }

        if (!is_null($this->container['counterpart_customer_reference']) && (mb_strlen($this->container['counterpart_customer_reference']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_customer_reference', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['counterpart_debitor_id']) && (mb_strlen($this->container['counterpart_debitor_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'counterpart_debitor_id', the character length must be smaller than or equal to 100.";
        }

        if (!is_null($this->container['counterpart_debitor_id']) && (mb_strlen($this->container['counterpart_debitor_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_debitor_id', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['type']) && (mb_strlen($this->container['type']) > 270)) {
            $invalidProperties[] = "invalid value for 'type', the character length must be smaller than or equal to 270.";
        }

        if (!is_null($this->container['type']) && (mb_strlen($this->container['type']) < 1)) {
            $invalidProperties[] = "invalid value for 'type', the character length must be bigger than or equal to 1.";
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
     * Gets amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param float $amount Amount. Required.
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
     * Gets currency
     *
     * @return \FinAPI\Client\Model\Currency|null
     */
    public function getCurrency()
    {
        return $this->container['currency'];
    }

    /**
     * Sets currency
     *
     * @param \FinAPI\Client\Model\Currency|null $currency currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        if (is_null($currency)) {
            throw new \InvalidArgumentException('non-nullable currency cannot be null');
        }
        $this->container['currency'] = $currency;

        return $this;
    }

    /**
     * Gets original_amount
     *
     * @return float|null
     */
    public function getOriginalAmount()
    {
        return $this->container['original_amount'];
    }

    /**
     * Sets original_amount
     *
     * @param float|null $original_amount Original amount
     *
     * @return self
     */
    public function setOriginalAmount($original_amount)
    {
        if (is_null($original_amount)) {
            throw new \InvalidArgumentException('non-nullable original_amount cannot be null');
        }
        $this->container['original_amount'] = $original_amount;

        return $this;
    }

    /**
     * Gets original_currency
     *
     * @return \FinAPI\Client\Model\Currency|null
     */
    public function getOriginalCurrency()
    {
        return $this->container['original_currency'];
    }

    /**
     * Sets original_currency
     *
     * @param \FinAPI\Client\Model\Currency|null $original_currency original_currency
     *
     * @return self
     */
    public function setOriginalCurrency($original_currency)
    {
        if (is_null($original_currency)) {
            throw new \InvalidArgumentException('non-nullable original_currency cannot be null');
        }
        $this->container['original_currency'] = $original_currency;

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
     * @param string|null $purpose Purpose. Any symbols are allowed. Optional. Default value: null.
     *
     * @return self
     */
    public function setPurpose($purpose)
    {
        if (is_null($purpose)) {
            throw new \InvalidArgumentException('non-nullable purpose cannot be null');
        }
        if ((mb_strlen($purpose) > 2000)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling NewTransaction., must be smaller than or equal to 2000.');
        }
        if ((mb_strlen($purpose) < 1)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['purpose'] = $purpose;

        return $this;
    }

    /**
     * Gets counterpart
     *
     * @return string|null
     */
    public function getCounterpart()
    {
        return $this->container['counterpart'];
    }

    /**
     * Sets counterpart
     *
     * @param string|null $counterpart Counterpart. Any symbols are allowed. Optional. Default value: null.
     *
     * @return self
     */
    public function setCounterpart($counterpart)
    {
        if (is_null($counterpart)) {
            throw new \InvalidArgumentException('non-nullable counterpart cannot be null');
        }
        if ((mb_strlen($counterpart) > 80)) {
            throw new \InvalidArgumentException('invalid length for $counterpart when calling NewTransaction., must be smaller than or equal to 80.');
        }
        if ((mb_strlen($counterpart) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['counterpart'] = $counterpart;

        return $this;
    }

    /**
     * Gets counterpart_iban
     *
     * @return string|null
     */
    public function getCounterpartIban()
    {
        return $this->container['counterpart_iban'];
    }

    /**
     * Sets counterpart_iban
     *
     * @param string|null $counterpart_iban Counterpart IBAN. Optional. Default value: null.
     *
     * @return self
     */
    public function setCounterpartIban($counterpart_iban)
    {
        if (is_null($counterpart_iban)) {
            throw new \InvalidArgumentException('non-nullable counterpart_iban cannot be null');
        }
        $this->container['counterpart_iban'] = $counterpart_iban;

        return $this;
    }

    /**
     * Gets counterpart_blz
     *
     * @return string|null
     */
    public function getCounterpartBlz()
    {
        return $this->container['counterpart_blz'];
    }

    /**
     * Sets counterpart_blz
     *
     * @param string|null $counterpart_blz Counterpart BLZ. Optional. Default value: null.
     *
     * @return self
     */
    public function setCounterpartBlz($counterpart_blz)
    {
        if (is_null($counterpart_blz)) {
            throw new \InvalidArgumentException('non-nullable counterpart_blz cannot be null');
        }
        $this->container['counterpart_blz'] = $counterpart_blz;

        return $this;
    }

    /**
     * Gets counterpart_bic
     *
     * @return string|null
     */
    public function getCounterpartBic()
    {
        return $this->container['counterpart_bic'];
    }

    /**
     * Sets counterpart_bic
     *
     * @param string|null $counterpart_bic Counterpart BIC. Optional. Default value: null.
     *
     * @return self
     */
    public function setCounterpartBic($counterpart_bic)
    {
        if (is_null($counterpart_bic)) {
            throw new \InvalidArgumentException('non-nullable counterpart_bic cannot be null');
        }
        $this->container['counterpart_bic'] = $counterpart_bic;

        return $this;
    }

    /**
     * Gets counterpart_account_number
     *
     * @return string|null
     */
    public function getCounterpartAccountNumber()
    {
        return $this->container['counterpart_account_number'];
    }

    /**
     * Sets counterpart_account_number
     *
     * @param string|null $counterpart_account_number Counterpart account number. Maximum length is 34. Optional. Default value: null.
     *
     * @return self
     */
    public function setCounterpartAccountNumber($counterpart_account_number)
    {
        if (is_null($counterpart_account_number)) {
            throw new \InvalidArgumentException('non-nullable counterpart_account_number cannot be null');
        }
        $this->container['counterpart_account_number'] = $counterpart_account_number;

        return $this;
    }

    /**
     * Gets booking_date
     *
     * @return \DateTime|null
     */
    public function getBookingDate()
    {
        return $this->container['booking_date'];
    }

    /**
     * Sets booking_date
     *
     * @param \DateTime|null $booking_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Booking date.<br/><br/>If the date lies back more than 10 days from the booking date of the latest transaction that currently exists in the account, then this transaction will be ignored and not imported. If the date depicts a date in the future, then finAPI will deal with it the same way as it does with real transactions during a real update (see fields 'bankBookingDate' and 'finapiBookingDate' in the Transaction Resource for explanation).<br/><br/>This field is optional, default value is the current date.
     *
     * @return self
     */
    public function setBookingDate($booking_date)
    {
        if (is_null($booking_date)) {
            throw new \InvalidArgumentException('non-nullable booking_date cannot be null');
        }
        $this->container['booking_date'] = $booking_date;

        return $this;
    }

    /**
     * Gets value_date
     *
     * @return \DateTime|null
     */
    public function getValueDate()
    {
        return $this->container['value_date'];
    }

    /**
     * Sets value_date
     *
     * @param \DateTime|null $value_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Value date. Optional. Default value: Same as the booking date.
     *
     * @return self
     */
    public function setValueDate($value_date)
    {
        if (is_null($value_date)) {
            throw new \InvalidArgumentException('non-nullable value_date cannot be null');
        }
        $this->container['value_date'] = $value_date;

        return $this;
    }

    /**
     * Gets type_id
     *
     * @return int|null
     */
    public function getTypeId()
    {
        return $this->container['type_id'];
    }

    /**
     * Sets type_id
     *
     * @param int|null $type_id The transaction type id. It's usually a number between 1 and 999. You can look up valid transaction in the following document on page 198: <a href='https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv4/FinTS_4.1_Messages_Finanzdatenformate_2014-01-20-FV.pdf' target='_blank'>FinTS Financial Transaction Services</a>.<br/> For numbers not listed here, the service call might fail.
     *
     * @return self
     */
    public function setTypeId($type_id)
    {
        if (is_null($type_id)) {
            throw new \InvalidArgumentException('non-nullable type_id cannot be null');
        }
        $this->container['type_id'] = $type_id;

        return $this;
    }

    /**
     * Gets counterpart_mandate_reference
     *
     * @return string|null
     */
    public function getCounterpartMandateReference()
    {
        return $this->container['counterpart_mandate_reference'];
    }

    /**
     * Sets counterpart_mandate_reference
     *
     * @param string|null $counterpart_mandate_reference The mandate reference of the counterpart.
     *
     * @return self
     */
    public function setCounterpartMandateReference($counterpart_mandate_reference)
    {
        if (is_null($counterpart_mandate_reference)) {
            throw new \InvalidArgumentException('non-nullable counterpart_mandate_reference cannot be null');
        }
        if ((mb_strlen($counterpart_mandate_reference) > 270)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_mandate_reference when calling NewTransaction., must be smaller than or equal to 270.');
        }
        if ((mb_strlen($counterpart_mandate_reference) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_mandate_reference when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_mandate_reference'] = $counterpart_mandate_reference;

        return $this;
    }

    /**
     * Gets counterpart_creditor_id
     *
     * @return string|null
     */
    public function getCounterpartCreditorId()
    {
        return $this->container['counterpart_creditor_id'];
    }

    /**
     * Sets counterpart_creditor_id
     *
     * @param string|null $counterpart_creditor_id The creditor ID of the counterpart. Exists only for SEPA direct debit transactions (\"Lastschrift\").
     *
     * @return self
     */
    public function setCounterpartCreditorId($counterpart_creditor_id)
    {
        if (is_null($counterpart_creditor_id)) {
            throw new \InvalidArgumentException('non-nullable counterpart_creditor_id cannot be null');
        }
        if ((mb_strlen($counterpart_creditor_id) > 270)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_creditor_id when calling NewTransaction., must be smaller than or equal to 270.');
        }
        if ((mb_strlen($counterpart_creditor_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_creditor_id when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_creditor_id'] = $counterpart_creditor_id;

        return $this;
    }

    /**
     * Gets counterpart_customer_reference
     *
     * @return string|null
     */
    public function getCounterpartCustomerReference()
    {
        return $this->container['counterpart_customer_reference'];
    }

    /**
     * Sets counterpart_customer_reference
     *
     * @param string|null $counterpart_customer_reference The customer reference of the counterpart.
     *
     * @return self
     */
    public function setCounterpartCustomerReference($counterpart_customer_reference)
    {
        if (is_null($counterpart_customer_reference)) {
            throw new \InvalidArgumentException('non-nullable counterpart_customer_reference cannot be null');
        }
        if ((mb_strlen($counterpart_customer_reference) > 270)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_customer_reference when calling NewTransaction., must be smaller than or equal to 270.');
        }
        if ((mb_strlen($counterpart_customer_reference) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_customer_reference when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_customer_reference'] = $counterpart_customer_reference;

        return $this;
    }

    /**
     * Gets counterpart_debitor_id
     *
     * @return string|null
     */
    public function getCounterpartDebitorId()
    {
        return $this->container['counterpart_debitor_id'];
    }

    /**
     * Sets counterpart_debitor_id
     *
     * @param string|null $counterpart_debitor_id The originator's identification code. Exists only for SEPA money transfer transactions (\"Überweisung\").
     *
     * @return self
     */
    public function setCounterpartDebitorId($counterpart_debitor_id)
    {
        if (is_null($counterpart_debitor_id)) {
            throw new \InvalidArgumentException('non-nullable counterpart_debitor_id cannot be null');
        }
        if ((mb_strlen($counterpart_debitor_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_debitor_id when calling NewTransaction., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($counterpart_debitor_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_debitor_id when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_debitor_id'] = $counterpart_debitor_id;

        return $this;
    }

    /**
     * Gets type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string|null $type Transaction type, according to the bank. If set, this will contain a term in the language of the bank, that you can display to the user. Some examples of common values are: \"Lastschrift\", \"Auslands&uuml;berweisung\", \"Geb&uuml;hren\", \"Zinsen\".
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_null($type)) {
            throw new \InvalidArgumentException('non-nullable type cannot be null');
        }
        if ((mb_strlen($type) > 270)) {
            throw new \InvalidArgumentException('invalid length for $type when calling NewTransaction., must be smaller than or equal to 270.');
        }
        if ((mb_strlen($type) < 1)) {
            throw new \InvalidArgumentException('invalid length for $type when calling NewTransaction., must be bigger than or equal to 1.');
        }

        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets type_code_swift
     *
     * @return string|null
     */
    public function getTypeCodeSwift()
    {
        return $this->container['type_code_swift'];
    }

    /**
     * Sets type_code_swift
     *
     * @param string|null $type_code_swift SWIFT transaction type code.
     *
     * @return self
     */
    public function setTypeCodeSwift($type_code_swift)
    {
        if (is_null($type_code_swift)) {
            throw new \InvalidArgumentException('non-nullable type_code_swift cannot be null');
        }
        $this->container['type_code_swift'] = $type_code_swift;

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
     * @param string|null $sepa_purpose_code SEPA purpose code.
     *
     * @return self
     */
    public function setSepaPurposeCode($sepa_purpose_code)
    {
        if (is_null($sepa_purpose_code)) {
            throw new \InvalidArgumentException('non-nullable sepa_purpose_code cannot be null');
        }
        $this->container['sepa_purpose_code'] = $sepa_purpose_code;

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


