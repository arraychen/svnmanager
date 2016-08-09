<?xml version="1.0" encoding="UTF-8"?>
<application ID="phonebook" state="debug">
	<session enabled="true" />
	<user class="PhonebookUser" />
	<request default="HomePage" />
	<cache enabled="true" />
	<vsm enabled="false" />
    <alias name="Pages" path="." />
    <using namespace="System.Web.UI.WebControls" />
    <using namespace="System.Security" />
    <using namespace="Pages" />
	<secured page="AddEntryPage" />
	<secured page="EditEntryPage" />
	<parameter name="DSN">sqlite://phonebook%2Fphonebook.db</parameter>
</application>