<?php

/**
 * File DateTime.php
 * Created by HuuLe at 12/08/2020 10:29
 */

namespace HuuLe\AmazonSDK;

use DateTime;
use DateTimeZone;

trait Helpers
{
    /**
     * Make Date Time function
     * @param string|int $time (default is now)
     * @param string $timezone
     * @return DateTime
     * @author HuuLe
     * @throws \Exception
     */
    public function makeDateTime($time = 'now', $timezone = Constant::MWS_DEFAULT_TIMEZONE)
    {
        if (empty($timezone))
            $timezone = date_default_timezone_get();
        return new DateTime($time, new DateTimeZone($timezone));
    }
}
