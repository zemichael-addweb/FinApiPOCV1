<?php
/**
 * BankInterface
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
 * BankInterface Class Doc Comment
 *
 * @category Class
 * @description Interface used to connect to a bank
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class BankInterface implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'BankInterface';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'banking_interface' => '\FinAPI\Client\Model\BankingInterface',
        'tpp_authentication_group' => '\FinAPI\Client\Model\BankInterfaceTppAuthenticationGroup',
        'login_credentials' => '\FinAPI\Client\Model\BankInterfaceLoginField[]',
        'properties' => 'BankInterfaceProperty[]',
        'login_hint' => 'string',
        'health' => 'int',
        'last_communication_attempt' => '\DateTime',
        'last_successful_communication' => '\DateTime',
        'is_ais_supported' => 'bool',
        'is_pis_supported' => 'bool',
        'ais_capabilities' => '\FinAPI\Client\Model\BankInterfaceAisCapabilities',
        'payment_capabilities' => '\FinAPI\Client\Model\BankInterfacePaymentCapabilities',
        'payment_constraints' => '\FinAPI\Client\Model\BankInterfacePaymentConstraints',
        'ais_account_types' => 'AccountType[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'banking_interface' => null,
        'tpp_authentication_group' => null,
        'login_credentials' => null,
        'properties' => null,
        'login_hint' => null,
        'health' => 'int32',
        'last_communication_attempt' => 'date-time',
        'last_successful_communication' => 'date-time',
        'is_ais_supported' => null,
        'is_pis_supported' => null,
        'ais_capabilities' => null,
        'payment_capabilities' => null,
        'payment_constraints' => null,
        'ais_account_types' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'banking_interface' => false,
		'tpp_authentication_group' => false,
		'login_credentials' => false,
		'properties' => false,
		'login_hint' => false,
		'health' => false,
		'last_communication_attempt' => false,
		'last_successful_communication' => false,
		'is_ais_supported' => false,
		'is_pis_supported' => false,
		'ais_capabilities' => false,
		'payment_capabilities' => false,
		'payment_constraints' => false,
		'ais_account_types' => false
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
        'banking_interface' => 'bankingInterface',
        'tpp_authentication_group' => 'tppAuthenticationGroup',
        'login_credentials' => 'loginCredentials',
        'properties' => 'properties',
        'login_hint' => 'loginHint',
        'health' => 'health',
        'last_communication_attempt' => 'lastCommunicationAttempt',
        'last_successful_communication' => 'lastSuccessfulCommunication',
        'is_ais_supported' => 'isAisSupported',
        'is_pis_supported' => 'isPisSupported',
        'ais_capabilities' => 'aisCapabilities',
        'payment_capabilities' => 'paymentCapabilities',
        'payment_constraints' => 'paymentConstraints',
        'ais_account_types' => 'aisAccountTypes'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'banking_interface' => 'setBankingInterface',
        'tpp_authentication_group' => 'setTppAuthenticationGroup',
        'login_credentials' => 'setLoginCredentials',
        'properties' => 'setProperties',
        'login_hint' => 'setLoginHint',
        'health' => 'setHealth',
        'last_communication_attempt' => 'setLastCommunicationAttempt',
        'last_successful_communication' => 'setLastSuccessfulCommunication',
        'is_ais_supported' => 'setIsAisSupported',
        'is_pis_supported' => 'setIsPisSupported',
        'ais_capabilities' => 'setAisCapabilities',
        'payment_capabilities' => 'setPaymentCapabilities',
        'payment_constraints' => 'setPaymentConstraints',
        'ais_account_types' => 'setAisAccountTypes'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'banking_interface' => 'getBankingInterface',
        'tpp_authentication_group' => 'getTppAuthenticationGroup',
        'login_credentials' => 'getLoginCredentials',
        'properties' => 'getProperties',
        'login_hint' => 'getLoginHint',
        'health' => 'getHealth',
        'last_communication_attempt' => 'getLastCommunicationAttempt',
        'last_successful_communication' => 'getLastSuccessfulCommunication',
        'is_ais_supported' => 'getIsAisSupported',
        'is_pis_supported' => 'getIsPisSupported',
        'ais_capabilities' => 'getAisCapabilities',
        'payment_capabilities' => 'getPaymentCapabilities',
        'payment_constraints' => 'getPaymentConstraints',
        'ais_account_types' => 'getAisAccountTypes'
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
        $this->setIfExists('banking_interface', $data ?? [], null);
        $this->setIfExists('tpp_authentication_group', $data ?? [], null);
        $this->setIfExists('login_credentials', $data ?? [], null);
        $this->setIfExists('properties', $data ?? [], null);
        $this->setIfExists('login_hint', $data ?? [], null);
        $this->setIfExists('health', $data ?? [], null);
        $this->setIfExists('last_communication_attempt', $data ?? [], null);
        $this->setIfExists('last_successful_communication', $data ?? [], null);
        $this->setIfExists('is_ais_supported', $data ?? [], null);
        $this->setIfExists('is_pis_supported', $data ?? [], null);
        $this->setIfExists('ais_capabilities', $data ?? [], null);
        $this->setIfExists('payment_capabilities', $data ?? [], null);
        $this->setIfExists('payment_constraints', $data ?? [], null);
        $this->setIfExists('ais_account_types', $data ?? [], null);
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

        if ($this->container['banking_interface'] === null) {
            $invalidProperties[] = "'banking_interface' can't be null";
        }
        if ($this->container['login_credentials'] === null) {
            $invalidProperties[] = "'login_credentials' can't be null";
        }
        if ($this->container['properties'] === null) {
            $invalidProperties[] = "'properties' can't be null";
        }
        if ($this->container['health'] === null) {
            $invalidProperties[] = "'health' can't be null";
        }
        if (($this->container['health'] > 100)) {
            $invalidProperties[] = "invalid value for 'health', must be smaller than or equal to 100.";
        }

        if (($this->container['health'] < 0)) {
            $invalidProperties[] = "invalid value for 'health', must be bigger than or equal to 0.";
        }

        if ($this->container['is_ais_supported'] === null) {
            $invalidProperties[] = "'is_ais_supported' can't be null";
        }
        if ($this->container['is_pis_supported'] === null) {
            $invalidProperties[] = "'is_pis_supported' can't be null";
        }
        if ($this->container['ais_capabilities'] === null) {
            $invalidProperties[] = "'ais_capabilities' can't be null";
        }
        if ($this->container['payment_capabilities'] === null) {
            $invalidProperties[] = "'payment_capabilities' can't be null";
        }
        if ($this->container['ais_account_types'] === null) {
            $invalidProperties[] = "'ais_account_types' can't be null";
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
     * Gets banking_interface
     *
     * @return \FinAPI\Client\Model\BankingInterface
     */
    public function getBankingInterface()
    {
        return $this->container['banking_interface'];
    }

    /**
     * Sets banking_interface
     *
     * @param \FinAPI\Client\Model\BankingInterface $banking_interface banking_interface
     *
     * @return self
     */
    public function setBankingInterface($banking_interface)
    {
        if (is_null($banking_interface)) {
            throw new \InvalidArgumentException('non-nullable banking_interface cannot be null');
        }
        $this->container['banking_interface'] = $banking_interface;

        return $this;
    }

    /**
     * Gets tpp_authentication_group
     *
     * @return \FinAPI\Client\Model\BankInterfaceTppAuthenticationGroup|null
     */
    public function getTppAuthenticationGroup()
    {
        return $this->container['tpp_authentication_group'];
    }

    /**
     * Sets tpp_authentication_group
     *
     * @param \FinAPI\Client\Model\BankInterfaceTppAuthenticationGroup|null $tpp_authentication_group tpp_authentication_group
     *
     * @return self
     */
    public function setTppAuthenticationGroup($tpp_authentication_group)
    {
        if (is_null($tpp_authentication_group)) {
            throw new \InvalidArgumentException('non-nullable tpp_authentication_group cannot be null');
        }
        $this->container['tpp_authentication_group'] = $tpp_authentication_group;

        return $this;
    }

    /**
     * Gets login_credentials
     *
     * @return \FinAPI\Client\Model\BankInterfaceLoginField[]
     */
    public function getLoginCredentials()
    {
        return $this->container['login_credentials'];
    }

    /**
     * Sets login_credentials
     *
     * @param \FinAPI\Client\Model\BankInterfaceLoginField[] $login_credentials Login fields for this interface (in the order that we suggest to show them to the user)<br/> <strong>Type:</strong> BankInterfaceLoginField
     *
     * @return self
     */
    public function setLoginCredentials($login_credentials)
    {
        if (is_null($login_credentials)) {
            throw new \InvalidArgumentException('non-nullable login_credentials cannot be null');
        }
        $this->container['login_credentials'] = $login_credentials;

        return $this;
    }

    /**
     * Gets properties
     *
     * @return BankInterfaceProperty[]
     */
    public function getProperties()
    {
        return $this->container['properties'];
    }

    /**
     * Sets properties
     *
     * @param BankInterfaceProperty[] $properties Set of interface properties/specifics. Possible values:<br><br>&bull; <code>REDIRECT_APPROACH</code> - means that the interface uses a redirect approach when authorizing the user. It requires you to pass the 'redirectUrl' field in all services which define the field. If the user already has imported a bank connection of the same bank that he is about to import, we recommend to confront the user with the question: <blockquote>For the selected bank you have already imported successfully the following accounts: &lt;account list&gt;. Are you sure that you want to import another bank connection from &lt;bank name&gt;? </blockquote>&bull; <code>DECOUPLED_APPROACH</code> - means that the interface can trigger a decoupled approval during user authorization.<br/><br/>&bull; <code>DETAILED_CONSENT</code> - means that the interface requires a list of account references when authorizing the user. It requires you to pass the 'accountReferences' field in all services which define the field.<br/><br/>Note that this set will be empty if the interface does not have any specific properties.<br/> <strong>Type:</strong> BankInterfaceProperty
     *
     * @return self
     */
    public function setProperties($properties)
    {
        if (is_null($properties)) {
            throw new \InvalidArgumentException('non-nullable properties cannot be null');
        }


        $this->container['properties'] = $properties;

        return $this;
    }

    /**
     * Gets login_hint
     *
     * @return string|null
     */
    public function getLoginHint()
    {
        return $this->container['login_hint'];
    }

    /**
     * Sets login_hint
     *
     * @param string|null $login_hint Login hint. Contains a German message for the user that explains what kind of credentials are expected.<br/><br/>Please note that it is essential to always show the login hint to the user if there is one, as the credentials that finAPI requires for the bank might be different to the credentials that the user knows from his online banking.<br/><br/>Also note that the contents of this field should always be interpreted as HTML, as the text might contain HTML tags for highlighted words, paragraphs, etc.
     *
     * @return self
     */
    public function setLoginHint($login_hint)
    {
        if (is_null($login_hint)) {
            throw new \InvalidArgumentException('non-nullable login_hint cannot be null');
        }
        $this->container['login_hint'] = $login_hint;

        return $this;
    }

    /**
     * Gets health
     *
     * @return int
     */
    public function getHealth()
    {
        return $this->container['health'];
    }

    /**
     * Sets health
     *
     * @param int $health The health status of this interface. This is a value between 0 and 100, depicting the percentage of successful communication attempts with the bank via this interface during the last couple of bank connection imports or updates (across the entire finAPI system). <br/><br/>Note:<br/>&bull; 'Successful' communication attempt means that there was no technical error trying to establish a communication with the bank. Non-technical errors (like incorrect credentials) are regarded successful communication attempts.<br/>&bull; If an interface is not supported (see fields 'isAisSupported'/'isPisSupported'), the health will always be 0.
     *
     * @return self
     */
    public function setHealth($health)
    {
        if (is_null($health)) {
            throw new \InvalidArgumentException('non-nullable health cannot be null');
        }

        if (($health > 100)) {
            throw new \InvalidArgumentException('invalid value for $health when calling BankInterface., must be smaller than or equal to 100.');
        }
        if (($health < 0)) {
            throw new \InvalidArgumentException('invalid value for $health when calling BankInterface., must be bigger than or equal to 0.');
        }

        $this->container['health'] = $health;

        return $this;
    }

    /**
     * Gets last_communication_attempt
     *
     * @return \DateTime|null
     */
    public function getLastCommunicationAttempt()
    {
        return $this->container['last_communication_attempt'];
    }

    /**
     * Sets last_communication_attempt
     *
     * @param \DateTime|null $last_communication_attempt <strong>Format:</strong> 'YYYY-MM-DD'T'HH:MM:SS.SSSXXX' (RFC 3339, section 5.6)<br/>Time of the last communication attempt with this interface during an import, update or connect interface (across the entire finAPI system).
     *
     * @return self
     */
    public function setLastCommunicationAttempt($last_communication_attempt)
    {
        if (is_null($last_communication_attempt)) {
            throw new \InvalidArgumentException('non-nullable last_communication_attempt cannot be null');
        }
        $this->container['last_communication_attempt'] = $last_communication_attempt;

        return $this;
    }

    /**
     * Gets last_successful_communication
     *
     * @return \DateTime|null
     */
    public function getLastSuccessfulCommunication()
    {
        return $this->container['last_successful_communication'];
    }

    /**
     * Sets last_successful_communication
     *
     * @param \DateTime|null $last_successful_communication <strong>Format:</strong> 'YYYY-MM-DD'T'HH:MM:SS.SSSXXX' (RFC 3339, section 5.6)<br/>Time of the last successful communication with this interface during an import, update or connect interface (across the entire finAPI system).
     *
     * @return self
     */
    public function setLastSuccessfulCommunication($last_successful_communication)
    {
        if (is_null($last_successful_communication)) {
            throw new \InvalidArgumentException('non-nullable last_successful_communication cannot be null');
        }
        $this->container['last_successful_communication'] = $last_successful_communication;

        return $this;
    }

    /**
     * Gets is_ais_supported
     *
     * @return bool
     */
    public function getIsAisSupported()
    {
        return $this->container['is_ais_supported'];
    }

    /**
     * Sets is_ais_supported
     *
     * @param bool $is_ais_supported Whether this interface has the general capability to perform Account Information Services (AIS), i.e. if this interface can be used to download accounts, balances and transactions.
     *
     * @return self
     */
    public function setIsAisSupported($is_ais_supported)
    {
        if (is_null($is_ais_supported)) {
            throw new \InvalidArgumentException('non-nullable is_ais_supported cannot be null');
        }
        $this->container['is_ais_supported'] = $is_ais_supported;

        return $this;
    }

    /**
     * Gets is_pis_supported
     *
     * @return bool
     */
    public function getIsPisSupported()
    {
        return $this->container['is_pis_supported'];
    }

    /**
     * Sets is_pis_supported
     *
     * @param bool $is_pis_supported Whether this interface has the general capability to perform Payment Initiation Services (PIS). For more details, see the field 'paymentCapabilities'.
     *
     * @return self
     */
    public function setIsPisSupported($is_pis_supported)
    {
        if (is_null($is_pis_supported)) {
            throw new \InvalidArgumentException('non-nullable is_pis_supported cannot be null');
        }
        $this->container['is_pis_supported'] = $is_pis_supported;

        return $this;
    }

    /**
     * Gets ais_capabilities
     *
     * @return \FinAPI\Client\Model\BankInterfaceAisCapabilities
     */
    public function getAisCapabilities()
    {
        return $this->container['ais_capabilities'];
    }

    /**
     * Sets ais_capabilities
     *
     * @param \FinAPI\Client\Model\BankInterfaceAisCapabilities $ais_capabilities ais_capabilities
     *
     * @return self
     */
    public function setAisCapabilities($ais_capabilities)
    {
        if (is_null($ais_capabilities)) {
            throw new \InvalidArgumentException('non-nullable ais_capabilities cannot be null');
        }
        $this->container['ais_capabilities'] = $ais_capabilities;

        return $this;
    }

    /**
     * Gets payment_capabilities
     *
     * @return \FinAPI\Client\Model\BankInterfacePaymentCapabilities
     */
    public function getPaymentCapabilities()
    {
        return $this->container['payment_capabilities'];
    }

    /**
     * Sets payment_capabilities
     *
     * @param \FinAPI\Client\Model\BankInterfacePaymentCapabilities $payment_capabilities payment_capabilities
     *
     * @return self
     */
    public function setPaymentCapabilities($payment_capabilities)
    {
        if (is_null($payment_capabilities)) {
            throw new \InvalidArgumentException('non-nullable payment_capabilities cannot be null');
        }
        $this->container['payment_capabilities'] = $payment_capabilities;

        return $this;
    }

    /**
     * Gets payment_constraints
     *
     * @return \FinAPI\Client\Model\BankInterfacePaymentConstraints|null
     */
    public function getPaymentConstraints()
    {
        return $this->container['payment_constraints'];
    }

    /**
     * Sets payment_constraints
     *
     * @param \FinAPI\Client\Model\BankInterfacePaymentConstraints|null $payment_constraints payment_constraints
     *
     * @return self
     */
    public function setPaymentConstraints($payment_constraints)
    {
        if (is_null($payment_constraints)) {
            throw new \InvalidArgumentException('non-nullable payment_constraints cannot be null');
        }
        $this->container['payment_constraints'] = $payment_constraints;

        return $this;
    }

    /**
     * Gets ais_account_types
     *
     * @return AccountType[]
     */
    public function getAisAccountTypes()
    {
        return $this->container['ais_account_types'];
    }

    /**
     * Sets ais_account_types
     *
     * @param AccountType[] $ais_account_types The set of account types that we can confirm can be successfully imported through this interface. This field can help you select the appropriate interface if you want to fetch only specific account types when importing or updating a bank connection (see the field <code>accountTypes</code> in the respective services).<br/><br/>Note:<br/>&bull; The set can change over time. When we learn that a certain account type can be received, it will be added to the set. But an account type can also disappear from the set, when it was no longer received for a while.<br/>&bull; If an account type is not contained in the set, it could still be that the interface will provide such accounts - we just haven't seen this for a while. Accordingly, this field is only meant to hint you at the most promising interface. If none of a bank's interfaces have your desired account type listed, we still advise you to make an attempt.<br/>&bull; The set is not updated in real-time, but rather periodically. When you make a successful import of an account type that is not yet listed, there will be a delay until it appears.<br/> <strong>Type:</strong> AccountType
     *
     * @return self
     */
    public function setAisAccountTypes($ais_account_types)
    {
        if (is_null($ais_account_types)) {
            throw new \InvalidArgumentException('non-nullable ais_account_types cannot be null');
        }
        $this->container['ais_account_types'] = $ais_account_types;

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


