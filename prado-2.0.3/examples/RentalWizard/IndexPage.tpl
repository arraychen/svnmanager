<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>PRADO Down Under Car and Truck Rental - a TWizard Component Example</title>
	<link rel="stylesheet" type="text/css" href="media/RentalWizard/site.css" />
	<link rel="stylesheet" type="text/css" href="media/RentalWizard/print.css" media="print" />
</head>

<body>

<h1>A TWizard Component Example</h1>
<p class="info">
This example demonstrates the advantage of splitting a large and complex form 
into smaller "step-by-step guided" forms using PRADO's TWizard component.
The example is a prototype for an online "Cars and Trucks Rental" quotation and
booking application. The targeted audience is located in Australia. For this 
example, only temporary data will be store by sever, no data will be stored permanently. 
</p>
<com:TForm ID="Form">

	<com:TWizard ID="RentalWizard" CssClass="wizard" 
		OnNextCommand="change" 
		OnJumpToCommand="change" 
		OnStepChanged="stepChanged" >
		
		<h2>PRADO Down Under Car and Truck Rental</h2>
		<com:TWizardTemplate Type="NavigationSideBar" CssClass="navigationSteps">
			
			<!-- 1st step button -->
			<com:TLinkButton 
			CssClass="#$this->Page->getActiveClass('stepguide step1',0)"
			CausesValidation="false"
			Enabled="#$this->Page->isLinkEnabled(0)"
			title="Step 1 car rental pickup &amp; return locations &amp; dates" 
			CommandName="jumpto" CommandParameter="0" >
				<span class="num">1</span>
				<span class="title">Pick Up &amp; Return</span>
				<span class="description">Location, Date &amp; Time</span>
			</com:TLinkButton>

			<!-- 2nd step button -->
			<com:TLinkButton 
			CssClass="#$this->Page->getActiveClass('stepguide step2',1)"
			Enabled="#$this->Page->isLinkEnabled(1)"
			CausesValidation="#$this->Page->RentalWizard->ActiveStepIndex == 0"
			title="Step 2 select vehicle type and class for quote" 
			CommandName="jumpto" CommandParameter="1" >
				<span class="num">2</span>
				<span class="title">Select Vehicle</span>
				<span class="description">Vehicle Class</span>
			</com:TLinkButton>

			<!-- 3rd step button -->
			<com:TLinkButton 
			CssClass="#$this->Page->getActiveClass('stepguide step3',2)"
			Enabled="#$this->Page->isLinkEnabled(2)"
			CausesValidation="#$this->Page->RentalWizard->ActiveStepIndex == 1"
			title="Step 3 Vehicle rental quote price details" 
			CommandName="jumpto" CommandParameter="2" >
				<span class="num">3</span>
				<span class="title">Quote</span>
				<span class="description">Price Details</span>
			</com:TLinkButton>

			<!-- 4th step button -->
			<com:TLinkButton 
			CssClass="#$this->Page->getActiveClass('stepguide step4',3)"
			Enabled="#$this->Page->isLinkEnabled(3)"
			CausesValidation="#$this->Page->RentalWizard->ActiveStepIndex == 2"
			title="Step 4 contact details &amp; payment information"
			CommandName="jumpto" CommandParameter="3" >
				<span class="num">4</span>
				<span class="title">Contact Details</span>
				<span class="description">Payment Information</span>
			</com:TLinkButton>	

			<!-- 5th step button -->
			<com:TLinkButton 
			CssClass="#$this->Page->getActiveClass('stepguide step5',4)"
			Enabled="#$this->Page->isLinkEnabled(4)"
			CausesValidation="#$this->Page->RentalWizard->ActiveStepIndex == 3"
			title="Step 5 booking confirmation reservation number"
			CommandName="jumpto" CommandParameter="4" >
				<span class="num">5</span>
				<span class="title">Confirmation</span>
				<span class="description">Reservation Number</span>
			</com:TLinkButton>	
			<span class="stepquote">QUOTE</span>
			<span class="stepbook">BOOK</span>
		</com:TWizardTemplate>		

		<!-- start wizard forms -->
		<fieldset class="form <% if($this->Page->RentalWizard->ActiveStepIndex == 5) echo 'final'; %>">		
			<legend><%= $this->Page->RentalWizard->ActiveStep->Title %></legend>
			<com:TValidationSummary CssClass="validator" 
				Style="color:black;"
				HeaderText="<p>Your rental details could not be processed because</p>"/>
			
		<com:TWizardStep Title="Pick Up &amp; Return" CssClass="step">
		<!-- 1st step form -->

			<!-- field 1 -->
			<fieldset class="subform" title="Vehicle Category" >
				<legend><span class="number">1</span> Vehicle Category</legend>
				<p>Please select the rental vehicle category.</p>
				<div class="element" title="Select vehicle category">
					<com:TFormLabel For="VehicleCat" Text="Vehicle Category:" />
					<com:TDropDownList ID="VehicleCat" AutoPostBack="true"
						OnSelectionChanged="Page.changeVehicleCat" />
				</div>
			</fieldset>

			<!-- field 2 -->
			<fieldset class="subform" title="Driver's Age">
				<legend><span class="number">2</span> Driver's Age</legend>
				<p>Please select the age group of the driver on the rental date.</p>
				<div class="element" title="Select driver's age group">
					<com:TFormLabel For="DriverAgeGroup" Text="Driver's Age Group:" />
					<com:TDropDownList ID="DriverAgeGroup">
						<com:TListItem Text="25+" Value="25+" />
						<com:TListItem Text="21-24" Value="21-24" />
					</com:TDropDownList>
				</div>
			</fieldset>

			<!-- field 3 -->
			<fieldset class="subform" id="datetime" title="Date & Time Information" >
				<legend><span class="number">3</span> Date &amp; Time Information</legend>
				<p>Please select the dates and times for pick up and return of your rental vehicle.</p>
				<div class="element" title="Pick up date (mm/dd/yyyy)" >
					<com:TFormLabel For="PickUpDate" Text="Pick Up Date:" EncodeText="false"/>
					<span class="required">*</span>
					<com:TDatePicker ID="PickUpDate" DateFormat="%m/%d/%Y" /> 
					<tt>mm/dd/yyyy</tt>
					<com:TRequiredFieldValidator
						ControlToValidate="PickUpDate"
						Display="None"
						Anchor="datetime"
						ControlCssClass="requiredInput"
						ErrorMessage="the pick up date is not valid."
						/>
					<com:TRangeValidator 
						ControlToValidate="PickUpDate"
						ValueType="Date"
						Display="None"
						MinValue="#date('m/d/Y')"
						MaxValue="#date('m/d/Y',strtotime('+2 year'))"
						DateFormat="%m/%d/%Y"
						Anchor="datetime"
						ControlCssClass="requiredInput2"
						ErrorMessage="#sprintf('the pick up date is before today, select a date between %s and %s.', date('m/d/Y'), date('m/d/Y',strtotime('+2 year')))" 
						/>
				</div>
				<div class="element" title="Pick up time">
					<com:TFormLabel For="PickUpTime" Text="Pick Up Time:" />
					<com:TDropDownList ID="PickUpTime" />
				</div>
				<div class="element" title="Return date (mm/dd/yyyy)" >
					<com:TFormLabel For="ReturnDate" Text="Return Date:" EncodeText="false"/> 
					<span class="required">*</span>
					<com:TDatePicker ID="ReturnDate" DateFormat="%m/%d/%Y" />
					<tt>mm/dd/yyyy</tt>
					<com:TRequiredFieldValidator
						ControlToValidate="ReturnDate"
						Display="None"
						Anchor="datetime"
						ControlCssClass="requiredInput"
						ErrorMessage="the return date is not valid."
						/>
					<com:TCompareValidator
						ControlToValidate="ReturnDate"
						ControlToCompare="PickUpDate"
						Display="None"
						ValueType="Date"
						Operator="GreaterThanEqual"
						DateFormat="%m/%d/%Y"
						Anchor="datetime"
						ControlCssClass="requiredInput2"
						ErrorMessage="the return date is before the pick up date."
						/>
				</div>
				<div class="element" title="Return time" >
					<com:TFormLabel For="ReturnTime" Text="Return Time:" />
					<com:TDropDownList ID="ReturnTime" />
				</div>
			</fieldset>	
			
			<!-- field 4 -->
			<fieldset class="subform" id="locationinfo" title="Location Information">
				<legend><span class="number">4</span> Location Information</legend>
				<p>Please select the locations for pick up and return of your rental vehicle.</p>
				<div class="element" title="Pick up location">
					<com:TFormLabel For="PickUpLocation" Text="Pick Up Location:" />					
					<span class="required">*</span>
					<com:TDropDownList ID="PickUpLocation" />
					<com:TRangeValidator 
						ControlToValidate="PickUpLocation"
						ValueType="Integer"
						MinValue="2"
						MaxValue="183"
						Anchor="locationinfo"
						ControlCssClass="requiredInput"
						ErrorMessage="the selected pick up location is invalid" 
						Display="None"
						/>
				</div>
				<div class="element" title="Return location">
					<com:TFormLabel For="ReturnLocation" Text="Return Location:" />
					<com:TDropDownList ID="ReturnLocation" />
				</div>
			</fieldset>

			<!-- field 5 -->
			<com:TPanel ID="FrequentFlyerPanel">
			<fieldset class="subform" title="Frequent Flyer" >
				<legend><span class="number">5</span> Frequent Flyer</legend>
				<p>Please enter your frequent flyer number where applicable.</p>
				<div class="element" title="Frequent Flyer number">
					<com:TFormLabel For="FrequentFlyer" Text="Frequent Flyer #:" />
					<com:TTextBox ID="FrequentFlyer" />
				</div>
			</fieldset>
			</com:TPanel>
		</com:TWizardStep>
		
		<com:TWizardStep Title="Select Vehicle" CssClass="step">
		<!-- 2nd step form -->

			<!-- field 1 -->
			<fieldset class="subform" title="Vehicle Type">
				<legend><span class="number">1</span> Vehicle Type</legend>
				<p>Please select the type of vehicle you wish to rent.</p>
				<div class="element" title="Select vehicle type">
					<com:TFormLabel For="VehicleType" Text="Vehicle Type:" />
					<com:TDropDownList ID="VehicleType"  AutoPostBack="true" 
						OnSelectionChanged="Page.changeVehicleType" />
				</div>
			</fieldset>		

			<!-- field 2 -->
			<fieldset class="subform" >
				<legend id="selectVehicle"><span class="number">2</span> Vehicle Class <span class="simpleRequired">*</span></legend>				
				<com:RentalVehiclesList ID="VehicleList" />
				<com:TRequiredListValidator 
					ControlToValidate="VehicleList"
					MinSelection="1"
					Display="None"
					Anchor="selectVehicle"
					ErrorMessage="a vehicle has not been selected" />
			</fieldset>

			<!-- field 3 -->
			<fieldset class="subform" title="Reduce Your Liability">
				<legend><span class="number">3</span> Reduce Your Liability</legend>
				<p>Standard Cover is included in your rental fee, and indemnifies the Hirer and Authorised Drivers for damage to the vehicle and Third Party Property, subject to the Terms and Conditions.</p>
				<p>Premium Protection is strongly recommended to reduce your liability in the event of loss or damage for a small daily fee.</p>
				<table width="95%" align="center" title="Include premium protection cover">
					<tr><td><com:TCheckBox ID="PremiumProtect" /></td>
					<td>
					<com:TFormLabel For="PremiumProtect" CssClass="liability">
						<strong>Premium Protection</strong> includes Standard Cover and provides the maximum reduction of both your Liability Fee and Single Vehicle Accident Liability Fee.
					</com:TFormLabel>
					</td></tr>
				</table>
			</fieldset>

			<!-- field 4 -->
			<com:TPanel ID="OptionsPanel">
			<fieldset class="subform" title="Options">
				<legend><span class="number">4</span> Options</legend>
				<p>Please make your choice of available options by selecting the check box and entering a quantity required where applicable.</p>
				<table width="95%" align="center">
					<tr><th width="5%">&nbsp;</th>
						<th width="80%">Option</th><th width="5%">Qty</th><th width="5%">Rate</th>
						<th width="5%">&nbsp;</th>
					</tr>
					<tr title="Include baby seats">
						<td><com:TCheckBox ID="BabySeat" /></td>
						<td><com:TFormLabel For="BabySeat">Baby Seat</com:TFormLabel></td>
						<td><com:TTextBox ID="BabySeatQty" Text="1" Style="width:3em;margin-right:1em;" />
						<td class="price">$5.00</td>
						<td nowrap="nowrap"><strong>/day</strong></td>
					</tr>
				</table>
			</fieldset>
			</com:TPanel>
		</com:TWizardStep>		
			
		<com:TWizardStep Title="Quote" CssClass="step">
		<!-- 3rd step form -->

			<!-- field 1 -->
			<fieldset class="subform">
				<legend><span class="number">1</span> Price Details</legend>
				<p>Please find an estimate of the cost of your vehicle rental below.</p>
				<table>
					<tr><th width="85%">Base Rate:</th><th width="15%" class="price">Price</th></tr>
					<tr>
						<td><%= $this->Page->VehicleDetails('Name') %> for 
							<%= $this->Page->RentalQuote('Days') %> day(s) @ 
							$<%= $this->Page->RentalQuote('BaseRate') %>/day
						</td>
						<td class="price">$<%= $this->Page->RentalQuote('BasePrice') %></td>
					</tr>
					<tr><th colspan="2">Kilometre Restrictions:</th></tr>
					<tr><td colspan="2">This rental includes <%= $this->Page->RentalQuote('MaxKM') %> kilometres free per day with additional kilometres charged 
								at the rate of $<%= $this->Page->RentalQuote('KMRate') %>/km.</td></tr>
					<tr><th colspan="2">Options:</th></tr>
					<tr>
						<td>Standard Cover (this is included in your base rate).</td>
						<td class="price">$0.00</td>
					</tr>
					<com:TPlaceHolder 
						Visible="#$this->Page->RentalWizard->PremiumProtect->Checked" >
					<tr>
						<td>Premium Protection Cover for <%= $this->Page->RentalQuote('Days') %> day(s) 
							@ $<%= $this->Page->RentalQuote('CoverRate') %>/day.</td>
						<td class="price">$<%= $this->Page->RentalQuote('Cover') %></td>
					</tr>
					</com:TPlaceHolder>
					<com:TPlaceHolder 
						Visible="#$this->Page->RentalWizard->BabySeat->Checked" >
					<tr>
						<td><%= $this->Page->RentalWizard->BabySeatQty->Text %> *
							Baby Seat for <%= $this->Page->RentalQuote('Days') %> day(s) @ $5.00/day
							(max charge $33.00 each).
						</td>
						<td class="price">$<%= $this->Page->RentalQuote('BabySeat') %></td>
					</tr>
					</com:TPlaceHolder>
					<tr><th colspan="2">Taxes &amp; Fees:</th></tr>
					<tr>
						<td>Vehicle Registration Recovery Fee</td>
						<td class="price">$<%= $this->Page->RentalQuote('Duty1') %></td>
					</tr>		
					<tr>
						<td>Stamp Duty Recovery Fee</td>
						<td class="price">$<%= $this->Page->RentalQuote('Duty2') %></td>
					</tr>										
					<tr>
						<th class="price">Total Price:</th>
						<th class="price">$<%= $this->Page->RentalQuote('Total') %></th>
					</tr>		
					<tr>
						<td class="price">Includes GST of:</td>
						<td class="price">$<%= $this->Page->RentalQuote('GST') %></td>
					</tr>							
				</table>				
			</fieldset>
		</com:TWizardStep>

		<com:TWizardStep Title="Contact Details" CssClass="step">
		<!-- 4th step form -->

			<!-- field 1 -->
			<fieldset class="subform" id="termsconditions" title="Terms and Conditions">
				<legend><span class="number">1</span> Terms and Conditions</legend>
				<p>It is important that you understand theTerms and Conditions before completing your reservation.</p>
				<p>Please indicate that you accept the rental terms and conditions by clicking the check box.</p>
				<p style="text-align: right; margin-right:2em;"><a href="#">View Terms and Conditions.</a></p>
				<div class="element" title="Accept terms and conditions">
					<com:TCheckBox ID="AcceptTerms" />
					<com:TFormLabel CssClass="liability" For="AcceptTerms">
						<strong>I accept the rental terms and conditions.</strong>
						<span class="simpleRequired">*</span>
					</com:TFormLabel>
					<com:TRequiredFieldValidator 
						ControlToValidate="AcceptTerms"
						Anchor="termsconditions"
						Display="None"						
						ErrorMessage="you have not indicated acceptance of the terms and conditions"
					/>
				</div>
			</fieldset>
			
			<!-- field 2 -->
			<fieldset class="subform" id="driverinfo" title="Driver Information">
				<legend><span class="number">2</span> Driver Information</legend>
				<p>Please enter the driver's first and last name.</p>
				<div class="element" title="Driver's first name">
					<com:TFormLabel For="FirstName">Driver's First Name:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="FirstName" Style="width: 17em" />
					<com:TRequiredFieldValidator
						ControlToValidate="FirstName"
						Display="None"
						Anchor="driverinfo"
						ControlCssClass="requiredInput"
						ErrorMessage="the driver's first name is required" />
				</div>
				<div class="element" title="Driver's last name">
					<com:TFormLabel For="LastName">Driver's Last Name:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="LastName" Style="width: 17em" />
					<com:TRequiredFieldValidator
						ControlToValidate="LastName"
						Display="None"
						Anchor="driverinfo"
						ControlCssClass="requiredInput"
						ErrorMessage="the driver's last name is required" />					
				</div>
			</fieldset>	
			
			<!-- field 3 -->
			<fieldset class="subform" id="contactdetails" title="Contact Details">
				<legend><span class="number">3</span> Contact Details</legend>
				<p>Please enter the driver's contact details. <strong>A direct phone number is mandatory.</strong> Please enter either the home or work number including the Area Code.</p>
				<div class="element" title="Email address">
					<com:TFormLabel For="Email">Contact Email:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="Email" Style="width: 17em" />
					<com:TEmailAddressValidator
						ControlToValidate="Email"
						Display="None"
						Anchor="contactdetails"
						ControlCssClass="requiredInput"
						ErrorMessage="a valid email address is required" />					
					<com:TRequiredFieldValidator
						ControlToValidate="Email"
						Display="None"
						Anchor="contactdetails"
						ControlCssClass="requiredInput2"
						ErrorMessage="a valid email address is required" />
				</div>
				<div class="element" title="Home phone number">
					<com:TFormLabel For="HomePhone">Home Phone:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="HomePhone" />
					<com:TRequiredFieldValidator
						ControlToValidate="HomePhone"
						Display="None"
						Anchor="contactdetails"
						ControlCssClass="requiredInput"
						ErrorMessage="a home phone number is required" />
				</div>
				<div class="element" title="Work phone number">
					<com:TFormLabel For="WorkPhone">Work Phone:</com:TFormLabel>
					<com:TTextBox ID="WorkPhone" />
				</div>
				<div class="element" title="Mobile phone number">
					<com:TFormLabel For="MobilePhone">Mobile Phone:</com:TFormLabel>
					<com:TTextBox ID="MobilePhone" />
				</div>
				<p>If travelling, please enter mobile number.</p>
			</fieldset>	
			
			<!-- field 4 -->
			<fieldset id="paymentdetails" class="subform">
				<legend><span class="number">4</span> Payment Details</legend>
				<p>Credit Card details are required to secure the booking. Full payment is required on collection of the vehicle. <strong>Important</strong> - the Credit Card holder must be present at the time of vehicle pick up and return and must hold a valid drivers license for the type of vehicle being rented. Deposits for Commercial Vehicle bookings may be debited prior to vehicle collection.</p>
				<div class="element" title="Credit card type">
					<com:TFormLabel For="CardType">Card Type:</com:TFormLabel>
					<span class="required">*</span>
					<com:TDropDownList ID="CardType">
						<com:TListItem Text="American Express" />
						<com:TListItem Text="Bank Card" />
						<com:TListItem Text="Dinners" />
						<com:TListItem Text="Mastercard" />
						<com:TListItem Text="Visa" />
					</com:TDropDownList>
				</div>
				<div class="element" title="Credit card number (xxxx xxxx xxxx xxxx)">
					<com:TFormLabel For="CardNumber">Credit Card Number:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="CardNumber" Style="width: 17em" />
					<com:TRegularExpressionValidator
						ControlToValidate="CardNumber"
						Display="None"
						Anchor="paymentdetails"
						ControlCssClass="requiredInput"
						RegularExpression="^\d{4}(\s|-)*\d{4}(\s|-)*\d{4}(\s|-)*\d{4}(\s)*$"
						ErrorMessage="the credit number doesn't seem to be valid" />							
					<com:TRequiredFieldValidator
						ControlToValidate="CardNumber"
						Display="None"
						Anchor="paymentdetails"
						ControlCssClass="requiredInput2"
						ErrorMessage="a valid credit card number is required" />			
				</div>
				<div class="element" title="Credit card expiry date">
					<com:TFormLabel For="ExpiryMonth">Expiry Date:</com:TFormLabel>
					<span class="required">*</span>
					<com:TDropDownList ID="ExpiryMonth">
						<com:TListItem Text="01" /><com:TListItem Text="02" /><com:TListItem Text="03" />
						<com:TListItem Text="04" /><com:TListItem Text="05" /><com:TListItem Text="06" />
						<com:TListItem Text="07" /><com:TListItem Text="08" /><com:TListItem Text="09" />
						<com:TListItem Text="10" /><com:TListItem Text="11" /><com:TListItem Text="12" />
					</com:TDropDownList>
					<com:TDropDownList ID="ExpiryYear" />
				</div>
				<div class="element" title="Credit card holder's first name">
					<com:TFormLabel For="CardFirstName">Card Holder's First Name:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="CardFirstName" Style="width: 17em"/>
					<com:TRequiredFieldValidator
						ControlToValidate="CardFirstName"
						Display="None"
						Anchor="paymentdetails"
						ControlCssClass="requiredInput"
						ErrorMessage="the credit card holder's first name was not entered" />
				</div>
				<div class="element" title="Credit card holder's middle initial">
					<com:TFormLabel For="CardInitial">Middle Initial:</com:TFormLabel>
					<com:TTextBox ID="CardInitial" />
				</div>
				<div class="element" title="Credit card holder's last name">
					<com:TFormLabel For="CardInitial">Last Name:</com:TFormLabel>
					<span class="required">*</span>
					<com:TTextBox ID="CardLastName" />
					<com:TRequiredFieldValidator
						ControlToValidate="CardLastName"
						Display="None"
						Anchor="paymentdetails"
						ControlCssClass="requiredInput"
						ErrorMessage="the credit card holder's last name was not entered" />					
				</div>				
				<p>Please enter a purchase order # or reference # if applicable.</p>
				<div class="element" title="Purchase order or reference number">
					<com:TFormLabel For="OrderRef">Purchase Order / Ref #:</com:TFormLabel>
					<com:TTextBox ID="OrderRef" />
				</div>								
			</fieldset>									
		</com:TWizardStep>

		<com:TWizardStep Title="Confirmation" CssClass="step">
		<!-- 5th step form -->
			<fieldset id="invoice" class="subform">			
				<legend><span class="number">1</span> Invoice</legend>
				<p>Please find copy of your vehicle rental invoice and your reservation number below.
				 You should quote this number when inquiring your rental with PRADO Down Under cars and trucks rental service.</p>
				 <p class="reservation">Reservation #: <%= $this->Page->RentalInvoice('reservation') %> </p>
				<table width="100%"  border="1" class="confirmation">
				  <tr>
				    <th scope="row" width="40%">Invoice #</th>
				    <td><%= $this->Page->RentalInvoice('invoice#') %></td>
				  </tr>				  
				  <tr>
				    <th scope="row">Invoice Date</th>
				    <td><%= date('m/d/Y') %></td>
				  </tr>
				  <tr>
				    <th scope="row">Total Payable </th>
				    <td class="price"> $89.75 </td>
				  </tr>
				  <tr>
				    <th scope="row"> Includes GST of </th>
				    <td  class="price"> $7.26 </td>
				  </tr>
				</table>				
				<h3>Contact Details</h3>
				<table width="100%"  border="1" class="confirmation">
				  <tr>
				    <th scope="row" width="40%" >Driver's First Name </th>
				    <td><%= $this->Page->RentalWizard->FirstName->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Driver's Last Name </th>
				    <td><%= $this->Page->RentalWizard->LastName->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Contact Email</th>
				    <td><%= $this->Page->RentalWizard->Email->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Home Phone</th>
				    <td><%= $this->Page->RentalWizard->HomePhone->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Work Phone </th>
				    <td><%= $this->Page->RentalWizard->WorkPhone->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Mobile Phone</th>
				    <td><%= $this->Page->RentalWizard->MobilePhone->Text %></td>
				  </tr>
				</table>					
				<h3>Payment Details</h3>
				<table width="100%"  border="1" class="confirmation">
				  <tr>
				    <th scope="row" width="40%" >Card Type</th>
				    <td><%= $this->Page->RentalWizard->CardType->SelectedItem->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Credit Card Number </th>
				    <td><%= $this->Page->RentalWizard->CardNumber->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Expiry Date </th>
				    <td>
				    	<%= $this->Page->RentalWizard->ExpiryMonth->SelectedItem->Text %> /
				    	<%= $this->Page->RentalWizard->ExpiryYear->SelectedItem->Text %>
				    </td>
				  </tr>
				  <tr>
				    <th scope="row">Card Holder's Name </th>
				    <td><%= $this->Page->RentalWizard->CardFirstName->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Middle Initial </th>
				    <td><%= $this->Page->RentalWizard->CardInitial->Text %></td>
				  </tr>
				  <tr>
				    <th scope="row">Last Name </th>
				    <td><%= $this->Page->RentalWizard->CardLastName->Text %></td>
				  </tr>
				</table>				
				<p>To print this page, click the following link below or choose <tt>Print</tt> from your file menu.</p>
				<p class="printpage"><a href="javascript:window.print()">Print this page</a></p>
				<p>Please confirm that all the details are accurrate. If you have any questions or comments, please call 1800 xxx xxx.</p>
			</fieldset>
		</com:TWizardStep>

		<com:TWizardStep Type="Final">
			<p>This completes the TWizard example. 
			<a href="wizard.php" title="see the example again">Would you like to see the example once more?</a></p>
		</com:TWizardStep>
		
		<!-- templates -->
			<com:TWizardTemplate Type="NavigationStart" CssClass="navigation">							
					<com:TButton Text="Continue >" CssClass="button" CommandName="next" title="Continue to next step"/>
			</com:TWizardTemplate>

			<com:TWizardTemplate Type="NavigationStep" CssClass="navigation">
				<com:TButton Text="< Back" CssClass="button" CausesValidation="false" CommandName="previous" title="Return to previous step"/>
				<com:TButton Text="Continue >" CssClass="button" CommandName="next" title="Continue to next step" />
			</com:TWizardTemplate>

			<com:TWizardTemplate Type="NavigationFinish" CssClass="navigation">	
				<com:TButton Text="< Back" CssClass="button" CausesValidation="false" CommandName="previous" title="Return to previous step"/>					
				<com:TButton Text="Finish" CssClass="button" CommandName="finish" title="Completion" />
			</com:TWizardTemplate>

	</fieldset>
	<!-- end wizard forms -->

	<!-- start interary list -->
		<com:TPanel CssClass="interary" title="Your Interary"
		Visible="#$this->Page->RentalWizard->ActiveStepIndex < 5"
		>
			<h4>Your Interary</h4>
			<com:TPanel CssClass="item" Style="border: 0 none" 
			Visible="#$this->Page->RentalWizard->ActiveStepIndex == 0" >
				<p>As you progress through this process a summary of your vehicle rental information will be displayed.</p>
				<p>Changes to your rental choices can be made at any time during this process.</p>				
			</com:TPanel>
			<com:TPanel CssClass="item"
			Visible="#$this->Page->RentalWizard->ActiveStepIndex > 0" >
				<table>
					<tr><th>Driver's Age:</th>
						<td><%= $this->Page->RentalWizard->DriverAgeGroup->SelectedValue %></td>
					</tr>
					<tr><th>Pick Up:</th>
						<td>
						<%= $this->Page->RentalWizard->PickUpTime->SelectedItem->Text %> <br />
						<com:TDateFormat Pattern="fulldate">
							<%= $this->Page->RentalWizard->PickUpDate->Text %>
						</com:TDateFormat><br />
						<%= $this->Page->RentalWizard->PickUpLocation->SelectedItem->Text %>
						</td>
					</tr>
					<tr><th>Return:</th>
						<td>
						<%= $this->Page->RentalWizard->ReturnTime->SelectedItem->Text %> <br />
						<com:TDateFormat Pattern="fulldate">
							<%= $this->Page->RentalWizard->ReturnDate->Text %>
						</com:TDateFormat><br />
						<% $location = $this->Page->RentalWizard->PickUpLocation->SelectedItem;
						   $return = $this->Page->RentalWizard->ReturnLocation->SelectedItem;
							if($return->Value < 2){ echo $location->Text; }else{ echo $return->Text; }
						%>
						</td>
					</tr>
				</table>
			</com:TPanel>
			<com:TPanel CssClass="item"
			Visible="#$this->Page->RentalWizard->ActiveStepIndex <= 1"	>
				<p class="image" ><com:TImage ID="VehicleCatImage" ImageUrl="media/RentalWizard/Car.jpg" AlternateText="Car rental" /></p>
			</com:TPanel>
			<com:TPanel CssClass="item" 
			Visible="#$this->Page->RentalWizard->ActiveStepIndex > 1" >
				<table>
					<tr><th>Vehicle:</th>
						<td><%= $this->Page->VehicleDetails('Name') %></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><img src="media/RentalWizard/cars/<%= $this->Page->VehicleDetails('Image') %>" Alt="Car Image" 
								title="<%= $this->Page->VehicleDetails('Name') %>" />
						</td>
					<tr>
						<th>Class:</th><td><%= $this->Page->VehicleDetails('Type') %></td>
					</tr>
					<tr>
						<th>Description:</th>
						<td><%= $this->Page->VehicleDetails('Description') %></td>
					</tr>
				</table>
			</com:TPanel>
			<com:TPanel CssClass="item" 
			Visible="#$this->Page->RentalWizard->ActiveStepIndex > 2" >
				<table>
					<tr><th>Base Rate:</th><th class="price">Price</th></tr>
					<tr>
						<td>Economy Hyundai Getz (similar) Manual & Air for 
							<%= $this->Page->RentalQuote('Days') %> day(s) @ 
							$<%= $this->Page->RentalQuote('BaseRate') %>/day
						</td>
						<td class="price">$<%= $this->Page->RentalQuote('BasePrice') %></td>
					</tr>
					<tr><th colspan="2">Kilometre Restrictions:</th></tr>
					<tr><td colspan="2">This rental includes <%= $this->Page->RentalQuote('MaxKM') %> kilometres free per day with additional kilometres charged 
								at the rate of $<%= $this->Page->RentalQuote('KMRate') %>/km.</td></tr>
					<com:TPlaceHolder
						Visible="#$this->Page->RentalWizard->PremiumProtect->Checked || $this->Page->RentalWizard->BabySeat->Checked" >
					<tr><th colspan="2">Options:</th></tr>
					</com:TPlaceHolder>
					<com:TPlaceHolder 
						Visible="#$this->Page->RentalWizard->PremiumProtect->Checked" >
					<tr>
						<td>Premium Protection Cover for <%= $this->Page->RentalQuote('Days') %> day(s) 
							@ $<%= $this->Page->RentalQuote('CoverRate') %>/day.</td>
						<td class="price">$<%= $this->Page->RentalQuote('Cover') %></td>
					</tr>
					</com:TPlaceHolder>
					<com:TPlaceHolder 
						Visible="#$this->Page->RentalWizard->BabySeat->Checked" >
					<tr>
						<td><%= $this->Page->RentalWizard->BabySeatQty->Text %> *
							Baby Seat for <%= $this->Page->RentalQuote('Days') %> day(s) @ $5.00/day
							(max charge $33.00 each).
						</td>
						<td class="price">$<%= $this->Page->RentalQuote('BabySeat') %></td>
					</tr>
					</com:TPlaceHolder>
					<tr><th colspan="2">Taxes &amp; Fees:</th></tr>
					<tr>
						<td>Vehicle Registration Recovery Fee</td>
						<td class="price">$<%= $this->Page->RentalQuote('Duty1') %></td>
					</tr>		
					<tr>
						<td>Stamp Duty Recovery Fee</td>
						<td class="price">$<%= $this->Page->RentalQuote('Duty2') %></td>
					</tr>										
					<tr>
						<th class="price">Total Price:</th>
						<th class="price">$<%= $this->Page->RentalQuote('Total') %></th>
					</tr>		
					<tr>
						<td class="price">Includes GST of:</td>
						<td class="price">$<%= $this->Page->RentalQuote('GST') %></td>
					</tr>							
				</table>	
			</com:TPanel>
		</com:TPanel>
		<!-- end interary list -->

	</com:TWizard>

</com:TForm> 

<p class="copyright">Copyrights 2005 Xiang Wei Zhuo. All right reserved.</p>
</body>
</html>
