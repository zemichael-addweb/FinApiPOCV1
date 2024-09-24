<?php
/**
 * EnabledProducts
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
 * EnabledProducts Class Doc Comment
 *
 * @category Class
 * @description Information about the finAPI products available to this client.
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class EnabledProducts implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'EnabledProducts';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'access' => 'bool',
        'web_form' => 'bool',
        'customer_dashboard' => 'bool',
        'data_intelligence' => 'bool',
        'giro_ident' => 'bool',
        'schufa_api' => 'bool',
        'di_labelling' => 'bool',
        'contract_manager' => 'bool',
        'giro_check' => 'bool',
        'kredit_check' => 'bool',
        'kredit_check_b2_b' => 'bool',
        'debit_flex' => 'bool',
        'transparency_register' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'access' => null,
        'web_form' => null,
        'customer_dashboard' => null,
        'data_intelligence' => null,
        'giro_ident' => null,
        'schufa_api' => null,
        'di_labelling' => null,
        'contract_manager' => null,
        'giro_check' => null,
        'kredit_check' => null,
        'kredit_check_b2_b' => null,
        'debit_flex' => null,
        'transparency_register' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'access' => false,
		'web_form' => false,
		'customer_dashboard' => false,
		'data_intelligence' => false,
		'giro_ident' => false,
		'schufa_api' => false,
		'di_labelling' => false,
		'contract_manager' => false,
		'giro_check' => false,
		'kredit_check' => false,
		'kredit_check_b2_b' => false,
		'debit_flex' => false,
		'transparency_register' => false
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
        'access' => 'access',
        'web_form' => 'webForm',
        'customer_dashboard' => 'customerDashboard',
        'data_intelligence' => 'dataIntelligence',
        'giro_ident' => 'giroIdent',
        'schufa_api' => 'schufaApi',
        'di_labelling' => 'diLabelling',
        'contract_manager' => 'contractManager',
        'giro_check' => 'giroCheck',
        'kredit_check' => 'kreditCheck',
        'kredit_check_b2_b' => 'kreditCheckB2B',
        'debit_flex' => 'debitFlex',
        'transparency_register' => 'transparencyRegister'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'access' => 'setAccess',
        'web_form' => 'setWebForm',
        'customer_dashboard' => 'setCustomerDashboard',
        'data_intelligence' => 'setDataIntelligence',
        'giro_ident' => 'setGiroIdent',
        'schufa_api' => 'setSchufaApi',
        'di_labelling' => 'setDiLabelling',
        'contract_manager' => 'setContractManager',
        'giro_check' => 'setGiroCheck',
        'kredit_check' => 'setKreditCheck',
        'kredit_check_b2_b' => 'setKreditCheckB2B',
        'debit_flex' => 'setDebitFlex',
        'transparency_register' => 'setTransparencyRegister'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'access' => 'getAccess',
        'web_form' => 'getWebForm',
        'customer_dashboard' => 'getCustomerDashboard',
        'data_intelligence' => 'getDataIntelligence',
        'giro_ident' => 'getGiroIdent',
        'schufa_api' => 'getSchufaApi',
        'di_labelling' => 'getDiLabelling',
        'contract_manager' => 'getContractManager',
        'giro_check' => 'getGiroCheck',
        'kredit_check' => 'getKreditCheck',
        'kredit_check_b2_b' => 'getKreditCheckB2B',
        'debit_flex' => 'getDebitFlex',
        'transparency_register' => 'getTransparencyRegister'
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
        $this->setIfExists('access', $data ?? [], null);
        $this->setIfExists('web_form', $data ?? [], null);
        $this->setIfExists('customer_dashboard', $data ?? [], null);
        $this->setIfExists('data_intelligence', $data ?? [], null);
        $this->setIfExists('giro_ident', $data ?? [], null);
        $this->setIfExists('schufa_api', $data ?? [], null);
        $this->setIfExists('di_labelling', $data ?? [], null);
        $this->setIfExists('contract_manager', $data ?? [], null);
        $this->setIfExists('giro_check', $data ?? [], null);
        $this->setIfExists('kredit_check', $data ?? [], null);
        $this->setIfExists('kredit_check_b2_b', $data ?? [], null);
        $this->setIfExists('debit_flex', $data ?? [], null);
        $this->setIfExists('transparency_register', $data ?? [], null);
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

        if ($this->container['access'] === null) {
            $invalidProperties[] = "'access' can't be null";
        }
        if ($this->container['web_form'] === null) {
            $invalidProperties[] = "'web_form' can't be null";
        }
        if ($this->container['customer_dashboard'] === null) {
            $invalidProperties[] = "'customer_dashboard' can't be null";
        }
        if ($this->container['data_intelligence'] === null) {
            $invalidProperties[] = "'data_intelligence' can't be null";
        }
        if ($this->container['giro_ident'] === null) {
            $invalidProperties[] = "'giro_ident' can't be null";
        }
        if ($this->container['schufa_api'] === null) {
            $invalidProperties[] = "'schufa_api' can't be null";
        }
        if ($this->container['di_labelling'] === null) {
            $invalidProperties[] = "'di_labelling' can't be null";
        }
        if ($this->container['contract_manager'] === null) {
            $invalidProperties[] = "'contract_manager' can't be null";
        }
        if ($this->container['giro_check'] === null) {
            $invalidProperties[] = "'giro_check' can't be null";
        }
        if ($this->container['kredit_check'] === null) {
            $invalidProperties[] = "'kredit_check' can't be null";
        }
        if ($this->container['kredit_check_b2_b'] === null) {
            $invalidProperties[] = "'kredit_check_b2_b' can't be null";
        }
        if ($this->container['debit_flex'] === null) {
            $invalidProperties[] = "'debit_flex' can't be null";
        }
        if ($this->container['transparency_register'] === null) {
            $invalidProperties[] = "'transparency_register' can't be null";
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
     * Gets access
     *
     * @return bool
     */
    public function getAccess()
    {
        return $this->container['access'];
    }

    /**
     * Sets access
     *
     * @param bool $access Whether Access is available to this client.
     *
     * @return self
     */
    public function setAccess($access)
    {
        if (is_null($access)) {
            throw new \InvalidArgumentException('non-nullable access cannot be null');
        }
        $this->container['access'] = $access;

        return $this;
    }

    /**
     * Gets web_form
     *
     * @return bool
     */
    public function getWebForm()
    {
        return $this->container['web_form'];
    }

    /**
     * Sets web_form
     *
     * @param bool $web_form Whether the Web Form is available to this client.
     *
     * @return self
     */
    public function setWebForm($web_form)
    {
        if (is_null($web_form)) {
            throw new \InvalidArgumentException('non-nullable web_form cannot be null');
        }
        $this->container['web_form'] = $web_form;

        return $this;
    }

    /**
     * Gets customer_dashboard
     *
     * @return bool
     */
    public function getCustomerDashboard()
    {
        return $this->container['customer_dashboard'];
    }

    /**
     * Sets customer_dashboard
     *
     * @param bool $customer_dashboard Whether the CustomerDashboard is available to this client.
     *
     * @return self
     */
    public function setCustomerDashboard($customer_dashboard)
    {
        if (is_null($customer_dashboard)) {
            throw new \InvalidArgumentException('non-nullable customer_dashboard cannot be null');
        }
        $this->container['customer_dashboard'] = $customer_dashboard;

        return $this;
    }

    /**
     * Gets data_intelligence
     *
     * @return bool
     */
    public function getDataIntelligence()
    {
        return $this->container['data_intelligence'];
    }

    /**
     * Sets data_intelligence
     *
     * @param bool $data_intelligence Whether Data Intelligence is available to this client.
     *
     * @return self
     */
    public function setDataIntelligence($data_intelligence)
    {
        if (is_null($data_intelligence)) {
            throw new \InvalidArgumentException('non-nullable data_intelligence cannot be null');
        }
        $this->container['data_intelligence'] = $data_intelligence;

        return $this;
    }

    /**
     * Gets giro_ident
     *
     * @return bool
     */
    public function getGiroIdent()
    {
        return $this->container['giro_ident'];
    }

    /**
     * Sets giro_ident
     *
     * @param bool $giro_ident Whether GiroIdent is available to this client.
     *
     * @return self
     */
    public function setGiroIdent($giro_ident)
    {
        if (is_null($giro_ident)) {
            throw new \InvalidArgumentException('non-nullable giro_ident cannot be null');
        }
        $this->container['giro_ident'] = $giro_ident;

        return $this;
    }

    /**
     * Gets schufa_api
     *
     * @return bool
     */
    public function getSchufaApi()
    {
        return $this->container['schufa_api'];
    }

    /**
     * Sets schufa_api
     *
     * @param bool $schufa_api Whether the SCHUFA API is available to this client.
     *
     * @return self
     */
    public function setSchufaApi($schufa_api)
    {
        if (is_null($schufa_api)) {
            throw new \InvalidArgumentException('non-nullable schufa_api cannot be null');
        }
        $this->container['schufa_api'] = $schufa_api;

        return $this;
    }

    /**
     * Gets di_labelling
     *
     * @return bool
     */
    public function getDiLabelling()
    {
        return $this->container['di_labelling'];
    }

    /**
     * Sets di_labelling
     *
     * @param bool $di_labelling Whether DI Labelling is available to this client.
     *
     * @return self
     */
    public function setDiLabelling($di_labelling)
    {
        if (is_null($di_labelling)) {
            throw new \InvalidArgumentException('non-nullable di_labelling cannot be null');
        }
        $this->container['di_labelling'] = $di_labelling;

        return $this;
    }

    /**
     * Gets contract_manager
     *
     * @return bool
     */
    public function getContractManager()
    {
        return $this->container['contract_manager'];
    }

    /**
     * Sets contract_manager
     *
     * @param bool $contract_manager Whether the ContractManager is available to this client.
     *
     * @return self
     */
    public function setContractManager($contract_manager)
    {
        if (is_null($contract_manager)) {
            throw new \InvalidArgumentException('non-nullable contract_manager cannot be null');
        }
        $this->container['contract_manager'] = $contract_manager;

        return $this;
    }

    /**
     * Gets giro_check
     *
     * @return bool
     */
    public function getGiroCheck()
    {
        return $this->container['giro_check'];
    }

    /**
     * Sets giro_check
     *
     * @param bool $giro_check Whether GiroCheck is available to this client.
     *
     * @return self
     */
    public function setGiroCheck($giro_check)
    {
        if (is_null($giro_check)) {
            throw new \InvalidArgumentException('non-nullable giro_check cannot be null');
        }
        $this->container['giro_check'] = $giro_check;

        return $this;
    }

    /**
     * Gets kredit_check
     *
     * @return bool
     */
    public function getKreditCheck()
    {
        return $this->container['kredit_check'];
    }

    /**
     * Sets kredit_check
     *
     * @param bool $kredit_check Whether KreditCheck is available to this client.
     *
     * @return self
     */
    public function setKreditCheck($kredit_check)
    {
        if (is_null($kredit_check)) {
            throw new \InvalidArgumentException('non-nullable kredit_check cannot be null');
        }
        $this->container['kredit_check'] = $kredit_check;

        return $this;
    }

    /**
     * Gets kredit_check_b2_b
     *
     * @return bool
     */
    public function getKreditCheckB2B()
    {
        return $this->container['kredit_check_b2_b'];
    }

    /**
     * Sets kredit_check_b2_b
     *
     * @param bool $kredit_check_b2_b Whether KreditCheck B2B is available to this client.
     *
     * @return self
     */
    public function setKreditCheckB2B($kredit_check_b2_b)
    {
        if (is_null($kredit_check_b2_b)) {
            throw new \InvalidArgumentException('non-nullable kredit_check_b2_b cannot be null');
        }
        $this->container['kredit_check_b2_b'] = $kredit_check_b2_b;

        return $this;
    }

    /**
     * Gets debit_flex
     *
     * @return bool
     */
    public function getDebitFlex()
    {
        return $this->container['debit_flex'];
    }

    /**
     * Sets debit_flex
     *
     * @param bool $debit_flex Whether DebitFlex is available to this client.
     *
     * @return self
     */
    public function setDebitFlex($debit_flex)
    {
        if (is_null($debit_flex)) {
            throw new \InvalidArgumentException('non-nullable debit_flex cannot be null');
        }
        $this->container['debit_flex'] = $debit_flex;

        return $this;
    }

    /**
     * Gets transparency_register
     *
     * @return bool
     */
    public function getTransparencyRegister()
    {
        return $this->container['transparency_register'];
    }

    /**
     * Sets transparency_register
     *
     * @param bool $transparency_register Whether the TransparencyRegister is available to this client.
     *
     * @return self
     */
    public function setTransparencyRegister($transparency_register)
    {
        if (is_null($transparency_register)) {
            throw new \InvalidArgumentException('non-nullable transparency_register cannot be null');
        }
        $this->container['transparency_register'] = $transparency_register;

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


