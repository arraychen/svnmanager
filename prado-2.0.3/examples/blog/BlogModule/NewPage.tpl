<%@Page Master="LayoutPage" %>
<com:TContent ID="content">
<table border="0" cellspacing="0" cellpadding="5" width="400">
<tr><th colspan="2">New blog</th></tr>
<tr>
  <td align="right" valign="top">Title</td>
  <td><com:TTextBox ID="Title" Columns="40" AutoTrim="true" />
  <com:TRequiredFieldValidator ControlToValidate="Title" Display="Dynamic"><br/>Please choose a title.</com:TRequiredFieldValidator>
  </td>
</tr>
<tr>
  <td align="right" valign="top">Content</td>
  <td><com:THtmlArea ID="Content" Rows="15"/>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><com:TButton Text="Post" OnClick="Page.onClickPostBtn" /></td>
</tr>
</table>
</com:TContent>