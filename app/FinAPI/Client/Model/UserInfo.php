<?php
/**
 * UserInfo
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
 * UserInfo Class Doc Comment
 *
 * @category Class
 * @description Container for user information
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class UserInfo implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'UserInfo';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'user_id' => 'string',
        'registration_date' => '\DateTime',
        'deletion_date' => '\DateTime',
        'last_active_date' => '\DateTime',
        'bank_connection_count' => 'int',
        'latest_bank_connection_import_date' => '\DateTime',
        'latest_bank_connection_deletion_date' => '\DateTime',
        'monthly_stats' => '\FinAPI\Client\Model\MonthlyUserStats[]',
        'is_locked' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'user_id' => null,
        'registration_date' => 'date',
        'deletion_date' => 'date',
        'last_active_date' => 'date',
        'bank_connection_count' => 'int32',
        'latest_bank_connection_import_date' => 'date',
        'latest_bank_connection_deletion_date' => 'date',
        'monthly_stats' => null,
        'is_locked' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'user_id' => false,
		'registration_date' => false,
		'deletion_date' => false,
		'last_active_date' => false,
		'bank_connection_count' => false,
		'latest_bank_connection_import_date' => false,
		'latest_bank_connection_deletion_date' => false,
		'monthly_stats' => false,
		'is_locked' => false
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
        'user_id' => 'userId',
        'registration_date' => 'registrationDate',
        'deletion_date' => 'deletionDate',
        'last_active_date' => 'lastActiveDate',
        'bank_connection_count' => 'bankConnectionCount',
        'latest_bank_connection_import_date' => 'latestBankConnectionImportDate',
        'latest_bank_connection_deletion_date' => 'latestBankConnectionDeletionDate',
        'monthly_stats' => 'monthlyStats',
        'is_locked' => 'isLocked'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'user_id' => 'setUserId',
        'registration_date' => 'setRegistrationDate',
        'deletion_date' => 'setDeletionDate',
        'last_active_date' => 'setLastActiveDate',
        'bank_connection_count' => 'setBankConnectionCount',
        'latest_bank_connection_import_date' => 'setLatestBankConnectionImportDate',
        'latest_bank_connection_deletion_date' => 'setLatestBankConnectionDeletionDate',
        'monthly_stats' => 'setMonthlyStats',
        'is_locked' => 'setIsLocked'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'user_id' => 'getUserId',
        'registration_date' => 'getRegistrationDate',
        'deletion_date' => 'getDeletionDate',
        'last_active_date' => 'getLastActiveDate',
        'bank_connection_count' => 'getBankConnectionCount',
        'latest_bank_connection_import_date' => 'getLatestBankConnectionImportDate',
        'latest_bank_connection_deletion_date' => 'getLatestBankConnectionDeletionDate',
        'monthly_stats' => 'getMonthlyStats',
        'is_locked' => 'getIsLocked'
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
        $this->setIfExists('user_id', $data ?? [], null);
        $this->setIfExists('registration_date', $data ?? [], null);
        $this->setIfExists('deletion_date', $data ?? [], null);
        $this->setIfExists('last_active_date', $data ?? [], null);
        $this->setIfExists('bank_connection_count', $data ?? [], null);
        $this->setIfExists('latest_bank_connection_import_date', $data ?? [], null);
        $this->setIfExists('latest_bank_connection_deletion_date', $data ?? [], null);
        $this->setIfExists('monthly_stats', $data ?? [], null);
        $this->setIfExists('is_locked', $data ?? [], null);
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

        if ($this->container['user_id'] === null) {
            $invalidProperties[] = "'user_id' can't be null";
        }
        if ($this->container['registration_date'] === null) {
            $invalidProperties[] = "'registration_date' can't be null";
        }
        if ($this->container['bank_connection_count'] === null) {
            $invalidProperties[] = "'bank_connection_count' can't be null";
        }
        if ($this->container['is_locked'] === null) {
            $invalidProperties[] = "'is_locked' can't be null";
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
     * Gets user_id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->container['user_id'];
    }

    /**
     * Sets user_id
     *
     * @param string $user_id User's identifier.
     *
     * @return self
     */
    public function setUserId($user_id)
    {
        if (is_null($user_id)) {
            throw new \InvalidArgumentException('non-nullable user_id cannot be null');
        }
        $this->container['user_id'] = $user_id;

        return $this;
    }

    /**
     * Gets registration_date
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->container['registration_date'];
    }

    /**
     * Sets registration_date
     *
     * @param \DateTime $registration_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>User's registration date.
     *
     * @return self
     */
    public function setRegistrationDate($registration_date)
    {
        if (is_null($registration_date)) {
            throw new \InvalidArgumentException('non-nullable registration_date cannot be null');
        }
        $this->container['registration_date'] = $registration_date;

        return $this;
    }

    /**
     * Gets deletion_date
     *
     * @return \DateTime|null
     */
    public function getDeletionDate()
    {
        return $this->container['deletion_date'];
    }

    /**
     * Sets deletion_date
     *
     * @param \DateTime|null $deletion_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>User's deletion date. May be null if the user has not been deleted.
     *
     * @return self
     */
    public function setDeletionDate($deletion_date)
    {
        if (is_null($deletion_date)) {
            throw new \InvalidArgumentException('non-nullable deletion_date cannot be null');
        }
        $this->container['deletion_date'] = $deletion_date;

        return $this;
    }

    /**
     * Gets last_active_date
     *
     * @return \DateTime|null
     */
    public function getLastActiveDate()
    {
        return $this->container['last_active_date'];
    }

    /**
     * Sets last_active_date
     *
     * @param \DateTime|null $last_active_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>User's last active date. May be null if the user has not yet logged in.
     *
     * @return self
     */
    public function setLastActiveDate($last_active_date)
    {
        if (is_null($last_active_date)) {
            throw new \InvalidArgumentException('non-nullable last_active_date cannot be null');
        }
        $this->container['last_active_date'] = $last_active_date;

        return $this;
    }

    /**
     * Gets bank_connection_count
     *
     * @return int
     */
    public function getBankConnectionCount()
    {
        return $this->container['bank_connection_count'];
    }

    /**
     * Sets bank_connection_count
     *
     * @param int $bank_connection_count Number of bank connections that currently exist for this user.
     *
     * @return self
     */
    public function setBankConnectionCount($bank_connection_count)
    {
        if (is_null($bank_connection_count)) {
            throw new \InvalidArgumentException('non-nullable bank_connection_count cannot be null');
        }
        $this->container['bank_connection_count'] = $bank_connection_count;

        return $this;
    }

    /**
     * Gets latest_bank_connection_import_date
     *
     * @return \DateTime|null
     */
    public function getLatestBankConnectionImportDate()
    {
        return $this->container['latest_bank_connection_import_date'];
    }

    /**
     * Sets latest_bank_connection_import_date
     *
     * @param \DateTime|null $latest_bank_connection_import_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Latest date of when a bank connection was imported for this user. This field is null when there has never been a bank connection import.
     *
     * @return self
     */
    public function setLatestBankConnectionImportDate($latest_bank_connection_import_date)
    {
        if (is_null($latest_bank_connection_import_date)) {
            throw new \InvalidArgumentException('non-nullable latest_bank_connection_import_date cannot be null');
        }
        $this->container['latest_bank_connection_import_date'] = $latest_bank_connection_import_date;

        return $this;
    }

    /**
     * Gets latest_bank_connection_deletion_date
     *
     * @return \DateTime|null
     */
    public function getLatestBankConnectionDeletionDate()
    {
        return $this->container['latest_bank_connection_deletion_date'];
    }

    /**
     * Sets latest_bank_connection_deletion_date
     *
     * @param \DateTime|null $latest_bank_connection_deletion_date <strong>Format:</strong> 'YYYY-MM-DD'<br/>Latest date of when a bank connection was deleted for this user. This field is null when there has never been a bank connection deletion.
     *
     * @return self
     */
    public function setLatestBankConnectionDeletionDate($latest_bank_connection_deletion_date)
    {
        if (is_null($latest_bank_connection_deletion_date)) {
            throw new \InvalidArgumentException('non-nullable latest_bank_connection_deletion_date cannot be null');
        }
        $this->container['latest_bank_connection_deletion_date'] = $latest_bank_connection_deletion_date;

        return $this;
    }

    /**
     * Gets monthly_stats
     *
     * @return \FinAPI\Client\Model\MonthlyUserStats[]|null
     */
    public function getMonthlyStats()
    {
        return $this->container['monthly_stats'];
    }

    /**
     * Sets monthly_stats
     *
     * @param \FinAPI\Client\Model\MonthlyUserStats[]|null $monthly_stats Additional information about the user's data or activities, broken down in months. The list will by default contain an entry for each month starting with the month of when the user was registered, up to the current month. The date range may vary when you have limited it in the request. <br/><br/>Please note:<br/>&bull; this field is only set when 'includeMonthlyStats' = true, otherwise it will be null.<br/>&bull; the list is always ordered from the latest month first, to the oldest month last.<br/>&bull; the list will never contain an entry for a month that was prior to the month of when the user was registered, or after the month of when the user was deleted, even when you have explicitly set a respective date range. This means that the list may be empty if you are requesting a date range where the user didn't exist yet, or didn't exist any longer.<br/> <strong>Type:</strong> MonthlyUserStats
     *
     * @return self
     */
    public function setMonthlyStats($monthly_stats)
    {
        if (is_null($monthly_stats)) {
            throw new \InvalidArgumentException('non-nullable monthly_stats cannot be null');
        }
        $this->container['monthly_stats'] = $monthly_stats;

        return $this;
    }

    /**
     * Gets is_locked
     *
     * @return bool
     */
    public function getIsLocked()
    {
        return $this->container['is_locked'];
    }

    /**
     * Sets is_locked
     *
     * @param bool $is_locked Whether the user is currently locked (for further information, see the 'maxUserLoginAttempts' setting in your client's configuration). Note that deleted users will always have this field set to 'false'.
     *
     * @return self
     */
    public function setIsLocked($is_locked)
    {
        if (is_null($is_locked)) {
            throw new \InvalidArgumentException('non-nullable is_locked cannot be null');
        }
        $this->container['is_locked'] = $is_locked;

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


