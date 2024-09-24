<?php
/**
 * Language
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
 * Language Class Doc Comment
 *
 * @category Class
 * @package  FinAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Language
{
    /**
     * Possible values of this enum
     */
    public const AA = 'AA';

    public const AB = 'AB';

    public const AE = 'AE';

    public const AF = 'AF';

    public const AK = 'AK';

    public const AM = 'AM';

    public const AN = 'AN';

    public const AR = 'AR';

    public const _AS = 'AS';

    public const AV = 'AV';

    public const AY = 'AY';

    public const AZ = 'AZ';

    public const BA = 'BA';

    public const BE = 'BE';

    public const BG = 'BG';

    public const BH = 'BH';

    public const BI = 'BI';

    public const BM = 'BM';

    public const BN = 'BN';

    public const BO = 'BO';

    public const BR = 'BR';

    public const BS = 'BS';

    public const CA = 'CA';

    public const CE = 'CE';

    public const CH = 'CH';

    public const CO = 'CO';

    public const CR = 'CR';

    public const CS = 'CS';

    public const CU = 'CU';

    public const CV = 'CV';

    public const CY = 'CY';

    public const DA = 'DA';

    public const DE = 'DE';

    public const DV = 'DV';

    public const DZ = 'DZ';

    public const EE = 'EE';

    public const EL = 'EL';

    public const EN = 'EN';

    public const EO = 'EO';

    public const ES = 'ES';

    public const ET = 'ET';

    public const EU = 'EU';

    public const FA = 'FA';

    public const FF = 'FF';

    public const FI = 'FI';

    public const FJ = 'FJ';

    public const FO = 'FO';

    public const FR = 'FR';

    public const FY = 'FY';

    public const GA = 'GA';

    public const GD = 'GD';

    public const GL = 'GL';

    public const GN = 'GN';

    public const GU = 'GU';

    public const GV = 'GV';

    public const HA = 'HA';

    public const HE = 'HE';

    public const HI = 'HI';

    public const HO = 'HO';

    public const HR = 'HR';

    public const HT = 'HT';

    public const HU = 'HU';

    public const HY = 'HY';

    public const HZ = 'HZ';

    public const IA = 'IA';

    public const ID = 'ID';

    public const IE = 'IE';

    public const IG = 'IG';

    public const II = 'II';

    public const IK = 'IK';

    public const IO = 'IO';

    public const IS = 'IS';

    public const IT = 'IT';

    public const IU = 'IU';

    public const JA = 'JA';

    public const JV = 'JV';

    public const KA = 'KA';

    public const KG = 'KG';

    public const KI = 'KI';

    public const KJ = 'KJ';

    public const KK = 'KK';

    public const KL = 'KL';

    public const KM = 'KM';

    public const KN = 'KN';

    public const KO = 'KO';

    public const KR = 'KR';

    public const KS = 'KS';

    public const KU = 'KU';

    public const KV = 'KV';

    public const KW = 'KW';

    public const KY = 'KY';

    public const LA = 'LA';

    public const LB = 'LB';

    public const LG = 'LG';

    public const LI = 'LI';

    public const LN = 'LN';

    public const LO = 'LO';

    public const LT = 'LT';

    public const LU = 'LU';

    public const LV = 'LV';

    public const MG = 'MG';

    public const MH = 'MH';

    public const MI = 'MI';

    public const MK = 'MK';

    public const ML = 'ML';

    public const MN = 'MN';

    public const MR = 'MR';

    public const MS = 'MS';

    public const MT = 'MT';

    public const MY = 'MY';

    public const NA = 'NA';

    public const NB = 'NB';

    public const ND = 'ND';

    public const NE = 'NE';

    public const NG = 'NG';

    public const NL = 'NL';

    public const NN = 'NN';

    public const NO = 'NO';

    public const NR = 'NR';

    public const NV = 'NV';

    public const NY = 'NY';

    public const OC = 'OC';

    public const OJ = 'OJ';

    public const OM = 'OM';

    public const _OR = 'OR';

    public const OS = 'OS';

    public const PA = 'PA';

    public const PI = 'PI';

    public const PL = 'PL';

    public const PS = 'PS';

    public const PT = 'PT';

    public const QU = 'QU';

    public const RM = 'RM';

    public const RN = 'RN';

    public const RO = 'RO';

    public const RU = 'RU';

    public const RW = 'RW';

    public const SA = 'SA';

    public const SC = 'SC';

    public const SD = 'SD';

    public const SE = 'SE';

    public const SG = 'SG';

    public const SI = 'SI';

    public const SK = 'SK';

    public const SL = 'SL';

    public const SM = 'SM';

    public const SN = 'SN';

    public const SO = 'SO';

    public const SQ = 'SQ';

    public const SR = 'SR';

    public const SS = 'SS';

    public const ST = 'ST';

    public const SU = 'SU';

    public const SV = 'SV';

    public const SW = 'SW';

    public const TA = 'TA';

    public const TE = 'TE';

    public const TG = 'TG';

    public const TH = 'TH';

    public const TI = 'TI';

    public const TK = 'TK';

    public const TL = 'TL';

    public const TN = 'TN';

    public const TO = 'TO';

    public const TR = 'TR';

    public const TS = 'TS';

    public const TT = 'TT';

    public const TW = 'TW';

    public const TY = 'TY';

    public const UG = 'UG';

    public const UK = 'UK';

    public const UR = 'UR';

    public const UZ = 'UZ';

    public const VE = 'VE';

    public const VI = 'VI';

    public const VO = 'VO';

    public const WA = 'WA';

    public const WO = 'WO';

    public const XH = 'XH';

    public const YI = 'YI';

    public const YO = 'YO';

    public const ZA = 'ZA';

    public const ZH = 'ZH';

    public const ZU = 'ZU';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::AA,
            self::AB,
            self::AE,
            self::AF,
            self::AK,
            self::AM,
            self::AN,
            self::AR,
            self::_AS,
            self::AV,
            self::AY,
            self::AZ,
            self::BA,
            self::BE,
            self::BG,
            self::BH,
            self::BI,
            self::BM,
            self::BN,
            self::BO,
            self::BR,
            self::BS,
            self::CA,
            self::CE,
            self::CH,
            self::CO,
            self::CR,
            self::CS,
            self::CU,
            self::CV,
            self::CY,
            self::DA,
            self::DE,
            self::DV,
            self::DZ,
            self::EE,
            self::EL,
            self::EN,
            self::EO,
            self::ES,
            self::ET,
            self::EU,
            self::FA,
            self::FF,
            self::FI,
            self::FJ,
            self::FO,
            self::FR,
            self::FY,
            self::GA,
            self::GD,
            self::GL,
            self::GN,
            self::GU,
            self::GV,
            self::HA,
            self::HE,
            self::HI,
            self::HO,
            self::HR,
            self::HT,
            self::HU,
            self::HY,
            self::HZ,
            self::IA,
            self::ID,
            self::IE,
            self::IG,
            self::II,
            self::IK,
            self::IO,
            self::IS,
            self::IT,
            self::IU,
            self::JA,
            self::JV,
            self::KA,
            self::KG,
            self::KI,
            self::KJ,
            self::KK,
            self::KL,
            self::KM,
            self::KN,
            self::KO,
            self::KR,
            self::KS,
            self::KU,
            self::KV,
            self::KW,
            self::KY,
            self::LA,
            self::LB,
            self::LG,
            self::LI,
            self::LN,
            self::LO,
            self::LT,
            self::LU,
            self::LV,
            self::MG,
            self::MH,
            self::MI,
            self::MK,
            self::ML,
            self::MN,
            self::MR,
            self::MS,
            self::MT,
            self::MY,
            self::NA,
            self::NB,
            self::ND,
            self::NE,
            self::NG,
            self::NL,
            self::NN,
            self::NO,
            self::NR,
            self::NV,
            self::NY,
            self::OC,
            self::OJ,
            self::OM,
            self::_OR,
            self::OS,
            self::PA,
            self::PI,
            self::PL,
            self::PS,
            self::PT,
            self::QU,
            self::RM,
            self::RN,
            self::RO,
            self::RU,
            self::RW,
            self::SA,
            self::SC,
            self::SD,
            self::SE,
            self::SG,
            self::SI,
            self::SK,
            self::SL,
            self::SM,
            self::SN,
            self::SO,
            self::SQ,
            self::SR,
            self::SS,
            self::ST,
            self::SU,
            self::SV,
            self::SW,
            self::TA,
            self::TE,
            self::TG,
            self::TH,
            self::TI,
            self::TK,
            self::TL,
            self::TN,
            self::TO,
            self::TR,
            self::TS,
            self::TT,
            self::TW,
            self::TY,
            self::UG,
            self::UK,
            self::UR,
            self::UZ,
            self::VE,
            self::VI,
            self::VO,
            self::WA,
            self::WO,
            self::XH,
            self::YI,
            self::YO,
            self::ZA,
            self::ZH,
            self::ZU
        ];
    }
}


