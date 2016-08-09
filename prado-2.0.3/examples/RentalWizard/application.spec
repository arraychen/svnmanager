<?xml version="1.0" encoding="UTF-8"?>
<application ID="RentalWizard" state="debug">
	
	<request default="VehicleRental:IndexPage" />
	<session enabled="true" />
	<vsmanager enabled="true" />

    <alias name="Pages" path="." />
    
	<using namespace="System.Web.UI.WebControls" />
    <using namespace="Pages" />

	<globalization />

  <module ID="VehicleRental" class="VehicleRentalModule" />

</application>