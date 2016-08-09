<%include SVNManagerApp.global.header %>
<h1>Login</h1>
<table border="0" cellspacing="0" cellpadding="5" width="400">
<tr><th colspan="2">Login</th></tr>
<tr>
  <td align="right">Username</td><td><com:TTextBox ID="Username" />
  <com:TRequiredFieldValidator ControlToValidate="Username" ErrorMessage="Username is required." Display="Dynamic" />
  </td>
</tr>
<tr>
  <td align="right">Password</td><td><com:TTextBox ID="Password" TextMode="Password" />
  <com:TRequiredFieldValidator ControlToValidate="Password" ErrorMessage="Password is required." Display="Dynamic" />
  <com:TCustomValidator ControlToValidate="Password" OnServerValidate="onLogin" ErrorMessage="Login failed." Display="Dynamic" />  
  </td>
</tr>
<tr>
  <td></td><td><com:TButton Text="Login" OnClick="onClickLoginBtn" /></td>
</tr>
</table>
<%include SVNManagerApp.global.footer %>