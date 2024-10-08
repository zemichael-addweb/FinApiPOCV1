<?php
/**
 * Transaction
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
 * Transaction Class Doc Comment
 *
 * @category Class
 * @description Container for a transaction&#39;s data
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Transaction implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Transaction';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'id' => 'int',
        'parent_id' => 'int',
        'account_id' => 'int',
        'value_date' => '\DateTime',
        'bank_booking_date' => '\DateTime',
        'finapi_booking_date' => '\DateTime',
        'amount' => 'float',
        'currency' => '\FinAPI\Client\Model\Currency',
        'purpose' => 'string',
        'counterpart_name' => 'string',
        'counterpart_account_number' => 'string',
        'counterpart_iban' => 'string',
        'counterpart_blz' => 'string',
        'counterpart_bic' => 'string',
        'counterpart_bank_name' => 'string',
        'counterpart_mandate_reference' => 'string',
        'counterpart_customer_reference' => 'string',
        'counterpart_creditor_id' => 'string',
        'counterpart_debitor_id' => 'string',
        'type' => 'string',
        'type_code_zka' => 'string',
        'type_code_swift' => 'string',
        'sepa_purpose_code' => 'string',
        'bank_transaction_code' => 'string',
        'bank_transaction_code_description' => 'string',
        'primanota' => 'string',
        'category' => '\FinAPI\Client\Model\TransactionCategory',
        'labels' => '\FinAPI\Client\Model\Label[]',
        'is_potential_duplicate' => 'bool',
        'is_adjusting_entry' => 'bool',
        'is_new' => 'bool',
        'import_date' => '\DateTime',
        'children' => 'int[]',
        'paypal_data' => '\FinAPI\Client\Model\PendingTransactionPaypalData',
        'certis_data' => '\FinAPI\Client\Model\PendingTransactionCertisData',
        'end_to_end_reference' => 'string',
        'compensation_amount' => 'float',
        'original_amount' => 'float',
        'original_currency' => '\FinAPI\Client\Model\Currency',
        'fee_amount' => 'float',
        'fee_currency' => '\FinAPI\Client\Model\Currency',
        'different_debitor' => 'string',
        'different_creditor' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'id' => 'int64',
        'parent_id' => 'int64',
        'account_id' => 'int64',
        'value_date' => 'date',
        'bank_booking_date' => 'date',
        'finapi_booking_date' => 'date',
        'amount' => null,
        'currency' => null,
        'purpose' => null,
        'counterpart_name' => null,
        'counterpart_account_number' => null,
        'counterpart_iban' => null,
        'counterpart_blz' => null,
        'counterpart_bic' => null,
        'counterpart_bank_name' => null,
        'counterpart_mandate_reference' => null,
        'counterpart_customer_reference' => null,
        'counterpart_creditor_id' => null,
        'counterpart_debitor_id' => null,
        'type' => null,
        'type_code_zka' => null,
        'type_code_swift' => null,
        'sepa_purpose_code' => null,
        'bank_transaction_code' => null,
        'bank_transaction_code_description' => null,
        'primanota' => null,
        'category' => null,
        'labels' => null,
        'is_potential_duplicate' => null,
        'is_adjusting_entry' => null,
        'is_new' => null,
        'import_date' => 'date-time',
        'children' => 'int64',
        'paypal_data' => null,
        'certis_data' => null,
        'end_to_end_reference' => null,
        'compensation_amount' => null,
        'original_amount' => null,
        'original_currency' => null,
        'fee_amount' => null,
        'fee_currency' => null,
        'different_debitor' => null,
        'different_creditor' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'id' => false,
		'parent_id' => false,
		'account_id' => false,
		'value_date' => false,
		'bank_booking_date' => false,
		'finapi_booking_date' => false,
		'amount' => false,
		'currency' => false,
		'purpose' => false,
		'counterpart_name' => false,
		'counterpart_account_number' => false,
		'counterpart_iban' => false,
		'counterpart_blz' => false,
		'counterpart_bic' => false,
		'counterpart_bank_name' => false,
		'counterpart_mandate_reference' => false,
		'counterpart_customer_reference' => false,
		'counterpart_creditor_id' => false,
		'counterpart_debitor_id' => false,
		'type' => false,
		'type_code_zka' => false,
		'type_code_swift' => false,
		'sepa_purpose_code' => false,
		'bank_transaction_code' => false,
		'bank_transaction_code_description' => false,
		'primanota' => false,
		'category' => false,
		'labels' => false,
		'is_potential_duplicate' => false,
		'is_adjusting_entry' => false,
		'is_new' => false,
		'import_date' => false,
		'children' => false,
		'paypal_data' => false,
		'certis_data' => false,
		'end_to_end_reference' => false,
		'compensation_amount' => false,
		'original_amount' => false,
		'original_currency' => false,
		'fee_amount' => false,
		'fee_currency' => false,
		'different_debitor' => false,
		'different_creditor' => false
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
        'id' => 'id',
        'parent_id' => 'parentId',
        'account_id' => 'accountId',
        'value_date' => 'valueDate',
        'bank_booking_date' => 'bankBookingDate',
        'finapi_booking_date' => 'finapiBookingDate',
        'amount' => 'amount',
        'currency' => 'currency',
        'purpose' => 'purpose',
        'counterpart_name' => 'counterpartName',
        'counterpart_account_number' => 'counterpartAccountNumber',
        'counterpart_iban' => 'counterpartIban',
        'counterpart_blz' => 'counterpartBlz',
        'counterpart_bic' => 'counterpartBic',
        'counterpart_bank_name' => 'counterpartBankName',
        'counterpart_mandate_reference' => 'counterpartMandateReference',
        'counterpart_customer_reference' => 'counterpartCustomerReference',
        'counterpart_creditor_id' => 'counterpartCreditorId',
        'counterpart_debitor_id' => 'counterpartDebitorId',
        'type' => 'type',
        'type_code_zka' => 'typeCodeZka',
        'type_code_swift' => 'typeCodeSwift',
        'sepa_purpose_code' => 'sepaPurposeCode',
        'bank_transaction_code' => 'bankTransactionCode',
        'bank_transaction_code_description' => 'bankTransactionCodeDescription',
        'primanota' => 'primanota',
        'category' => 'category',
        'labels' => 'labels',
        'is_potential_duplicate' => 'isPotentialDuplicate',
        'is_adjusting_entry' => 'isAdjustingEntry',
        'is_new' => 'isNew',
        'import_date' => 'importDate',
        'children' => 'children',
        'paypal_data' => 'paypalData',
        'certis_data' => 'certisData',
        'end_to_end_reference' => 'endToEndReference',
        'compensation_amount' => 'compensationAmount',
        'original_amount' => 'originalAmount',
        'original_currency' => 'originalCurrency',
        'fee_amount' => 'feeAmount',
        'fee_currency' => 'feeCurrency',
        'different_debitor' => 'differentDebitor',
        'different_creditor' => 'differentCreditor'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'parent_id' => 'setParentId',
        'account_id' => 'setAccountId',
        'value_date' => 'setValueDate',
        'bank_booking_date' => 'setBankBookingDate',
        'finapi_booking_date' => 'setFinapiBookingDate',
        'amount' => 'setAmount',
        'currency' => 'setCurrency',
        'purpose' => 'setPurpose',
        'counterpart_name' => 'setCounterpartName',
        'counterpart_account_number' => 'setCounterpartAccountNumber',
        'counterpart_iban' => 'setCounterpartIban',
        'counterpart_blz' => 'setCounterpartBlz',
        'counterpart_bic' => 'setCounterpartBic',
        'counterpart_bank_name' => 'setCounterpartBankName',
        'counterpart_mandate_reference' => 'setCounterpartMandateReference',
        'counterpart_customer_reference' => 'setCounterpartCustomerReference',
        'counterpart_creditor_id' => 'setCounterpartCreditorId',
        'counterpart_debitor_id' => 'setCounterpartDebitorId',
        'type' => 'setType',
        'type_code_zka' => 'setTypeCodeZka',
        'type_code_swift' => 'setTypeCodeSwift',
        'sepa_purpose_code' => 'setSepaPurposeCode',
        'bank_transaction_code' => 'setBankTransactionCode',
        'bank_transaction_code_description' => 'setBankTransactionCodeDescription',
        'primanota' => 'setPrimanota',
        'category' => 'setCategory',
        'labels' => 'setLabels',
        'is_potential_duplicate' => 'setIsPotentialDuplicate',
        'is_adjusting_entry' => 'setIsAdjustingEntry',
        'is_new' => 'setIsNew',
        'import_date' => 'setImportDate',
        'children' => 'setChildren',
        'paypal_data' => 'setPaypalData',
        'certis_data' => 'setCertisData',
        'end_to_end_reference' => 'setEndToEndReference',
        'compensation_amount' => 'setCompensationAmount',
        'original_amount' => 'setOriginalAmount',
        'original_currency' => 'setOriginalCurrency',
        'fee_amount' => 'setFeeAmount',
        'fee_currency' => 'setFeeCurrency',
        'different_debitor' => 'setDifferentDebitor',
        'different_creditor' => 'setDifferentCreditor'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'parent_id' => 'getParentId',
        'account_id' => 'getAccountId',
        'value_date' => 'getValueDate',
        'bank_booking_date' => 'getBankBookingDate',
        'finapi_booking_date' => 'getFinapiBookingDate',
        'amount' => 'getAmount',
        'currency' => 'getCurrency',
        'purpose' => 'getPurpose',
        'counterpart_name' => 'getCounterpartName',
        'counterpart_account_number' => 'getCounterpartAccountNumber',
        'counterpart_iban' => 'getCounterpartIban',
        'counterpart_blz' => 'getCounterpartBlz',
        'counterpart_bic' => 'getCounterpartBic',
        'counterpart_bank_name' => 'getCounterpartBankName',
        'counterpart_mandate_reference' => 'getCounterpartMandateReference',
        'counterpart_customer_reference' => 'getCounterpartCustomerReference',
        'counterpart_creditor_id' => 'getCounterpartCreditorId',
        'counterpart_debitor_id' => 'getCounterpartDebitorId',
        'type' => 'getType',
        'type_code_zka' => 'getTypeCodeZka',
        'type_code_swift' => 'getTypeCodeSwift',
        'sepa_purpose_code' => 'getSepaPurposeCode',
        'bank_transaction_code' => 'getBankTransactionCode',
        'bank_transaction_code_description' => 'getBankTransactionCodeDescription',
        'primanota' => 'getPrimanota',
        'category' => 'getCategory',
        'labels' => 'getLabels',
        'is_potential_duplicate' => 'getIsPotentialDuplicate',
        'is_adjusting_entry' => 'getIsAdjustingEntry',
        'is_new' => 'getIsNew',
        'import_date' => 'getImportDate',
        'children' => 'getChildren',
        'paypal_data' => 'getPaypalData',
        'certis_data' => 'getCertisData',
        'end_to_end_reference' => 'getEndToEndReference',
        'compensation_amount' => 'getCompensationAmount',
        'original_amount' => 'getOriginalAmount',
        'original_currency' => 'getOriginalCurrency',
        'fee_amount' => 'getFeeAmount',
        'fee_currency' => 'getFeeCurrency',
        'different_debitor' => 'getDifferentDebitor',
        'different_creditor' => 'getDifferentCreditor'
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
        $this->setIfExists('id', $data ?? [], null);
        $this->setIfExists('parent_id', $data ?? [], null);
        $this->setIfExists('account_id', $data ?? [], null);
        $this->setIfExists('value_date', $data ?? [], null);
        $this->setIfExists('bank_booking_date', $data ?? [], null);
        $this->setIfExists('finapi_booking_date', $data ?? [], null);
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('currency', $data ?? [], null);
        $this->setIfExists('purpose', $data ?? [], null);
        $this->setIfExists('counterpart_name', $data ?? [], null);
        $this->setIfExists('counterpart_account_number', $data ?? [], null);
        $this->setIfExists('counterpart_iban', $data ?? [], null);
        $this->setIfExists('counterpart_blz', $data ?? [], null);
        $this->setIfExists('counterpart_bic', $data ?? [], null);
        $this->setIfExists('counterpart_bank_name', $data ?? [], null);
        $this->setIfExists('counterpart_mandate_reference', $data ?? [], null);
        $this->setIfExists('counterpart_customer_reference', $data ?? [], null);
        $this->setIfExists('counterpart_creditor_id', $data ?? [], null);
        $this->setIfExists('counterpart_debitor_id', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('type_code_zka', $data ?? [], null);
        $this->setIfExists('type_code_swift', $data ?? [], null);
        $this->setIfExists('sepa_purpose_code', $data ?? [], null);
        $this->setIfExists('bank_transaction_code', $data ?? [], null);
        $this->setIfExists('bank_transaction_code_description', $data ?? [], null);
        $this->setIfExists('primanota', $data ?? [], null);
        $this->setIfExists('category', $data ?? [], null);
        $this->setIfExists('labels', $data ?? [], null);
        $this->setIfExists('is_potential_duplicate', $data ?? [], null);
        $this->setIfExists('is_adjusting_entry', $data ?? [], null);
        $this->setIfExists('is_new', $data ?? [], null);
        $this->setIfExists('import_date', $data ?? [], null);
        $this->setIfExists('children', $data ?? [], null);
        $this->setIfExists('paypal_data', $data ?? [], null);
        $this->setIfExists('certis_data', $data ?? [], null);
        $this->setIfExists('end_to_end_reference', $data ?? [], null);
        $this->setIfExists('compensation_amount', $data ?? [], null);
        $this->setIfExists('original_amount', $data ?? [], null);
        $this->setIfExists('original_currency', $data ?? [], null);
        $this->setIfExists('fee_amount', $data ?? [], null);
        $this->setIfExists('fee_currency', $data ?? [], null);
        $this->setIfExists('different_debitor', $data ?? [], null);
        $this->setIfExists('different_creditor', $data ?? [], null);
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

        if ($this->container['id'] === null) {
            $invalidProperties[] = "'id' can't be null";
        }
        if ($this->container['account_id'] === null) {
            $invalidProperties[] = "'account_id' can't be null";
        }
        if ($this->container['value_date'] === null) {
            $invalidProperties[] = "'value_date' can't be null";
        }
        if ($this->container['bank_booking_date'] === null) {
            $invalidProperties[] = "'bank_booking_date' can't be null";
        }
        if ($this->container['finapi_booking_date'] === null) {
            $invalidProperties[] = "'finapi_booking_date' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if (!is_null($this->container['bank_transaction_code_description']) && (mb_strlen($this->container['bank_transaction_code_description']) > 256)) {
            $invalidProperties[] = "invalid value for 'bank_transaction_code_description', the character length must be smaller than or equal to 256.";
        }

        if ($this->container['labels'] === null) {
            $invalidProperties[] = "'labels' can't be null";
        }
        if ($this->container['is_potential_duplicate'] === null) {
            $invalidProperties[] = "'is_potential_duplicate' can't be null";
        }
        if ($this->container['is_adjusting_entry'] === null) {
            $invalidProperties[] = "'is_adjusting_entry' can't be null";
        }
        if ($this->container['is_new'] === null) {
            $invalidProperties[] = "'is_new' can't be null";
        }
        if ($this->container['import_date'] === null) {
            $invalidProperties[] = "'import_date' can't be null";
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
     * Gets id
     *
     * @return int
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param int $id Transaction identifier
     *
     * @return self
     */
    public function setId($id)
    {
        if (is_null($id)) {
            throw new \InvalidArgumentException('non-nullable id cannot be null');
        }
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets parent_id
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->container['parent_id'];
    }

    /**
     * Sets parent_id
     *
     * @param int|null $parent_id Parent transaction identifier
     *
     * @return self
     */
    public function setParentId($parent_id)
    {
        if (is_null($parent_id)) {
            throw new \InvalidArgumentException('non-nullable parent_id cannot be null');
        }
        $this->container['parent_id'] = $parent_id;

        return $this;
    }

    /**
     * Gets account_id
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->container['account_id'];
    }

    /**
     * Sets account_id
     *
     * @param int $account_id Account identifier
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
     * Gets value_date
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->container['value_date'];
    }

    /**
     * Sets value_date
     *
     * @param \DateTime $value_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Value date.
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
     * Gets bank_booking_date
     *
     * @return \DateTime
     */
    public function getBankBookingDate()
    {
        return $this->container['bank_booking_date'];
    }

    /**
     * Sets bank_booking_date
     *
     * @param \DateTime $bank_booking_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Bank booking date.
     *
     * @return self
     */
    public function setBankBookingDate($bank_booking_date)
    {
        if (is_null($bank_booking_date)) {
            throw new \InvalidArgumentException('non-nullable bank_booking_date cannot be null');
        }
        $this->container['bank_booking_date'] = $bank_booking_date;

        return $this;
    }

    /**
     * Gets finapi_booking_date
     *
     * @return \DateTime
     */
    public function getFinapiBookingDate()
    {
        return $this->container['finapi_booking_date'];
    }

    /**
     * Sets finapi_booking_date
     *
     * @param \DateTime $finapi_booking_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>finAPI Booking date. NOTE: In some cases, banks may deliver transactions that are booked in future, but already included in the current account balance. To keep the account balance consistent with the set of transactions, such \"future transactions\" will be imported with their finapiBookingDate set to the current date (i.e.: date of import). The finapiBookingDate will automatically get adjusted towards the bankBookingDate each time the associated bank account is updated. Example: A transaction is imported on July, 3rd, with a bank reported booking date of July, 6th. The transaction will be imported with its finapiBookingDate set to July, 3rd. Then, on July 4th, the associated account is updated. During this update, the transaction's finapiBookingDate will be automatically adjusted to July 4th. This adjustment of the finapiBookingDate takes place on each update until the bank account is updated on July 6th or later, in which case the transaction's finapiBookingDate will be adjusted to its final value, July 6th.<br/> The finapiBookingDate is the date that is used by the finAPI PFM services. E.g. when you calculate the spendings of an account for the current month, and have a transaction with finapiBookingDate in the current month but bankBookingDate at the beginning of the next month, then this transaction is included in the calculations (as the bank has this transaction's amount included in the current account balance as well).
     *
     * @return self
     */
    public function setFinapiBookingDate($finapi_booking_date)
    {
        if (is_null($finapi_booking_date)) {
            throw new \InvalidArgumentException('non-nullable finapi_booking_date cannot be null');
        }
        $this->container['finapi_booking_date'] = $finapi_booking_date;

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
     * @param float $amount Transaction amount
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
     * @param string|null $purpose Transaction purpose. Maximum length: 2000
     *
     * @return self
     */
    public function setPurpose($purpose)
    {
        if (is_null($purpose)) {
            throw new \InvalidArgumentException('non-nullable purpose cannot be null');
        }
        $this->container['purpose'] = $purpose;

        return $this;
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
     * @param string|null $counterpart_name Counterpart name. Maximum length: 80
     *
     * @return self
     */
    public function setCounterpartName($counterpart_name)
    {
        if (is_null($counterpart_name)) {
            throw new \InvalidArgumentException('non-nullable counterpart_name cannot be null');
        }
        $this->container['counterpart_name'] = $counterpart_name;

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
     * @param string|null $counterpart_account_number Counterpart account number
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
     * @param string|null $counterpart_iban Counterpart IBAN
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
     * @param string|null $counterpart_blz Counterpart BLZ
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
     * @param string|null $counterpart_bic Counterpart BIC
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
     * @param string|null $counterpart_bank_name Counterpart Bank name
     *
     * @return self
     */
    public function setCounterpartBankName($counterpart_bank_name)
    {
        if (is_null($counterpart_bank_name)) {
            throw new \InvalidArgumentException('non-nullable counterpart_bank_name cannot be null');
        }
        $this->container['counterpart_bank_name'] = $counterpart_bank_name;

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
     * @param string|null $counterpart_mandate_reference The mandate reference of the counterpart
     *
     * @return self
     */
    public function setCounterpartMandateReference($counterpart_mandate_reference)
    {
        if (is_null($counterpart_mandate_reference)) {
            throw new \InvalidArgumentException('non-nullable counterpart_mandate_reference cannot be null');
        }
        $this->container['counterpart_mandate_reference'] = $counterpart_mandate_reference;

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
     * @param string|null $counterpart_customer_reference The customer reference of the counterpart
     *
     * @return self
     */
    public function setCounterpartCustomerReference($counterpart_customer_reference)
    {
        if (is_null($counterpart_customer_reference)) {
            throw new \InvalidArgumentException('non-nullable counterpart_customer_reference cannot be null');
        }
        $this->container['counterpart_customer_reference'] = $counterpart_customer_reference;

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
        $this->container['counterpart_creditor_id'] = $counterpart_creditor_id;

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
     * @param string|null $type Transaction type, according to the bank. If set, this will contain a term in the language of the bank, that you can display to the user. Some examples of common values are: \"Lastschrift\", \"Auslands&uuml;berweisung\", \"Geb&uuml;hren\", \"Zinsen\". The maximum possible length of this field is 255 characters.
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_null($type)) {
            throw new \InvalidArgumentException('non-nullable type cannot be null');
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets type_code_zka
     *
     * @return string|null
     */
    public function getTypeCodeZka()
    {
        return $this->container['type_code_zka'];
    }

    /**
     * Sets type_code_zka
     *
     * @param string|null $type_code_zka ZKA business transaction code which relates to the transaction's type. Possible values range from 1 through 999. If no information about the ZKA type code is available, then this field will be null.
     *
     * @return self
     */
    public function setTypeCodeZka($type_code_zka)
    {
        if (is_null($type_code_zka)) {
            throw new \InvalidArgumentException('non-nullable type_code_zka cannot be null');
        }
        $this->container['type_code_zka'] = $type_code_zka;

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
     * @param string|null $type_code_swift SWIFT transaction type code. If no information about the SWIFT code is available, then this field will be null.
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
     * @param string|null $sepa_purpose_code SEPA purpose code, according to ISO 20022
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
     * Gets bank_transaction_code
     *
     * @return string|null
     */
    public function getBankTransactionCode()
    {
        return $this->container['bank_transaction_code'];
    }

    /**
     * Sets bank_transaction_code
     *
     * @param string|null $bank_transaction_code Bank transaction code, according to ISO 20022
     *
     * @return self
     */
    public function setBankTransactionCode($bank_transaction_code)
    {
        if (is_null($bank_transaction_code)) {
            throw new \InvalidArgumentException('non-nullable bank_transaction_code cannot be null');
        }
        $this->container['bank_transaction_code'] = $bank_transaction_code;

        return $this;
    }

    /**
     * Gets bank_transaction_code_description
     *
     * @return string|null
     */
    public function getBankTransactionCodeDescription()
    {
        return $this->container['bank_transaction_code_description'];
    }

    /**
     * Sets bank_transaction_code_description
     *
     * @param string|null $bank_transaction_code_description Bank transaction code description, according to ISO 20022.<br/>The field is dynamic and can be initialized in different languages depending on the `Accept-Language` header provided within the request. Currently, only English and German are implemented, but this can get extended on demand.
     *
     * @return self
     */
    public function setBankTransactionCodeDescription($bank_transaction_code_description)
    {
        if (is_null($bank_transaction_code_description)) {
            throw new \InvalidArgumentException('non-nullable bank_transaction_code_description cannot be null');
        }
        if ((mb_strlen($bank_transaction_code_description) > 256)) {
            throw new \InvalidArgumentException('invalid length for $bank_transaction_code_description when calling Transaction., must be smaller than or equal to 256.');
        }

        $this->container['bank_transaction_code_description'] = $bank_transaction_code_description;

        return $this;
    }

    /**
     * Gets primanota
     *
     * @return string|null
     */
    public function getPrimanota()
    {
        return $this->container['primanota'];
    }

    /**
     * Sets primanota
     *
     * @param string|null $primanota Transaction primanota (bank side identification number)
     *
     * @return self
     */
    public function setPrimanota($primanota)
    {
        if (is_null($primanota)) {
            throw new \InvalidArgumentException('non-nullable primanota cannot be null');
        }
        $this->container['primanota'] = $primanota;

        return $this;
    }

    /**
     * Gets category
     *
     * @return \FinAPI\Client\Model\TransactionCategory|null
     */
    public function getCategory()
    {
        return $this->container['category'];
    }

    /**
     * Sets category
     *
     * @param \FinAPI\Client\Model\TransactionCategory|null $category category
     *
     * @return self
     */
    public function setCategory($category)
    {
        if (is_null($category)) {
            throw new \InvalidArgumentException('non-nullable category cannot be null');
        }
        $this->container['category'] = $category;

        return $this;
    }

    /**
     * Gets labels
     *
     * @return \FinAPI\Client\Model\Label[]
     */
    public function getLabels()
    {
        return $this->container['labels'];
    }

    /**
     * Sets labels
     *
     * @param \FinAPI\Client\Model\Label[] $labels Array of assigned labels<br/> <strong>Type:</strong> Label
     *
     * @return self
     */
    public function setLabels($labels)
    {
        if (is_null($labels)) {
            throw new \InvalidArgumentException('non-nullable labels cannot be null');
        }
        $this->container['labels'] = $labels;

        return $this;
    }

    /**
     * Gets is_potential_duplicate
     *
     * @return bool
     */
    public function getIsPotentialDuplicate()
    {
        return $this->container['is_potential_duplicate'];
    }

    /**
     * Sets is_potential_duplicate
     *
     * @param bool $is_potential_duplicate While finAPI uses a well-elaborated algorithm for uniquely identifying transactions, there is still the possibility that during an account update, a transaction that was imported previously may be imported a second time as a new transaction. For example, this can happen if some transaction data changes on the bank server side. However, finAPI also includes an algorithm of identifying such \"potential duplicate\" transactions. If this field is set to true, it means that finAPI detected a similar transaction that might actually be the same. It is recommended to communicate this information to the end user, and give him an option to delete the transaction in case he confirms that it really is a duplicate.
     *
     * @return self
     */
    public function setIsPotentialDuplicate($is_potential_duplicate)
    {
        if (is_null($is_potential_duplicate)) {
            throw new \InvalidArgumentException('non-nullable is_potential_duplicate cannot be null');
        }
        $this->container['is_potential_duplicate'] = $is_potential_duplicate;

        return $this;
    }

    /**
     * Gets is_adjusting_entry
     *
     * @return bool
     */
    public function getIsAdjustingEntry()
    {
        return $this->container['is_adjusting_entry'];
    }

    /**
     * Sets is_adjusting_entry
     *
     * @param bool $is_adjusting_entry Indicating whether this transaction is an adjusting entry ('Zwischensaldo').<br/><br/>Adjusting entries do not originate from the bank, but are added by finAPI during an account update when the bank reported account balance does not add up to the set of transactions that finAPI receives for the account. In this case, the adjusting entry will fix the deviation between the balance and the received transactions so that both adds up again.<br/><br/>Possible causes for such deviations are:<br/>- Inconsistencies in how the bank calculates the balance, for instance when not yet booked transactions are already included in the balance, but not included in the set of transactions<br/>- Gaps in the transaction history that finAPI receives, for instance because the account has not been updated for a while and older transactions are no longer available
     *
     * @return self
     */
    public function setIsAdjustingEntry($is_adjusting_entry)
    {
        if (is_null($is_adjusting_entry)) {
            throw new \InvalidArgumentException('non-nullable is_adjusting_entry cannot be null');
        }
        $this->container['is_adjusting_entry'] = $is_adjusting_entry;

        return $this;
    }

    /**
     * Gets is_new
     *
     * @return bool
     */
    public function getIsNew()
    {
        return $this->container['is_new'];
    }

    /**
     * Sets is_new
     *
     * @param bool $is_new Indicating whether this transaction is 'new' or not. Any newly imported transaction will have this flag initially set to true. How you use this field is up to your interpretation. For example, you might want to set it to false once a user has clicked on/seen the transaction. You can change this flag to 'false' with the PATCH method.
     *
     * @return self
     */
    public function setIsNew($is_new)
    {
        if (is_null($is_new)) {
            throw new \InvalidArgumentException('non-nullable is_new cannot be null');
        }
        $this->container['is_new'] = $is_new;

        return $this;
    }

    /**
     * Gets import_date
     *
     * @return \DateTime
     */
    public function getImportDate()
    {
        return $this->container['import_date'];
    }

    /**
     * Sets import_date
     *
     * @param \DateTime $import_date <strong>Format:</strong> 'YYYY-MM-DD'T'HH:MM:SS.SSSXXX' (RFC 3339, section 5.6)<br/>Date of transaction import.
     *
     * @return self
     */
    public function setImportDate($import_date)
    {
        if (is_null($import_date)) {
            throw new \InvalidArgumentException('non-nullable import_date cannot be null');
        }
        $this->container['import_date'] = $import_date;

        return $this;
    }

    /**
     * Gets children
     *
     * @return int[]|null
     */
    public function getChildren()
    {
        return $this->container['children'];
    }

    /**
     * Sets children
     *
     * @param int[]|null $children Sub-transactions identifiers (if this transaction is split)
     *
     * @return self
     */
    public function setChildren($children)
    {
        if (is_null($children)) {
            throw new \InvalidArgumentException('non-nullable children cannot be null');
        }
        $this->container['children'] = $children;

        return $this;
    }

    /**
     * Gets paypal_data
     *
     * @return \FinAPI\Client\Model\PendingTransactionPaypalData|null
     */
    public function getPaypalData()
    {
        return $this->container['paypal_data'];
    }

    /**
     * Sets paypal_data
     *
     * @param \FinAPI\Client\Model\PendingTransactionPaypalData|null $paypal_data paypal_data
     *
     * @return self
     */
    public function setPaypalData($paypal_data)
    {
        if (is_null($paypal_data)) {
            throw new \InvalidArgumentException('non-nullable paypal_data cannot be null');
        }
        $this->container['paypal_data'] = $paypal_data;

        return $this;
    }

    /**
     * Gets certis_data
     *
     * @return \FinAPI\Client\Model\PendingTransactionCertisData|null
     */
    public function getCertisData()
    {
        return $this->container['certis_data'];
    }

    /**
     * Sets certis_data
     *
     * @param \FinAPI\Client\Model\PendingTransactionCertisData|null $certis_data certis_data
     *
     * @return self
     */
    public function setCertisData($certis_data)
    {
        if (is_null($certis_data)) {
            throw new \InvalidArgumentException('non-nullable certis_data cannot be null');
        }
        $this->container['certis_data'] = $certis_data;

        return $this;
    }

    /**
     * Gets end_to_end_reference
     *
     * @return string|null
     */
    public function getEndToEndReference()
    {
        return $this->container['end_to_end_reference'];
    }

    /**
     * Sets end_to_end_reference
     *
     * @param string|null $end_to_end_reference End-To-End reference
     *
     * @return self
     */
    public function setEndToEndReference($end_to_end_reference)
    {
        if (is_null($end_to_end_reference)) {
            throw new \InvalidArgumentException('non-nullable end_to_end_reference cannot be null');
        }
        $this->container['end_to_end_reference'] = $end_to_end_reference;

        return $this;
    }

    /**
     * Gets compensation_amount
     *
     * @return float|null
     */
    public function getCompensationAmount()
    {
        return $this->container['compensation_amount'];
    }

    /**
     * Sets compensation_amount
     *
     * @param float|null $compensation_amount Compensation Amount. Sum of reimbursement of out-of-pocket expenses plus processing brokerage in case of a national return / refund debit as well as an optional interest equalisation. Exists predominantly for SEPA direct debit returns.
     *
     * @return self
     */
    public function setCompensationAmount($compensation_amount)
    {
        if (is_null($compensation_amount)) {
            throw new \InvalidArgumentException('non-nullable compensation_amount cannot be null');
        }
        $this->container['compensation_amount'] = $compensation_amount;

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
     * @param float|null $original_amount Original Amount of the original direct debit. Exists predominantly for SEPA direct debit returns.
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
     * Gets fee_amount
     *
     * @return float|null
     */
    public function getFeeAmount()
    {
        return $this->container['fee_amount'];
    }

    /**
     * Sets fee_amount
     *
     * @param float|null $fee_amount Amount of the transaction fee. Some banks charge a specific fee per transaction. Only returned by a few banks.
     *
     * @return self
     */
    public function setFeeAmount($fee_amount)
    {
        if (is_null($fee_amount)) {
            throw new \InvalidArgumentException('non-nullable fee_amount cannot be null');
        }
        $this->container['fee_amount'] = $fee_amount;

        return $this;
    }

    /**
     * Gets fee_currency
     *
     * @return \FinAPI\Client\Model\Currency|null
     */
    public function getFeeCurrency()
    {
        return $this->container['fee_currency'];
    }

    /**
     * Sets fee_currency
     *
     * @param \FinAPI\Client\Model\Currency|null $fee_currency fee_currency
     *
     * @return self
     */
    public function setFeeCurrency($fee_currency)
    {
        if (is_null($fee_currency)) {
            throw new \InvalidArgumentException('non-nullable fee_currency cannot be null');
        }
        $this->container['fee_currency'] = $fee_currency;

        return $this;
    }

    /**
     * Gets different_debitor
     *
     * @return string|null
     */
    public function getDifferentDebitor()
    {
        return $this->container['different_debitor'];
    }

    /**
     * Sets different_debitor
     *
     * @param string|null $different_debitor Payer's/debtor's reference party (in the case of a credit transfer) or payee's/creditor's reference party (in the case of a direct debit)
     *
     * @return self
     */
    public function setDifferentDebitor($different_debitor)
    {
        if (is_null($different_debitor)) {
            throw new \InvalidArgumentException('non-nullable different_debitor cannot be null');
        }
        $this->container['different_debitor'] = $different_debitor;

        return $this;
    }

    /**
     * Gets different_creditor
     *
     * @return string|null
     */
    public function getDifferentCreditor()
    {
        return $this->container['different_creditor'];
    }

    /**
     * Sets different_creditor
     *
     * @param string|null $different_creditor Payee's/creditor's reference party (in the case of a credit transfer) or payer's/debtor's reference party (in the case of a direct debit)
     *
     * @return self
     */
    public function setDifferentCreditor($different_creditor)
    {
        if (is_null($different_creditor)) {
            throw new \InvalidArgumentException('non-nullable different_creditor cannot be null');
        }
        $this->container['different_creditor'] = $different_creditor;

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


