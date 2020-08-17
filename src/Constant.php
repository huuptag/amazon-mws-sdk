<?php
/**
 * File Constant.php
 * Created by HuuLe at 10/08/2020 16:43
 */

namespace HuuLe\AmazonSDK;

class Constant
{
    const MWS_SERVICE_URL = 'https://mws.amazonservices.com';
    const MWS_ORDER_SERVICE_URL = 'https://mws.amazonservices.com/Orders/2013-09-01';
    const MWS_PRODUCT_SERVICE_URL = 'https://mws.amazonservices.com/Products/2011-10-01';
    const MWS_APPLICATION_NAME = 'Amazon SDK';
    const MWS_APPLICATION_VERSION = '1.0';
    const MWS_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

    // Response Parser: default date time format
    const DEFAULT_TIMEZONE = 'UTC';
    const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    // Status enum
    const STATUS_SUBMITTED                      = '_SUBMITTED_';
    const STATUS_IN_PROGRESS                    = '_IN_PROGRESS_';
    const STATUS_CANCELLED                      = '_CANCELLED_';
    const STATUS_DONE                           = '_DONE_';
    const STATUS_DONE_NO_DATA                   = '_DONE_NO_DATA_';
    const STATUS_IN_SAFETY_NET                  = '_IN_SAFETY_NET_';
    const STATUS_UNCONFIRMED                    = '_UNCONFIRMED_';
    const STATUS_AWAITING_ASYNCHRONOUS_REPLY    = '_AWAITING_ASYNCHRONOUS_REPLY_';

    // Schedule enum
    const SCHEDULE_15_MINUTES_  = '_15_MINUTES_';
    const SCHEDULE_30_MINUTES_  = '_30_MINUTES_';
    const SCHEDULE_1_HOUR_      = '_1_HOUR_';
    const SCHEDULE_2_HOURS_     = '_2_HOURS_';
    const SCHEDULE_4_HOURS_     = '_4_HOURS_';
    const SCHEDULE_8_HOURS_     = '_8_HOURS_';
    const SCHEDULE_12_HOURS_    = '_12_HOURS_';
    const SCHEDULE_1_DAY_       = '_1_DAY_';
    const SCHEDULE_2_DAYS_      = '_2_DAYS_';
    const SCHEDULE_72_HOURS_    = '_72_HOURS_';
    const SCHEDULE_1_WEEK_      = '_1_WEEK_';
    const SCHEDULE_14_DAYS_     = '_14_DAYS_';
    const SCHEDULE_15_DAYS_     = '_15_DAYS_';
    const SCHEDULE_30_DAYS_     = '_30_DAYS_';
    const SCHEDULE_NEVER_       = '_NEVER_';

    // ID Type enum
    const ID_TYPE_ASIN          = 'ASIN';
    const ID_TYPE_GCID          = 'GCID';
    const ID_TYPE_SELLER_SKU    = 'SellerSKU';
    const ID_TYPE_UPC           = 'UPC';
    const ID_TYPE_EAN           = 'EAN';
    const ID_TYPE_ISBN          = 'ISBN';
    const ID_TYPE_JAN           = 'JAN';

    // Item Condition enum
    const ITEM_CONDITION_ANY            = 'Any';
    const ITEM_CONDITION_NEW            = 'New';
    const ITEM_CONDITION_USED           = 'Used';
    const ITEM_CONDITION_COLLECTIBLE    = 'Collectible';
    const ITEM_CONDITION_REFURBISHED    = 'Refurbished';
    const ITEM_CONDITION_CLUB           = 'Club';

    // Currency Code enum
    const CURRENCY_CODE_USD = 'USD';
    const CURRENCY_CODE_EUR = 'EUR';
    const CURRENCY_CODE_GBP = 'GBP';
    const CURRENCY_CODE_RMB = 'RMB';
    const CURRENCY_CODE_INR = 'INR';
    const CURRENCY_CODE_JPY = 'JPY';
    const CURRENCY_CODE_CAD = 'CAD';
    const CURRENCY_CODE_MXN = 'MXN';
}
