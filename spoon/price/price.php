<?php

/**
 * Spoon Library
 *
 * This source file is part of the Spoon Library. More information,
 * documentation and tutorials can be found @ http://www.spoon-library.com
 */

/**
 * This price class provides methods to calculate with prices.
 *
 * @package		spoon
 * @subpackage	price
 *
 *
 * @author		Jeroen Desloovere <info@jeroendesloovere.be>
 * @since		1.3.1
 */
class SpoonPrice
{
	/**
	 * Total value
	 *
	 * @param float
	 */
	private $total = 0;


	/**
	 * VAT included as default
	 *
	 * @param bool
	 */
	private $VATIncluded = true;


	/**
	 * Class constructor.
	 *
	 * @param	float[optional] $value			The value you want to calculate with.
	 * @param	bool[optional] $VATIncluded 	This overrides the default VAT included value. VAT default included.
	 */
	public function __construct($value = null, $VATIncluded = true)
	{
		// redefine VAT included
		$this->setVATIncluded((bool) $VATIncluded);

		// add value
		if(isset($value)) $this->add($value, (bool) $VATIncluded);
	}


	/**
	 * Add a value to the total
	 *
	 * @param	int $value				The value that will be added.
	 * @param	bool $VATIncluded		Is the VAT included in the value?
	 */
	public function add($value, $VATIncluded = null)
	{
		// add VAT to this value
		if(!$this->isVATIncluded($VATIncluded)) $value = SpoonCurrency::addVAT((float) $value);

		// redefine total : add value
		$this->total += (float) $value;
	}


	/**
	 * Delete a value from the total
	 *
	 * @param	int $value				The value that will be deleted.
	 * @param	bool $VATIncluded 		Is the VAT included in the value?
	 */
	public function delete($value, $VATIncluded = null)
	{
		// add VAT to this value
		if(!$this->isVATIncluded($VATIncluded)) $value = SpoonCurrency::addVAT((float) $value);

		// redefine total : delete value
		$this->total -= (float) $value;
	}


	/**
	 * Divide the total with a value
	 *
	 * @param 	int $value
	 */
	public function divide($value)
	{
		// redefine total
		$this->total = $this->total / (float) $value;
	}


	/**
	 * Get total
	 *
	 * @return	float
	 * @param	bool $VATIncluded 		Does the VAT has to be included in the value?
	 */
	public function get($VATIncluded = null)
	{	
		// redefine variables
		$total = (float) $this->total;

		// default == false && you want to exclude the VAT
		if($VATIncluded === false) $total = SpoonCurrency::deleteVAT($total);

		// return rounded total
		return SpoonCurrency::getRoundedValue($total);
	}


	/**
	 * Get VAT value from total
	 *
	 * @return	float 		The VAT from the total value.
	 */
	public function getVAT()
	{	
		// define total
		$VAT = SpoonCurrency::getVAT($this->total);

		// return VAT
		return SpoonCurrency::getRoundedValue($VAT);
	}


	/**
	 * Is VAT included as default?
	 *
	 * @return	bool
	 * @param	bool $VATIncluded		Does the VAT has to be included in the value?
	 */
	public function isVATIncluded($VATIncluded = null)
	{
		// is VAT included per default or not
		return ($VATIncluded !== null) ? (bool) $VATIncluded : $this->VATIncluded;
	}


	/**
	 * Set VAT included or not
	 *
	 * @param	bool $VATIncluded		Does the VAT has to be included in the value?
	 */
	public function setVATIncluded($VATIncluded = null)
	{
		// redefine VAT included per default or not
		$this->VATIncluded = (bool) $VATIncluded;
	}


	/**
	 * Multiply the total
	 *
	 * @param	int $value		The value the total will be multiplied by.
	 */
	public function times($value)
	{
		// redefine total
		$this->total = $this->total * (float) $value;
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
class SpoonPriceException extends SpoonException {}