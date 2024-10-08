<?php
/**
 * ClientConfigurationParams
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
 * ClientConfigurationParams Class Doc Comment
 *
 * @category Class
 * @description Client configuration parameters
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class ClientConfigurationParams implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ClientConfigurationParams';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'user_notification_callback_url' => 'string',
        'user_synchronization_callback_url' => 'string',
        'refresh_tokens_validity_period' => 'int',
        'user_access_tokens_validity_period' => 'int',
        'client_access_tokens_validity_period' => 'int',
        'fin_ts_product_registration_number' => 'string',
        'beta_banks_enabled' => 'bool',
        'preferred_consent_type' => '\FinAPI\Client\Model\PreferredConsentType',
        'user_auto_verification_enabled' => 'bool',
        'business_banks_enabled' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'user_notification_callback_url' => null,
        'user_synchronization_callback_url' => null,
        'refresh_tokens_validity_period' => 'int32',
        'user_access_tokens_validity_period' => 'int32',
        'client_access_tokens_validity_period' => 'int32',
        'fin_ts_product_registration_number' => null,
        'beta_banks_enabled' => null,
        'preferred_consent_type' => null,
        'user_auto_verification_enabled' => null,
        'business_banks_enabled' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'user_notification_callback_url' => false,
		'user_synchronization_callback_url' => false,
		'refresh_tokens_validity_period' => false,
		'user_access_tokens_validity_period' => false,
		'client_access_tokens_validity_period' => false,
		'fin_ts_product_registration_number' => false,
		'beta_banks_enabled' => false,
		'preferred_consent_type' => false,
		'user_auto_verification_enabled' => false,
		'business_banks_enabled' => false
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
        'user_notification_callback_url' => 'userNotificationCallbackUrl',
        'user_synchronization_callback_url' => 'userSynchronizationCallbackUrl',
        'refresh_tokens_validity_period' => 'refreshTokensValidityPeriod',
        'user_access_tokens_validity_period' => 'userAccessTokensValidityPeriod',
        'client_access_tokens_validity_period' => 'clientAccessTokensValidityPeriod',
        'fin_ts_product_registration_number' => 'finTSProductRegistrationNumber',
        'beta_banks_enabled' => 'betaBanksEnabled',
        'preferred_consent_type' => 'preferredConsentType',
        'user_auto_verification_enabled' => 'userAutoVerificationEnabled',
        'business_banks_enabled' => 'businessBanksEnabled'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'user_notification_callback_url' => 'setUserNotificationCallbackUrl',
        'user_synchronization_callback_url' => 'setUserSynchronizationCallbackUrl',
        'refresh_tokens_validity_period' => 'setRefreshTokensValidityPeriod',
        'user_access_tokens_validity_period' => 'setUserAccessTokensValidityPeriod',
        'client_access_tokens_validity_period' => 'setClientAccessTokensValidityPeriod',
        'fin_ts_product_registration_number' => 'setFinTsProductRegistrationNumber',
        'beta_banks_enabled' => 'setBetaBanksEnabled',
        'preferred_consent_type' => 'setPreferredConsentType',
        'user_auto_verification_enabled' => 'setUserAutoVerificationEnabled',
        'business_banks_enabled' => 'setBusinessBanksEnabled'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'user_notification_callback_url' => 'getUserNotificationCallbackUrl',
        'user_synchronization_callback_url' => 'getUserSynchronizationCallbackUrl',
        'refresh_tokens_validity_period' => 'getRefreshTokensValidityPeriod',
        'user_access_tokens_validity_period' => 'getUserAccessTokensValidityPeriod',
        'client_access_tokens_validity_period' => 'getClientAccessTokensValidityPeriod',
        'fin_ts_product_registration_number' => 'getFinTsProductRegistrationNumber',
        'beta_banks_enabled' => 'getBetaBanksEnabled',
        'preferred_consent_type' => 'getPreferredConsentType',
        'user_auto_verification_enabled' => 'getUserAutoVerificationEnabled',
        'business_banks_enabled' => 'getBusinessBanksEnabled'
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
        $this->setIfExists('user_notification_callback_url', $data ?? [], null);
        $this->setIfExists('user_synchronization_callback_url', $data ?? [], null);
        $this->setIfExists('refresh_tokens_validity_period', $data ?? [], null);
        $this->setIfExists('user_access_tokens_validity_period', $data ?? [], null);
        $this->setIfExists('client_access_tokens_validity_period', $data ?? [], null);
        $this->setIfExists('fin_ts_product_registration_number', $data ?? [], null);
        $this->setIfExists('beta_banks_enabled', $data ?? [], null);
        $this->setIfExists('preferred_consent_type', $data ?? [], null);
        $this->setIfExists('user_auto_verification_enabled', $data ?? [], null);
        $this->setIfExists('business_banks_enabled', $data ?? [], null);
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

        if (!is_null($this->container['fin_ts_product_registration_number']) && (mb_strlen($this->container['fin_ts_product_registration_number']) > 25)) {
            $invalidProperties[] = "invalid value for 'fin_ts_product_registration_number', the character length must be smaller than or equal to 25.";
        }

        if (!is_null($this->container['fin_ts_product_registration_number']) && (mb_strlen($this->container['fin_ts_product_registration_number']) < 0)) {
            $invalidProperties[] = "invalid value for 'fin_ts_product_registration_number', the character length must be bigger than or equal to 0.";
        }

        if (!is_null($this->container['fin_ts_product_registration_number']) && !preg_match("/[0-9A-F]*/", $this->container['fin_ts_product_registration_number'])) {
            $invalidProperties[] = "invalid value for 'fin_ts_product_registration_number', must be conform to the pattern /[0-9A-F]*/.";
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
     * Gets user_notification_callback_url
     *
     * @return string|null
     */
    public function getUserNotificationCallbackUrl()
    {
        return $this->container['user_notification_callback_url'];
    }

    /**
     * Sets user_notification_callback_url
     *
     * @param string|null $user_notification_callback_url Callback URL to which finAPI sends the notification messages that are triggered from the automatic batch update of the users' bank connections. This field is only relevant if the automatic batch update is enabled for your client. For details about what the notification messages look like, please see the documentation in the 'Notification Rules' section. finAPI will call this URL with HTTP method POST. Note that the response of the call is not processed by finAPI. Also note that while the callback URL may be a non-secured (http) URL on the finAPI sandbox or alpha environment, it MUST be a SSL-secured (https) URL on the finAPI live system.<p>The maximum allowed length of the URL is 512. If you have previously set a callback URL and now want to clear it (thus disabling user-related notifications altogether), you can pass an empty string (\"\").
     *
     * @return self
     */
    public function setUserNotificationCallbackUrl($user_notification_callback_url)
    {
        if (is_null($user_notification_callback_url)) {
            throw new \InvalidArgumentException('non-nullable user_notification_callback_url cannot be null');
        }
        $this->container['user_notification_callback_url'] = $user_notification_callback_url;

        return $this;
    }

    /**
     * Gets user_synchronization_callback_url
     *
     * @return string|null
     */
    public function getUserSynchronizationCallbackUrl()
    {
        return $this->container['user_synchronization_callback_url'];
    }

    /**
     * Sets user_synchronization_callback_url
     *
     * @param string|null $user_synchronization_callback_url Callback URL for user synchronization. This field should be set if you - as a finAPI customer - have multiple clients using finAPI. In such case, all of your clients will share the same user base, making it possible for a user to be created in one client, but then deleted in another. To keep the client-side user data consistent in all clients, you should set a callback URL for each client. finAPI will send a notification to the callback URL of each client whenever a user of your user base gets deleted. Note that finAPI will send a deletion notification to ALL clients, including the one that made the user deletion request to finAPI. So when deleting a user in finAPI, a client should rely on the callback to delete the user on its own side. <p>The notification that finAPI sends to the clients' callback URLs will be a POST request, with this body: <pre>{    \"userId\" : string // contains the identifier of the deleted user    \"event\" : string // this will always be \"DELETED\" }</pre><br/>Note that finAPI does not process the response of this call. Also note that while the callback URL may be a non-secured (http) URL on the finAPI sandbox or alpha system, it MUST be a SSL-secured (https) URL on the live system.</p>As long as you have just one client, you can ignore this field and let it be null. However keep in mind that in this case your client will not receive any callback when a user gets deleted - so the deletion of the user on the client-side must not be forgotten. Of course you may still use the callback URL even for just one client, if you want to implement the deletion of the user on the client-side via the callback from finAPI.<p> The maximum allowed length of the URL is 512. If you have previously set a callback URL and now want to clear it (thus disabling user synchronization related notifications for this client), you can pass an empty string (\"\").
     *
     * @return self
     */
    public function setUserSynchronizationCallbackUrl($user_synchronization_callback_url)
    {
        if (is_null($user_synchronization_callback_url)) {
            throw new \InvalidArgumentException('non-nullable user_synchronization_callback_url cannot be null');
        }
        $this->container['user_synchronization_callback_url'] = $user_synchronization_callback_url;

        return $this;
    }

    /**
     * Gets refresh_tokens_validity_period
     *
     * @return int|null
     */
    public function getRefreshTokensValidityPeriod()
    {
        return $this->container['refresh_tokens_validity_period'];
    }

    /**
     * Sets refresh_tokens_validity_period
     *
     * @param int|null $refresh_tokens_validity_period The validity period that newly requested refresh tokens initially have (in seconds). The value must be greater than or equal to 60, or 0. A value of 0 means that the tokens never expire (Unless explicitly invalidated, e.g. by revocation , or when a user gets locked, or when the password is reset for a user).
     *
     * @return self
     */
    public function setRefreshTokensValidityPeriod($refresh_tokens_validity_period)
    {
        if (is_null($refresh_tokens_validity_period)) {
            throw new \InvalidArgumentException('non-nullable refresh_tokens_validity_period cannot be null');
        }
        $this->container['refresh_tokens_validity_period'] = $refresh_tokens_validity_period;

        return $this;
    }

    /**
     * Gets user_access_tokens_validity_period
     *
     * @return int|null
     */
    public function getUserAccessTokensValidityPeriod()
    {
        return $this->container['user_access_tokens_validity_period'];
    }

    /**
     * Sets user_access_tokens_validity_period
     *
     * @param int|null $user_access_tokens_validity_period The validity period that newly requested access tokens for users initially have (in seconds). The value must be greater than or equal to 60, or 0. A value of 0 means that the tokens never expire.
     *
     * @return self
     */
    public function setUserAccessTokensValidityPeriod($user_access_tokens_validity_period)
    {
        if (is_null($user_access_tokens_validity_period)) {
            throw new \InvalidArgumentException('non-nullable user_access_tokens_validity_period cannot be null');
        }
        $this->container['user_access_tokens_validity_period'] = $user_access_tokens_validity_period;

        return $this;
    }

    /**
     * Gets client_access_tokens_validity_period
     *
     * @return int|null
     */
    public function getClientAccessTokensValidityPeriod()
    {
        return $this->container['client_access_tokens_validity_period'];
    }

    /**
     * Sets client_access_tokens_validity_period
     *
     * @param int|null $client_access_tokens_validity_period The validity period that newly requested access tokens for clients initially have (in seconds). The value must be greater than or equal to 60, or 0. A value of 0 means that the tokens never expire.
     *
     * @return self
     */
    public function setClientAccessTokensValidityPeriod($client_access_tokens_validity_period)
    {
        if (is_null($client_access_tokens_validity_period)) {
            throw new \InvalidArgumentException('non-nullable client_access_tokens_validity_period cannot be null');
        }
        $this->container['client_access_tokens_validity_period'] = $client_access_tokens_validity_period;

        return $this;
    }

    /**
     * Gets fin_ts_product_registration_number
     *
     * @return string|null
     */
    public function getFinTsProductRegistrationNumber()
    {
        return $this->container['fin_ts_product_registration_number'];
    }

    /**
     * Sets fin_ts_product_registration_number
     *
     * @param string|null $fin_ts_product_registration_number The FinTS product registration number. Please follow <a href='https://www.hbci-zka.de/register/prod_register.htm' target='_blank'>this link</a> to apply for a registration number. Only customers who have an AISP or PISP license must define their FinTS product registration number. Customers who are relying on the finAPI Web Form will be assigned to finAPI's FinTS product registration number automatically and do not have to register themselves. If you have previously set a FinTS product registration number and now want to clear it, you can pass an empty string (\"\"). Only hexadecimal characters in capital case are allowed.<br/><br/>NOTE: If you have multiple <a href='https://documentation.finapi.io/access/application-management' target='_blank'>app clients</a> with different FinTS product registration numbers, you must make sure that the client which you use for FinTS bank communications for a certain user is always the client which was used to create that user (otherwise the bank might reject a request due to an unexpected registration number).
     *
     * @return self
     */
    public function setFinTsProductRegistrationNumber($fin_ts_product_registration_number)
    {
        if (is_null($fin_ts_product_registration_number)) {
            throw new \InvalidArgumentException('non-nullable fin_ts_product_registration_number cannot be null');
        }
        if ((mb_strlen($fin_ts_product_registration_number) > 25)) {
            throw new \InvalidArgumentException('invalid length for $fin_ts_product_registration_number when calling ClientConfigurationParams., must be smaller than or equal to 25.');
        }
        if ((mb_strlen($fin_ts_product_registration_number) < 0)) {
            throw new \InvalidArgumentException('invalid length for $fin_ts_product_registration_number when calling ClientConfigurationParams., must be bigger than or equal to 0.');
        }
        if ((!preg_match("/[0-9A-F]*/", $fin_ts_product_registration_number))) {
            throw new \InvalidArgumentException("invalid value for \$fin_ts_product_registration_number when calling ClientConfigurationParams., must conform to the pattern /[0-9A-F]*/.");
        }

        $this->container['fin_ts_product_registration_number'] = $fin_ts_product_registration_number;

        return $this;
    }

    /**
     * Gets beta_banks_enabled
     *
     * @return bool|null
     */
    public function getBetaBanksEnabled()
    {
        return $this->container['beta_banks_enabled'];
    }

    /**
     * Sets beta_banks_enabled
     *
     * @param bool|null $beta_banks_enabled Whether the set of banks that are available to your client should include \"Beta banks\". Beta banks provide pre-release interfaces that are still in a beta phase. Communication to the bank via such interfaces might be unstable, and the correctness and/or quality of data delivery or payment execution cannot be guaranteed.<br/>As the word \"BETA\" already indicates, Beta banks are subject to changes. Their properties, as well as their behaviour can change based on continuous tests and customer feedback. Also, to keep our bank list clean, we might remove Beta banks at any point in time, including all related user data (bank connections, accounts, transactions etc). We still recommend you to enable beta banks in your application, because it enables us to release a stable interface faster. However, you should point it out to your users when using a beta bank (also see field Bank.isBeta).<br/><br/>If this field is true, then the GET /banks services will include beta banks in their results, and you can use beta banks in any service where you can pass a bank identifier. If the field is false, then beta banks will not exist for your client.<br/><br/>NOTE: This setting affects ALL your clients.
     *
     * @return self
     */
    public function setBetaBanksEnabled($beta_banks_enabled)
    {
        if (is_null($beta_banks_enabled)) {
            throw new \InvalidArgumentException('non-nullable beta_banks_enabled cannot be null');
        }
        $this->container['beta_banks_enabled'] = $beta_banks_enabled;

        return $this;
    }

    /**
     * Gets preferred_consent_type
     *
     * @return \FinAPI\Client\Model\PreferredConsentType|null
     */
    public function getPreferredConsentType()
    {
        return $this->container['preferred_consent_type'];
    }

    /**
     * Sets preferred_consent_type
     *
     * @param \FinAPI\Client\Model\PreferredConsentType|null $preferred_consent_type preferred_consent_type
     *
     * @return self
     */
    public function setPreferredConsentType($preferred_consent_type)
    {
        if (is_null($preferred_consent_type)) {
            throw new \InvalidArgumentException('non-nullable preferred_consent_type cannot be null');
        }
        $this->container['preferred_consent_type'] = $preferred_consent_type;

        return $this;
    }

    /**
     * Gets user_auto_verification_enabled
     *
     * @return bool|null
     */
    public function getUserAutoVerificationEnabled()
    {
        return $this->container['user_auto_verification_enabled'];
    }

    /**
     * Sets user_auto_verification_enabled
     *
     * @param bool|null $user_auto_verification_enabled Whether users should automatically get verified upon creation. If this field is set to 'false', then any user that is created must first be verified with the \"Verify a user\" service before he can be authorized. If the field is 'true', then no verification is required by the client and the user can be authorized immediately after creation. A change to this setting will affect only users that get created from that point onward, but not users that have already existed previously.<br/><br/>NOTE: This setting affects ALL your clients.
     *
     * @return self
     */
    public function setUserAutoVerificationEnabled($user_auto_verification_enabled)
    {
        if (is_null($user_auto_verification_enabled)) {
            throw new \InvalidArgumentException('non-nullable user_auto_verification_enabled cannot be null');
        }
        $this->container['user_auto_verification_enabled'] = $user_auto_verification_enabled;

        return $this;
    }

    /**
     * Gets business_banks_enabled
     *
     * @return bool|null
     */
    public function getBusinessBanksEnabled()
    {
        return $this->container['business_banks_enabled'];
    }

    /**
     * Sets business_banks_enabled
     *
     * @param bool|null $business_banks_enabled Determines whether banks that exclusively offer business accounts are enabled for your application.<br/> <b>Recommendation:</b> - Set this flag to false if your application primarily focuses on B2C (Business-to-Consumer) use cases. This will ensure that banks irrelevant to your users, such as those providing only business accounts, are hidden, offering a more streamlined and relevant experience.<br/> - Set this flag to true if your application targets B2B (Business-to-Business) scenarios, where business accounts are necessary.<br/><br/>NOTE: This setting affects ALL your clients.
     *
     * @return self
     */
    public function setBusinessBanksEnabled($business_banks_enabled)
    {
        if (is_null($business_banks_enabled)) {
            throw new \InvalidArgumentException('non-nullable business_banks_enabled cannot be null');
        }
        $this->container['business_banks_enabled'] = $business_banks_enabled;

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


