<%include SVNManagerApp.global.header %>
<h1>Repository administration</h1>
<table class="buttonmenu">
<com:TPanel Visible="#$this->Module->hasGrants()">
	<tr>
		<td>
			Create a new Repository
		</td>
		<td>
			<com:TButton ID="CreateButton" Text="Create" OnClick="onClickCreateBtn" />
		</td>
	</tr>
</com:TPanel>
<com:TPanel Visible="#$this->Module->ownsRepositories()||$this->User->isAdmin()">
	<tr>
		<td>
			Remove a Repository
		</td>
		<td>
			<com:TButton ID="RemoveButton" Text="Remove" OnClick="onClickRemoveBtn" />
		</td>
	</tr>
	<tr>
		<td>
			Edit a Repository
		</td>
		<td>
			<com:TButton ID="EditButton" Text="Edit" OnClick="onClickEditBtn" />
		</td>
	</tr>
	<tr><td> </td></tr>
	<tr>
		<td>
			Change User Privileges of a Repository
		</td>
		<td>
			<com:TButton ID="UserPrivilegesButton" Text="User Privileges" OnClick="onClickUserPrivilegesBtn" />
		</td>
	</tr>
	<tr>
		<td>
			Change Group Privileges of a Repository
		</td>
		<td>
			<com:TButton ID="GroupPrivilegesButton" Text="Group Privileges" OnClick="onClickGroupPrivilegesBtn" />		
		</td>
	</tr>
	<tr><td> </td></tr>
	<tr>
		<td>
			Download a Repository Dump
		</td>
		<td>
			<com:TButton ID="DumpRepositoryButton" Text="Dump" OnClick="onClickDumpBtn" />		
		</td>
	</tr>
</com:TPanel>	
<com:TPanel Visible="#$this->Module->hasGrants()" >
	<tr>
		<td>
			Upload a Repository Dump
		</td>
		<td>
			<com:TButton ID="LoadRepositoryButton" Text="Load" OnClick="onClickLoadBtn" />		
		</td>
	</tr>
</com:TPanel>
<com:TPanel Visible="#$this->Module->ownsRepositories()||$this->User->isAdmin()">
	<tr>
		<td>
			Recover a (not working) Repository
		</td>
		<td>
			<com:TButton ID="RecoverRepositoryButton" Text="Recover" OnClick="onClickRecoverBtn" />
		</td>
	<tr>
</com:TPanel>
<com:TPanel Visible="#$this->User->isAdmin()">
	<tr>
		<td>
			Import existing repositories not controlled by SVNManager
		</td>
		<td>
			<com:TButton ID="ImportRepositoryButton" Text="Import" OnClick="onClickImportBtn" />
		</td>
	<tr>
</com:TPanel>
</table>
<%include SVNManagerApp.global.footer %>