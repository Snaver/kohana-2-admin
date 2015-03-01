<?php
/**
 * Humanise class.
 * Helper for "humanising" dates and numbers and other things.
 * @author Ian Tearle @iantearle
 * @license MIT License 2013
 */
class humanise_Core {
	const DAYFORMAT = 'd M, Y';
	const TIMEFORMAT = 'g:ia';
	const DATEFORMAT = 'd/m/Y h:i:sa';

	static function humanise() {
		return $this;
	}

	/**
	 * ordinal function.
	 * Converts an integer to its ordinal as a string. 1 is '1st', 2 is '2nd', etc.
	 *
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	static function ordinal($value) {
		$value = (integer) $value;
		$ord = array('th','st','nd','rd','th','th','th','th','th','th');
		// special cases
		if(in_array($value % 100, array(11, 12, 13))) {
			return sprintf("%d%s", $value, $ord[0]);
		}

		return sprintf('%d%s', $value, $ord[$value % 10]);
	}

	function intcomma($number, $decimals=0, $decimal='.', $separator=',') {
		return number_format($number, $decimals, $decimal, $separator);
	}

	/**
	 * Formats the value like a 'human-readable' number (i.e. '13 K', '4.1 M', '102', etc).
	 *
	 * For example:
	 * If value is 123456789, the output would be 123.5 Million.
	 */
	function intword($number, $units=array(' hundred', ' thousand', ' hundred thousand', ' million', ' billion', ' trillion'), $smallestAccepted=100, $decimals = 1) {
		$number = intval($number);
		if($number < $smallestAccepted) return $number;

		if($number < 100) {
			return Humanise::intcomma($number, $decimals);
		}

		if($number < 1000) {
			$value = $number / 100;
			return Humanise::intcomma($value, $decimals) . $units[0];
		}

		if($number < 100000) {
			$value = $number / 1000.0;
			return Humanise::intcomma($value, $decimals) . $units[1];
		}

		if($number < 1000000) {
			$value = $number / 100000.0;
			return Humanise::intcomma($value, $decimals) . $units[2];
		}

		if($number < 1000000000) {
			$value = $number / 1000000.0;
			return Humanise::intcomma($value, $decimals) . $units[3];
		}

		// senseless on a 32 bit system probably.
		if($number < 1000000000000) {
			$value = $number / 1000000000.0;
			return Humanise::intcomma($value, $decimals) . $units[4];
		}

		if($number < 1000000000000000) {
			$value = $number / 1000000000000.0;
			return Humanise::intcomma($value, $decimals) . $units[5];
		}

		return $number;	// too big.
	}

	/**
	 * naturalDay function.
	 *
	 * Returns a "humanised" day - today, tomorrow, yesterday if relevant,
	 * otherwise it returns the date in $format format
	 *
	 * @access public
	 * @param mixed $timestamp (default: null)
	 * @param mixed $format (default: null)
	 * @return void
	 */
	static function naturalDay($timestamp = null, $format = null) {
		date_default_timezone_set('Europe/London');
		if(is_null($timestamp)) {
			$timestamp = time();
		}
		if(is_null($format)) {
			$format = self::DAYFORMAT;
		}
		$oneday = 60*60*24;
		$today = strtotime('today');
		$tomorrow = $today + $oneday;
		$yesterday = $today - $oneday;
		// if time is 12:00 yesterday or more and less than 12:00 the day after tomorrow
		if($timestamp >= $yesterday && $timestamp <= $tomorrow + $oneday) {
			// if time is greater than today
			if($timestamp > $today) {
				// if time is less than 12:00 tomorrow
				if($timestamp < $tomorrow) {
					return 'Today';
				}
				return 'Tomorrow';
			}
			return 'Yesterday';
		}

		return date($format, $timestamp);
	}

	/**
	 * naturalTime function.
	 *
	 * Returns a "humanised" time - so, if entry was today, it will say "about 16 minutes ago", "about 8 hours ago",
	 * but if it isn't it will return the time formatted in $format format
	 *
	 * @access public
	 * @param mixed $timestamp (default: null)
	 * @param mixed $format (default: null)
	 * @return void
	 */
	static function naturalTime($timestamp = null, $format = null) {
		date_default_timezone_set('Europe/London');
		if(is_null($timestamp)) {
			$timestamp = time();
		}
		if(is_null($format)) {
			$format = self::TIMEFORMAT;
		}
		$now = time();
		$hour = 60*60;
		if(self::naturalDay($timestamp, $format) == 'Today') {
			$hourago = $now - $hour;
			$hourfromnow = $now + $hour;
			// if timestamp passed in was after an hour ago...
			if($timestamp > $hourago) {
				// if timestamp passed in is in the future...
				if($timestamp > $now) {
					// return how many minutes from now
					$seconds = $timestamp - $now;
					$minutes = (integer) round($seconds/60);
					// if more than 60 minutes ago, report in hours
					if($minutes > 60) {
						$hours = round($minutes/60);
						return "in about $hours hours";
					}
					// if it got rounded down to zero, or it was one, report one
					if(!$minutes || $minutes === 1) {
						return "just now";
					}
					return "in about $minutes minutes";
				}
				// return how many minutes from now
				$seconds = $now - $timestamp;
				$minutes = (integer) round($seconds/60);
				// if it got rounded down to zero, or it was one, report one
				if(!$minutes || $minutes === 1) {
					return "just now";
				}
				return "about $minutes minutes ago";
			}
		}

		return date($format, $timestamp);
	}

	/**
	 * strNum function.
	 *
	 * @access public
	 * @param mixed $value
	 * @param string $language (default: 'en')
	 * @return void
	 */
	static function strNum($value, $language='en') {
		$f = new NumberFormatter($language, NumberFormatter::SPELLOUT);

		return $f->format($value);
	}

	/**
	 * fileSize function.
	 * Formats the value like a 'human-readable' file size (i.e. '13 KB', '4.1 MB', '102 bytes', etc).
	 *
	 * For example:
	 * If value is 123456789, the output would be 117.7 MB.
	 *
	 * @access public
	 * @param mixed $filesize
	 * @param mixed $kilo
	 * @param mixed $decimals
	 * @param mixed $decPoint
	 * @param mixed $thousandsSep
	 * @param mixed $suffixSep
	 * @return void
	 */
	static function fileSize($filesize, $kilo=null, $decimals=null, $decPoint=null, $thousandsSep=null, $suffixSep=null) {
		$kilo = ($kilo === null) ? 1024 : $kilo;
		if($filesize <= 0) {
			return '0 bytes';
		}
		if($filesize < $kilo && $decimals === null) {
			$decimals = 0;
		}
		if($suffixSep === null) {
			$suffixSep = ' ';
		}
		if($thousandsSep === null) {
			$thousandsSep = ',';
		}
		if($suffixSep === null) {
			$suffixSep = ' ';
		}

		return self::intword($filesize, array('bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'), 10, 1);
	}

	/**
	 * numberFormat function.
	 * format number by adding thousands separaters and significant digits while rounding
	 *
	 * @access public
	 * @param mixed $number
	 * @param mixed $decimals
	 * @param mixed $decPoint
	 * @param mixed $thousandsSep
	 * @return void
	 */
	private function numberFormat($number, $decimals=null, $decPoint=null, $thousandsSep=null) {
		$decimals = ($decimals === null) ? 2 : abs($decimals);
		$decPoint = ($decPoint === null) ? '.' : $decPoint;
		$thousandsSep = ($thousandsSep === null) ? ',' : $thousandsSep;

		$sign = $number < 0 ? '-' : '';
		$number = abs(+$number || 0);

		$intPart = (int) number_format($number, $decimals) + '';
		$j = count($intPart) > 3 ? count($intPart) % 3 : 0;

		return $sign + ($j ? substr($intPart, 0, $j) + $thousandsSep : '') + str_replace('/(\d{3})(?=\d)/g', '$1' + $thousandsSep, substr($intPart, $j)) + ($decimals ? $decPoint + strrpos(number_format(abs($number - $intPart), $decimals), 2) : '');
	}
}