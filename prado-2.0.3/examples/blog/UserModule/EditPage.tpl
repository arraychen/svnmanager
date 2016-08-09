<%@Page Master="LayoutPage" %>
<com:TContent ID="content">
<table border="0" cellspacing="0" cellpadding="5" width="400">
<tr><th colspan="2">Update account</th></tr>
<tr>
  <td align="right" valign="top">Username</td>
  <td><com:TLabel ID="Username" Style="font-weight:bold" /></td>
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
<tr>
  <td align="right" valign="top">Email Address</td>
  <td><com:TTextBox ID="Email" AutoTrim="true" />
  <com:TRequiredFieldValidator ControlToValidate="Email" Display="Dynamic"><br/>Please provide your email address.</com:TRequiredFieldValidator>
  <com:TEmailAddressValidator ControlToValidate="Email" Display="Dynamic"><br/>You entered an invalid email address.</com:TEmailAddressValidator>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><com:TButton Text="Update" OnClick="Page.onClickUpdateBtn" /></td>
</tr>
</table>
</com:TContent>