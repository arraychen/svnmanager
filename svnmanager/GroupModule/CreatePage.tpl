<%include SVNManagerApp.global.header %>
<h1>SVN Admin</h1>
<h2>Create New Group</h2>
<com:TPanel ID="CreatePanel" Visible="true">
<table>
	<tr>
		<th>Name</th>
		<td>
			<com:TTextBox ID="Name" MaxLength="32" Columns="16"/>
			<com:TRequiredFieldValidator ControlToValidate="Name" Display="Dynamic">
			Please enter a name for the new group!
			</com:TRequiredFieldValidator>
			<com:TCustomValidator ControlToValidate="Name" OnServerValidate="isNotTaken" Display="Dynamic" >
			This group exists, please choose another!
			</com:TCustomValidator>
		</td>
	</tr>
	<tr>
		<td>
			<com:TButton ID="Confirm" Text="Confirm" OnClick="onConfirmBtn" />
		</td>
		<td align="right">
			<com:TButton ID="Cancel" Text="Cancel" CausesValidation="false" OnClick="onCancelBtn" />
		</td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">Group created!</h4>
	<com:TLinkButton Text="Go back to Group Admin menu" OnClick="onGoBack"/></br>
	<com:TLinkButton Text="Edit new group" OnClick="onEdit" />
</com:TPanel>
<%include SVNManagerApp.global.footer %>