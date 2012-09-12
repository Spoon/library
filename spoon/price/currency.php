<?php

/**
 * Spoon Library
 *
 * This source file is part of the Spoon Library. More information,
 * documentation and tutorials can be found @ http://www.spoon-library.com
 */

/**
 * This currency class provides methods used for currencies.
 *
 * @package		spoon
 * @subpackage	price
 *
 *
 * @author		Jeroen Desloovere <info@jeroendesloovere.be>
 * @since		1.3.1
 *
 * @todo		Write external library for converting prices from one currency to another.
 */
class SpoonCurrency
{
	/**
	 * Values after the comma allowed in result
	 *
	 * @param int
	 */
	private static $decimals = 2;


	/**
	 * Decimal mode - http://php.net/manual/en/function.round.php
	 *
	 * @param string
	 */
	private static $decimalMode = PHP_ROUND_HALF_UP;
	
	
	/**
	 * Decimal modes - http://php.net/manual/en/function.round.php
	 *
	 * @param array
	 */
	private static $decimalModes = array(PHP_ROUND_HALF_UP, PHP_ROUND_HALF_DOWN, PHP_ROUND_HALF_EVEN, PHP_ROUND_HALF_ODD);


	/**
	 * Decimal separator
	 *
	 * @param string
	 */
	private static $decimalSeparator = ',';


	/**
	 * Thousands separator
	 *
	 * @param string
	 */
	private static $thousandsSeparator = '';


	/**
	 * VAT, Value Added Taxes
	 *
	 * @param float
	 */
	private static $VAT = 0.21;


	/**
	 * Add VAT to the value
	 *
	 * @return	float  			The value with the VAT included.
	 * @param	float $value  	The value with the VAT excluded.
	 */
	public static function addVAT($value)
	{
		// redefine value
		$value = (float) $value * (1 + self::$VAT);

		// return the rounded value
		return self::getRoundedValue($value);
	}


	/**
	 * Delete VAT from the value
	 *
	 * @return	float  			The value with the VAT excluded.
	 * @param	float $value  	The value with the VAT included.
	 */
	public static function deleteVAT($value)
	{
		// redefine value
		$value = (float) $value / (1 + self::$VAT);

		// return the rounded value
		return self::getRoundedValue($value);
	}


	/**
	 * Get formatted value
	 *
	 * @return	float
	 * @param	float $value 							The value you want to get converted.						
	 * @param	int[optional] $decimals 				The amount of decimals.
	 * @param	string[optional] $decimalSeparator 		The separator for decimals.
	 * @param	string[optional] $thousandsSeparator	The thousands separator.
	 */
	public static function get($value, $decimals = null, $decimalSeparator = null, $thousandsSeparator = null)
	{
		// define variables
		$decimals = ($decimals !== null) ? (int) $decimals : self::$decimals;
		$decimalSeparator = ($decimalSeparator !== null) ? (string) $decimalSeparator : self::$decimalSeparator;
		$thousandsSeparator = ($thousandsSeparator !== null) ? (string) $thousandsSeparator : self::$thousandsSeparator;

		// return formatted value
		return number_format((float) $value, $decimals, $decimalSeparator, $thousandsSeparator);
	}


	/**
	 * Get value ready for database input
	 *
	 * @return	float
	 * @param 	string $value					The value to validate.
	 * @param	string[optional] $error			The error message to set.
	 * @param	int[optional] $decimals			The allowed number of digits after the decimal separator. Defaults to 2.
	 * @param	bool[optional] $allowCommas		Do you want to use commas as a decimal separator? Defaults to true.
	 */
	public static function getValue($value, $decimals = 2, $allowCommas = true)
	{
		// replace commas if needed
		if($allowCommas) $value = str_replace(',', '.', (string) $value);

		// trim zero characters after the decimal separator
		if(mb_strpos($value, '.') !== false)
		{
			$value = rtrim($value, '0');
			if(substr($value, -1) == '.') $value = substr($value, 0, -1);
		}

		// get rounded value
		$value = self::getRoundedValue($value, (int) $decimals);

		// return database clean value
		return $value;
	}


	/**
	 * Get VAT from value
	 *
	 * @result	float $value	The VAT from the price.
	 * @param	float $value  	Value with the VAT included.
	 */
	public static function getVAT($value)
	{
		// redefine value
		$value = $value - self::deleteVAT($value);

		// return value
		return $value;
	}


	/**
	 * Get rounded value
	 *
	 * @return	float  			The rounded value.
	 * @param	float $value  	The value with the VAT excluded.
	 */
	public static function getRoundedValue($value, $decimals = null)
	{
		// redefine decimals
		$decimals = ($decimals !== null) ? (int) $decimals : self::$decimals;

		// return rounded value. Mode doesn't exist in the round function in PHP < 5.2.7
		if(phpversion() < '5.2.7') return round($value, $decimals);

		// return rounded value.
		else return round($value, $decimals, self::$decimalMode);
	}


	/**
	 * Set decimal mode
	 *
	 * @param	string $value	The amount of decimals allowed after the comma.
	 */
	public static function setDecimalMode($value)
	{
		// value does not exists
		if(!in_array($value, self::$decimalModes))
		{
			throw new SpoonCurrencyException('The decimal mode does not exists, check http://php.net/manual/en/function.round.php for more info.');
		}

		// redefine decimal mode
		else self::$decimalMode = $value;
	}


	/**
	 * Set decimal separator
	 *
	 * @param	string $value	The separator you want to use for decimals.
	 */
	public static function setDecimalSeparator($value)
	{
		// redefine decimal separator
		self::$decimalSeparator = (string) $value;
	}


	/**
	 * Set decimals
	 *
	 * @param	int $value	The amount of decimals allowed after the comma.
	 */
	public static function setDecimals($value)
	{
		// redefine decimals
		self::$decimals = (int) $value;
	}


	/**
	 * Set thousands separator
	 *
	 * @param	string $value	The separator you want to use for thousands.
	 */
	public static function setThousandsSeparator($value)
	{
		// redefine thousands separator
		self::$thousandsSeparator = (string) $value;
	}


	/**
	 * Set VAT
	 *
	 * @param	float $value	The VAT percentage
	 */
	public static function setVAT($value)
	{
		// redefine VAT
		self::$VAT = (float) ($value / 100);
	}
}

/**
 * This exception is used to handle price related exceptions.
 *
 * @package		spoon
 * @subpackage	price
 *
 *
 * @author		Jeroen Desloovere <info@jeroendesloovere.be>
 * @since		1.3.1
 */
class SpoonCurrencyException extends SpoonException {}
