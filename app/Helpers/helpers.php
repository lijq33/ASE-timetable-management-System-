<?php
 
use Carbon\Carbon;

/**
 *   $timetable['StartTime'] = "Wed Feb 14 2018 15:00:00 GMT+0800 (Singapore Standard Time)";
 *   Wed Feb 14 2018 16:00:00 GMT+0800 (Singapore Standard Time)";
 *  day month date year Hour:min:second GMT+0800 (Singapore Standard Time)
 */
function mergeDateTime($time, $date)
{
    $datetime = Carbon::parse("$date . $time")->format('D M d Y H:i:s') . " GMT+0800 (Singapore Standard Time)";
    return $datetime;
}