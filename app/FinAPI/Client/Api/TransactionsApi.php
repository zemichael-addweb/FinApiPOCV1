<?php
/**
 * TransactionsApi
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
 * TransactionsApi Class Doc Comment
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class TransactionsApi
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
        'deleteAllTransactions' => [
            'application/json',
        ],
        'deleteTransaction' => [
            'application/json',
        ],
        'editMultipleTransactions' => [
            'application/json',
        ],
        'editTransaction' => [
            'application/json',
        ],
        'getAndSearchAllTransactions' => [
            'application/json',
        ],
        'getTransaction' => [
            'application/json',
        ],
        'restoreTransaction' => [
            'application/json',
        ],
        'splitTransaction' => [
            'application/json',
        ],
        'triggerCategorization' => [
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
     * Operation deleteAllTransactions
     *
     * Delete all transactions
     *
     * @param  \DateTime $max_deletion_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;finapiBookingDate&#39; is equal to or earlier to the given date. The date may not be in future. If not specified, then no date limitation will be in place for the deletion. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;importDate&#39; is later than or equal to the given date. The date may not be in future. This is useful e.g. if a bank returns incorrect transactions and then fixes that issue. Then you could put the date when the error was first observed as &#39;minImportDate&#39;. This would lead to deletion of all transactions after the issue was introduced and allow finAPI to refetch them from scratch. This only works if safeMode is set to false and &#39;rememberDeletion&#39; is undefined or set to false. You also can not use this parameter alongside &#39;maxDeletionDate&#39;. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions whose account&#39;s identifier is included in this list will be get deleted. The maximum number of identifiers is 1000. (optional)
     * @param  bool $safe_mode When passing &#39;true&#39;, then only those transactions are being deleted where at least one of the following holds true: &lt;br/&gt;1. The transaction belongs to an account of a test bank &lt;br/&gt;2. The transaction&#39;s &#39;potentialDuplicate&#39; flag is set to TRUE&lt;br/&gt;3. The transaction is an adjusting entry (&#39;Zwischensaldo&#39; transaction) that was added by finAPI&lt;br/&gt; When passing &#39;false&#39;, then finAPI will delete transactions independent of these characteristics. The default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  bool $remember_deletion When passing &#39;true&#39;, then finAPI will make sure to not re-import deleted transactions on future account updates. When &#39;false&#39;, then deleted transactions might be re-imported. Default value for this parameter is &#39;false&#39;. (optional, default to false)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function deleteAllTransactions($max_deletion_date = null, $min_import_date = null, $account_ids = null, $safe_mode = true, $remember_deletion = false, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllTransactions'][0])
    {
        list($response) = $this->deleteAllTransactionsWithHttpInfo($max_deletion_date, $min_import_date, $account_ids, $safe_mode, $remember_deletion, $x_http_method_override, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation deleteAllTransactionsWithHttpInfo
     *
     * Delete all transactions
     *
     * @param  \DateTime $max_deletion_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;finapiBookingDate&#39; is equal to or earlier to the given date. The date may not be in future. If not specified, then no date limitation will be in place for the deletion. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;importDate&#39; is later than or equal to the given date. The date may not be in future. This is useful e.g. if a bank returns incorrect transactions and then fixes that issue. Then you could put the date when the error was first observed as &#39;minImportDate&#39;. This would lead to deletion of all transactions after the issue was introduced and allow finAPI to refetch them from scratch. This only works if safeMode is set to false and &#39;rememberDeletion&#39; is undefined or set to false. You also can not use this parameter alongside &#39;maxDeletionDate&#39;. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions whose account&#39;s identifier is included in this list will be get deleted. The maximum number of identifiers is 1000. (optional)
     * @param  bool $safe_mode When passing &#39;true&#39;, then only those transactions are being deleted where at least one of the following holds true: &lt;br/&gt;1. The transaction belongs to an account of a test bank &lt;br/&gt;2. The transaction&#39;s &#39;potentialDuplicate&#39; flag is set to TRUE&lt;br/&gt;3. The transaction is an adjusting entry (&#39;Zwischensaldo&#39; transaction) that was added by finAPI&lt;br/&gt; When passing &#39;false&#39;, then finAPI will delete transactions independent of these characteristics. The default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  bool $remember_deletion When passing &#39;true&#39;, then finAPI will make sure to not re-import deleted transactions on future account updates. When &#39;false&#39;, then deleted transactions might be re-imported. Default value for this parameter is &#39;false&#39;. (optional, default to false)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function deleteAllTransactionsWithHttpInfo($max_deletion_date = null, $min_import_date = null, $account_ids = null, $safe_mode = true, $remember_deletion = false, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllTransactions'][0])
    {
        $request = $this->deleteAllTransactionsRequest($max_deletion_date, $min_import_date, $account_ids, $safe_mode, $remember_deletion, $x_http_method_override, $x_request_id, $contentType);

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
     * Operation deleteAllTransactionsAsync
     *
     * Delete all transactions
     *
     * @param  \DateTime $max_deletion_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;finapiBookingDate&#39; is equal to or earlier to the given date. The date may not be in future. If not specified, then no date limitation will be in place for the deletion. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;importDate&#39; is later than or equal to the given date. The date may not be in future. This is useful e.g. if a bank returns incorrect transactions and then fixes that issue. Then you could put the date when the error was first observed as &#39;minImportDate&#39;. This would lead to deletion of all transactions after the issue was introduced and allow finAPI to refetch them from scratch. This only works if safeMode is set to false and &#39;rememberDeletion&#39; is undefined or set to false. You also can not use this parameter alongside &#39;maxDeletionDate&#39;. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions whose account&#39;s identifier is included in this list will be get deleted. The maximum number of identifiers is 1000. (optional)
     * @param  bool $safe_mode When passing &#39;true&#39;, then only those transactions are being deleted where at least one of the following holds true: &lt;br/&gt;1. The transaction belongs to an account of a test bank &lt;br/&gt;2. The transaction&#39;s &#39;potentialDuplicate&#39; flag is set to TRUE&lt;br/&gt;3. The transaction is an adjusting entry (&#39;Zwischensaldo&#39; transaction) that was added by finAPI&lt;br/&gt; When passing &#39;false&#39;, then finAPI will delete transactions independent of these characteristics. The default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  bool $remember_deletion When passing &#39;true&#39;, then finAPI will make sure to not re-import deleted transactions on future account updates. When &#39;false&#39;, then deleted transactions might be re-imported. Default value for this parameter is &#39;false&#39;. (optional, default to false)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAllTransactionsAsync($max_deletion_date = null, $min_import_date = null, $account_ids = null, $safe_mode = true, $remember_deletion = false, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllTransactions'][0])
    {
        return $this->deleteAllTransactionsAsyncWithHttpInfo($max_deletion_date, $min_import_date, $account_ids, $safe_mode, $remember_deletion, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteAllTransactionsAsyncWithHttpInfo
     *
     * Delete all transactions
     *
     * @param  \DateTime $max_deletion_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;finapiBookingDate&#39; is equal to or earlier to the given date. The date may not be in future. If not specified, then no date limitation will be in place for the deletion. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;importDate&#39; is later than or equal to the given date. The date may not be in future. This is useful e.g. if a bank returns incorrect transactions and then fixes that issue. Then you could put the date when the error was first observed as &#39;minImportDate&#39;. This would lead to deletion of all transactions after the issue was introduced and allow finAPI to refetch them from scratch. This only works if safeMode is set to false and &#39;rememberDeletion&#39; is undefined or set to false. You also can not use this parameter alongside &#39;maxDeletionDate&#39;. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions whose account&#39;s identifier is included in this list will be get deleted. The maximum number of identifiers is 1000. (optional)
     * @param  bool $safe_mode When passing &#39;true&#39;, then only those transactions are being deleted where at least one of the following holds true: &lt;br/&gt;1. The transaction belongs to an account of a test bank &lt;br/&gt;2. The transaction&#39;s &#39;potentialDuplicate&#39; flag is set to TRUE&lt;br/&gt;3. The transaction is an adjusting entry (&#39;Zwischensaldo&#39; transaction) that was added by finAPI&lt;br/&gt; When passing &#39;false&#39;, then finAPI will delete transactions independent of these characteristics. The default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  bool $remember_deletion When passing &#39;true&#39;, then finAPI will make sure to not re-import deleted transactions on future account updates. When &#39;false&#39;, then deleted transactions might be re-imported. Default value for this parameter is &#39;false&#39;. (optional, default to false)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAllTransactionsAsyncWithHttpInfo($max_deletion_date = null, $min_import_date = null, $account_ids = null, $safe_mode = true, $remember_deletion = false, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllTransactions'][0])
    {
        $returnType = '\FinAPI\Client\Model\IdentifierList';
        $request = $this->deleteAllTransactionsRequest($max_deletion_date, $min_import_date, $account_ids, $safe_mode, $remember_deletion, $x_http_method_override, $x_request_id, $contentType);

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
     * Create request for operation 'deleteAllTransactions'
     *
     * @param  \DateTime $max_deletion_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;finapiBookingDate&#39; is equal to or earlier to the given date. The date may not be in future. If not specified, then no date limitation will be in place for the deletion. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;If specified, then only those transactions are being deleted whose &#39;importDate&#39; is later than or equal to the given date. The date may not be in future. This is useful e.g. if a bank returns incorrect transactions and then fixes that issue. Then you could put the date when the error was first observed as &#39;minImportDate&#39;. This would lead to deletion of all transactions after the issue was introduced and allow finAPI to refetch them from scratch. This only works if safeMode is set to false and &#39;rememberDeletion&#39; is undefined or set to false. You also can not use this parameter alongside &#39;maxDeletionDate&#39;. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions whose account&#39;s identifier is included in this list will be get deleted. The maximum number of identifiers is 1000. (optional)
     * @param  bool $safe_mode When passing &#39;true&#39;, then only those transactions are being deleted where at least one of the following holds true: &lt;br/&gt;1. The transaction belongs to an account of a test bank &lt;br/&gt;2. The transaction&#39;s &#39;potentialDuplicate&#39; flag is set to TRUE&lt;br/&gt;3. The transaction is an adjusting entry (&#39;Zwischensaldo&#39; transaction) that was added by finAPI&lt;br/&gt; When passing &#39;false&#39;, then finAPI will delete transactions independent of these characteristics. The default value for this parameter is &#39;true&#39;. (optional, default to true)
     * @param  bool $remember_deletion When passing &#39;true&#39;, then finAPI will make sure to not re-import deleted transactions on future account updates. When &#39;false&#39;, then deleted transactions might be re-imported. Default value for this parameter is &#39;false&#39;. (optional, default to false)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function deleteAllTransactionsRequest($max_deletion_date = null, $min_import_date = null, $account_ids = null, $safe_mode = true, $remember_deletion = false, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteAllTransactions'][0])
    {









        $resourcePath = '/api/v2/transactions';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_deletion_date,
            'maxDeletionDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_import_date,
            'minImportDate', // param base name
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
            $safe_mode,
            'safeMode', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $remember_deletion,
            'rememberDeletion', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);

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
     * Operation deleteTransaction
     *
     * Delete a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return void
     */
    public function deleteTransaction($id, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteTransaction'][0])
    {
        $this->deleteTransactionWithHttpInfo($id, $x_http_method_override, $x_request_id, $contentType);
    }

    /**
     * Operation deleteTransactionWithHttpInfo
     *
     * Delete a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deleteTransactionWithHttpInfo($id, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteTransaction'][0])
    {
        $request = $this->deleteTransactionRequest($id, $x_http_method_override, $x_request_id, $contentType);

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
     * Operation deleteTransactionAsync
     *
     * Delete a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteTransactionAsync($id, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteTransaction'][0])
    {
        return $this->deleteTransactionAsyncWithHttpInfo($id, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteTransactionAsyncWithHttpInfo
     *
     * Delete a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteTransactionAsyncWithHttpInfo($id, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteTransaction'][0])
    {
        $returnType = '';
        $request = $this->deleteTransactionRequest($id, $x_http_method_override, $x_request_id, $contentType);

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
     * Create request for operation 'deleteTransaction'
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function deleteTransactionRequest($id, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['deleteTransaction'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling deleteTransaction'
            );
        }




        $resourcePath = '/api/v2/transactions/{id}';
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
     * Operation editMultipleTransactions
     *
     * Edit multiple transactions
     *
     * @param  \FinAPI\Client\Model\UpdateMultipleTransactionsParams $update_multiple_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editMultipleTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function editMultipleTransactions($update_multiple_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editMultipleTransactions'][0])
    {
        list($response) = $this->editMultipleTransactionsWithHttpInfo($update_multiple_transactions_params, $x_http_method_override, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation editMultipleTransactionsWithHttpInfo
     *
     * Edit multiple transactions
     *
     * @param  \FinAPI\Client\Model\UpdateMultipleTransactionsParams $update_multiple_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editMultipleTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\IdentifierList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function editMultipleTransactionsWithHttpInfo($update_multiple_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editMultipleTransactions'][0])
    {
        $request = $this->editMultipleTransactionsRequest($update_multiple_transactions_params, $x_http_method_override, $x_request_id, $contentType);

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
     * Operation editMultipleTransactionsAsync
     *
     * Edit multiple transactions
     *
     * @param  \FinAPI\Client\Model\UpdateMultipleTransactionsParams $update_multiple_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editMultipleTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editMultipleTransactionsAsync($update_multiple_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editMultipleTransactions'][0])
    {
        return $this->editMultipleTransactionsAsyncWithHttpInfo($update_multiple_transactions_params, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation editMultipleTransactionsAsyncWithHttpInfo
     *
     * Edit multiple transactions
     *
     * @param  \FinAPI\Client\Model\UpdateMultipleTransactionsParams $update_multiple_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editMultipleTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editMultipleTransactionsAsyncWithHttpInfo($update_multiple_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editMultipleTransactions'][0])
    {
        $returnType = '\FinAPI\Client\Model\IdentifierList';
        $request = $this->editMultipleTransactionsRequest($update_multiple_transactions_params, $x_http_method_override, $x_request_id, $contentType);

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
     * Create request for operation 'editMultipleTransactions'
     *
     * @param  \FinAPI\Client\Model\UpdateMultipleTransactionsParams $update_multiple_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editMultipleTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function editMultipleTransactionsRequest($update_multiple_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editMultipleTransactions'][0])
    {

        // verify the required parameter 'update_multiple_transactions_params' is set
        if ($update_multiple_transactions_params === null || (is_array($update_multiple_transactions_params) && count($update_multiple_transactions_params) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $update_multiple_transactions_params when calling editMultipleTransactions'
            );
        }




        $resourcePath = '/api/v2/transactions';
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



        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($update_multiple_transactions_params)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($update_multiple_transactions_params));
            } else {
                $httpBody = $update_multiple_transactions_params;
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
     * Operation editTransaction
     *
     * Edit a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  \FinAPI\Client\Model\UpdateTransactionsParams $update_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function editTransaction($id, $update_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editTransaction'][0])
    {
        list($response) = $this->editTransactionWithHttpInfo($id, $update_transactions_params, $x_http_method_override, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation editTransactionWithHttpInfo
     *
     * Edit a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  \FinAPI\Client\Model\UpdateTransactionsParams $update_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function editTransactionWithHttpInfo($id, $update_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editTransaction'][0])
    {
        $request = $this->editTransactionRequest($id, $update_transactions_params, $x_http_method_override, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\Transaction' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Transaction' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Transaction', []),
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

            $returnType = '\FinAPI\Client\Model\Transaction';
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
                        '\FinAPI\Client\Model\Transaction',
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
     * Operation editTransactionAsync
     *
     * Edit a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  \FinAPI\Client\Model\UpdateTransactionsParams $update_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editTransactionAsync($id, $update_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editTransaction'][0])
    {
        return $this->editTransactionAsyncWithHttpInfo($id, $update_transactions_params, $x_http_method_override, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation editTransactionAsyncWithHttpInfo
     *
     * Edit a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  \FinAPI\Client\Model\UpdateTransactionsParams $update_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function editTransactionAsyncWithHttpInfo($id, $update_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editTransaction'][0])
    {
        $returnType = '\FinAPI\Client\Model\Transaction';
        $request = $this->editTransactionRequest($id, $update_transactions_params, $x_http_method_override, $x_request_id, $contentType);

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
     * Create request for operation 'editTransaction'
     *
     * @param  int $id Identifier of transaction (required)
     * @param  \FinAPI\Client\Model\UpdateTransactionsParams $update_transactions_params Update transactions parameters (required)
     * @param  string $x_http_method_override Some HTTP clients do not support the HTTP methods PATCH or DELETE. If you are using such a client in your application, you can use a POST request instead with this header indicating the originally intended HTTP method. POST Requests having this  header set will be treated either as PATCH or DELETE by the finAPI servers. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['editTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function editTransactionRequest($id, $update_transactions_params, $x_http_method_override = null, $x_request_id = null, string $contentType = self::contentTypes['editTransaction'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling editTransaction'
            );
        }

        // verify the required parameter 'update_transactions_params' is set
        if ($update_transactions_params === null || (is_array($update_transactions_params) && count($update_transactions_params) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $update_transactions_params when calling editTransaction'
            );
        }




        $resourcePath = '/api/v2/transactions/{id}';
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
        if (isset($update_transactions_params)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($update_transactions_params));
            } else {
                $httpBody = $update_transactions_params;
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
     * Operation getAndSearchAllTransactions
     *
     * Get and search all transactions
     *
     * @param  string $view This parameter defines finAPI&#39;s logical view on the transactions when querying them: &#39;bankView&#39; regards only the original transactions as they were received from the bank, without considering how the transactions might have gotten split by the user (see POST /transactions/&amp;lt;id&amp;gt;/split). This means that if a transaction is split into logical sub-transactions, then the service will still regard only the original transaction, and NOT the logical sub-transactions in its processing (though for convenience, the transactions will have the data of their sub-transactions included in the response). &#39;userView&#39; by contrast regards the transactions as they exist for the user. For transactions that have not been split into logical sub-transactions, there is no difference to the \&quot;bankView\&quot;. But for transaction that have been split into logical sub-transactions, the service will ONLY regard these sub-transactions, and not the originally received transaction (though for convenience, the sub-transactions will have the identifier of their original transaction included in the response). (required)
     * @param  int[] $ids A comma-separated list of transaction identifiers. If specified, then only transactions whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those transactions will be contained in the result whose &#39;purpose&#39;, &#39;endToEndReference&#39;, or one of the counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $counterpart If specified, then only those transactions will be contained in the result whose counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of the counterpart fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $purpose If specified, then only those transactions will be contained in the result whose purpose field contains the given search string (the matching works case-insensitive). If no transactions contain the search string in the purpose field, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the purpose for a transaction to get included into the result. (optional)
     * @param  string $currency If specified, then only transactions with the given currency will be regarded. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions that relate to the given accounts will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  \DateTime $min_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  \DateTime $max_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  float $min_amount If specified, then only transactions whose amount is equal to or greater than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_amount If specified, then only transactions whose amount is equal to or less than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $direction If specified, then only transactions with the given direction(s) will be regarded. Use &#39;income&#39; for regarding only received payments (amount &gt;&#x3D; 0), &#39;spending&#39; for regarding only outgoing payments (amount &lt; 0), or &#39;all&#39; to regard both directions. If not specified, the direction defaults to &#39;all&#39;. (optional, default to 'all')
     * @param  int[] $label_ids A comma-separated list of label identifiers. If specified, then only transactions that  have been marked with at least one of the given labels will be contained in the result. (optional)
     * @param  int[] $category_ids A comma-separated list of category identifiers. If specified, then the result will contain only transactions whose category is either one of the target categories, or - but only if the &#39;includeChildCategories&#39; flag is set to &#39;true&#39; - whose category is a sub-category of one of the target categories. To include transactions without any category, pass the value &#39;0&#39; as the category ID.&lt;br/&gt;&lt;br/&gt;NOTE: If your client is restricted to certain categories (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;), then you may only specify categories that match your restrictions. Alternatively, you can leave this field unset, in which case finAPI will automatically populate this field with all categories that are defined in your restrictions. (optional)
     * @param  bool $include_child_categories This flag controls how the &#39;categoryIds&#39; are handled. If set to &#39;true&#39;, then all transactions with the target categories, as well as all transactions with any of their sub-categories will be regarded. If set to &#39;false&#39;, then sub-categories of a category are excluded and only those transactions are regarded whose category matches exactly the target category. The default value for this flag is &#39;true&#39;.&lt;br/&gt;&lt;br/&gt;Note that this field has an effect independent of whether you pass the &#39;categoryIds&#39; yourself, or whether the &#39;categoryIds&#39; are populated automatically based on your client&#39;s category restrictions (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;). (optional, default to true)
     * @param  bool $is_new If specified, then only transactions that have their &#39;isNew&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_potential_duplicate If specified, then only transactions that have their &#39;isPotentialDuplicate&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_adjusting_entry If specified, then only transactions that have their &#39;isAdjustingEntry&#39; flag set to true/false will be regarded. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can use the following fields for ordering the response: &#39;id&#39;, &#39;parentId&#39;, &#39;accountId&#39;, &#39;valueDate&#39;, &#39;bankBookingDate&#39;, &#39;finapiBookingDate&#39;, &#39;amount&#39;, &#39;purpose&#39;, &#39;counterpartName&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;type&#39;, &#39;primanota&#39;, &#39;category.id&#39;, &#39;category.name&#39;, &#39;isPotentialDuplicate&#39;, &#39;isNew&#39; and &#39;importDate&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/transactions?order&#x3D;finapiBookingDate,desc&amp;order&#x3D;counterpartName&#39; will return the latest transactions first. If there are more transactions on the same day, then these transactions are ordered by the counterpart name (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\PageableTransactionList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getAndSearchAllTransactions($view, $ids = null, $search = null, $counterpart = null, $purpose = null, $currency = null, $account_ids = null, $min_bank_booking_date = null, $max_bank_booking_date = null, $min_finapi_booking_date = null, $max_finapi_booking_date = null, $min_amount = null, $max_amount = null, $direction = 'all', $label_ids = null, $category_ids = null, $include_child_categories = true, $is_new = null, $is_potential_duplicate = null, $is_adjusting_entry = null, $min_import_date = null, $max_import_date = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllTransactions'][0])
    {
        list($response) = $this->getAndSearchAllTransactionsWithHttpInfo($view, $ids, $search, $counterpart, $purpose, $currency, $account_ids, $min_bank_booking_date, $max_bank_booking_date, $min_finapi_booking_date, $max_finapi_booking_date, $min_amount, $max_amount, $direction, $label_ids, $category_ids, $include_child_categories, $is_new, $is_potential_duplicate, $is_adjusting_entry, $min_import_date, $max_import_date, $page, $per_page, $order, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getAndSearchAllTransactionsWithHttpInfo
     *
     * Get and search all transactions
     *
     * @param  string $view This parameter defines finAPI&#39;s logical view on the transactions when querying them: &#39;bankView&#39; regards only the original transactions as they were received from the bank, without considering how the transactions might have gotten split by the user (see POST /transactions/&amp;lt;id&amp;gt;/split). This means that if a transaction is split into logical sub-transactions, then the service will still regard only the original transaction, and NOT the logical sub-transactions in its processing (though for convenience, the transactions will have the data of their sub-transactions included in the response). &#39;userView&#39; by contrast regards the transactions as they exist for the user. For transactions that have not been split into logical sub-transactions, there is no difference to the \&quot;bankView\&quot;. But for transaction that have been split into logical sub-transactions, the service will ONLY regard these sub-transactions, and not the originally received transaction (though for convenience, the sub-transactions will have the identifier of their original transaction included in the response). (required)
     * @param  int[] $ids A comma-separated list of transaction identifiers. If specified, then only transactions whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those transactions will be contained in the result whose &#39;purpose&#39;, &#39;endToEndReference&#39;, or one of the counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $counterpart If specified, then only those transactions will be contained in the result whose counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of the counterpart fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $purpose If specified, then only those transactions will be contained in the result whose purpose field contains the given search string (the matching works case-insensitive). If no transactions contain the search string in the purpose field, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the purpose for a transaction to get included into the result. (optional)
     * @param  string $currency If specified, then only transactions with the given currency will be regarded. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions that relate to the given accounts will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  \DateTime $min_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  \DateTime $max_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  float $min_amount If specified, then only transactions whose amount is equal to or greater than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_amount If specified, then only transactions whose amount is equal to or less than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $direction If specified, then only transactions with the given direction(s) will be regarded. Use &#39;income&#39; for regarding only received payments (amount &gt;&#x3D; 0), &#39;spending&#39; for regarding only outgoing payments (amount &lt; 0), or &#39;all&#39; to regard both directions. If not specified, the direction defaults to &#39;all&#39;. (optional, default to 'all')
     * @param  int[] $label_ids A comma-separated list of label identifiers. If specified, then only transactions that  have been marked with at least one of the given labels will be contained in the result. (optional)
     * @param  int[] $category_ids A comma-separated list of category identifiers. If specified, then the result will contain only transactions whose category is either one of the target categories, or - but only if the &#39;includeChildCategories&#39; flag is set to &#39;true&#39; - whose category is a sub-category of one of the target categories. To include transactions without any category, pass the value &#39;0&#39; as the category ID.&lt;br/&gt;&lt;br/&gt;NOTE: If your client is restricted to certain categories (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;), then you may only specify categories that match your restrictions. Alternatively, you can leave this field unset, in which case finAPI will automatically populate this field with all categories that are defined in your restrictions. (optional)
     * @param  bool $include_child_categories This flag controls how the &#39;categoryIds&#39; are handled. If set to &#39;true&#39;, then all transactions with the target categories, as well as all transactions with any of their sub-categories will be regarded. If set to &#39;false&#39;, then sub-categories of a category are excluded and only those transactions are regarded whose category matches exactly the target category. The default value for this flag is &#39;true&#39;.&lt;br/&gt;&lt;br/&gt;Note that this field has an effect independent of whether you pass the &#39;categoryIds&#39; yourself, or whether the &#39;categoryIds&#39; are populated automatically based on your client&#39;s category restrictions (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;). (optional, default to true)
     * @param  bool $is_new If specified, then only transactions that have their &#39;isNew&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_potential_duplicate If specified, then only transactions that have their &#39;isPotentialDuplicate&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_adjusting_entry If specified, then only transactions that have their &#39;isAdjustingEntry&#39; flag set to true/false will be regarded. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can use the following fields for ordering the response: &#39;id&#39;, &#39;parentId&#39;, &#39;accountId&#39;, &#39;valueDate&#39;, &#39;bankBookingDate&#39;, &#39;finapiBookingDate&#39;, &#39;amount&#39;, &#39;purpose&#39;, &#39;counterpartName&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;type&#39;, &#39;primanota&#39;, &#39;category.id&#39;, &#39;category.name&#39;, &#39;isPotentialDuplicate&#39;, &#39;isNew&#39; and &#39;importDate&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/transactions?order&#x3D;finapiBookingDate,desc&amp;order&#x3D;counterpartName&#39; will return the latest transactions first. If there are more transactions on the same day, then these transactions are ordered by the counterpart name (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllTransactions'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\PageableTransactionList|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getAndSearchAllTransactionsWithHttpInfo($view, $ids = null, $search = null, $counterpart = null, $purpose = null, $currency = null, $account_ids = null, $min_bank_booking_date = null, $max_bank_booking_date = null, $min_finapi_booking_date = null, $max_finapi_booking_date = null, $min_amount = null, $max_amount = null, $direction = 'all', $label_ids = null, $category_ids = null, $include_child_categories = true, $is_new = null, $is_potential_duplicate = null, $is_adjusting_entry = null, $min_import_date = null, $max_import_date = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllTransactions'][0])
    {
        $request = $this->getAndSearchAllTransactionsRequest($view, $ids, $search, $counterpart, $purpose, $currency, $account_ids, $min_bank_booking_date, $max_bank_booking_date, $min_finapi_booking_date, $max_finapi_booking_date, $min_amount, $max_amount, $direction, $label_ids, $category_ids, $include_child_categories, $is_new, $is_potential_duplicate, $is_adjusting_entry, $min_import_date, $max_import_date, $page, $per_page, $order, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\PageableTransactionList' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\PageableTransactionList' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\PageableTransactionList', []),
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

            $returnType = '\FinAPI\Client\Model\PageableTransactionList';
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
                        '\FinAPI\Client\Model\PageableTransactionList',
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
     * Operation getAndSearchAllTransactionsAsync
     *
     * Get and search all transactions
     *
     * @param  string $view This parameter defines finAPI&#39;s logical view on the transactions when querying them: &#39;bankView&#39; regards only the original transactions as they were received from the bank, without considering how the transactions might have gotten split by the user (see POST /transactions/&amp;lt;id&amp;gt;/split). This means that if a transaction is split into logical sub-transactions, then the service will still regard only the original transaction, and NOT the logical sub-transactions in its processing (though for convenience, the transactions will have the data of their sub-transactions included in the response). &#39;userView&#39; by contrast regards the transactions as they exist for the user. For transactions that have not been split into logical sub-transactions, there is no difference to the \&quot;bankView\&quot;. But for transaction that have been split into logical sub-transactions, the service will ONLY regard these sub-transactions, and not the originally received transaction (though for convenience, the sub-transactions will have the identifier of their original transaction included in the response). (required)
     * @param  int[] $ids A comma-separated list of transaction identifiers. If specified, then only transactions whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those transactions will be contained in the result whose &#39;purpose&#39;, &#39;endToEndReference&#39;, or one of the counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $counterpart If specified, then only those transactions will be contained in the result whose counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of the counterpart fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $purpose If specified, then only those transactions will be contained in the result whose purpose field contains the given search string (the matching works case-insensitive). If no transactions contain the search string in the purpose field, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the purpose for a transaction to get included into the result. (optional)
     * @param  string $currency If specified, then only transactions with the given currency will be regarded. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions that relate to the given accounts will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  \DateTime $min_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  \DateTime $max_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  float $min_amount If specified, then only transactions whose amount is equal to or greater than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_amount If specified, then only transactions whose amount is equal to or less than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $direction If specified, then only transactions with the given direction(s) will be regarded. Use &#39;income&#39; for regarding only received payments (amount &gt;&#x3D; 0), &#39;spending&#39; for regarding only outgoing payments (amount &lt; 0), or &#39;all&#39; to regard both directions. If not specified, the direction defaults to &#39;all&#39;. (optional, default to 'all')
     * @param  int[] $label_ids A comma-separated list of label identifiers. If specified, then only transactions that  have been marked with at least one of the given labels will be contained in the result. (optional)
     * @param  int[] $category_ids A comma-separated list of category identifiers. If specified, then the result will contain only transactions whose category is either one of the target categories, or - but only if the &#39;includeChildCategories&#39; flag is set to &#39;true&#39; - whose category is a sub-category of one of the target categories. To include transactions without any category, pass the value &#39;0&#39; as the category ID.&lt;br/&gt;&lt;br/&gt;NOTE: If your client is restricted to certain categories (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;), then you may only specify categories that match your restrictions. Alternatively, you can leave this field unset, in which case finAPI will automatically populate this field with all categories that are defined in your restrictions. (optional)
     * @param  bool $include_child_categories This flag controls how the &#39;categoryIds&#39; are handled. If set to &#39;true&#39;, then all transactions with the target categories, as well as all transactions with any of their sub-categories will be regarded. If set to &#39;false&#39;, then sub-categories of a category are excluded and only those transactions are regarded whose category matches exactly the target category. The default value for this flag is &#39;true&#39;.&lt;br/&gt;&lt;br/&gt;Note that this field has an effect independent of whether you pass the &#39;categoryIds&#39; yourself, or whether the &#39;categoryIds&#39; are populated automatically based on your client&#39;s category restrictions (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;). (optional, default to true)
     * @param  bool $is_new If specified, then only transactions that have their &#39;isNew&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_potential_duplicate If specified, then only transactions that have their &#39;isPotentialDuplicate&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_adjusting_entry If specified, then only transactions that have their &#39;isAdjustingEntry&#39; flag set to true/false will be regarded. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can use the following fields for ordering the response: &#39;id&#39;, &#39;parentId&#39;, &#39;accountId&#39;, &#39;valueDate&#39;, &#39;bankBookingDate&#39;, &#39;finapiBookingDate&#39;, &#39;amount&#39;, &#39;purpose&#39;, &#39;counterpartName&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;type&#39;, &#39;primanota&#39;, &#39;category.id&#39;, &#39;category.name&#39;, &#39;isPotentialDuplicate&#39;, &#39;isNew&#39; and &#39;importDate&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/transactions?order&#x3D;finapiBookingDate,desc&amp;order&#x3D;counterpartName&#39; will return the latest transactions first. If there are more transactions on the same day, then these transactions are ordered by the counterpart name (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllTransactionsAsync($view, $ids = null, $search = null, $counterpart = null, $purpose = null, $currency = null, $account_ids = null, $min_bank_booking_date = null, $max_bank_booking_date = null, $min_finapi_booking_date = null, $max_finapi_booking_date = null, $min_amount = null, $max_amount = null, $direction = 'all', $label_ids = null, $category_ids = null, $include_child_categories = true, $is_new = null, $is_potential_duplicate = null, $is_adjusting_entry = null, $min_import_date = null, $max_import_date = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllTransactions'][0])
    {
        return $this->getAndSearchAllTransactionsAsyncWithHttpInfo($view, $ids, $search, $counterpart, $purpose, $currency, $account_ids, $min_bank_booking_date, $max_bank_booking_date, $min_finapi_booking_date, $max_finapi_booking_date, $min_amount, $max_amount, $direction, $label_ids, $category_ids, $include_child_categories, $is_new, $is_potential_duplicate, $is_adjusting_entry, $min_import_date, $max_import_date, $page, $per_page, $order, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getAndSearchAllTransactionsAsyncWithHttpInfo
     *
     * Get and search all transactions
     *
     * @param  string $view This parameter defines finAPI&#39;s logical view on the transactions when querying them: &#39;bankView&#39; regards only the original transactions as they were received from the bank, without considering how the transactions might have gotten split by the user (see POST /transactions/&amp;lt;id&amp;gt;/split). This means that if a transaction is split into logical sub-transactions, then the service will still regard only the original transaction, and NOT the logical sub-transactions in its processing (though for convenience, the transactions will have the data of their sub-transactions included in the response). &#39;userView&#39; by contrast regards the transactions as they exist for the user. For transactions that have not been split into logical sub-transactions, there is no difference to the \&quot;bankView\&quot;. But for transaction that have been split into logical sub-transactions, the service will ONLY regard these sub-transactions, and not the originally received transaction (though for convenience, the sub-transactions will have the identifier of their original transaction included in the response). (required)
     * @param  int[] $ids A comma-separated list of transaction identifiers. If specified, then only transactions whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those transactions will be contained in the result whose &#39;purpose&#39;, &#39;endToEndReference&#39;, or one of the counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $counterpart If specified, then only those transactions will be contained in the result whose counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of the counterpart fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $purpose If specified, then only those transactions will be contained in the result whose purpose field contains the given search string (the matching works case-insensitive). If no transactions contain the search string in the purpose field, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the purpose for a transaction to get included into the result. (optional)
     * @param  string $currency If specified, then only transactions with the given currency will be regarded. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions that relate to the given accounts will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  \DateTime $min_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  \DateTime $max_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  float $min_amount If specified, then only transactions whose amount is equal to or greater than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_amount If specified, then only transactions whose amount is equal to or less than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $direction If specified, then only transactions with the given direction(s) will be regarded. Use &#39;income&#39; for regarding only received payments (amount &gt;&#x3D; 0), &#39;spending&#39; for regarding only outgoing payments (amount &lt; 0), or &#39;all&#39; to regard both directions. If not specified, the direction defaults to &#39;all&#39;. (optional, default to 'all')
     * @param  int[] $label_ids A comma-separated list of label identifiers. If specified, then only transactions that  have been marked with at least one of the given labels will be contained in the result. (optional)
     * @param  int[] $category_ids A comma-separated list of category identifiers. If specified, then the result will contain only transactions whose category is either one of the target categories, or - but only if the &#39;includeChildCategories&#39; flag is set to &#39;true&#39; - whose category is a sub-category of one of the target categories. To include transactions without any category, pass the value &#39;0&#39; as the category ID.&lt;br/&gt;&lt;br/&gt;NOTE: If your client is restricted to certain categories (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;), then you may only specify categories that match your restrictions. Alternatively, you can leave this field unset, in which case finAPI will automatically populate this field with all categories that are defined in your restrictions. (optional)
     * @param  bool $include_child_categories This flag controls how the &#39;categoryIds&#39; are handled. If set to &#39;true&#39;, then all transactions with the target categories, as well as all transactions with any of their sub-categories will be regarded. If set to &#39;false&#39;, then sub-categories of a category are excluded and only those transactions are regarded whose category matches exactly the target category. The default value for this flag is &#39;true&#39;.&lt;br/&gt;&lt;br/&gt;Note that this field has an effect independent of whether you pass the &#39;categoryIds&#39; yourself, or whether the &#39;categoryIds&#39; are populated automatically based on your client&#39;s category restrictions (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;). (optional, default to true)
     * @param  bool $is_new If specified, then only transactions that have their &#39;isNew&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_potential_duplicate If specified, then only transactions that have their &#39;isPotentialDuplicate&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_adjusting_entry If specified, then only transactions that have their &#39;isAdjustingEntry&#39; flag set to true/false will be regarded. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can use the following fields for ordering the response: &#39;id&#39;, &#39;parentId&#39;, &#39;accountId&#39;, &#39;valueDate&#39;, &#39;bankBookingDate&#39;, &#39;finapiBookingDate&#39;, &#39;amount&#39;, &#39;purpose&#39;, &#39;counterpartName&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;type&#39;, &#39;primanota&#39;, &#39;category.id&#39;, &#39;category.name&#39;, &#39;isPotentialDuplicate&#39;, &#39;isNew&#39; and &#39;importDate&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/transactions?order&#x3D;finapiBookingDate,desc&amp;order&#x3D;counterpartName&#39; will return the latest transactions first. If there are more transactions on the same day, then these transactions are ordered by the counterpart name (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAndSearchAllTransactionsAsyncWithHttpInfo($view, $ids = null, $search = null, $counterpart = null, $purpose = null, $currency = null, $account_ids = null, $min_bank_booking_date = null, $max_bank_booking_date = null, $min_finapi_booking_date = null, $max_finapi_booking_date = null, $min_amount = null, $max_amount = null, $direction = 'all', $label_ids = null, $category_ids = null, $include_child_categories = true, $is_new = null, $is_potential_duplicate = null, $is_adjusting_entry = null, $min_import_date = null, $max_import_date = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllTransactions'][0])
    {
        $returnType = '\FinAPI\Client\Model\PageableTransactionList';
        $request = $this->getAndSearchAllTransactionsRequest($view, $ids, $search, $counterpart, $purpose, $currency, $account_ids, $min_bank_booking_date, $max_bank_booking_date, $min_finapi_booking_date, $max_finapi_booking_date, $min_amount, $max_amount, $direction, $label_ids, $category_ids, $include_child_categories, $is_new, $is_potential_duplicate, $is_adjusting_entry, $min_import_date, $max_import_date, $page, $per_page, $order, $x_request_id, $contentType);

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
     * Create request for operation 'getAndSearchAllTransactions'
     *
     * @param  string $view This parameter defines finAPI&#39;s logical view on the transactions when querying them: &#39;bankView&#39; regards only the original transactions as they were received from the bank, without considering how the transactions might have gotten split by the user (see POST /transactions/&amp;lt;id&amp;gt;/split). This means that if a transaction is split into logical sub-transactions, then the service will still regard only the original transaction, and NOT the logical sub-transactions in its processing (though for convenience, the transactions will have the data of their sub-transactions included in the response). &#39;userView&#39; by contrast regards the transactions as they exist for the user. For transactions that have not been split into logical sub-transactions, there is no difference to the \&quot;bankView\&quot;. But for transaction that have been split into logical sub-transactions, the service will ONLY regard these sub-transactions, and not the originally received transaction (though for convenience, the sub-transactions will have the identifier of their original transaction included in the response). (required)
     * @param  int[] $ids A comma-separated list of transaction identifiers. If specified, then only transactions whose identifier match any of the given identifiers will be regarded. The maximum number of identifiers is 1000. (optional)
     * @param  string $search If specified, then only those transactions will be contained in the result whose &#39;purpose&#39;, &#39;endToEndReference&#39;, or one of the counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of these fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $counterpart If specified, then only those transactions will be contained in the result whose counterpart fields (&#39;counterpart&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;mandateReference&#39;, &#39;customerReference&#39;, &#39;creditorId&#39;, or &#39;debitorId&#39;) contain the given search string (the matching works case-insensitive). If no transactions contain the search string in any of the counterpart fields, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the searched fields for a transaction to get included into the result. (optional)
     * @param  string $purpose If specified, then only those transactions will be contained in the result whose purpose field contains the given search string (the matching works case-insensitive). If no transactions contain the search string in the purpose field, then the result will be an empty list. NOTE: If the given search string consists of several terms (separated by whitespace), then ALL of these terms must be contained in the purpose for a transaction to get included into the result. (optional)
     * @param  string $currency If specified, then only transactions with the given currency will be regarded. (optional)
     * @param  int[] $account_ids A comma-separated list of account identifiers. If specified, then only transactions that relate to the given accounts will be regarded. If not specified, then all accounts will be regarded. (optional)
     * @param  \DateTime $min_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_bank_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as returned by the bank (&#x3D; original booking date), e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;bankBookingDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  \DateTime $min_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  \DateTime $max_finapi_booking_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s booking date as set by finAPI, e.g. &#39;2016-01-01&#39;. For details about the meaning of the finAPI booking date, please see the field&#39;s documentation in the service&#39;s response. (optional)
     * @param  float $min_amount If specified, then only transactions whose amount is equal to or greater than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  float $max_amount If specified, then only transactions whose amount is equal to or less than the given amount will be regarded. Can contain a positive or negative number with at most two decimal places. Examples: -300.12, or 90.95 (optional)
     * @param  string $direction If specified, then only transactions with the given direction(s) will be regarded. Use &#39;income&#39; for regarding only received payments (amount &gt;&#x3D; 0), &#39;spending&#39; for regarding only outgoing payments (amount &lt; 0), or &#39;all&#39; to regard both directions. If not specified, the direction defaults to &#39;all&#39;. (optional, default to 'all')
     * @param  int[] $label_ids A comma-separated list of label identifiers. If specified, then only transactions that  have been marked with at least one of the given labels will be contained in the result. (optional)
     * @param  int[] $category_ids A comma-separated list of category identifiers. If specified, then the result will contain only transactions whose category is either one of the target categories, or - but only if the &#39;includeChildCategories&#39; flag is set to &#39;true&#39; - whose category is a sub-category of one of the target categories. To include transactions without any category, pass the value &#39;0&#39; as the category ID.&lt;br/&gt;&lt;br/&gt;NOTE: If your client is restricted to certain categories (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;), then you may only specify categories that match your restrictions. Alternatively, you can leave this field unset, in which case finAPI will automatically populate this field with all categories that are defined in your restrictions. (optional)
     * @param  bool $include_child_categories This flag controls how the &#39;categoryIds&#39; are handled. If set to &#39;true&#39;, then all transactions with the target categories, as well as all transactions with any of their sub-categories will be regarded. If set to &#39;false&#39;, then sub-categories of a category are excluded and only those transactions are regarded whose category matches exactly the target category. The default value for this flag is &#39;true&#39;.&lt;br/&gt;&lt;br/&gt;Note that this field has an effect independent of whether you pass the &#39;categoryIds&#39; yourself, or whether the &#39;categoryIds&#39; are populated automatically based on your client&#39;s category restrictions (see GET /clientConfiguration, field &#39;categoryRestrictions&#39;). (optional, default to true)
     * @param  bool $is_new If specified, then only transactions that have their &#39;isNew&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_potential_duplicate If specified, then only transactions that have their &#39;isPotentialDuplicate&#39; flag set to true/false will be regarded. (optional)
     * @param  bool $is_adjusting_entry If specified, then only transactions that have their &#39;isAdjustingEntry&#39; flag set to true/false will be regarded. (optional)
     * @param  \DateTime $min_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Lower bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or later than the given date will be regarded. (optional)
     * @param  \DateTime $max_import_date &lt;strong&gt;Format:&lt;/strong&gt; &#39;YYYY-MM-DD&#39;&lt;br/&gt;Upper bound for a transaction&#39;s import date, e.g. &#39;2016-01-01&#39;. If specified, then only transactions whose &#39;importDate&#39; is equal to or earlier than the given date will be regarded. (optional)
     * @param  int $page Result page that you want to retrieve. (optional, default to 1)
     * @param  int $per_page Maximum number of records per page. By default it&#39;s 20. (optional, default to 20)
     * @param  string[] $order Determines the order of the results. You can use the following fields for ordering the response: &#39;id&#39;, &#39;parentId&#39;, &#39;accountId&#39;, &#39;valueDate&#39;, &#39;bankBookingDate&#39;, &#39;finapiBookingDate&#39;, &#39;amount&#39;, &#39;purpose&#39;, &#39;counterpartName&#39;, &#39;counterpartAccountNumber&#39;, &#39;counterpartIban&#39;, &#39;counterpartBlz&#39;, &#39;counterpartBic&#39;, &#39;type&#39;, &#39;primanota&#39;, &#39;category.id&#39;, &#39;category.name&#39;, &#39;isPotentialDuplicate&#39;, &#39;isNew&#39; and &#39;importDate&#39;. The default order for all services is &#39;id,asc&#39;. You can also order by multiple properties. In that case the order of the parameters passed is important. Example: &#39;/transactions?order&#x3D;finapiBookingDate,desc&amp;order&#x3D;counterpartName&#39; will return the latest transactions first. If there are more transactions on the same day, then these transactions are ordered by the counterpart name (ascending). The general format is: &#39;property[,asc|desc]&#39;, with &#39;asc&#39; being the default value. (optional)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAndSearchAllTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getAndSearchAllTransactionsRequest($view, $ids = null, $search = null, $counterpart = null, $purpose = null, $currency = null, $account_ids = null, $min_bank_booking_date = null, $max_bank_booking_date = null, $min_finapi_booking_date = null, $max_finapi_booking_date = null, $min_amount = null, $max_amount = null, $direction = 'all', $label_ids = null, $category_ids = null, $include_child_categories = true, $is_new = null, $is_potential_duplicate = null, $is_adjusting_entry = null, $min_import_date = null, $max_import_date = null, $page = 1, $per_page = 20, $order = null, $x_request_id = null, string $contentType = self::contentTypes['getAndSearchAllTransactions'][0])
    {

        // verify the required parameter 'view' is set
        if ($view === null || (is_array($view) && count($view) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $view when calling getAndSearchAllTransactions'
            );
        }






















        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling TransactionsApi.getAndSearchAllTransactions, must be bigger than or equal to 1.');
        }
        
        if ($per_page !== null && $per_page > 500) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling TransactionsApi.getAndSearchAllTransactions, must be smaller than or equal to 500.');
        }
        if ($per_page !== null && $per_page < 1) {
            throw new \InvalidArgumentException('invalid value for "$per_page" when calling TransactionsApi.getAndSearchAllTransactions, must be bigger than or equal to 1.');
        }
        



        $resourcePath = '/api/v2/transactions';
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
            $view,
            'view', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
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
            $counterpart,
            'counterpart', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $purpose,
            'purpose', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $currency,
            'currency', // param base name
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
            $min_bank_booking_date,
            'minBankBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_bank_booking_date,
            'maxBankBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_finapi_booking_date,
            'minFinapiBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_finapi_booking_date,
            'maxFinapiBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_amount,
            'minAmount', // param base name
            'number', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_amount,
            'maxAmount', // param base name
            'number', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $direction,
            'direction', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $label_ids,
            'labelIds', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $category_ids,
            'categoryIds', // param base name
            'array', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $include_child_categories,
            'includeChildCategories', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $is_new,
            'isNew', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $is_potential_duplicate,
            'isPotentialDuplicate', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $is_adjusting_entry,
            'isAdjustingEntry', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $min_import_date,
            'minImportDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $max_import_date,
            'maxImportDate', // param base name
            'string', // openApiType
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
     * Operation getTransaction
     *
     * Get a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function getTransaction($id, $x_request_id = null, string $contentType = self::contentTypes['getTransaction'][0])
    {
        list($response) = $this->getTransactionWithHttpInfo($id, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation getTransactionWithHttpInfo
     *
     * Get a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function getTransactionWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getTransaction'][0])
    {
        $request = $this->getTransactionRequest($id, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\Transaction' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Transaction' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Transaction', []),
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

            $returnType = '\FinAPI\Client\Model\Transaction';
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
                        '\FinAPI\Client\Model\Transaction',
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
     * Operation getTransactionAsync
     *
     * Get a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTransactionAsync($id, $x_request_id = null, string $contentType = self::contentTypes['getTransaction'][0])
    {
        return $this->getTransactionAsyncWithHttpInfo($id, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getTransactionAsyncWithHttpInfo
     *
     * Get a transaction
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTransactionAsyncWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['getTransaction'][0])
    {
        $returnType = '\FinAPI\Client\Model\Transaction';
        $request = $this->getTransactionRequest($id, $x_request_id, $contentType);

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
     * Create request for operation 'getTransaction'
     *
     * @param  int $id Identifier of transaction (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getTransactionRequest($id, $x_request_id = null, string $contentType = self::contentTypes['getTransaction'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling getTransaction'
            );
        }



        $resourcePath = '/api/v2/transactions/{id}';
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
     * Operation restoreTransaction
     *
     * Restore a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function restoreTransaction($id, $x_request_id = null, string $contentType = self::contentTypes['restoreTransaction'][0])
    {
        list($response) = $this->restoreTransactionWithHttpInfo($id, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation restoreTransactionWithHttpInfo
     *
     * Restore a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function restoreTransactionWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['restoreTransaction'][0])
    {
        $request = $this->restoreTransactionRequest($id, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\Transaction' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Transaction' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Transaction', []),
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

            $returnType = '\FinAPI\Client\Model\Transaction';
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
                        '\FinAPI\Client\Model\Transaction',
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
     * Operation restoreTransactionAsync
     *
     * Restore a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function restoreTransactionAsync($id, $x_request_id = null, string $contentType = self::contentTypes['restoreTransaction'][0])
    {
        return $this->restoreTransactionAsyncWithHttpInfo($id, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation restoreTransactionAsyncWithHttpInfo
     *
     * Restore a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function restoreTransactionAsyncWithHttpInfo($id, $x_request_id = null, string $contentType = self::contentTypes['restoreTransaction'][0])
    {
        $returnType = '\FinAPI\Client\Model\Transaction';
        $request = $this->restoreTransactionRequest($id, $x_request_id, $contentType);

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
     * Create request for operation 'restoreTransaction'
     *
     * @param  int $id Transaction identifier (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function restoreTransactionRequest($id, $x_request_id = null, string $contentType = self::contentTypes['restoreTransaction'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling restoreTransaction'
            );
        }



        $resourcePath = '/api/v2/transactions/{id}/restore';
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
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation splitTransaction
     *
     * Split a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  \FinAPI\Client\Model\SplitTransactionsParams $split_transactions_params Split transactions parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['splitTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage
     */
    public function splitTransaction($id, $split_transactions_params, $x_request_id = null, string $contentType = self::contentTypes['splitTransaction'][0])
    {
        list($response) = $this->splitTransactionWithHttpInfo($id, $split_transactions_params, $x_request_id, $contentType);
        return $response;
    }

    /**
     * Operation splitTransactionWithHttpInfo
     *
     * Split a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  \FinAPI\Client\Model\SplitTransactionsParams $split_transactions_params Split transactions parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['splitTransaction'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \FinAPI\Client\Model\Transaction|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\BadCredentialsError|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage|\FinAPI\Client\Model\ErrorMessage, HTTP status code, HTTP response headers (array of strings)
     */
    public function splitTransactionWithHttpInfo($id, $split_transactions_params, $x_request_id = null, string $contentType = self::contentTypes['splitTransaction'][0])
    {
        $request = $this->splitTransactionRequest($id, $split_transactions_params, $x_request_id, $contentType);

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
                    if ('\FinAPI\Client\Model\Transaction' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\FinAPI\Client\Model\Transaction' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\FinAPI\Client\Model\Transaction', []),
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

            $returnType = '\FinAPI\Client\Model\Transaction';
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
                        '\FinAPI\Client\Model\Transaction',
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
     * Operation splitTransactionAsync
     *
     * Split a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  \FinAPI\Client\Model\SplitTransactionsParams $split_transactions_params Split transactions parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['splitTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function splitTransactionAsync($id, $split_transactions_params, $x_request_id = null, string $contentType = self::contentTypes['splitTransaction'][0])
    {
        return $this->splitTransactionAsyncWithHttpInfo($id, $split_transactions_params, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation splitTransactionAsyncWithHttpInfo
     *
     * Split a transaction
     *
     * @param  int $id Transaction identifier (required)
     * @param  \FinAPI\Client\Model\SplitTransactionsParams $split_transactions_params Split transactions parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['splitTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function splitTransactionAsyncWithHttpInfo($id, $split_transactions_params, $x_request_id = null, string $contentType = self::contentTypes['splitTransaction'][0])
    {
        $returnType = '\FinAPI\Client\Model\Transaction';
        $request = $this->splitTransactionRequest($id, $split_transactions_params, $x_request_id, $contentType);

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
     * Create request for operation 'splitTransaction'
     *
     * @param  int $id Transaction identifier (required)
     * @param  \FinAPI\Client\Model\SplitTransactionsParams $split_transactions_params Split transactions parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['splitTransaction'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function splitTransactionRequest($id, $split_transactions_params, $x_request_id = null, string $contentType = self::contentTypes['splitTransaction'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling splitTransaction'
            );
        }

        // verify the required parameter 'split_transactions_params' is set
        if ($split_transactions_params === null || (is_array($split_transactions_params) && count($split_transactions_params) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $split_transactions_params when calling splitTransaction'
            );
        }



        $resourcePath = '/api/v2/transactions/{id}/split';
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
        if (isset($split_transactions_params)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($split_transactions_params));
            } else {
                $httpBody = $split_transactions_params;
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
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation triggerCategorization
     *
     * Trigger categorization
     *
     * @param  \FinAPI\Client\Model\TriggerCategorizationParams $trigger_categorization_params Trigger categorization parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerCategorization'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return void
     */
    public function triggerCategorization($trigger_categorization_params, $x_request_id = null, string $contentType = self::contentTypes['triggerCategorization'][0])
    {
        $this->triggerCategorizationWithHttpInfo($trigger_categorization_params, $x_request_id, $contentType);
    }

    /**
     * Operation triggerCategorizationWithHttpInfo
     *
     * Trigger categorization
     *
     * @param  \FinAPI\Client\Model\TriggerCategorizationParams $trigger_categorization_params Trigger categorization parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerCategorization'] to see the possible values for this operation
     *
     * @throws \FinAPI\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function triggerCategorizationWithHttpInfo($trigger_categorization_params, $x_request_id = null, string $contentType = self::contentTypes['triggerCategorization'][0])
    {
        $request = $this->triggerCategorizationRequest($trigger_categorization_params, $x_request_id, $contentType);

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
     * Operation triggerCategorizationAsync
     *
     * Trigger categorization
     *
     * @param  \FinAPI\Client\Model\TriggerCategorizationParams $trigger_categorization_params Trigger categorization parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerCategorization'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function triggerCategorizationAsync($trigger_categorization_params, $x_request_id = null, string $contentType = self::contentTypes['triggerCategorization'][0])
    {
        return $this->triggerCategorizationAsyncWithHttpInfo($trigger_categorization_params, $x_request_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation triggerCategorizationAsyncWithHttpInfo
     *
     * Trigger categorization
     *
     * @param  \FinAPI\Client\Model\TriggerCategorizationParams $trigger_categorization_params Trigger categorization parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerCategorization'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function triggerCategorizationAsyncWithHttpInfo($trigger_categorization_params, $x_request_id = null, string $contentType = self::contentTypes['triggerCategorization'][0])
    {
        $returnType = '';
        $request = $this->triggerCategorizationRequest($trigger_categorization_params, $x_request_id, $contentType);

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
     * Create request for operation 'triggerCategorization'
     *
     * @param  \FinAPI\Client\Model\TriggerCategorizationParams $trigger_categorization_params Trigger categorization parameters (required)
     * @param  string $x_request_id With any API call, you can pass a request ID. The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error. If you don&#39;t pass a request ID for a call, finAPI will generate a random ID internally. The request ID is always returned back in the response of a service, as a header with name &#39;X-Request-Id&#39;. We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response (especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerCategorization'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function triggerCategorizationRequest($trigger_categorization_params, $x_request_id = null, string $contentType = self::contentTypes['triggerCategorization'][0])
    {

        // verify the required parameter 'trigger_categorization_params' is set
        if ($trigger_categorization_params === null || (is_array($trigger_categorization_params) && count($trigger_categorization_params) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $trigger_categorization_params when calling triggerCategorization'
            );
        }



        $resourcePath = '/api/v2/transactions/triggerCategorization';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


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
        if (isset($trigger_categorization_params)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($trigger_categorization_params));
            } else {
                $httpBody = $trigger_categorization_params;
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
            'POST',
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
