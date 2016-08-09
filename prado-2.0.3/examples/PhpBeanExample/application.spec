<?xml version="1.0" encoding="UTF-8"?>
<application ID="PhpBeanExample">
	<request default="HelloWorld" />
    <alias name="Pages" path="." />
    <using namespace="System.Web.UI.WebControls" />
    <using namespace="Pages" />
    
    <using namespace="System.Web.Services" />
    
    <services>
    	<service 
    		type="PhpBeans"
    		host="jasrags.net"
    		user="HelloWorld"
    		pass="HelloWorld"
    		port="3843"
    		timeout="2">
    		<class name="HelloWorld" />
    	</service>
    </services>
</application>