<?php
/**
 * CountryCode
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * finAPI Web Form 2.0
 *
 * The following pages give you some general information on how to use our APIs.<br/>The actual API services documentation then follows further below. You can use the menu to jump between API sections.<br/><br/>This page has a built-in HTTP(S) client, so you can test the services directly from within this page, by filling in the request parameters and/or body in the respective services, and then hitting the TRY button. Note that you need to be authorized to make a successful API call. To authorize, refer to the '<a target='_blank' href='https://docs.finapi.io/?product=access#tag--Authorization'>Authorization</a>' section of Access, or in case you already have a valid user token, just use the QUICK AUTH on the left.<br/>Please also remember that all user management functions should be looked up in <a target='_blank' href='https://docs.finapi.io/?product=access'>Access</a>.<br/><br/>You should also check out the <a target='_blank' href='https://documentation.finapi.io/webform/'>Web Form 2.0 Public Documentation</a> as well as <a target='_blank' href='https://documentation.finapi.io/access/'>Access Public Documentation</a> for more information. If you need any help with the API, contact <a href='mailto:support@finapi.io'>support@finapi.io</a>.<br/><h2 id=\"general-information\">General information</h2><h3 id=\"general-request-ids\"><strong>Request IDs</strong></h3>With any API call, you can pass a request ID via a header with name \"X-Request-Id\". The request ID can be an arbitrary string with up to 255 characters. Passing a longer string will result in an error.<br/><br/>If you don't pass a request ID for a call, finAPI will generate a random ID internally.<br/><br/>The request ID is always returned back in the response of a service, as a header with name \"X-Request-Id\".<br/><br/>We highly recommend to always pass a (preferably unique) request ID, and include it into your client application logs whenever you make a request or receive a response(especially in the case of an error response). finAPI is also logging request IDs on its end. Having a request ID can help the finAPI support team to work more efficiently and solve tickets faster.<h3 id=\"type-coercion\"><strong>Type Coercion</strong></h3>In order to ease the integration for some languages, which do not natively support high precision number representations, Web Form 2.0 API supports relax type binding for the openAPI type <code>number</code>, which is used for money amount fields. If you use one of those languages, to avoid precision errors that can appear from <code>float</code> values, you can pass the amount as a <code>string</code>.<h3 id=\"general-faq\"><strong>FAQ</strong></h3><strong>Is there a finAPI SDK?</strong><br/>Currently we do not offer a native SDK, but there is the option to generate an SDKfor almost any target language via OpenAPI. Use the 'Download SDK' button on this page for SDK generation.<br/><br/><strong>Why do I need to keep authorizing when calling services on this page?</strong><br/>This page is a \"one-page-app\". Reloading the page resets the OAuth authorization context. There is generally no need to reload the page, so just don't do it and your authorization will persist.
 *
 * The version of the OpenAPI document: 2.779.0
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.5.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace FinApi\Client\Model;
use \FinApi\Client\ObjectSerializer;

/**
 * CountryCode Class Doc Comment
 *
 * @category Class
 * @description &lt;strong&gt;Country code:&lt;/strong&gt; The ISO 3166 ALPHA-2 country code of the counterparty&#39;s address.
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CountryCode
{
    /**
     * Possible values of this enum
     */
    public const AD = 'AD';

    public const AE = 'AE';

    public const AF = 'AF';

    public const AG = 'AG';

    public const AI = 'AI';

    public const AL = 'AL';

    public const AM = 'AM';

    public const AO = 'AO';

    public const AQ = 'AQ';

    public const AR = 'AR';

    public const _AS = 'AS';

    public const AT = 'AT';

    public const AU = 'AU';

    public const AW = 'AW';

    public const AX = 'AX';

    public const AZ = 'AZ';

    public const BA = 'BA';

    public const BB = 'BB';

    public const BD = 'BD';

    public const BE = 'BE';

    public const BF = 'BF';

    public const BG = 'BG';

    public const BH = 'BH';

    public const BI = 'BI';

    public const BJ = 'BJ';

    public const BL = 'BL';

    public const BM = 'BM';

    public const BN = 'BN';

    public const BO = 'BO';

    public const BQ = 'BQ';

    public const BR = 'BR';

    public const BS = 'BS';

    public const BT = 'BT';

    public const BV = 'BV';

    public const BW = 'BW';

    public const BY = 'BY';

    public const BZ = 'BZ';

    public const CA = 'CA';

    public const CC = 'CC';

    public const CD = 'CD';

    public const CF = 'CF';

    public const CG = 'CG';

    public const CH = 'CH';

    public const CI = 'CI';

    public const CK = 'CK';

    public const CL = 'CL';

    public const CM = 'CM';

    public const CN = 'CN';

    public const CO = 'CO';

    public const CR = 'CR';

    public const CU = 'CU';

    public const CV = 'CV';

    public const CW = 'CW';

    public const CX = 'CX';

    public const CY = 'CY';

    public const CZ = 'CZ';

    public const DE = 'DE';

    public const DJ = 'DJ';

    public const DK = 'DK';

    public const DM = 'DM';

    public const _DO = 'DO';

    public const DZ = 'DZ';

    public const EC = 'EC';

    public const EE = 'EE';

    public const EG = 'EG';

    public const EH = 'EH';

    public const ER = 'ER';

    public const ES = 'ES';

    public const ET = 'ET';

    public const FI = 'FI';

    public const FJ = 'FJ';

    public const FK = 'FK';

    public const FM = 'FM';

    public const FO = 'FO';

    public const FR = 'FR';

    public const GA = 'GA';

    public const GB = 'GB';

    public const GD = 'GD';

    public const GE = 'GE';

    public const GF = 'GF';

    public const GG = 'GG';

    public const GH = 'GH';

    public const GI = 'GI';

    public const GL = 'GL';

    public const GM = 'GM';

    public const GN = 'GN';

    public const GP = 'GP';

    public const GQ = 'GQ';

    public const GR = 'GR';

    public const GS = 'GS';

    public const GT = 'GT';

    public const GU = 'GU';

    public const GW = 'GW';

    public const GY = 'GY';

    public const HK = 'HK';

    public const HM = 'HM';

    public const HN = 'HN';

    public const HR = 'HR';

    public const HT = 'HT';

    public const HU = 'HU';

    public const ID = 'ID';

    public const IE = 'IE';

    public const IL = 'IL';

    public const IM = 'IM';

    public const IN = 'IN';

    public const IO = 'IO';

    public const IQ = 'IQ';

    public const IR = 'IR';

    public const IS = 'IS';

    public const IT = 'IT';

    public const JE = 'JE';

    public const JM = 'JM';

    public const JO = 'JO';

    public const JP = 'JP';

    public const KE = 'KE';

    public const KG = 'KG';

    public const KH = 'KH';

    public const KI = 'KI';

    public const KM = 'KM';

    public const KN = 'KN';

    public const KP = 'KP';

    public const KR = 'KR';

    public const KW = 'KW';

    public const KY = 'KY';

    public const KZ = 'KZ';

    public const LA = 'LA';

    public const LB = 'LB';

    public const LC = 'LC';

    public const LI = 'LI';

    public const LK = 'LK';

    public const LR = 'LR';

    public const LS = 'LS';

    public const LT = 'LT';

    public const LU = 'LU';

    public const LV = 'LV';

    public const LY = 'LY';

    public const MA = 'MA';

    public const MC = 'MC';

    public const MD = 'MD';

    public const ME = 'ME';

    public const MF = 'MF';

    public const MG = 'MG';

    public const MH = 'MH';

    public const MK = 'MK';

    public const ML = 'ML';

    public const MM = 'MM';

    public const MN = 'MN';

    public const MO = 'MO';

    public const MP = 'MP';

    public const MQ = 'MQ';

    public const MR = 'MR';

    public const MS = 'MS';

    public const MT = 'MT';

    public const MU = 'MU';

    public const MV = 'MV';

    public const MW = 'MW';

    public const MX = 'MX';

    public const MY = 'MY';

    public const MZ = 'MZ';

    public const NA = 'NA';

    public const NC = 'NC';

    public const NE = 'NE';

    public const NF = 'NF';

    public const NG = 'NG';

    public const NI = 'NI';

    public const NL = 'NL';

    public const NO = 'NO';

    public const NP = 'NP';

    public const NR = 'NR';

    public const NU = 'NU';

    public const NZ = 'NZ';

    public const OM = 'OM';

    public const PA = 'PA';

    public const PE = 'PE';

    public const PF = 'PF';

    public const PG = 'PG';

    public const PH = 'PH';

    public const PK = 'PK';

    public const PL = 'PL';

    public const PM = 'PM';

    public const PN = 'PN';

    public const PR = 'PR';

    public const PS = 'PS';

    public const PT = 'PT';

    public const PW = 'PW';

    public const PY = 'PY';

    public const QA = 'QA';

    public const RE = 'RE';

    public const RO = 'RO';

    public const RS = 'RS';

    public const RU = 'RU';

    public const RW = 'RW';

    public const SA = 'SA';

    public const SB = 'SB';

    public const SC = 'SC';

    public const SD = 'SD';

    public const SE = 'SE';

    public const SG = 'SG';

    public const SH = 'SH';

    public const SI = 'SI';

    public const SJ = 'SJ';

    public const SK = 'SK';

    public const SL = 'SL';

    public const SM = 'SM';

    public const SN = 'SN';

    public const SO = 'SO';

    public const SR = 'SR';

    public const SS = 'SS';

    public const ST = 'ST';

    public const SV = 'SV';

    public const SX = 'SX';

    public const SY = 'SY';

    public const SZ = 'SZ';

    public const TC = 'TC';

    public const TD = 'TD';

    public const TF = 'TF';

    public const TG = 'TG';

    public const TH = 'TH';

    public const TJ = 'TJ';

    public const TK = 'TK';

    public const TL = 'TL';

    public const TM = 'TM';

    public const TN = 'TN';

    public const TO = 'TO';

    public const TR = 'TR';

    public const TT = 'TT';

    public const TV = 'TV';

    public const TW = 'TW';

    public const TZ = 'TZ';

    public const UA = 'UA';

    public const UG = 'UG';

    public const UM = 'UM';

    public const US = 'US';

    public const UY = 'UY';

    public const UZ = 'UZ';

    public const VA = 'VA';

    public const VC = 'VC';

    public const VE = 'VE';

    public const VG = 'VG';

    public const VI = 'VI';

    public const VN = 'VN';

    public const VU = 'VU';

    public const WF = 'WF';

    public const WS = 'WS';

    public const XK = 'XK';

    public const YE = 'YE';

    public const YT = 'YT';

    public const ZA = 'ZA';

    public const ZM = 'ZM';

    public const ZW = 'ZW';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::AD,
            self::AE,
            self::AF,
            self::AG,
            self::AI,
            self::AL,
            self::AM,
            self::AO,
            self::AQ,
            self::AR,
            self::_AS,
            self::AT,
            self::AU,
            self::AW,
            self::AX,
            self::AZ,
            self::BA,
            self::BB,
            self::BD,
            self::BE,
            self::BF,
            self::BG,
            self::BH,
            self::BI,
            self::BJ,
            self::BL,
            self::BM,
            self::BN,
            self::BO,
            self::BQ,
            self::BR,
            self::BS,
            self::BT,
            self::BV,
            self::BW,
            self::BY,
            self::BZ,
            self::CA,
            self::CC,
            self::CD,
            self::CF,
            self::CG,
            self::CH,
            self::CI,
            self::CK,
            self::CL,
            self::CM,
            self::CN,
            self::CO,
            self::CR,
            self::CU,
            self::CV,
            self::CW,
            self::CX,
            self::CY,
            self::CZ,
            self::DE,
            self::DJ,
            self::DK,
            self::DM,
            self::_DO,
            self::DZ,
            self::EC,
            self::EE,
            self::EG,
            self::EH,
            self::ER,
            self::ES,
            self::ET,
            self::FI,
            self::FJ,
            self::FK,
            self::FM,
            self::FO,
            self::FR,
            self::GA,
            self::GB,
            self::GD,
            self::GE,
            self::GF,
            self::GG,
            self::GH,
            self::GI,
            self::GL,
            self::GM,
            self::GN,
            self::GP,
            self::GQ,
            self::GR,
            self::GS,
            self::GT,
            self::GU,
            self::GW,
            self::GY,
            self::HK,
            self::HM,
            self::HN,
            self::HR,
            self::HT,
            self::HU,
            self::ID,
            self::IE,
            self::IL,
            self::IM,
            self::IN,
            self::IO,
            self::IQ,
            self::IR,
            self::IS,
            self::IT,
            self::JE,
            self::JM,
            self::JO,
            self::JP,
            self::KE,
            self::KG,
            self::KH,
            self::KI,
            self::KM,
            self::KN,
            self::KP,
            self::KR,
            self::KW,
            self::KY,
            self::KZ,
            self::LA,
            self::LB,
            self::LC,
            self::LI,
            self::LK,
            self::LR,
            self::LS,
            self::LT,
            self::LU,
            self::LV,
            self::LY,
            self::MA,
            self::MC,
            self::MD,
            self::ME,
            self::MF,
            self::MG,
            self::MH,
            self::MK,
            self::ML,
            self::MM,
            self::MN,
            self::MO,
            self::MP,
            self::MQ,
            self::MR,
            self::MS,
            self::MT,
            self::MU,
            self::MV,
            self::MW,
            self::MX,
            self::MY,
            self::MZ,
            self::NA,
            self::NC,
            self::NE,
            self::NF,
            self::NG,
            self::NI,
            self::NL,
            self::NO,
            self::NP,
            self::NR,
            self::NU,
            self::NZ,
            self::OM,
            self::PA,
            self::PE,
            self::PF,
            self::PG,
            self::PH,
            self::PK,
            self::PL,
            self::PM,
            self::PN,
            self::PR,
            self::PS,
            self::PT,
            self::PW,
            self::PY,
            self::QA,
            self::RE,
            self::RO,
            self::RS,
            self::RU,
            self::RW,
            self::SA,
            self::SB,
            self::SC,
            self::SD,
            self::SE,
            self::SG,
            self::SH,
            self::SI,
            self::SJ,
            self::SK,
            self::SL,
            self::SM,
            self::SN,
            self::SO,
            self::SR,
            self::SS,
            self::ST,
            self::SV,
            self::SX,
            self::SY,
            self::SZ,
            self::TC,
            self::TD,
            self::TF,
            self::TG,
            self::TH,
            self::TJ,
            self::TK,
            self::TL,
            self::TM,
            self::TN,
            self::TO,
            self::TR,
            self::TT,
            self::TV,
            self::TW,
            self::TZ,
            self::UA,
            self::UG,
            self::UM,
            self::US,
            self::UY,
            self::UZ,
            self::VA,
            self::VC,
            self::VE,
            self::VG,
            self::VI,
            self::VN,
            self::VU,
            self::WF,
            self::WS,
            self::XK,
            self::YE,
            self::YT,
            self::ZA,
            self::ZM,
            self::ZW
        ];
    }
}


