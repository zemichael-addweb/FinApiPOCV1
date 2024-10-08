<?php
/**
 * ErrorCode
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
use \FinAPI\Client\ObjectSerializer;

/**
 * ErrorCode Class Doc Comment
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ErrorCode
{
    /**
     * Possible values of this enum
     */
    public const ADDITIONAL_AUTHENTICATION_REQUIRED = 'ADDITIONAL_AUTHENTICATION_REQUIRED';

    public const BAD_REQUEST = 'BAD_REQUEST';

    public const BANK_SERVER_REJECTION = 'BANK_SERVER_REJECTION';

    public const COLLECTIVE_MONEY_TRANSFER_NOT_SUPPORTED = 'COLLECTIVE_MONEY_TRANSFER_NOT_SUPPORTED';

    public const ENTITY_EXISTS = 'ENTITY_EXISTS';

    public const FORBIDDEN = 'FORBIDDEN';

    public const IBAN_ONLY_DIRECT_DEBIT_NOT_SUPPORTED = 'IBAN_ONLY_DIRECT_DEBIT_NOT_SUPPORTED';

    public const IBAN_ONLY_MONEY_TRANSFER_NOT_SUPPORTED = 'IBAN_ONLY_MONEY_TRANSFER_NOT_SUPPORTED';

    public const ILLEGAL_ENTITY_STATE = 'ILLEGAL_ENTITY_STATE';

    public const ILLEGAL_FIELD_VALUE = 'ILLEGAL_FIELD_VALUE';

    public const ILLEGAL_PAGE_SIZE = 'ILLEGAL_PAGE_SIZE';

    public const INVALID_CONSENT = 'INVALID_CONSENT';

    public const INVALID_FILTER_OPTIONS = 'INVALID_FILTER_OPTIONS';

    public const INVALID_ACCOUNT_TYPES = 'INVALID_ACCOUNT_TYPES';

    public const INVALID_TOKEN = 'INVALID_TOKEN';

    public const INVALID_TWO_STEP_PROCEDURE = 'INVALID_TWO_STEP_PROCEDURE';

    public const LOCKED = 'LOCKED';

    public const LOGIN_FIELDS_MISMATCH = 'LOGIN_FIELDS_MISMATCH';

    public const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';

    public const MISSING_CREDENTIALS = 'MISSING_CREDENTIALS';

    public const MISSING_FIELD = 'MISSING_FIELD';

    public const MISSING_TWO_STEP_PROCEDURE = 'MISSING_TWO_STEP_PROCEDURE';

    public const NO_ACCOUNTS_FOR_TYPE_LIST = 'NO_ACCOUNTS_FOR_TYPE_LIST';

    public const NO_CERTIFICATE = 'NO_CERTIFICATE';

    public const NO_EXISTING_CHALLENGE = 'NO_EXISTING_CHALLENGE';

    public const NO_TPP_CLIENT_CREDENTIALS = 'NO_TPP_CLIENT_CREDENTIALS';

    public const NO_PSU_METADATA = 'NO_PSU_METADATA';

    public const TOO_MANY_IDS = 'TOO_MANY_IDS';

    public const UNAUTHORIZED_ACCESS = 'UNAUTHORIZED_ACCESS';

    public const UNEXPECTED_ERROR = 'UNEXPECTED_ERROR';

    public const UNKNOWN_ENTITY = 'UNKNOWN_ENTITY';

    public const UNSUPPORTED_BANK = 'UNSUPPORTED_BANK';

    public const UNSUPPORTED_FEATURE = 'UNSUPPORTED_FEATURE';

    public const UNSUPPORTED_MEDIA_TYPE = 'UNSUPPORTED_MEDIA_TYPE';

    public const UNSUPPORTED_ORDER = 'UNSUPPORTED_ORDER';

    public const USER_ALREADY_VERIFIED = 'USER_ALREADY_VERIFIED';

    public const USER_NOT_VERIFIED = 'USER_NOT_VERIFIED';

    public const WEB_FORM_ABORTED = 'WEB_FORM_ABORTED';

    public const WEB_FORM_REQUIRED = 'WEB_FORM_REQUIRED';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::ADDITIONAL_AUTHENTICATION_REQUIRED,
            self::BAD_REQUEST,
            self::BANK_SERVER_REJECTION,
            self::COLLECTIVE_MONEY_TRANSFER_NOT_SUPPORTED,
            self::ENTITY_EXISTS,
            self::FORBIDDEN,
            self::IBAN_ONLY_DIRECT_DEBIT_NOT_SUPPORTED,
            self::IBAN_ONLY_MONEY_TRANSFER_NOT_SUPPORTED,
            self::ILLEGAL_ENTITY_STATE,
            self::ILLEGAL_FIELD_VALUE,
            self::ILLEGAL_PAGE_SIZE,
            self::INVALID_CONSENT,
            self::INVALID_FILTER_OPTIONS,
            self::INVALID_ACCOUNT_TYPES,
            self::INVALID_TOKEN,
            self::INVALID_TWO_STEP_PROCEDURE,
            self::LOCKED,
            self::LOGIN_FIELDS_MISMATCH,
            self::METHOD_NOT_ALLOWED,
            self::MISSING_CREDENTIALS,
            self::MISSING_FIELD,
            self::MISSING_TWO_STEP_PROCEDURE,
            self::NO_ACCOUNTS_FOR_TYPE_LIST,
            self::NO_CERTIFICATE,
            self::NO_EXISTING_CHALLENGE,
            self::NO_TPP_CLIENT_CREDENTIALS,
            self::NO_PSU_METADATA,
            self::TOO_MANY_IDS,
            self::UNAUTHORIZED_ACCESS,
            self::UNEXPECTED_ERROR,
            self::UNKNOWN_ENTITY,
            self::UNSUPPORTED_BANK,
            self::UNSUPPORTED_FEATURE,
            self::UNSUPPORTED_MEDIA_TYPE,
            self::UNSUPPORTED_ORDER,
            self::USER_ALREADY_VERIFIED,
            self::USER_NOT_VERIFIED,
            self::WEB_FORM_ABORTED,
            self::WEB_FORM_REQUIRED
        ];
    }
}


