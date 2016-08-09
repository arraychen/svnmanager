<?php
/**
 * TWizard example's Module class
 * @author $Author: weizhuo $
 * @version $Id: VehicleRentalModule.php,v 1.2 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * Add the I18N namespace, the TDateFormat is used.
 */
using('System.I18N');

/**
 * VehicleRentalModule class
 * 
 * The data module that handles all the data from the forms. Contains
 * instances of business objects. 
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Sat Jan 29 21:48:45 EST 2005
 * @package prado.examples
 */
class VehicleRentalModule extends TModule
{
	/**
	 * The list of vehicles in the database.
	 * @var RentalVehicles 
	 */
	protected $vehicleList;
	
	/**
	 * The quotation data.
	 * @var array
	 */
	protected $quote;
	
	/**
	 * Override the parent implementation. Initialize the vehicle list.
	 */
	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->vehicleList = new RentalVehicles();
		
	}
	
	/**
	 * Get the list of rental shop locations.
	 * @return array list of locations. 
	 */
	function getLocationList()
	{
		return file(dirname(__FILE__).'/locations.txt');
	}	
	
	/**
	 * Get the list of time for pickup and return in 15 minute increments.
	 * @return array list of 15 minute increment for 24 hours. 
	 */
	function getTimeList()
	{
		$times = array();
		for($i = 1; $i < 97; $i++)
		{
			$mins = ($i*15)%60;
			$hour = intval(($i*15)/60);
			$twelve = $hour%12;
			$twelve = $hour == 12 || $hour == 24 ? 12 : $twelve;
			$AM = $hour < 12 ? 'AM':'PM';
			$times[$i] = sprintf('%02d:%02d  ---  (%02d:%02d %s)',
									$hour, $mins, $twelve, $mins, $AM);
		}
		return $times;
	}
		
	/**
	 * Get the list of available vehicles.
	 * @return RentalVehicles a list of vehicles. 
	 */
	function getVehicleList()
	{
		return $this->vehicleList;
	}
	
	/**
	 * Calculate the quote for a given vehicle and some input data.
	 * @param array the vehicle details
	 * @param array a list of input data.
	 * @return boolean true if no error in calculations, false otherwise.
	 */
	function calculateQuote($vehicle, $data)
	{
		$pickup = strtotime($data['PickUpDate']);		
		$pickup += ($data['PickUpTime']+1)*15*60;		

		$return = strtotime($data['ReturnDate']);
		$return += ($data['ReturnTime']+1)*15*60;
		
		$data['pickup'] = $pickup;
		$data['return'] = $return;
		
		$rental = new VehicleRental($vehicle, $data);
		
		$duty = $rental->getStampDuty();		
		$options = $rental->getOptions();
		$babySeat = $options['BabySeat'];
				
		$this->quote['BaseRate'] = sprintf("%01.2f", $vehicle['Price']);
		$this->quote['BasePrice'] = sprintf("%01.2f", $rental->getBasePrice());
		$this->quote['Days'] = $rental->getDays();
		$this->quote['MaxKM'] = $rental->getMaxKM();
		$this->quote['KMRate'] = sprintf("%01.2f", $rental->getPerKMRate());
		$this->quote['Cover'] = sprintf("%01.2f", $rental->getPremiumCoverPrice());
		$this->quote['CoverRate'] = sprintf("%01.2f", $rental->getPremiumCoverRate());		
		$this->quote['BabySeat'] = sprintf("%01.2f", $babySeat);		
		$this->quote['Duty1'] = sprintf("%01.2f", $duty[0]);
		$this->quote['Duty2'] = sprintf("%01.2f", $duty[1]);
		
		$this->quote['GST'] = sprintf("%01.2f", $rental->getTax());
		$this->quote['Total'] = sprintf("%01.2f", $rental->getTotal());
		
		//var_dump($this->quote);
		return true;
	}
	
	/**
	 * Get the quote data.
	 * @return array list of quote data. 
	 */
	function getQuote()
	{
		return $this->quote;
	}
	
	/**
	 * Return the invoice details.
	 * @return array invoice details. 
	 */
	function getInvoice()
	{
		$invoice = rand(1000,1050);
		$reservation = rand(10000,90000);
		$details['invoice#'] = $invoice;
		$details['reservation'] = $reservation;
		return $details;
	}
	

}