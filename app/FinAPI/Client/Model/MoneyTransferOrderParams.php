<?php
/**
 * MoneyTransferOrderParams
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
 * MoneyTransferOrderParams Class Doc Comment
 *
 * @category Class
 * @description Parameters for a money transfer order
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class MoneyTransferOrderParams implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'MoneyTransferOrderParams';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'counterpart_name' => 'string',
        'counterpart_iban' => 'string',
        'counterpart_bic' => 'string',
        'counterpart_bank_name' => 'string',
        'amount' => 'float',
        'currency' => '\FinAPI\Client\Model\Currency',
        'purpose' => 'string',
        'sepa_purpose_code' => 'string',
        'counterpart_address' => '\FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress',
        'end_to_end_id' => 'string',
        'structured_remittance_information' => 'string[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'counterpart_name' => null,
        'counterpart_iban' => null,
        'counterpart_bic' => null,
        'counterpart_bank_name' => null,
        'amount' => null,
        'currency' => null,
        'purpose' => null,
        'sepa_purpose_code' => null,
        'counterpart_address' => null,
        'end_to_end_id' => null,
        'structured_remittance_information' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'counterpart_name' => false,
		'counterpart_iban' => false,
		'counterpart_bic' => false,
		'counterpart_bank_name' => false,
		'amount' => false,
		'currency' => false,
		'purpose' => false,
		'sepa_purpose_code' => false,
		'counterpart_address' => false,
		'end_to_end_id' => false,
		'structured_remittance_information' => false
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
        'counterpart_name' => 'counterpartName',
        'counterpart_iban' => 'counterpartIban',
        'counterpart_bic' => 'counterpartBic',
        'counterpart_bank_name' => 'counterpartBankName',
        'amount' => 'amount',
        'currency' => 'currency',
        'purpose' => 'purpose',
        'sepa_purpose_code' => 'sepaPurposeCode',
        'counterpart_address' => 'counterpartAddress',
        'end_to_end_id' => 'endToEndId',
        'structured_remittance_information' => 'structuredRemittanceInformation'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'counterpart_name' => 'setCounterpartName',
        'counterpart_iban' => 'setCounterpartIban',
        'counterpart_bic' => 'setCounterpartBic',
        'counterpart_bank_name' => 'setCounterpartBankName',
        'amount' => 'setAmount',
        'currency' => 'setCurrency',
        'purpose' => 'setPurpose',
        'sepa_purpose_code' => 'setSepaPurposeCode',
        'counterpart_address' => 'setCounterpartAddress',
        'end_to_end_id' => 'setEndToEndId',
        'structured_remittance_information' => 'setStructuredRemittanceInformation'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'counterpart_name' => 'getCounterpartName',
        'counterpart_iban' => 'getCounterpartIban',
        'counterpart_bic' => 'getCounterpartBic',
        'counterpart_bank_name' => 'getCounterpartBankName',
        'amount' => 'getAmount',
        'currency' => 'getCurrency',
        'purpose' => 'getPurpose',
        'sepa_purpose_code' => 'getSepaPurposeCode',
        'counterpart_address' => 'getCounterpartAddress',
        'end_to_end_id' => 'getEndToEndId',
        'structured_remittance_information' => 'getStructuredRemittanceInformation'
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
        $this->setIfExists('counterpart_name', $data ?? [], null);
        $this->setIfExists('counterpart_iban', $data ?? [], null);
        $this->setIfExists('counterpart_bic', $data ?? [], null);
        $this->setIfExists('counterpart_bank_name', $data ?? [], null);
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('currency', $data ?? [], null);
        $this->setIfExists('purpose', $data ?? [], null);
        $this->setIfExists('sepa_purpose_code', $data ?? [], null);
        $this->setIfExists('counterpart_address', $data ?? [], null);
        $this->setIfExists('end_to_end_id', $data ?? [], null);
        $this->setIfExists('structured_remittance_information', $data ?? [], null);
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

        if (!is_null($this->container['counterpart_name']) && (mb_strlen($this->container['counterpart_name']) > 70)) {
            $invalidProperties[] = "invalid value for 'counterpart_name', the character length must be smaller than or equal to 70.";
        }

        if (!is_null($this->container['counterpart_name']) && (mb_strlen($this->container['counterpart_name']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_name', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['counterpart_iban'] === null) {
            $invalidProperties[] = "'counterpart_iban' can't be null";
        }
        if (!is_null($this->container['counterpart_bank_name']) && (mb_strlen($this->container['counterpart_bank_name']) > 128)) {
            $invalidProperties[] = "invalid value for 'counterpart_bank_name', the character length must be smaller than or equal to 128.";
        }

        if (!is_null($this->container['counterpart_bank_name']) && (mb_strlen($this->container['counterpart_bank_name']) < 1)) {
            $invalidProperties[] = "invalid value for 'counterpart_bank_name', the character length must be bigger than or equal to 1.";
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

        if (!is_null($this->container['end_to_end_id']) && (mb_strlen($this->container['end_to_end_id']) > 35)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be smaller than or equal to 35.";
        }

        if (!is_null($this->container['end_to_end_id']) && (mb_strlen($this->container['end_to_end_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['structured_remittance_information'] === null) {
            $invalidProperties[] = "'structured_remittance_information' can't be null";
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
     * Gets counterpart_name
     *
     * @return string|null
     */
    public function getCounterpartName()
    {
        return $this->container['counterpart_name'];
    }

    /**
     * Sets counterpart_name
     *
     * @param string|null $counterpart_name Name of the counterpart. Has to be provided for the SEPA EUR transfers (see BankInterface.paymentConstraints.sepaMoneyTransfer.mandatoryFields.counterpartName). Note: Neither finAPI nor the involved bank servers are guaranteed to validate the counterpart name. Even if the name does not depict the actual registered account holder of the target account, the order might still be successful.<br/>Please refer to the <a href='https://documentation.finapi.io/payments/payment-data-validation' target='_blank'> Payment Data Validation documentation </a> for more details.
     *
     * @return self
     */
    public function setCounterpartName($counterpart_name)
    {
        if (is_null($counterpart_name)) {
            throw new \InvalidArgumentException('non-nullable counterpart_name cannot be null');
        }
        if ((mb_strlen($counterpart_name) > 70)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_name when calling MoneyTransferOrderParams., must be smaller than or equal to 70.');
        }
        if ((mb_strlen($counterpart_name) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_name when calling MoneyTransferOrderParams., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_name'] = $counterpart_name;

        return $this;
    }

    /**
     * Gets counterpart_iban
     *
     * @return string
     */
    public function getCounterpartIban()
    {
        return $this->container['counterpart_iban'];
    }

    /**
     * Sets counterpart_iban
     *
     * @param string $counterpart_iban IBAN of the counterpart's account.
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
     * @param string|null $counterpart_bic BIC of the counterpart's account. This field is required for SEPA payments (i.e. payments in EUR currency), when there is no 'IBAN_ONLY'-capability in the respective account/interface combination that is to be used when submitting the payment.<br/>Bank-specific constraints may apply to this field. Please refer to BankInterface.paymentConstraints to make sure the payment you are creating won't get rejected.
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
     * Gets counterpart_bank_name
     *
     * @return string|null
     */
    public function getCounterpartBankName()
    {
        return $this->container['counterpart_bank_name'];
    }

    /**
     * Sets counterpart_bank_name
     *
     * @param string|null $counterpart_bank_name Name of the counterpart's bank. Only required for banks that have BankInterface.paymentConstraints.sepaMoneyTransfer.mandatoryFields.counterpartBankName constraint. <br/>Note: Neither finAPI nor the involved bank servers are guaranteed to validate the counterpart bank name.
     *
     * @return self
     */
    public function setCounterpartBankName($counterpart_bank_name)
    {
        if (is_null($counterpart_bank_name)) {
            throw new \InvalidArgumentException('non-nullable counterpart_bank_name cannot be null');
        }
        if ((mb_strlen($counterpart_bank_name) > 128)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_bank_name when calling MoneyTransferOrderParams., must be smaller than or equal to 128.');
        }
        if ((mb_strlen($counterpart_bank_name) < 1)) {
            throw new \InvalidArgumentException('invalid length for $counterpart_bank_name when calling MoneyTransferOrderParams., must be bigger than or equal to 1.');
        }

        $this->container['counterpart_bank_name'] = $counterpart_bank_name;

        return $this;
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
     * @param float $amount The amount of the payment. Must be a positive decimal number with at most two decimal places.<br/>Please refer to the <a href='https://documentation.finapi.io/payments/payment-data-validation' target='_blank'> Payment Data Validation documentation </a> for more details.
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
     * @param string|null $purpose The purpose of the transfer transaction.<br/>Please refer to the <a href='https://documentation.finapi.io/payments/payment-data-validation' target='_blank'> Payment Data Validation documentation </a> for more details.<br/>Bank-specific constraints may apply to this field. Please refer to BankInterface.paymentConstraints to make sure the payment you are creating won't get rejected.
     *
     * @return self
     */
    public function setPurpose($purpose)
    {
        if (is_null($purpose)) {
            throw new \InvalidArgumentException('non-nullable purpose cannot be null');
        }
        if ((mb_strlen($purpose) > 2000)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling MoneyTransferOrderParams., must be smaller than or equal to 2000.');
        }
        if ((mb_strlen($purpose) < 1)) {
            throw new \InvalidArgumentException('invalid length for $purpose when calling MoneyTransferOrderParams., must be bigger than or equal to 1.');
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
     * @param string|null $sepa_purpose_code SEPA purpose code, according to ISO 20022, external codes set.<br/>Please note that the SEPA purpose code may be ignored by some banks and will be discarded for the non-SEPA payments.
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
     * Gets counterpart_address
     *
     * @return \FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress|null
     */
    public function getCounterpartAddress()
    {
        return $this->container['counterpart_address'];
    }

    /**
     * Sets counterpart_address
     *
     * @param \FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress|null $counterpart_address counterpart_address
     *
     * @return self
     */
    public function setCounterpartAddress($counterpart_address)
    {
        if (is_null($counterpart_address)) {
            throw new \InvalidArgumentException('non-nullable counterpart_address cannot be null');
        }
        $this->container['counterpart_address'] = $counterpart_address;

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
     * @param string|null $end_to_end_id End-To-End ID for the transfer transaction.<br/>Only applicable for the SEPA EUR transfers and will be discarded for other transfers.<br/>Please refer to the <a href='https://documentation.finapi.io/payments/payment-data-validation' target='_blank'> Payment Data Validation documentation </a> for more details.<br/>Bank-specific constraints may apply to this field. Please refer to BankInterface.paymentConstraints to make sure the payment you are creating won't get rejected.
     *
     * @return self
     */
    public function setEndToEndId($end_to_end_id)
    {
        if (is_null($end_to_end_id)) {
            throw new \InvalidArgumentException('non-nullable end_to_end_id cannot be null');
        }
        if ((mb_strlen($end_to_end_id) > 35)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling MoneyTransferOrderParams., must be smaller than or equal to 35.');
        }
        if ((mb_strlen($end_to_end_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling MoneyTransferOrderParams., must be bigger than or equal to 1.');
        }

        $this->container['end_to_end_id'] = $end_to_end_id;

        return $this;
    }

    /**
     * Gets structured_remittance_information
     *
     * @return string[]
     */
    public function getStructuredRemittanceInformation()
    {
        return $this->container['structured_remittance_information'];
    }

    /**
     * Sets structured_remittance_information
     *
     * @param string[] $structured_remittance_information Structure Remittance Information.<br/>This attribute is used to submit structured remittance information for the domestic payments.<br/>Please refer to the <a href='https://documentation.finapi.io/payments/Czech-Republic-Domestic-Transfers.3045916711.html' target='_blank'>documentation</a> for more details.
     *
     * @return self
     */
    public function setStructuredRemittanceInformation($structured_remittance_information)
    {
        if (is_null($structured_remittance_information)) {
            throw new \InvalidArgumentException('non-nullable structured_remittance_information cannot be null');
        }
        $this->container['structured_remittance_information'] = $structured_remittance_information;

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


