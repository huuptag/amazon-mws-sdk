<?php
/**
 * File Constant.php
 * Created by HuuLe at 10/08/2020 16:43
 */

namespace HuuLe\AmazonSDK;

class Constant
{
    const MWS_APPLICATION_NAME = 'HuuLe Amazon SDK';
    const MWS_APPLICATION_VERSION = '1.0';
    const MWS_SERVICE_URL = 'https://mws.amazonservices.com';
    const MWS_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';
    const MWS_DEFAULT_TIMEZONE = 'UTC';

    const MWS_DEBUG = true;

    // Response Parser: default date time format
    const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

    // Status
    const STATUS_SUBMITTED = '_SUBMITTED_';
    const STATUS_IN_PROGRESS = '_IN_PROGRESS_';
    const STATUS_CANCELLED = '_CANCELLED_';
    const STATUS_DONE = '_DONE_';
    const STATUS_DONE_NO_DATA = '_DONE_NO_DATA_';
}
