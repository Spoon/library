<?php

/**
 * Spoon Library
 *
 * This source file is part of the Spoon Library. More information,
 * documentation and tutorials can be found @ http://www.spoon-library.com
 *
 * @package		spoon
 * @subpackage	http
 *
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		0.1.1
 */


/**
 * This class is used to manipulate strings
 *
 * @package		spoon
 * @subpackage	text
 *
 *
 * @author		Jack Lightbody <jack.lightbody@gmail.com>
 * @since		1.3.0
 */
class SpoonText
{
	/**
	 * Strips the tags from a string
	 *
	 * @return	string $string		The tag stripped string
	 * @param	string $string		The string you want to strip tags from
	 * @param	string $allowed		The tags that are allowed to be in the text
	 */
	public static function stripTags($string,$allowed) 
	{
		if ($string == null) {
			return ""; //if we don't return a string some dbs might insert a 0
		}
     	return strip_tags($string, $allowed);
	}
	/**
	 * Camelcases a string
	 *
	 * @return	string $string		The camelcased string
	 * @param	string $string		The snake cased string that you want to camelcase
	 */
	public static function camelcase($string) 
	{
		//remove all spaces
		$string = str_replace(array('_', '-', '/'), ' ', $string);
		
		//capitalize first
		$string=ucwords($string);
		
		//remove spaces
		$string = str_replace(' ', '', $string);
		
		return $string;		
	}
	 /**
	 * Unamelcases a string into snake_case
	 *
	 * @return	string $string		The snake_cased string
	 * @param	string $string		The camelcased string that you want to snake_case
	 */
     public static function uncamelcaseSnake($string) 
     {
     	//get all caps and insert _ between
     	$string=preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);
     	
     	//lower case
		$string = strtolower($string);	
		
		return $string;
	}
}


/**
 * This exception is used to handle text related exceptions.
 *
 * @package		spoon
 * @subpackage	text
 *
 *
 * @author		Jack Lightbody <jack.lightbody@gmail.com>
 * @since		1.3.0
 */
class SpoonTextException extends SpoonException {}
