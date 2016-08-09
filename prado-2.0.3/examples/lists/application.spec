<?xml version="1.0" encoding="UTF-8"?>
<application ID="blog" state="debug">
	<request default="HomePage" format="get" />
	
    <alias name="Pages" path="." />
    <using namespace="System.Web.UI.WebControls" />
    <using namespace="Pages" />
	
	<parameter name="DSN">sqlite://blog%2Fblog.db</parameter>
</application>