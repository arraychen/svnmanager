<%include SVNManagerApp.global.header %>
<h1>Change User</h1>
<com:TPanel ID="EditPanel" Visible="true">
<table cellspacing="0" cellpadding="5">
	<tr>
		<th align="right">ID</th><td><com:TLabel ID="UserID" /></td>
	</tr>
	<tr>
		<th align="right">Name</th>
		<td>
			<com:TTextBox ID="UserName" />
			<com:TCustomValidator ControlToValidate="UserName" Display="Dynamic" OnServerValidate="onCheckUsername" Text="Please choose other name" />
		</td>
	</tr>
	<tr>
		<th align="right">New password</th><td><com:TTextBox ID="NewPassword" TextMode="Password" /></td>
	</tr>
	<tr>
		<th align="right">Repeat new password</th>
		<td>
			<com:TTextBox ID="RepeatNewPassword" TextMode="Password" />
			<com:TCompareValidator ControlToValidate="NewPassword" ControlToCompare="RepeatNewPassword" Display="Dynamic">Your password entries did not match.</com:TCompareValidator>
		</td>
	</tr>
	<tr>
		<th align="right">Email</th>
		<td>
			<com:TTextBox ID="Email" Columns="32" />
			<com:TEmailAddressValidator ControlToValidate="Email" Display="Dynamic">Invalid e-mail address!</com:TEmailAddressValidator>
		</td>
	</tr>
	<tr>
		<th align="right">Admin</th><td><com:TCheckBox ID="Admin" /></td>
	</tr>
	<tr>
		<th align="right">Repository Grants</th><td><com:TTextBox ID="Grants" /></td>
	</tr>
	<tr>
		<th class="altth" align="right">Password</th>
		<td>
			<com:TTextBox ID="Password" TextMode="Password" />
			<com:TCustomValidator ControlToValidate="Password" OnServerValidate="onRequirePassword" ErrorMessage="Password required" Display="Dynamic" />
		</td>		
	</tr>
	<tr>
		<tr></td>
		<tr></td>
		<td><com:TButton ID="ConfirmButton" Text="Confirm" OnClick="onConfirmBtn" /></td>
		<td><com:TButton ID="CancelButton" Text="Cancel" CausesValidation="false" OnClick="onCancelBtn"/></td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false">
	<h3 class="message">Changes applied!</h4>
	<com:TLinkButton Text="Go back to User Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>