<?php
/**
 * AccountsApi
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

namespace FinAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use FinAPI\Client\ApiException;
use FinAPI\Client\Configuration;
use FinAPI\Client\HeaderSelector;
use FinAPI\Client\ObjectSerializer;

/**
 * AccountsApi Class Doc Comment
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class AccountsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'deleteAccount' => [
            'application/json',
        ],
        'deleteAllAccounts' => [
            'application/json',
        ],
        'editAccount' => [
            'application/json',
        ],
        'getAccount' => [
            'application/json',
        ],
        'getAndSearchAllAccounts' => [
            'application/json',
        ],
        'getDailyBalances' => [
            'application/json',
        ],
    ];

/**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null,
        $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation deleteAccount
     *
     * Delete an account
     *
     * @param  int $id Identifier of the account to delete (required)
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return void
     */
    public function deleteAccount($id, $psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAccount'][0])
    {
        $this->deleteAccountWithHttpInfo($id, $psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);
    }

    /**
     * Operation deleteAccountWithHttpInfo
     *
     * Delete an account
     *
     * @param  int $id Identifier of the account to delete (required)
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deleteAccountWithHttpInfo($id, $psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAccount'][0])
    {
        $request = $this->deleteAccountRequest($id, $psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return [null, $statusCode, $response->getHeaders()];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 423:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation deleteAccountAsync
     *
     * Delete an account
     *
     * @param  int $id Identifier of the account to delete (required)
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAccountAsync($id, $psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAccount'][0])
    {
        return $this->deleteAccountAsyncWithHttpInfo($id, $psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteAccountAsyncWithHttpInfo
     *
     * Delete an account
     *
     * @param  int $id Identifier of the account to delete (required)
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAccountAsyncWithHttpInfo($id, $psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAccount'][0])
    {
        $returnType = '';
        $request = $this->deleteAccountRequest($id, $psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'deleteAccount'
     *
     * @param  int $id Identifier of the account to delete (required)
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function deleteAccountRequest($id, $psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAccount'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling deleteAccount'
            );
        }







        $resourcePath = '/api/v2/accounts/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($psu_ip_address !== null) {
            $headerParams['PSU-IP-Address'] = ObjectSerializer::toHeaderValue($psu_ip_address);
        }
        // header params
        if ($psu_device_os !== null) {
            $headerParams['PSU-Device-OS'] = ObjectSerializer::toHeaderValue($psu_device_os);
        }
        // header params
        if ($psu_user_agent !== null) {
            $headerParams['PSU-User-Agent'] = ObjectSerializer::toHeaderValue($psu_user_agent);
        }
        // header params
        if ($x_http_method_override !== null) {
            $headerParams['X-HTTP-Method-Override'] = ObjectSerializer::toHeaderValue($x_http_method_override);
        }
        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }

        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'DELETE',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation deleteAllAccounts
     *
     * Delete all accounts
     *
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllAccounts'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function deleteAllAccounts($psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllAccounts'][0])
    {
        list($response) = $this->deleteAllAccountsWithHttpInfo($psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation deleteAllAccountsWithHttpInfo
     *
     * Delete all accounts
     *
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllAccounts'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function deleteAllAccountsWithHttpInfo($psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllAccounts'][0])
    {
        $request = $this->deleteAllAccountsRequest($psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\FinAPI\Client\Model\IdentifierList' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\IdentifierList' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\IdentifierList', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 401:
                    if ('\FinAPI\Client\Model\BadCredentialsError' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\BadCredentialsError' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\BadCredentialsError', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 423:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 500:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\FinAPI\Client\Model\IdentifierList';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\IdentifierList',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 423:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation deleteAllAccountsAsync
     *
     * Delete all accounts
     *
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAllAccountsAsync($psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllAccounts'][0])
    {
        return $this->deleteAllAccountsAsyncWithHttpInfo($psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteAllAccountsAsyncWithHttpInfo
     *
     * Delete all accounts
     *
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAllAccountsAsyncWithHttpInfo($psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllAccounts'][0])
    {
        $returnType = '\FinAPI\Client\Model\IdentifierList';
        $request = $this->deleteAllAccountsRequest($psu_ip_address, $psu_device_os, $psu_user_agent, $x_http_method_override, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'deleteAllAccounts'
     *
     * @param  string $psu_ip_address The IP address of the user&#39;s device. This header will be forwarded to the bank on XS2A requests. It has to be an IPv4 address, as some banks cannot work with IPv6 addresses. If a non-IPv4 address is passed, we will replace the value with our own IPv4 address as a fallback. (optional)
     * @param  string $psu_device_os The user&#39;s device and/or operating system identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $psu_user_agent The user&#39;s web browser or other client device identification. This header will be forwarded to the bank on XS2A requests. (optional)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function deleteAllAccountsRequest($psu_ip_address = null, $psu_device_os = null, $psu_user_agent = null, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllAccounts'][0])
    {







        $resourcePath = '/api/v2/accounts';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($psu_ip_address !== null) {
            $headerParams['PSU-IP-Address'] = ObjectSerializer::toHeaderValue($psu_ip_address);
        }
        // header params
        if ($psu_device_os !== null) {
            $headerParams['PSU-Device-OS'] = ObjectSerializer::toHeaderValue($psu_device_os);
        }
        // header params
        if ($psu_user_agent !== null) {
            $headerParams['PSU-User-Agent'] = ObjectSerializer::toHeaderValue($psu_user_agent);
        }
        // header params
        if ($x_http_method_override !== null) {
            $headerParams['X-HTTP-Method-Override'] = ObjectSerializer::toHeaderValue($x_http_method_override);
        }
        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'DELETE',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation editAccount
     *
     * Edit an account
     *
     * @param  int $id Identifier of the account to edit (required)
     * @param  \FinAPI\Client\Model\AccountParams $account_params New account name and/or type and/or &#39;isNew&#39; flag (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Account|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function editAccount($id, $account_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editAccount'][0])
    {
        list($response) = $this->editAccountWithHttpInfo($id, $account_params, $x_http_method_override, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation editAccountWithHttpInfo
     *
     * Edit an account
     *
     * @param  int $id Identifier of the account to edit (required)
     * @param  \FinAPI\Client\Model\AccountParams $account_params New account name and/or type and/or &#39;isNew&#39; flag (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Account|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function editAccountWithHttpInfo($id, $account_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editAccount'][0])
    {
        $request = $this->editAccountRequest($id, $account_params, $x_http_method_override, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\FinAPI\Client\Model\Account' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Account' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Account', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 400:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 401:
                    if ('\FinAPI\Client\Model\BadCredentialsError' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\BadCredentialsError' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\BadCredentialsError', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 404:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 423:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 500:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\FinAPI\Client\Model\Account';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\Account',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 423:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation editAccountAsync
     *
     * Edit an account
     *
     * @param  int $id Identifier of the account to edit (required)
     * @param  \FinAPI\Client\Model\AccountParams $account_params New account name and/or type and/or &#39;isNew&#39; flag (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editAccountAsync($id, $account_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editAccount'][0])
    {
        return $this->editAccountAsyncWithHttpInfo($id, $account_params, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation editAccountAsyncWithHttpInfo
     *
     * Edit an account
     *
     * @param  int $id Identifier of the account to edit (required)
     * @param  \FinAPI\Client\Model\AccountParams $account_params New account name and/or type and/or &#39;isNew&#39; flag (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editAccountAsyncWithHttpInfo($id, $account_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editAccount'][0])
    {
        $returnType = '\FinAPI\Client\Model\Account';
        $request = $this->editAccountRequest($id, $account_params, $x_http_method_override, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'editAccount'
     *
     * @param  int $id Identifier of the account to edit (required)
     * @param  \FinAPI\Client\Model\AccountParams $account_params New account name and/or type and/or &#39;isNew&#39; flag (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function editAccountRequest($id, $account_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editAccount'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling editAccount'
            );
        }

        // verify the required parameter 'account_params' is set
        if ($account_params === null || (is_array($account_params) && count($account_params) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_params when calling editAccount'
            );
        }




        $resourcePath = '/api/v2/accounts/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($x_http_method_override !== null) {
            $headerParams['X-HTTP-Method-Override'] = ObjectSerializer::toHeaderValue($x_http_method_override);
        }
        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }

        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($account_params)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($account_params));
            } else {
                $httpBody = $account_params;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'PATCH',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getAccount
     *
     * Get an account
     *
     * @param  int $id Identifier of requested account (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Account|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getAccount($id, $x_request_id = null, string $contentType = self::contentTypes['getAccount'][0])
    {
        list($response) = $this->getAccountWithHttpInfo($id, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getAccountWithHttpInfo
     *
     * Get an account
     *
     * @param  int $id Identifier of requested account (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAccount'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Account|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getAccountWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getAccount'][0])
    {
        $request = $this->getAccountRequest($id, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\FinAPI\Client\Model\Account' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Account' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Account', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 400:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 401:
                    if ('\FinAPI\Client\Model\BadCredentialsError' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\BadCredentialsError' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\BadCredentialsError', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 404:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 500:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\FinAPI\Client\Model\Account';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\Account',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getAccountAsync
     *
     * Get an account
     *
     * @param  int $id Identifier of requested account (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAccountAsync($id, $x_request_id = null, string $contentType = self::contentTypes['getAccount'][0])
    {
        return $this->getAccountAsyncWithHttpInfo($id, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getAccountAsyncWithHttpInfo
     *
     * Get an account
     *
     * @param  int $id Identifier of requested account (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAccountAsyncWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getAccount'][0])
    {
        $returnType = '\FinAPI\Client\Model\Account';
        $request = $this->getAccountRequest($id, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getAccount'
     *
     * @param  int $id Identifier of requested account (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAccount'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getAccountRequest($id, $x_request_id = null, string $contentType = self::contentTypes['getAccount'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling getAccount'
            );
        }



        $resourcePath = '/api/v2/accounts/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }

        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getAndSearchAllAccounts
     *
     * Get and search all accounts
     *
     * @param  int[] $ids A comma-separated list of account identifiers. If specified, then only accounts whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those accounts will be contained in the result whose &#39;accountName&#39;, &#39;iban&#39;, &#39;accountNumber&#39; or &#39;subAccountNumber&#39; contains the given search string (the matching works case-insensitive). If no accounts contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for an account to get included into the result. (optional)
     * @param  string[] $account_types A comma-separated list of account types. If specified, then only accounts that relate to the given types will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  int[] $bank_connection_ids A comma-separated list of bank connection identifiers. If specified, then only accounts that relate to the given bank connections will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or later than the given date. (optional)
     * @param  \DateTime $max_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or earlier than the given date. (optional)
     * @param  float $min_balance If specified, then only accounts whose balance is equal to or greater than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_balance If specified, then only accounts whose balance is equal to or less than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllAccounts'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\AccountList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getAndSearchAllAccounts($ids = null, $search = null, $account_types = null, $bank_connection_ids = null, $min_last_successful_update = null, $max_last_successful_update = null, $min_balance = null, $max_balance = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllAccounts'][0])
    {
        list($response) = $this->getAndSearchAllAccountsWithHttpInfo($ids, $search, $account_types, $bank_connection_ids, $min_last_successful_update, $max_last_successful_update, $min_balance, $max_balance, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getAndSearchAllAccountsWithHttpInfo
     *
     * Get and search all accounts
     *
     * @param  int[] $ids A comma-separated list of account identifiers. If specified, then only accounts whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those accounts will be contained in the result whose &#39;accountName&#39;, &#39;iban&#39;, &#39;accountNumber&#39; or &#39;subAccountNumber&#39; contains the given search string (the matching works case-insensitive). If no accounts contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for an account to get included into the result. (optional)
     * @param  string[] $account_types A comma-separated list of account types. If specified, then only accounts that relate to the given types will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  int[] $bank_connection_ids A comma-separated list of bank connection identifiers. If specified, then only accounts that relate to the given bank connections will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or later than the given date. (optional)
     * @param  \DateTime $max_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or earlier than the given date. (optional)
     * @param  float $min_balance If specified, then only accounts whose balance is equal to or greater than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_balance If specified, then only accounts whose balance is equal to or less than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllAccounts'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\AccountList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getAndSearchAllAccountsWithHttpInfo($ids = null, $search = null, $account_types = null, $bank_connection_ids = null, $min_last_successful_update = null, $max_last_successful_update = null, $min_balance = null, $max_balance = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllAccounts'][0])
    {
        $request = $this->getAndSearchAllAccountsRequest($ids, $search, $account_types, $bank_connection_ids, $min_last_successful_update, $max_last_successful_update, $min_balance, $max_balance, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\FinAPI\Client\Model\AccountList' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\AccountList' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\AccountList', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 400:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 401:
                    if ('\FinAPI\Client\Model\BadCredentialsError' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\BadCredentialsError' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\BadCredentialsError', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 500:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\FinAPI\Client\Model\AccountList';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\AccountList',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getAndSearchAllAccountsAsync
     *
     * Get and search all accounts
     *
     * @param  int[] $ids A comma-separated list of account identifiers. If specified, then only accounts whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those accounts will be contained in the result whose &#39;accountName&#39;, &#39;iban&#39;, &#39;accountNumber&#39; or &#39;subAccountNumber&#39; contains the given search string (the matching works case-insensitive). If no accounts contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for an account to get included into the result. (optional)
     * @param  string[] $account_types A comma-separated list of account types. If specified, then only accounts that relate to the given types will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  int[] $bank_connection_ids A comma-separated list of bank connection identifiers. If specified, then only accounts that relate to the given bank connections will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or later than the given date. (optional)
     * @param  \DateTime $max_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or earlier than the given date. (optional)
     * @param  float $min_balance If specified, then only accounts whose balance is equal to or greater than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_balance If specified, then only accounts whose balance is equal to or less than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllAccountsAsync($ids = null, $search = null, $account_types = null, $bank_connection_ids = null, $min_last_successful_update = null, $max_last_successful_update = null, $min_balance = null, $max_balance = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllAccounts'][0])
    {
        return $this->getAndSearchAllAccountsAsyncWithHttpInfo($ids, $search, $account_types, $bank_connection_ids, $min_last_successful_update, $max_last_successful_update, $min_balance, $max_balance, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getAndSearchAllAccountsAsyncWithHttpInfo
     *
     * Get and search all accounts
     *
     * @param  int[] $ids A comma-separated list of account identifiers. If specified, then only accounts whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those accounts will be contained in the result whose &#39;accountName&#39;, &#39;iban&#39;, &#39;accountNumber&#39; or &#39;subAccountNumber&#39; contains the given search string (the matching works case-insensitive). If no accounts contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for an account to get included into the result. (optional)
     * @param  string[] $account_types A comma-separated list of account types. If specified, then only accounts that relate to the given types will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  int[] $bank_connection_ids A comma-separated list of bank connection identifiers. If specified, then only accounts that relate to the given bank connections will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or later than the given date. (optional)
     * @param  \DateTime $max_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or earlier than the given date. (optional)
     * @param  float $min_balance If specified, then only accounts whose balance is equal to or greater than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_balance If specified, then only accounts whose balance is equal to or less than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllAccountsAsyncWithHttpInfo($ids = null, $search = null, $account_types = null, $bank_connection_ids = null, $min_last_successful_update = null, $max_last_successful_update = null, $min_balance = null, $max_balance = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllAccounts'][0])
    {
        $returnType = '\FinAPI\Client\Model\AccountList';
        $request = $this->getAndSearchAllAccountsRequest($ids, $search, $account_types, $bank_connection_ids, $min_last_successful_update, $max_last_successful_update, $min_balance, $max_balance, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getAndSearchAllAccounts'
     *
     * @param  int[] $ids A comma-separated list of account identifiers. If specified, then only accounts whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those accounts will be contained in the result whose &#39;accountName&#39;, &#39;iban&#39;, &#39;accountNumber&#39; or &#39;subAccountNumber&#39; contains the given search string (the matching works case-insensitive). If no accounts contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for an account to get included into the result. (optional)
     * @param  string[] $account_types A comma-separated list of account types. If specified, then only accounts that relate to the given types will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  int[] $bank_connection_ids A comma-separated list of bank connection identifiers. If specified, then only accounts that relate to the given bank connections will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or later than the given date. (optional)
     * @param  \DateTime $max_last_successful_update &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for an account&#39;s last successful update date, e.g. &#39;2016-01-01&#39;. If specified, then an account will only be regarded if any of its interfaces has a &#39;lastSuccessfulUpdate&#39; that is equal to or earlier than the given date. (optional)
     * @param  float $min_balance If specified, then only accounts whose balance is equal to or greater than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_balance If specified, then only accounts whose balance is equal to or less than the given balance will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getAndSearchAllAccountsRequest($ids = null, $search = null, $account_types = null, $bank_connection_ids = null, $min_last_successful_update = null, $max_last_successful_update = null, $min_balance = null, $max_balance = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllAccounts'][0])
    {











        $resourcePath = '/api/v2/accounts';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $ids,
            'ids', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $search,
            'search', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $account_types,
            'accountTypes', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $bank_connection_ids,
            'bankConnectionIds', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_last_successful_update,
            'minLastSuccessfulUpdate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_last_successful_update,
            'maxLastSuccessfulUpdate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_balance,
            'minBalance', // param base name
            'number', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_balance,
            'maxBalance', // param base name
            'number', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);

        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getDailyBalances
     *
     * Get daily balances
     *
     * @param  int[] $account_ids A comma-separated list of (non-security) account identifiers. If no accounts are specified, all (non-security) accounts of the user are regarded. (optional)
     * @param  \DateTime $start_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too).If &#39;startDate&#39; is not specified, it defaults to &#39;endDate&#39; minus one month. (optional)
     * @param  \DateTime $end_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too). If &#39;endDate&#39; is not specified, it defaults to today&#39;s date. (optional)
     * @param  bool $with_projection Whether finAPI should project the first and last actually existing balance of an account into the past and future. When passing &#39;true&#39;, then the result will always contain a daily balance for every day of the entire requested date range, even for days before the first actually existing balance, resp. after the last actually existing balance. Those days will have the same balance as the day of the first actual balance, resp. last actual balance, i.e. the first/last balance will be infinitely projected into the past/the future. When passing &#39;false&#39;, then the result will contain daily balances only from the day on where the first actual balance exists for any of the regarded accounts, and only up to the day where the last actual balance exists for any of the regarded accounts. Note that when in this case there are no actual balances within the requested date range, then an empty array will be returned. Default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by &#39;date&#39;, &#39;balance&#39;, &#39;income&#39; or &#39;spending&#39;. The default order for this service is &#39;date,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/accounts/dailyBalances?order&#x3D;date,desc&amp;order&#x3D;balance,asc&#39; will return daily balances ordered by &#39;date&#39; (descending), where items with the same &#39;date&#39; are ordered by &#39;balance&#39; (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDailyBalances'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\DailyBalanceList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getDailyBalances($account_ids = null, $start_date = null, $end_date = null, $with_projection = true, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getDailyBalances'][0])
    {
        list($response) = $this->getDailyBalancesWithHttpInfo($account_ids, $start_date, $end_date, $with_projection, $page, $per_page, $order, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getDailyBalancesWithHttpInfo
     *
     * Get daily balances
     *
     * @param  int[] $account_ids A comma-separated list of (non-security) account identifiers. If no accounts are specified, all (non-security) accounts of the user are regarded. (optional)
     * @param  \DateTime $start_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too).If &#39;startDate&#39; is not specified, it defaults to &#39;endDate&#39; minus one month. (optional)
     * @param  \DateTime $end_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too). If &#39;endDate&#39; is not specified, it defaults to today&#39;s date. (optional)
     * @param  bool $with_projection Whether finAPI should project the first and last actually existing balance of an account into the past and future. When passing &#39;true&#39;, then the result will always contain a daily balance for every day of the entire requested date range, even for days before the first actually existing balance, resp. after the last actually existing balance. Those days will have the same balance as the day of the first actual balance, resp. last actual balance, i.e. the first/last balance will be infinitely projected into the past/the future. When passing &#39;false&#39;, then the result will contain daily balances only from the day on where the first actual balance exists for any of the regarded accounts, and only up to the day where the last actual balance exists for any of the regarded accounts. Note that when in this case there are no actual balances within the requested date range, then an empty array will be returned. Default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by &#39;date&#39;, &#39;balance&#39;, &#39;income&#39; or &#39;spending&#39;. The default order for this service is &#39;date,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/accounts/dailyBalances?order&#x3D;date,desc&amp;order&#x3D;balance,asc&#39; will return daily balances ordered by &#39;date&#39; (descending), where items with the same &#39;date&#39; are ordered by &#39;balance&#39; (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDailyBalances'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\DailyBalanceList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getDailyBalancesWithHttpInfo($account_ids = null, $start_date = null, $end_date = null, $with_projection = true, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getDailyBalances'][0])
    {
        $request = $this->getDailyBalancesRequest($account_ids, $start_date, $end_date, $with_projection, $page, $per_page, $order, $x_request_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\FinAPI\Client\Model\DailyBalanceList' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\DailyBalanceList' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\DailyBalanceList', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 400:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 401:
                    if ('\FinAPI\Client\Model\BadCredentialsError' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\BadCredentialsError' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\BadCredentialsError', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 404:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 422:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 500:
                    if ('\FinAPI\Client\Model\ErrorMessage' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\ErrorMessage' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\ErrorMessage', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\FinAPI\Client\Model\DailyBalanceList';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\DailyBalanceList',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\BadCredentialsError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\FinAPI\Client\Model\ErrorMessage',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getDailyBalancesAsync
     *
     * Get daily balances
     *
     * @param  int[] $account_ids A comma-separated list of (non-security) account identifiers. If no accounts are specified, all (non-security) accounts of the user are regarded. (optional)
     * @param  \DateTime $start_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too).If &#39;startDate&#39; is not specified, it defaults to &#39;endDate&#39; minus one month. (optional)
     * @param  \DateTime $end_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too). If &#39;endDate&#39; is not specified, it defaults to today&#39;s date. (optional)
     * @param  bool $with_projection Whether finAPI should project the first and last actually existing balance of an account into the past and future. When passing &#39;true&#39;, then the result will always contain a daily balance for every day of the entire requested date range, even for days before the first actually existing balance, resp. after the last actually existing balance. Those days will have the same balance as the day of the first actual balance, resp. last actual balance, i.e. the first/last balance will be infinitely projected into the past/the future. When passing &#39;false&#39;, then the result will contain daily balances only from the day on where the first actual balance exists for any of the regarded accounts, and only up to the day where the last actual balance exists for any of the regarded accounts. Note that when in this case there are no actual balances within the requested date range, then an empty array will be returned. Default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by &#39;date&#39;, &#39;balance&#39;, &#39;income&#39; or &#39;spending&#39;. The default order for this service is &#39;date,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/accounts/dailyBalances?order&#x3D;date,desc&amp;order&#x3D;balance,asc&#39; will return daily balances ordered by &#39;date&#39; (descending), where items with the same &#39;date&#39; are ordered by &#39;balance&#39; (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDailyBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getDailyBalancesAsync($account_ids = null, $start_date = null, $end_date = null, $with_projection = true, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getDailyBalances'][0])
    {
        return $this->getDailyBalancesAsyncWithHttpInfo($account_ids, $start_date, $end_date, $with_projection, $page, $per_page, $order, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getDailyBalancesAsyncWithHttpInfo
     *
     * Get daily balances
     *
     * @param  int[] $account_ids A comma-separated list of (non-security) account identifiers. If no accounts are specified, all (non-security) accounts of the user are regarded. (optional)
     * @param  \DateTime $start_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too).If &#39;startDate&#39; is not specified, it defaults to &#39;endDate&#39; minus one month. (optional)
     * @param  \DateTime $end_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too). If &#39;endDate&#39; is not specified, it defaults to today&#39;s date. (optional)
     * @param  bool $with_projection Whether finAPI should project the first and last actually existing balance of an account into the past and future. When passing &#39;true&#39;, then the result will always contain a daily balance for every day of the entire requested date range, even for days before the first actually existing balance, resp. after the last actually existing balance. Those days will have the same balance as the day of the first actual balance, resp. last actual balance, i.e. the first/last balance will be infinitely projected into the past/the future. When passing &#39;false&#39;, then the result will contain daily balances only from the day on where the first actual balance exists for any of the regarded accounts, and only up to the day where the last actual balance exists for any of the regarded accounts. Note that when in this case there are no actual balances within the requested date range, then an empty array will be returned. Default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by &#39;date&#39;, &#39;balance&#39;, &#39;income&#39; or &#39;spending&#39;. The default order for this service is &#39;date,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/accounts/dailyBalances?order&#x3D;date,desc&amp;order&#x3D;balance,asc&#39; will return daily balances ordered by &#39;date&#39; (descending), where items with the same &#39;date&#39; are ordered by &#39;balance&#39; (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDailyBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getDailyBalancesAsyncWithHttpInfo($account_ids = null, $start_date = null, $end_date = null, $with_projection = true, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getDailyBalances'][0])
    {
        $returnType = '\FinAPI\Client\Model\DailyBalanceList';
        $request = $this->getDailyBalancesRequest($account_ids, $start_date, $end_date, $with_projection, $page, $per_page, $order, $x_request_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getDailyBalances'
     *
     * @param  int[] $account_ids A comma-separated list of (non-security) account identifiers. If no accounts are specified, all (non-security) accounts of the user are regarded. (optional)
     * @param  \DateTime $start_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too).If &#39;startDate&#39; is not specified, it defaults to &#39;endDate&#39; minus one month. (optional)
     * @param  \DateTime $end_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for the date range to be returned. Note that the requested date range [startDate .. endDate] may not exceed 1 year (366 days - considering Leap Years too). If &#39;endDate&#39; is not specified, it defaults to today&#39;s date. (optional)
     * @param  bool $with_projection Whether finAPI should project the first and last actually existing balance of an account into the past and future. When passing &#39;true&#39;, then the result will always contain a daily balance for every day of the entire requested date range, even for days before the first actually existing balance, resp. after the last actually existing balance. Those days will have the same balance as the day of the first actual balance, resp. last actual balance, i.e. the first/last balance will be infinitely projected into the past/the future. When passing &#39;false&#39;, then the result will contain daily balances only from the day on where the first actual balance exists for any of the regarded accounts, and only up to the day where the last actual balance exists for any of the regarded accounts. Note that when in this case there are no actual balances within the requested date range, then an empty array will be returned. Default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by &#39;date&#39;, &#39;balance&#39;, &#39;income&#39; or &#39;spending&#39;. The default order for this service is &#39;date,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/accounts/dailyBalances?order&#x3D;date,desc&amp;order&#x3D;balance,asc&#39; will return daily balances ordered by &#39;date&#39; (descending), where items with the same &#39;date&#39; are ordered by &#39;balance&#39; (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDailyBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getDailyBalancesRequest($account_ids = null, $start_date = null, $end_date = null, $with_projection = true, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getDailyBalances'][0])
    {





        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.getDailyBalances, must be bigger than or equal to 1.');
        }
        
        if ($per_page !== null && $per_page > 500) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling AccountsApi.getDailyBalances, must be smaller than or equal to 500.');
        }
        if ($per_page !== null && $per_page < 1) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling AccountsApi.getDailyBalances, must be bigger than or equal to 1.');
        }
        



        $resourcePath = '/api/v2/accounts/dailyBalances';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $account_ids,
            'accountIds', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $start_date,
            'startDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $end_date,
            'endDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $with_projection,
            'withProjection', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $page,
            'page', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $per_page,
            'perPage', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $order,
            'order', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);

        // header params
        if ($x_request_id !== null) {
            $headerParams['X-Request-Id'] = ObjectSerializer::toHeaderValue($x_request_id);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
