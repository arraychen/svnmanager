<?xml version="1.0" encoding="UTF-8"?>
<application ID="I18NExample" state="debug">
	
	<request default="IndexPage" format="get" />

    <alias name="Pages" path="." />
    
	<using namespace="System.Web.UI.WebControls" />
    <using namespace="System.Security" />
	<using namespace="System.Data" />
    <using namespace="Pages" />

	<!-- Internationalization components -->
	<using namespace="System.I18N" />

	
	<!-- using a custom Globalization class, the MyHTTPGlobalization.php uses
	     the $_GET URL to find the prefered language. The fallback is determined
		 from the client browser's language settings. -->
	<globalization 
		class="MyHTTPGlobalization" 
		defaultCulture="en" 
		defaultCharset="UTF-8" >
		
		<!-- The translation type and source -->
		<translation type="XLIFF" source="I18N/messages" autosave="true" />
		<!-- <translation type="gettext" source="I18N/messages" autosave="true" /> -->
		<!-- <translation type="SQLite" source="sqlite:///I18N/messages/sqlite_messages.db" autosave="true" /> -->
		<!-- <translation type="MySQL" source="mysql://root@localhost/i18n_example" autosave="true" /> -->

		<cache dir="I18N/messages/cache" />
		<currency defaultSymbol="$" />

	</globalization>



  <!-- the PRADO Translation Editor example -->

  <module ID="Translation" class="TranslationData" />

</application>