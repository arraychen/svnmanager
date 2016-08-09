<%include Pages.header %>
<h1>PRADO Phonebook</h1>
<com:TForm ID="Form">
<table border="0" cellspacing="0" cellpadding="5">
<tr><th colspan="2">Login</th></tr>
<tr>
  <td align="right">Username</td><td><com:TTextBox ID="Username" />
  <com:TRequiredFieldValidator ControlToValidate="Username" ErrorMessage="Username is required." />
  </td>
</tr>
<tr>
  <td align="right">Password</td><td><com:TTextBox ID="Password" TextMode="Password" />
  <com:TCustomValidator ControlToValidate="Password" OnServerValidate="onLogin" ErrorMessage="Login failed." />
  </td>
</tr>
<tr>
  <td></td><td><com:TButton Text="Login" /></td>
</tr>
</table>
</com:TForm>
<p>As a demo, the valid username and password are <i>root</i> and <i>prado</i>, respectively.</p>
<%include Pages.footer %>