<%include SVNManagerApp.global.header %>
<h1>SVN Admin</h1>
<h2>Change group</h2>
<com:TPanel ID="ChangePanel" Visible="true" >
<table cellspacing="0" cellpadding="5">
	<tr>
		<th>Name</th>
		<td>
			<com:TTextBox ID="Name" />
			<com:TRequiredFieldValidator ControlToValidate="Name" Display="Dynamic">Please enter a name!</com:TRequiredFieldValidator>
			<com:TCustomValidator ControlToValidate="Name" OnServerValidate="isValidName" Display="Dynamic">Please choose other name!</com:TCustomValidator>
		</td>
	</tr>
	<tr>
		<th>Owner</th>
		<td><com:TListBox ID="Owner" Rows="1" /></td>
	</tr>
	<tr>
		<th>Members</th>
		<td><com:TListBox ID="Members" SelectionMode="Multiple" Rows="10" /></td>
	</tr>
	<tr>
		<td><com:TButton ID="ConfirmButton" Text="Confirm" OnCommand="onConfirmButton" /></td>
		<td align="right"><com:TButton ID="CancelButton" Text="Cancel" OnCommand="onCancelButton" CausesValidation="false" /></td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">Group changed!</h4>
	<com:TLinkButton Text="Go back to Group Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>