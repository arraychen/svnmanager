<%include SVNManagerApp.global.header %>
<h1>Activation</h1>
<br/>
<com:TPanel Visible="#$this->Page->isValidTicket()" >
Please enter a username and password for your new account!
	<table>
		<tr>
		  <td align="right" valign="top">Username</td>
		  <td><com:TTextBox ID="Username" AutoTrim="true" />
		  <com:TRequiredFieldValidator ControlToValidate="Username" Display="Dynamic"><br/>Please choose a username.</com:TRequiredFieldValidator>
		  <com:TRegularExpressionValidator ControlToValidate="Username" Display="Dynamic" RegularExpression="[\w]{3,16}"><br/>Your username must contain only letters, digits and underscores, and it must contain at least 3 and at most 16 characters.</com:TRegularExpressionValidator>
		  <com:TCustomValidator ControlToValidate="Username" OnServerValidate="isUsernameTaken" Display="Dynamic"><br/>Sorry, your username is taken by someone else. Please choose another username.</com:TCustomValidator>
		  </td>
		</tr>
		<tr>
		  <td align="right" valign="top">Password</td>
		  <td><com:TTextBox ID="Password" TextMode="Password" />
		  <com:TRequiredFieldValidator ControlToValidate="Password" Display="Dynamic"><br/>Please choose a password.</com:TRequiredFieldValidator>
		  <com:TRegularExpressionValidator ControlToValidate="Password" Display="Dynamic" RegularExpression="[\w\.]{6,16}"><br/>Your password must contain only letters, digits and underscores, and it must contain at least 6 and at most 16 characters.</com:TRegularExpressionValidator>
		  </td>
		</tr>
		<tr>
		  <td align="right" valign="top">Re-type Password</td>
		  <td><com:TTextBox ID="Password2" TextMode="Password" />
		  <com:TRequiredFieldValidator ControlToValidate="Password2" Display="Dynamic"><br/>Please re-type your password.</com:TRequiredFieldValidator>
		  <com:TCompareValidator ControlToValidate="Password2" ControlToCompare="Password" Display="Dynamic"><br/>Your password entries did not match.</com:TCompareValidator>
		  </td>
		</tr>
		<tr><td><com:TButton ID="ConfirmButton" Text="Confirm" OnClick="onConfirmBtn" /></td></tr>
	</table>
</com:TPanel>
<com:TPanel Visible="#!$this->Page->isValidTicket()" >
	<div style="color : red;">
		<h2>Invalid ticket! Please contact administrator!</h2>
	</div>
</com:TPanel>
<%include SVNManagerApp.global.footer %>