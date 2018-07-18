<?php

namespace App\DatetimeChecker;

/**
 * Check if lifetime of short link expires or not.
 */
class DatetimeChecker
{
	public function isExpire(\DateTimeInterface $date)
	{
		date_default_timezone_set('Europe/Kiev');

		$date = date_format($date, 'd-m-Y H:i');
		$today = date("Y-m-d H:i");

		if($today > $date){

			return true;

		} else {

			return false;
		}
	}

}