<%include SVNManagerApp.global.header %>
<h1>User administration</h1>
<table class="buttonmenu">
	<com:TPanel Visible="#$this->User->isAdmin()">
		<tr>
			<td>
				Invite a new user to the repository system
			</td>
			<td>
				<com:TButton ID="InviteButton" Text="Invite" OnClick="onClickInviteBtn" />
			</td>
		</tr>
		<tr>
			<td>
				Manage existing invitations
			</td>
			<td>
				<com:TButton ID="InviteManageButton" Text="Manage Invite" OnClick="onClickManageInviteBtn" />
			</td>
		</tr>
		<tr>
			<td>
				Add a new user to the repository system
			</td>
			<td>
				<com:TButton ID="AddButton" Text="Add" OnClick="onClickAddBtn" />
			</td>
		</tr>
	</com:TPanel>
	<tr>
		<td>
			Edit a user
		</td>
		<td>
			<com:TButton ID="EditButton" Text="Edit" OnClick="onClickEditBtn" />
		</td>
	</tr>
	<com:TPanel Visible="#$this->User->isAdmin()">
		<tr>
			<td>
				Remove a user from the repository system
			</td>
			<td>
				<com:TButton ID="RemoveButton" Text="Remove" OnClick="onClickRemoveBtn" Visible="#$this->User->isAdmin()"/>
			</td>
		</tr>
	</com:TPanel>
</table>
<%include SVNManagerApp.global.footer %>