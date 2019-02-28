<?php
 
use Carbon\Carbon;

/**
 *  */
function formatDateTime($datetime, $format)
{
    return Carbon::createFromFormat($format, $datetime);
}

/**
 *  Thu Feb 15 2018 14:00:00 GMT+0800 (Singapore Standard Time)
 *  day month date year Hour:min:second GMT+0800 (Singapore Standard Time)
 */
function mergeDateTime($time, $date)
{
    new Date();
    return Carbon::createFromFormat();
}