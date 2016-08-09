<?php
/**
 * A lsit of rental vehicles.
 * @author $Author: weizhuo $
 * @version $Id: RentalVehicles.php,v 1.2 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * RentalVehicles class
 * 
 * RentalVehicles contains a list of vehicles for the company. 
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Sat Jan 29 21:57:00 EST 2005
 * @package prado.examples
 */
class RentalVehicles
{
	/**
	 * A list of vehicles.
	 * @var array 
	 */
	protected $vehicles = array();

	/**
	 * Constructor. Load the vehicle data from file.
	 */
	public function __construct()
	{
		$file = dirname(__FILE__).'/cars.txt';
		if(is_file($file) == false)
			throw new Exception('Rental Vehicle data file: '.$file.' not found.');
		$lines = file($file);
		$firstline = true;
		$headers = array();
		foreach($lines as $line)
		{
			$data = explode("\t", trim($line));
			
			if($firstline)
			{ 
				$firstline = false; 
				$headers = $data; 
				continue;
			}
			$details = array();
			foreach($headers as $i => $name)
				$details[$name] = $data[$i];
			if($details['Available'] == 'yes')
				$this->vehicles[$data[1]][$data[2]][$data[0]] = $details;
		}
	}

	/**
	 * For a given category, e.g. 'Commercial', return the available types.
	 * @param string rental category
	 * @return array list of vehicle types/classes avaiable. 
	 */
	function getTypes($category)
	{
		return array_keys($this->vehicles[$category]);
	}

	/**
	 * Get the list of rental categories.
	 * @return array list of categories. 
	 */
	function getCategories()
	{
		return array_keys($this->vehicles);
	}

	/**
	 * Get the list of vehicles for a specific category and type.
	 * @param string the category
	 * @param string the vehicle class/type
	 * @return array a list of available vehicles.
	 */
	function getVehicles($category=null, $type=null)
	{
		if(empty($category) && empty($type))
			return $this->vehicles;
		
		if(empty($type))
			return $this->vehicles[$category];

		return $this->vehicles[$category][$type];
	}

	/**
	 * Get the vehicle details for a specific vehicle ID.
	 * @param string the vehicle category.
	 * @param string the vehicle class/type.
	 * @param string the vehicle ID.
	 * @return array vehicle details array.
	 */
	function getVehicle($category, $type, $id)
	{
		return $this->vehicles[$category][$type][$id];
	}

	/**
	 * Return the vehicle details for a particular ID.
	 * @param string vehicle ID
	 * @return array vehicle details array. 
	 */
	function getVehicleByID($id)
	{
		foreach($this->vehicles as $types)
		{
			foreach($types as $cars)
			{
				foreach($cars as $carID => $car)
				{
					if($carID == $id) return $car;
				}
			}
		}
	}
}