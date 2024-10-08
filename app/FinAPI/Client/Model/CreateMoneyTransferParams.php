<?php
/**
 * CreateMoneyTransferParams
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
 * CreateMoneyTransferParams Class Doc Comment
 *
 * @category Class
 * @description Container for money transfer creation parameters
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CreateMoneyTransferParams implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CreateMoneyTransferParams';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'account_id' => 'int',
        'iban' => 'string',
        'bank_id' => 'int',
        'execution_date' => '\DateTime',
        'money_transfers' => '\FinAPI\Client\Model\MoneyTransferOrderParams[]',
        'instant_payment' => 'bool',
        'single_booking' => 'bool',
        'msg_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'account_id' => 'int64',
        'iban' => null,
        'bank_id' => 'int64',
        'execution_date' => 'date',
        'money_transfers' => null,
        'instant_payment' => null,
        'single_booking' => null,
        'msg_id' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'account_id' => false,
		'iban' => false,
		'bank_id' => false,
		'execution_date' => false,
		'money_transfers' => false,
		'instant_payment' => false,
		'single_booking' => false,
		'msg_id' => false
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
        'account_id' => 'accountId',
        'iban' => 'iban',
        'bank_id' => 'bankId',
        'execution_date' => 'executionDate',
        'money_transfers' => 'moneyTransfers',
        'instant_payment' => 'instantPayment',
        'single_booking' => 'singleBooking',
        'msg_id' => 'msgId'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'account_id' => 'setAccountId',
        'iban' => 'setIban',
        'bank_id' => 'setBankId',
        'execution_date' => 'setExecutionDate',
        'money_transfers' => 'setMoneyTransfers',
        'instant_payment' => 'setInstantPayment',
        'single_booking' => 'setSingleBooking',
        'msg_id' => 'setMsgId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'account_id' => 'getAccountId',
        'iban' => 'getIban',
        'bank_id' => 'getBankId',
        'execution_date' => 'getExecutionDate',
        'money_transfers' => 'getMoneyTransfers',
        'instant_payment' => 'getInstantPayment',
        'single_booking' => 'getSingleBooking',
        'msg_id' => 'getMsgId'
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
        $this->setIfExists('account_id', $data ?? [], null);
        $this->setIfExists('iban', $data ?? [], null);
        $this->setIfExists('bank_id', $data ?? [], null);
        $this->setIfExists('execution_date', $data ?? [], null);
        $this->setIfExists('money_transfers', $data ?? [], null);
        $this->setIfExists('instant_payment', $data ?? [], false);
        $this->setIfExists('single_booking', $data ?? [], false);
        $this->setIfExists('msg_id', $data ?? [], null);
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

        if ($this->container['money_transfers'] === null) {
            $invalidProperties[] = "'money_transfers' can't be null";
        }
        if ((count($this->container['money_transfers']) > 15000)) {
            $invalidProperties[] = "invalid value for 'money_transfers', number of items must be less than or equal to 15000.";
        }

        if ((count($this->container['money_transfers']) < 1)) {
            $invalidProperties[] = "invalid value for 'money_transfers', number of items must be greater than or equal to 1.";
        }

        if (!is_null($this->container['msg_id']) && (mb_strlen($this->container['msg_id']) > 35)) {
            $invalidProperties[] = "invalid value for 'msg_id', the character length must be smaller than or equal to 35.";
        }

        if (!is_null($this->container['msg_id']) && (mb_strlen($this->container['msg_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'msg_id', the character length must be bigger than or equal to 1.";
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
     * Gets account_id
     *
     * @return int|null
     */
    public function getAccountId()
    {
        return $this->container['account_id'];
    }

    /**
     * Sets account_id
     *
     * @param int|null $account_id Identifier of the account that should be used for the order. If you want to do a standalone order (finAPI Payment product, i.e. for an account that is not imported in finAPI) leave this field unset and instead use the fields <code>iban</code> and <code>bankId</code>.
     *
     * @return self
     */
    public function setAccountId($account_id)
    {
        if (is_null($account_id)) {
            throw new \InvalidArgumentException('non-nullable account_id cannot be null');
        }
        $this->container['account_id'] = $account_id;

        return $this;
    }

    /**
     * Gets iban
     *
     * @return string|null
     */
    public function getIban()
    {
        return $this->container['iban'];
    }

    /**
     * Sets iban
     *
     * @param string|null $iban IBAN of the account that should be used for the order. Use this field only if you want to do a standalone order (finAPI Payment product, i.e. for an account that is not imported in finAPI). Otherwise, use the <code>accountId</code> field and leave this field unset.
     *
     * @return self
     */
    public function setIban($iban)
    {
        if (is_null($iban)) {
            throw new \InvalidArgumentException('non-nullable iban cannot be null');
        }
        $this->container['iban'] = $iban;

        return $this;
    }

    /**
     * Gets bank_id
     *
     * @return int|null
     */
    public function getBankId()
    {
        return $this->container['bank_id'];
    }

    /**
     * Sets bank_id
     *
     * @param int|null $bank_id Identifier of the bank that should be used. Use this field only if you want to do a standalone order (finAPI Payment product, i.e. for an account that is not imported in finAPI) and when the <code>iban</code> is not sufficient to uniquely identify the bank (a bank search for the IBAN via <code>GET /banks?search=[IBAN]</code> returns multiple banks). If the IBAN uniquely identifies the bank, you may leave this field unset. Also, leave the field unset for non-standalone orders (using the <code>accountId</code> field).
     *
     * @return self
     */
    public function setBankId($bank_id)
    {
        if (is_null($bank_id)) {
            throw new \InvalidArgumentException('non-nullable bank_id cannot be null');
        }
        $this->container['bank_id'] = $bank_id;

        return $this;
    }

    /**
     * Gets execution_date
     *
     * @return \DateTime|null
     */
    public function getExecutionDate()
    {
        return $this->container['execution_date'];
    }

    /**
     * Sets execution_date
     *
     * @param \DateTime|null $execution_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Execution date for the money transfer(s). May not be in the past. For instant payments, it must either be omitted, or be the current date. If not specified, most banks will use the current date as the instructed date for execution.
     *
     * @return self
     */
    public function setExecutionDate($execution_date)
    {
        if (is_null($execution_date)) {
            throw new \InvalidArgumentException('non-nullable execution_date cannot be null');
        }
        $this->container['execution_date'] = $execution_date;

        return $this;
    }

    /**
     * Gets money_transfers
     *
     * @return \FinAPI\Client\Model\MoneyTransferOrderParams[]
     */
    public function getMoneyTransfers()
    {
        return $this->container['money_transfers'];
    }

    /**
     * Sets money_transfers
     *
     * @param \FinAPI\Client\Model\MoneyTransferOrderParams[] $money_transfers List of money transfer orders (may contain at most 15000 items). Please note that collective money transfer may not always be supported.<br/>Bank-specific constraints may apply to this field. Please refer to BankInterface.paymentConstraints to make sure the payment you are creating won't get rejected.<br/> <strong>Type:</strong> MoneyTransferOrderParams
     *
     * @return self
     */
    public function setMoneyTransfers($money_transfers)
    {
        if (is_null($money_transfers)) {
            throw new \InvalidArgumentException('non-nullable money_transfers cannot be null');
        }

        if ((count($money_transfers) > 15000)) {
            throw new \InvalidArgumentException('invalid value for $money_transfers when calling CreateMoneyTransferParams., number of items must be less than or equal to 15000.');
        }
        if ((count($money_transfers) < 1)) {
            throw new \InvalidArgumentException('invalid length for $money_transfers when calling CreateMoneyTransferParams., number of items must be greater than or equal to 1.');
        }
        $this->container['money_transfers'] = $money_transfers;

        return $this;
    }

    /**
     * Gets instant_payment
     *
     * @return bool|null
     */
    public function getInstantPayment()
    {
        return $this->container['instant_payment'];
    }

    /**
     * Sets instant_payment
     *
     * @param bool|null $instant_payment Whether the order should be submitted to the bank as an instant SEPA order. Default value is 'false'.<br/><br/>NOTE:<br/>&bull; Instant payments can only be submitted if you are self-licensed (and not using the finAPI Web Form) OR via our Web Form from the endpoint <a href='?product=web_form_2.0#tag--Payment-Initiation-Services' target='_blank'>here</a>.<br/>&bull; Submitting an instant payment will work only with interfaces that support it, see BankInterface.paymentCapabilities.sepaInstantMoneyTransfer<br/>&bull; Instant payments work only for a single order, not for collective orders.<br/>&bull; The bank may charge a fee for instant payments, depending on the agreement between the user and the bank.<br/>&bull; The payment might get rejected if the source and/or target account doesn't support instant payments.
     *
     * @return self
     */
    public function setInstantPayment($instant_payment)
    {
        if (is_null($instant_payment)) {
            throw new \InvalidArgumentException('non-nullable instant_payment cannot be null');
        }
        $this->container['instant_payment'] = $instant_payment;

        return $this;
    }

    /**
     * Gets single_booking
     *
     * @return bool|null
     */
    public function getSingleBooking()
    {
        return $this->container['single_booking'];
    }

    /**
     * Sets single_booking
     *
     * @param bool|null $single_booking This field is only relevant when you pass multiple orders. It determines whether the orders should be processed by the bank as one collective booking (in case of 'false'), or as single bookings (in case of 'true'). Note that it is subject to the bank whether it will regard the field. Default value is 'false'.
     *
     * @return self
     */
    public function setSingleBooking($single_booking)
    {
        if (is_null($single_booking)) {
            throw new \InvalidArgumentException('non-nullable single_booking cannot be null');
        }
        $this->container['single_booking'] = $single_booking;

        return $this;
    }

    /**
     * Gets msg_id
     *
     * @return string|null
     */
    public function getMsgId()
    {
        return $this->container['msg_id'];
    }

    /**
     * Sets msg_id
     *
     * @param string|null $msg_id Unique identifier for the message. This field is only relevant when you pass multiple orders and they should be processed by the bank as a single payment using the FINTS_SERVER interface.
     *
     * @return self
     */
    public function setMsgId($msg_id)
    {
        if (is_null($msg_id)) {
            throw new \InvalidArgumentException('non-nullable msg_id cannot be null');
        }
        if ((mb_strlen($msg_id) > 35)) {
            throw new \InvalidArgumentException('invalid length for $msg_id when calling CreateMoneyTransferParams., must be smaller than or equal to 35.');
        }
        if ((mb_strlen($msg_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $msg_id when calling CreateMoneyTransferParams., must be bigger than or equal to 1.');
        }

        $this->container['msg_id'] = $msg_id;

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


