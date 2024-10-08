<?php
/**
 * SecuritiesApi
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
 * SecuritiesApi Class Doc Comment
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class SecuritiesApi
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
        'getAndSearchAllSecurities' => [
            'application/json',
        ],
        'getSecurity' => [
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
     * Operation getAndSearchAllSecurities
     *
     * Get and search all securities
     *
     * @param  int[] $ids A comma-separated list of security identifiers. If specified, then only securities whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those securities will be contained in the result whose &#39;name&#39;, &#39;isin&#39; or &#39;wkn&#39; contains the given search string (the matching works case-insensitive). If no securities contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a security to get included into the result. (optional)
     * @param  int[] $account_ids Comma-separated list of identifiers of accounts (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by next fields: &#39;id&#39;, &#39;name&#39;, &#39;isin&#39;, &#39;wkn&#39;, &#39;quote&#39;, &#39;quantityNominal&#39;, &#39;marketValue&#39; and &#39;entryQuote&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllSecurities'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\PageableSecurityList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getAndSearchAllSecurities($ids = null, $search = null, $account_ids = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllSecurities'][0])
    {
        list($response) = $this->getAndSearchAllSecuritiesWithHttpInfo($ids, $search, $account_ids, $page, $per_page, $order, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getAndSearchAllSecuritiesWithHttpInfo
     *
     * Get and search all securities
     *
     * @param  int[] $ids A comma-separated list of security identifiers. If specified, then only securities whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those securities will be contained in the result whose &#39;name&#39;, &#39;isin&#39; or &#39;wkn&#39; contains the given search string (the matching works case-insensitive). If no securities contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a security to get included into the result. (optional)
     * @param  int[] $account_ids Comma-separated list of identifiers of accounts (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by next fields: &#39;id&#39;, &#39;name&#39;, &#39;isin&#39;, &#39;wkn&#39;, &#39;quote&#39;, &#39;quantityNominal&#39;, &#39;marketValue&#39; and &#39;entryQuote&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllSecurities'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\PageableSecurityList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getAndSearchAllSecuritiesWithHttpInfo($ids = null, $search = null, $account_ids = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllSecurities'][0])
    {
        $request = $this->getAndSearchAllSecuritiesRequest($ids, $search, $account_ids, $page, $per_page, $order, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\PageableSecurityList' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\PageableSecurityList' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\PageableSecurityList', []),
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

            $returnType = '\FinAPI\Client\Model\PageableSecurityList';
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
                        '\FinAPI\Client\Model\PageableSecurityList',
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
     * Operation getAndSearchAllSecuritiesAsync
     *
     * Get and search all securities
     *
     * @param  int[] $ids A comma-separated list of security identifiers. If specified, then only securities whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those securities will be contained in the result whose &#39;name&#39;, &#39;isin&#39; or &#39;wkn&#39; contains the given search string (the matching works case-insensitive). If no securities contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a security to get included into the result. (optional)
     * @param  int[] $account_ids Comma-separated list of identifiers of accounts (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by next fields: &#39;id&#39;, &#39;name&#39;, &#39;isin&#39;, &#39;wkn&#39;, &#39;quote&#39;, &#39;quantityNominal&#39;, &#39;marketValue&#39; and &#39;entryQuote&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllSecurities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllSecuritiesAsync($ids = null, $search = null, $account_ids = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllSecurities'][0])
    {
        return $this->getAndSearchAllSecuritiesAsyncWithHttpInfo($ids, $search, $account_ids, $page, $per_page, $order, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getAndSearchAllSecuritiesAsyncWithHttpInfo
     *
     * Get and search all securities
     *
     * @param  int[] $ids A comma-separated list of security identifiers. If specified, then only securities whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those securities will be contained in the result whose &#39;name&#39;, &#39;isin&#39; or &#39;wkn&#39; contains the given search string (the matching works case-insensitive). If no securities contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a security to get included into the result. (optional)
     * @param  int[] $account_ids Comma-separated list of identifiers of accounts (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by next fields: &#39;id&#39;, &#39;name&#39;, &#39;isin&#39;, &#39;wkn&#39;, &#39;quote&#39;, &#39;quantityNominal&#39;, &#39;marketValue&#39; and &#39;entryQuote&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllSecurities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllSecuritiesAsyncWithHttpInfo($ids = null, $search = null, $account_ids = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllSecurities'][0])
    {
        $returnType = '\FinAPI\Client\Model\PageableSecurityList';
        $request = $this->getAndSearchAllSecuritiesRequest($ids, $search, $account_ids, $page, $per_page, $order, $x_request_id, $contentType);

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
     * Create request for operation 'getAndSearchAllSecurities'
     *
     * @param  int[] $ids A comma-separated list of security identifiers. If specified, then only securities whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those securities will be contained in the result whose &#39;name&#39;, &#39;isin&#39; or &#39;wkn&#39; contains the given search string (the matching works case-insensitive). If no securities contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a security to get included into the result. (optional)
     * @param  int[] $account_ids Comma-separated list of identifiers of accounts (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can order the results by next fields: &#39;id&#39;, &#39;name&#39;, &#39;isin&#39;, &#39;wkn&#39;, &#39;quote&#39;, &#39;quantityNominal&#39;, &#39;marketValue&#39; and &#39;entryQuote&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllSecurities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getAndSearchAllSecuritiesRequest($ids = null, $search = null, $account_ids = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllSecurities'][0])
    {




        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling SecuritiesApi.getAndSearchAllSecurities, must be bigger than or equal to 1.');
        }
        
        if ($per_page !== null && $per_page > 500) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling SecuritiesApi.getAndSearchAllSecurities, must be smaller than or equal to 500.');
        }
        if ($per_page !== null && $per_page < 1) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling SecuritiesApi.getAndSearchAllSecurities, must be bigger than or equal to 1.');
        }
        



        $resourcePath = '/api/v2/securities';
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
            $account_ids,
            'accountIds', // param base name
            'array', // openApiType
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
     * Operation getSecurity
     *
     * Get a security
     *
     * @param  int $id Security identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSecurity'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Security|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getSecurity($id, $x_request_id = null, string $contentType = self::contentTypes['getSecurity'][0])
    {
        list($response) = $this->getSecurityWithHttpInfo($id, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getSecurityWithHttpInfo
     *
     * Get a security
     *
     * @param  int $id Security identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSecurity'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Security|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getSecurityWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getSecurity'][0])
    {
        $request = $this->getSecurityRequest($id, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\Security' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Security' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Security', []),
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

            $returnType = '\FinAPI\Client\Model\Security';
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
                        '\FinAPI\Client\Model\Security',
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
     * Operation getSecurityAsync
     *
     * Get a security
     *
     * @param  int $id Security identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSecurity'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getSecurityAsync($id, $x_request_id = null, string $contentType = self::contentTypes['getSecurity'][0])
    {
        return $this->getSecurityAsyncWithHttpInfo($id, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getSecurityAsyncWithHttpInfo
     *
     * Get a security
     *
     * @param  int $id Security identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSecurity'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getSecurityAsyncWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getSecurity'][0])
    {
        $returnType = '\FinAPI\Client\Model\Security';
        $request = $this->getSecurityRequest($id, $x_request_id, $contentType);

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
     * Create request for operation 'getSecurity'
     *
     * @param  int $id Security identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSecurity'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getSecurityRequest($id, $x_request_id = null, string $contentType = self::contentTypes['getSecurity'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling getSecurity'
            );
        }



        $resourcePath = '/api/v2/securities/{id}';
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
