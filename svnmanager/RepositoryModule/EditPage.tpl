<%include SVNManagerApp.global.header %>
<h1>Change repository</h1>
<com:TPanel ID="EditPanel" Visible="true">
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
		<th>Description</th>
		<td>
			<com:TTextBox ID="Description" MaxLength="127" Columns="40"/>
			<com:TRequiredFieldValidator ControlToValidate="Description" Display="Dynamic">
			Please enter a description for the new repository!
			</com:TRequiredFieldValidator>
		</td>
	</tr>
	<tr>
		<th>Owner</th>
		<td><com:TListBox ID="Owner" Rows="1"/></td>
	</tr>
	<tr>
		<td><com:TButton ID="ConfirmButton" Text="Confirm" OnCommand="onConfirmButton" /></td>
		<td><com:TButton ID="CancelButton" Text="Cancel" OnCommand="onCancelButton" CausesValidation="false" /></td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">Repository changed!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<%include SVNManagerApp.global.footer %>