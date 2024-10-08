<?php
/**
 * MultiStepAuthenticationCallback
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
 * MultiStepAuthenticationCallback Class Doc Comment
 *
 * @category Class
 * @description Container for multi-step authentication data, as passed by the client to finAPI
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class MultiStepAuthenticationCallback implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'MultiStepAuthenticationCallback';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'hash' => 'string',
        'challenge_response' => 'string',
        'two_step_procedure_id' => 'string',
        'redirect_callback' => 'string',
        'decoupled_callback' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'hash' => null,
        'challenge_response' => null,
        'two_step_procedure_id' => null,
        'redirect_callback' => null,
        'decoupled_callback' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'hash' => false,
		'challenge_response' => false,
		'two_step_procedure_id' => false,
		'redirect_callback' => false,
		'decoupled_callback' => false
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
        'hash' => 'hash',
        'challenge_response' => 'challengeResponse',
        'two_step_procedure_id' => 'twoStepProcedureId',
        'redirect_callback' => 'redirectCallback',
        'decoupled_callback' => 'decoupledCallback'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'hash' => 'setHash',
        'challenge_response' => 'setChallengeResponse',
        'two_step_procedure_id' => 'setTwoStepProcedureId',
        'redirect_callback' => 'setRedirectCallback',
        'decoupled_callback' => 'setDecoupledCallback'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'hash' => 'getHash',
        'challenge_response' => 'getChallengeResponse',
        'two_step_procedure_id' => 'getTwoStepProcedureId',
        'redirect_callback' => 'getRedirectCallback',
        'decoupled_callback' => 'getDecoupledCallback'
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
        $this->setIfExists('hash', $data ?? [], null);
        $this->setIfExists('challenge_response', $data ?? [], null);
        $this->setIfExists('two_step_procedure_id', $data ?? [], null);
        $this->setIfExists('redirect_callback', $data ?? [], null);
        $this->setIfExists('decoupled_callback', $data ?? [], null);
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

        if ($this->container['hash'] === null) {
            $invalidProperties[] = "'hash' can't be null";
        }
        if ((mb_strlen($this->container['hash']) > 32)) {
            $invalidProperties[] = "invalid value for 'hash', the character length must be smaller than or equal to 32.";
        }

        if ((mb_strlen($this->container['hash']) < 1)) {
            $invalidProperties[] = "invalid value for 'hash', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['challenge_response']) && (mb_strlen($this->container['challenge_response']) < 1)) {
            $invalidProperties[] = "invalid value for 'challenge_response', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['two_step_procedure_id']) && (mb_strlen($this->container['two_step_procedure_id']) > 512)) {
            $invalidProperties[] = "invalid value for 'two_step_procedure_id', the character length must be smaller than or equal to 512.";
        }

        if (!is_null($this->container['two_step_procedure_id']) && (mb_strlen($this->container['two_step_procedure_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'two_step_procedure_id', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['redirect_callback']) && (mb_strlen($this->container['redirect_callback']) > 2048)) {
            $invalidProperties[] = "invalid value for 'redirect_callback', the character length must be smaller than or equal to 2048.";
        }

        if (!is_null($this->container['redirect_callback']) && (mb_strlen($this->container['redirect_callback']) < 1)) {
            $invalidProperties[] = "invalid value for 'redirect_callback', the character length must be bigger than or equal to 1.";
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
     * Gets hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->container['hash'];
    }

    /**
     * Sets hash
     *
     * @param string $hash Hash that was returned in the previous multi-step authentication error.
     *
     * @return self
     */
    public function setHash($hash)
    {
        if (is_null($hash)) {
            throw new \InvalidArgumentException('non-nullable hash cannot be null');
        }
        if ((mb_strlen($hash) > 32)) {
            throw new \InvalidArgumentException('invalid length for $hash when calling MultiStepAuthenticationCallback., must be smaller than or equal to 32.');
        }
        if ((mb_strlen($hash) < 1)) {
            throw new \InvalidArgumentException('invalid length for $hash when calling MultiStepAuthenticationCallback., must be bigger than or equal to 1.');
        }

        $this->container['hash'] = $hash;

        return $this;
    }

    /**
     * Gets challenge_response
     *
     * @return string|null
     */
    public function getChallengeResponse()
    {
        return $this->container['challenge_response'];
    }

    /**
     * Sets challenge_response
     *
     * @param string|null $challenge_response Challenge response. Must be set when the previous multi-step authentication error had status 'CHALLENGE_RESPONSE_REQUIRED'.
     *
     * @return self
     */
    public function setChallengeResponse($challenge_response)
    {
        if (is_null($challenge_response)) {
            throw new \InvalidArgumentException('non-nullable challenge_response cannot be null');
        }

        if ((mb_strlen($challenge_response) < 1)) {
            throw new \InvalidArgumentException('invalid length for $challenge_response when calling MultiStepAuthenticationCallback., must be bigger than or equal to 1.');
        }

        $this->container['challenge_response'] = $challenge_response;

        return $this;
    }

    /**
     * Gets two_step_procedure_id
     *
     * @return string|null
     */
    public function getTwoStepProcedureId()
    {
        return $this->container['two_step_procedure_id'];
    }

    /**
     * Sets two_step_procedure_id
     *
     * @param string|null $two_step_procedure_id The bank-given ID of the two-step-procedure that should be used for authentication. Must be set when the previous multi-step authentication error had status 'TWO_STEP_PROCEDURE_REQUIRED'.
     *
     * @return self
     */
    public function setTwoStepProcedureId($two_step_procedure_id)
    {
        if (is_null($two_step_procedure_id)) {
            throw new \InvalidArgumentException('non-nullable two_step_procedure_id cannot be null');
        }
        if ((mb_strlen($two_step_procedure_id) > 512)) {
            throw new \InvalidArgumentException('invalid length for $two_step_procedure_id when calling MultiStepAuthenticationCallback., must be smaller than or equal to 512.');
        }
        if ((mb_strlen($two_step_procedure_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $two_step_procedure_id when calling MultiStepAuthenticationCallback., must be bigger than or equal to 1.');
        }

        $this->container['two_step_procedure_id'] = $two_step_procedure_id;

        return $this;
    }

    /**
     * Gets redirect_callback
     *
     * @return string|null
     */
    public function getRedirectCallback()
    {
        return $this->container['redirect_callback'];
    }

    /**
     * Sets redirect_callback
     *
     * @param string|null $redirect_callback Must be passed when the previous multi-step authentication error had status 'REDIRECT_REQUIRED'. The value must consist of the complete query parameter list that was contained in the received redirect from the bank.
     *
     * @return self
     */
    public function setRedirectCallback($redirect_callback)
    {
        if (is_null($redirect_callback)) {
            throw new \InvalidArgumentException('non-nullable redirect_callback cannot be null');
        }
        if ((mb_strlen($redirect_callback) > 2048)) {
            throw new \InvalidArgumentException('invalid length for $redirect_callback when calling MultiStepAuthenticationCallback., must be smaller than or equal to 2048.');
        }
        if ((mb_strlen($redirect_callback) < 1)) {
            throw new \InvalidArgumentException('invalid length for $redirect_callback when calling MultiStepAuthenticationCallback., must be bigger than or equal to 1.');
        }

        $this->container['redirect_callback'] = $redirect_callback;

        return $this;
    }

    /**
     * Gets decoupled_callback
     *
     * @return bool|null
     */
    public function getDecoupledCallback()
    {
        return $this->container['decoupled_callback'];
    }

    /**
     * Sets decoupled_callback
     *
     * @param bool|null $decoupled_callback Must be passed when the previous multi-step authentication error had status 'DECOUPLED_AUTH_REQUIRED' or 'DECOUPLED_AUTH_IN_PROGRESS'. The field represents the state of the decoupled authentication meaning that when it's set to 'true', the end-user has completed the authentication process on bank's side.<br/><br/>Please note: Don't repeat the service call too frequently. Some banks limit the amount of requests per minute. Our suggestion is to repeat the service call for the decoupled approach every 5 seconds.
     *
     * @return self
     */
    public function setDecoupledCallback($decoupled_callback)
    {
        if (is_null($decoupled_callback)) {
            throw new \InvalidArgumentException('non-nullable decoupled_callback cannot be null');
        }
        $this->container['decoupled_callback'] = $decoupled_callback;

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


