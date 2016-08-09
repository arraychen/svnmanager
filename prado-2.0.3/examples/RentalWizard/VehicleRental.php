<?php
/**
 * Calcuates a vehicle rental.
 * @author $Author: weizhuo $
 * @version $Id: VehicleRental.php,v 1.2 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * VehicleRental class.
 * 
 * Calculate the rental taxes, options, and costs.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Sat Jan 29 22:02:36 EST 2005
 * @package prado.examples
 */
class VehicleRental
{
	/**
	 * Vehicle details.
	 * @var array 
	 */
	private $vehicle;
	
	/**
	 * Days hired
	 * @var int 
	 */
	private $days;
	
	/**
	 * Rental data, e.g. form data.
	 * @var type 
	 */
	private $data;
		
	/**
	 * Constructor.
	 * @param array the vehicle for hire
	 * @param array the rental data from the user.
	 */
	function __construct($vehicle, $data)
	{
		$this->vehicle = $vehicle;	
		$this->data = $data;
	}

	/**
	 * Return the number of days requested.
	 * @return int days. 
	 */
	function getDays()
	{
		if(empty($this->days))
			$this->days = ceil(($this->data['return'] - $this->data['pickup'])/86400);

		return $this->days;
	}
	
	/**
	 * Calculate the base rental price
	 * @return float base price 
	 */
	function getBasePrice()
	{
		return $this->getDays() * $this->getBaseRate();
	}
	
	/**
	 * Get the vehicle base rental rate per day.
	 * @return float base rate per day. 
	 */
	function getBaseRate()
	{

		return $this->vehicle['Price'];
	}
	
	/**
	 * Get the maximum free KM per day.
	 * @return int max free KM/day 
	 */
	function getMaxKM()
	{
		return 200;
	}
	
	/**
	 * Get the excess rate for each KM over the max free KM/day
	 * @return float excess rate for over the max KM/day 
	 */
	function getPerKMRate()
	{
		return 0.2;
	}
	
	/**
	 * Returns the premium cover price for this rental.
	 * @param parameter
	 * @return float premium price. 
	 */
	function getPremiumCoverPrice()
	{
		if($this->data['PremiumProtect'])
			 return $this->getPremiumCoverRate() * $this->getDays();
		else
			return 0;
	}
	
	/**
	 * Get the premium rate.
	 * @return float premium rate. 
	 */
	function getPremiumCoverRate()
	{
		return 10 + intval(0.15 * $this->vehicle['Price']);
	}
	
	/**
	 * Get the tax for this rental.
	 * @return float tax 
	 */
	function getTax()
	{
		return 0.1 * $this->getPreTaxTotal();
	}
	
	/**
	 * Get a list of stamp duties involved with this rental.
	 * @return array list of stamp duties. 
	 */
	function getStampDuty()
	{
		return array(8, 1.89);
	}
	
	/**
	 * Get a list of options and their price.
	 * @return array list of options and its price. 
	 */
	function getOptions()
	{
		$babySeat = $this->getDays() * 5;

		//maxium baby seat charge each
		if($babySeat > 33) $babySeat = 33;
		
		$babySeat = $babySeat * $this->data['BabySeatQty'];
		
		if(!$this->data['BabySeat']) $babySeat = 0;
		
		return array('BabySeat' => $babySeat);
	}
	
	/**
	 * Get the total before tax.
	 * @return float pre-tax total. 
	 */
	function getPreTaxTotal()
	{
		$options = $this->getOptions();
		
		$subtotal = $this->getBasePrice()
					+ $this->getPremiumCoverPrice()
					+ $options['BabySeat'];

		return $subtotal;
	}
	
	/**
	 * Get the grand total.
	 * @return float total rental cost.
	 */
	function getTotal()
	{
		$duty = $this->getStampDuty();
		return $this->getPreTaxTotal() + $this->getTax() + $duty[0] + $duty[1];
	}
}
?>