<%include Pages.header %>
<h1>PRADO Phonebook</h1>
<com:TForm ID="Form">
<p><font size="+1">[ <a href="phonebook.php?page=HomePage">back</a> ]</font></p>
<table border="0" cellspacing="0" cellpadding="5">
<tr><th colspan="2">Edit Contact</th></tr>
<tr>
  <td align="right">Name</td><td><com:TTextBox ID="Name" />
  <com:TRequiredFieldValidator ControlToValidate="Name" ErrorMessage="Name is required." />
  </td>
</tr>
<tr>
  <td align="right">Email Address</td><td><com:TTextBox ID="Email" /></td>
</tr>
<tr>
  <td align="right">Phone Numbers</td><td><com:TTextBox ID="Phone" Columns="40" /></td>
</tr>
<tr>
  <td align="right">Address</td><td><com:TTextBox ID="Address" Columns="40" /></td>
</tr>
<tr>
  <td align="right" valign="top">Memo</td><td><com:TTextBox ID="Memo" Columns="30" TextMode="MultiLine" Rows="6"/></td>
</tr>
<tr>
  <td></td><td><com:TButton Text="Submit" OnClick="editEntry" /></td>
</tr>
</table>
</com:TForm>
<%include Pages.footer %>