<%include SVNManagerApp.global.header %>
<h1>Add User</h1>
<com:TPanel ID="AddPanel" Visible="true">
<table cellspacing="0" cellpadding="5">
	<tr>
		<th align="right">Name</th>
		<td>
			<com:TTextBox ID="UserName"/>
			<com:TRequiredFieldValidator ControlToValidate="UserName" ErrorMessage="User name is required"/>
			<com:TCustomValidator 		ControlToValidate="UserName" OnServerValidate="onCheckUsername" ErrorMessage="Name is already taken" Display="Dynamic" />
		</td>
	</tr>
	<tr>
		<th align="right">Password</th>
		<td>
			<com:TTextBox ID="Password" TextMode="Password" />
			<com:TRequiredFieldValidator ControlToValidate="Password" ErrorMessage="Password is required"/>
		</td>
	</tr>
	<tr>
		<th align="right">Repeat password</th>
		<td>
			<com:TTextBox ID="RepeatPassword" TextMode="Password" />
			<com:TCompareValidator ControlToValidate="Password" ControlToCompare="RepeatPassword" Display="Dynamic">Your password entries did not match.</com:TCompareValidator>
			<com:TRequiredFieldValidator ControlToValidate="RepeatPassword" ErrorMessage="Repeat is required"/>			
		</td>
	</tr>
	<tr>
		<th align="right">Email</th>
		<td>
			<com:TTextBox ID="Email" Columns="32" />
			<com:TRequiredFieldValidator ControlToValidate="Email" ErrorMessage="Email is required"/>
			<com:TEmailAddressValidator ControlToValidate="Email" Display="Dynamic">Invalid e-mail address!</com:TEmailAddressValidator>
		</td>
	</tr>
	<tr>
		<th align="right">Admin</th><td><com:TCheckBox ID="Admin" /></td>
	</tr>
	<tr>
		<th align="right">Repository Grants</th>
			<td>
				<com:TTextBox ID="Grants" Text="0" />
				<com:TRequiredFieldValidator ControlToValidate="Grants" ErrorMessage="Grants is required"/>				
				<com:TRangeValidator ControlToValidate="Grants" MinValue="0" MaxValue="1000" ValueType="Integer" ErrorMessage="Please enter a number between 0 and 1000" />
			</td>
	</tr>
	<tr>
		<th class="altth" align="right">Password</th>
		<td>
			<com:TTextBox ID="UserPassword" TextMode="Password" />
			<com:TRequiredFieldValidator ControlToValidate="UserPassword" ErrorMessage="Please enter your password" Display="Dynamic"/>
			<com:TCustomValidator ControlToValidate="UserPassword" OnServerValidate="onCheckPassword" ErrorMessage="Wrong password" Display="Dynamic" />
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
	<h3 class="message">User added!</h4>
	<com:TLinkButton Text="Go back to User Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>