<?php
 
use Carbon\Carbon;

/**
 *  
*/
function formatDateTime($datetime, $format)
{
    var_dump($datetime);
    return Carbon::createFromFormat($format, $datetime);
}

/**
 *  2018-02-12T02:30:00.000Z
 *  day month date year Hour:min:second GMT+0800 (Singapore Standard Time)
 */
function mergeDateTime($time, $date)
{
    new Date();
    return Carbon::createFromFormat();
}