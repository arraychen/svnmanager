<?xml version="1.0" encoding="UTF-8" ?>
<application ID="SVNManager" state="debug">
	<request default="Main:StartPage" format="get" />
	<user class="SVNManagerUser" />
	<session enabled="true" />
	<cache enabled="false" />
	<parser class="TResourceParser" />
	<locator class="TResourceLocator" />

	<alias name="SVNManagerApp" path="." />

	<using namespace="System.Web.UI.WebControls" />
	<using namespace="System.Security" />
	<using namespace="SVNManagerApp" />
	<using namespace="SVNManagerApp.global" />	
	<using namespace="SVNManagerApp.library" />
	
	<module ID="Main" class="DataModule">
		<using namespace="SVNManagerApp.MainModule" />		
	</module>

	<module ID="User" class="DataModule">
		<using namespace="SVNManagerApp.UserModule" />		
		<secured page="AddPage" />
		<secured page="AdminPage" />
		<secured page="EditPage" />
		<secured page="EditSelectPage" />
		<secured page="InvitePage" />
		<secured page="InviteManagePage" />
		<secured page="RemovePage" />
	</module>
	
	<module ID="Repository" class="DataModule">
		<using namespace="SVNManagerApp.RepositoryModule" />
		<secured page="AdminPage" />
		<secured page="CreatePage" />
		<secured page="DumpAnnouncePage" />
		<secured page="DumpOutputPage" /> 
		<secured page="DumpPage" />
		<secured page="EditPage" />
		<secured page="EditSelectPage" />
		<secured page="GroupPrivilegesEditPage" />
		<secured page="GroupPrivilegesPage" />
		<secured page="ImportSelectPage" />
		<secured page="LoadPage" />
		<secured page="RemovePage" />
		<secured page="UserPrivilegesEditPage" />
		<secured page="UserPrivilegesPage" />
	</module>	
	
	<module ID="Group" class="DataModule">
		<using namespace="SVNManagerApp.GroupModule" />
		<secured page="AdminPage" /> 
		<secured page="CreatePage" />
		<secured page="EditPage" /> 
		<secured page="EditSelectPage" />
		<secured page="ExportPage" /> 
		<secured page="RemovePage" /> 		
	</module>

</application>