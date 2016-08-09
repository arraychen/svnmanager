<%@Page Master="LayoutPage" %>
<com:TContent ID="content">
<table border="0" cellspacing="0" cellpadding="5" width="400">
<tr><th colspan="2">Login</th></tr>
<tr>
  <td align="right">Username</td><td><com:TTextBox ID="Username" />
  <com:TRequiredFieldValidator ControlToValidate="Username" ErrorMessage="Username is required." Display="Dynamic" />
  </td>
</tr>
<tr>
  <td align="right">Password</td><td><com:TTextBox ID="Password" TextMode="Password" />
  <com:TCustomValidator ControlToValidate="Password" OnServerValidate="Page.onLogin" ErrorMessage="Login failed." Display="Dynamic" />
  <com:TRequiredFieldValidator ControlToValidate="Password" ErrorMessage="Password is required." Display="Dynamic" />
  </td>
</tr>
<tr>
  <td></td><td><com:TButton Text="Login" OnClick="Page.onClickLoginBtn" /></td>
</tr>
</table>
</com:TContent>