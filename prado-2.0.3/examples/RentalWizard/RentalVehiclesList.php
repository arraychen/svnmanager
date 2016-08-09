<?php
/**
 * TWizard example's RentalVehicleList
 * @author $Author: weizhuo $
 * @version $Id: RentalVehiclesList.php,v 1.4 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * RentalVehicleList class.
 * 
 * Basic ListControl to display a list of vehicle and a radio button to select
 * the desired vehicle.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Sat Jan 29 21:28:53 EST 2005
 * @package prado.examples
 */
class RentalVehiclesList extends TListControl
{

	/**
	 * Draw the header.
	 */
	protected function getHeader($hasVehicles)
	{
		if($hasVehicles)
		{
			$string  = '<p>Please select your preferred rental vehicle.</p>';
			$string .= '<table width="95%" align="center" class="cardetails">';
			$string .= '<tr><th width="5%">&nbsp;</th>';
			$string .= '<th width="90%">Vehicle</th>';
			$string .= '<th width="5%">Rate</th></tr>';
		}
		else
		{
			$string = '<p>No vehicles available in this class.</p>';
		}
		return $string;
	}

	/**
	 * Draw the footer.
	 */
	protected function getFooter($hasVehicles)
	{
		if($hasVehicles) return '</table>';
	}

	/**
	 * Draws the car details.
	 */
	protected function getDetails($details)
	{
		$contents = "<tr>";
		$contents .= "<td>&nbsp;</td>\n";
		$contents .= "<td colspan=\"2\">\n";		
		$contents .= "<table class=\"specs\" border=\"1\">\n";
		
		$contents .= "<tr>";
		$contents .= "<td class=\"image\" rowspan=\"2\"><img src=\"media/RentalWizard/cars/{$details['Image']}\" /></td>\n";
		$contents .= "<td width=\"30%\" class=\"capacity\">".$this->getCapacity($details)."</td>";
		$contents .= "<td width=\"30%\" class=\"capacity\">".$this->getLuggage($details)."</td>";
		$contents .= "</tr>\n";
		
		$contents .= "<tr>";
		$contents .= "<td colspan=\"2\" class=\"description\">".$details['Description']."</td>\n";
		$contents .= "</tr>";
		$contents .= "</table>\n";
		
		$contents .= "</td>\n</tr>";
		
		return $contents;
	}
	
	/**
	 * Draws the adult and children capacity of the car.
	 */
	protected function getCapacity($details)
	{
		$adult = $details['Adults'];
		$children = $details['Children'];
		$contents = '';
		if($adult <= 6)
		{
			for($i = 1; $i<=$adult; $i++)
				$contents .= "<img alt=\"{$i} Adult\" src=\"media/RentalWizard/adults.gif\" />\n";
		}
		else 
		{
			$contents .= "<span class=\"count\">{$adult}x</span> <img align=\"absmiddle\" alt=\"Adult\" src=\"media/RentalWizard/adults.gif\" />\n";
		}
			
		for($i = 1; $i<=$children; $i++)
			$contents .= "<img alt=\"{$i} children\" src=\"media/RentalWizard/children.gif\" />\n";
		return $contents;
	}
	
	/**
	 * Draws the luggage capacity.
	 */
	protected function getLuggage($details)
	{
		$large = $details['LargeLuggage'];
		$small = $details['SmallLuggage'];
		$contents = '';
		if($large > 0)
			$contents .= "<span class=\"count\">{$large}x</span><img align=\"absmiddle\" alt=\"{$large} Large Luggage\" src=\"media/RentalWizard/large_luggage.gif\" /> &nbsp; \n";
		else 
			$contents .= "Limited Luggage";
			
		if($small > 0)
			$contents .= "<span class=\"count\">{$small}x</span><img align=\"absmiddle\" alt=\"{$small} Small Luggage\" src=\"media/RentalWizard/small_luggage.gif\" /> &nbsp; \n";
			
		return $contents;
	}
	
	/**
	 * Override the parent implementation to render the vehicle list table.
	 */
	function render() 
	{
		$id=$this->getUniqueID();
		$items=$this->getItems();
		$count=$items->length();
		$id=$this->getUniqueID();
		//var_dump($items);
		$contents = $this->getHeader($count>0);
		foreach($items as $index=>$item)
		{
			$value = $item->Value;
			$details = $item->Text;
			$selected = '';
			if($item->isSelected()) $selected = 'checked="checked"';
			$contents .= "\n<tr>\n";
			$contents .= "<td><input type=\"radio\" id=\"$id:$index\" value=\"$value\" name=\"{$id}[]\" $selected /></td>\n";
			$contents .= "<td><label for=\"$id:$index\">".$details['Name']."</label></td>\n";
			$contents .= "<td class=\"price\">$".$details['Price']."</td>\n";
			$contents .= "</tr>\n";
			$contents .= $this->getDetails($details);
		}

		$contents .= $this->getFooter($count>0);
	
		return $contents;
	}
}

?>