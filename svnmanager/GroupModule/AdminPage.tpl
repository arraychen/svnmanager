<%include SVNManagerApp.global.header %>
<h1>Group administration</h1>
<table class="buttonmenu">
<com:TPanel Visible="#$this->User->isAdmin()">
	<tr>
		<td>
			Create a new Group
		</td>
		<td>
			<com:TButton ID="CreateButton" Text="Create" OnClick="onClickCreateBtn" />
		</td>
	</tr>
<com:TPanel Visible="#$this->Module->areGroups()">
	<tr>
		<td>
			Remove a group
		</td>
		<td>
			<com:TButton ID="RemoveButton" Text="Remove" OnClick="onClickRemoveBtn"  />
		</td>
	</tr>
	<tr>
		<td>
			Change a Group
		</td>
		<td>
			<com:TButton ID="EditButton" Text="Edit" OnClick="onClickEditBtn" />
		</td>
	</tr>
	<tr>
		<td>
			Export email addresses for mailer.conf
		</td>
		<td>
			<com:TButton ID="ExportButton" Text="Export" OnClick="onClickExportBtn" />
		</td>
	</tr>
</com:TPanel>
</table>
</com:TPanel>	
<%include SVNManagerApp.global.footer %>