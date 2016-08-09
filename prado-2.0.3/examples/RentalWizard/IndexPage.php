<?php
/**
 * TWizard example's Car and Truck rentals.
 * @author $Author: weizhuo $
 * @version $Id: IndexPage.php,v 1.3 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * The TWizard example page.
 * 
 * This demonstrates a complex TWizard. The scenario is a car and trucks
 * rental quotation and booking form.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Sat Jan 29 21:55:13 EST 2005
 * @package prado.examples
 */
class IndexPage extends TPage
{
	/**
	 * Override the parent implementation. Load some default data.
	 */
	function onLoad($param)
	{
		parent::onLoad($param);

		$this->dataBind();

		//on loading the page for the first time, 
		//initialize the drop down lists
		if(!$this->IsPostBack)
		{
			//echo 'init';
			
			//init the pickup date
			$date = date('m/d/Y');
			$this->RentalWizard->PickUpDate->setText($date);
			
			//init the return date
			$date = date('m/d/Y',strtotime('+1 day'));
			$this->RentalWizard->ReturnDate->setText($date);
			
			//init the pick up time
			$time = $this->Module->getTimeList();
			$this->RentalWizard->PickUpTime->setDataSource($time);
			$this->RentalWizard->PickUpTime->dataBind();
			$this->RentalWizard->PickUpTime->setSelectedValue(36);

			//init return time
			$this->RentalWizard->ReturnTime->setDataSource($time);
			$this->RentalWizard->ReturnTime->dataBind();
			$this->RentalWizard->ReturnTime->setSelectedValue(68);

			//init the pick up location
			$locations = $this->Module->getLocationList();
			array_unshift($locations, '---------------------------------');
			array_unshift($locations, 'Select a Pick Up Location');
			$this->RentalWizard->PickUpLocation->setDataSource($locations);
			$this->RentalWizard->PickUpLocation->dataBind();			
			
			//init the return location
			array_shift($locations); array_shift($locations);
			array_unshift($locations, '---------------------------------');
			array_unshift($locations, 'Same as Pick Up Location');
			$this->RentalWizard->ReturnLocation->setDataSource($locations);
			$this->RentalWizard->ReturnLocation->dataBind();
			$year = intval(date('Y')); $years = array();
			for($i=0; $i<6; $i++) $years[] = $year+$i;			
			$this->RentalWizard->ExpiryYear->setDataSource($years);
			$this->RentalWizard->ExpiryYear->dataBind();

			//init the vehicle category
			$categories = $this->Module->getVehicleList()->getCategories();
			$this->RentalWizard->VehicleCat->setDataSource($categories);
			$this->RentalWizard->VehicleCat->dataBind();

			//set the default vehicle category and types
			$cat = $this->RentalWizard->VehicleCat->Items[0]->Text;
			$this->setVehicleTypes($cat);	
			$type = $this->RentalWizard->VehicleType->Items[0]->Text;
			$this->setVehicleList($cat, $type);
		}
	}

	/**
	 * Change the vehicle category. PostBack from ID="VehicleCat".
	 */
	function changeVehicleCat($sender, $param)
	{
		$cat = $this->RentalWizard->VehicleCat->SelectedItem->Text;
		
		//disable the frequent flyer for commercial rentals
		//also disable the baby set options
		$this->RentalWizard->FrequentFlyerPanel->setVisible($cat=='Car');
		$this->RentalWizard->OptionsPanel->setVisible($cat=='Car');

		//set the appropriate images
		$this->VehicleCatImage->setImageUrl("media/RentalWizard/$cat.jpg");
		$this->VehicleCatImage->setAlternateText("$cat Rental");
		
		//update the vehile types.
		$this->setVehicleTypes($cat);

		//update the vehicle list
		$type = $this->RentalWizard->VehicleType->Items[0]->Text;
		$this->setVehicleList($cat, $type);
	}

	/**
	 * Change the vehicle class/type. PostBack from ID="VehicleType".
	 */
	function changeVehicleType($sender, $param) 
	{
		$cat = $this->RentalWizard->VehicleCat->SelectedItem->Text;
		$type = $this->RentalWizard->VehicleType->SelectedItem->Text;

		//update the vehicle list
		$this->setVehicleList($cat, $type);
	}

	/**
	 * Set the vehicle type, ID="VehicleType".
	 * @param string vehicle category
	 */
	private function setVehicleTypes($cat)
	{
		$rentals = $this->Module->getVehicleList();

		$types = $rentals->getTypes($cat);
		$this->RentalWizard->VehicleType->setDataSource($types);
		$this->RentalWizard->VehicleType->dataBind();
	}

	/**
	 * Set the list of vehicles for a particular category and class/type.
	 * @param string vehicle rental category
	 * @param string vehicle class
	 */
	private function setVehicleList($cat, $type) 
	{
		$rentals = $this->Module->getVehicleList();
		$vehicles = $rentals->getVehicles($cat, $type);
		$this->RentalWizard->VehicleList->setDataSource($vehicles);
		$this->RentalWizard->VehicleList->dataBind();
	}

	/**
	 * Get the selected vehicle details.
	 * @param string which specification detials
	 * @return string the specs. 
	 */
	public function VehicleDetails($spec)
	{
		$vehicle = $this->RentalWizard->VehicleList->SelectedItem->Text;		
		return $vehicle[$spec];
	}

	/**
	 * Get the current rental quote details.
	 * @param string which details.
	 * @return string quote detail 
	 */
	public function RentalQuote($spec)
	{		
		$quote = $this->getViewState('RentalQuote','');
		return $quote[$spec];
	}
	
	public function RentalInvoice($spec)
	{
		$invoice = $this->getViewState('RentalInvoice','');
		return $invoice[$spec];
	}
	
	/**
	 * Capture the step change commands. Cancel the step if validators fails.
	 * If the next step is "quote", step 2, calculate the quote.	 
	 */
	public function change($sender, $param)
	{
		if(!$this->IsValid)
		{
			$param->cancel = true;	
			return;
		}
		
		if($param->nextStepIndex == 2)
		{
			$vehicle = $this->RentalWizard->VehicleList->SelectedItem->Text;		
			$data['PickUpDate'] = $this->RentalWizard->PickUpDate->Text;
			$data['PickUpTime'] = $this->RentalWizard->PickUpTime->SelectedValue;
			$data['ReturnDate'] = $this->RentalWizard->ReturnDate->Text;
			$data['ReturnTime'] = $this->RentalWizard->ReturnTime->SelectedValue;
			$data['PremiumProtect'] = $this->RentalWizard->PremiumProtect->Checked;	
			$data['BabySeat'] = $this->RentalWizard->BabySeat->Checked;
			$data['BabySeatQty'] = $this->RentalWizard->BabySeatQty->Text;
						
			//calculate the quote, cancel the page transitition on error
			$result = $this->Module->calculateQuote($vehicle, $data);	
			if($result)
			{
				$this->setViewState('RentalQuote', $this->Module->getQuote());
				$this->setViewState('RentalInvoice', $this->Module->getInvoice());
			}
			else
				$param->cancel = true;
		}
	}

	/**
	 * Capture the actual step change event. We need a dataBind to update
	 * the data bindings that uses the wizard step data.
	 */
	public function stepChanged($sender, $param)
	{		
		$this->dataBind();		
	}

	/**
	 * Deterime the class attribute values for the navigational steps.
	 * @param string the current class attribute value
	 * @param int the step index to check
	 * @return string appends " active" to $class parameter if the 
	 * navigation step should be active.
	 */
	public function getActiveClass($class, $index)
	{
		$current = $this->RentalWizard->ActiveStepIndex;
		if($index <= $current)
			return $class.' active';
		else
			return $class;
	}

	/**
	 * Determine if the navigational step link should be active or otherwise.
	 * @param int the navigation step index.
	 * @return boolean true if the link should be enabled, false otherwise. 
	 */
	public function isLinkEnabled($index)
	{
		$current = $this->RentalWizard->ActiveStepIndex;
		return $index != $current && $index <= $current+1;
	}
}
?>