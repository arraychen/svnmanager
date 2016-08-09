<%include Pages.header %>
<h1>PRADO Phonebook</h1>
<com:TForm ID="Form">
<p style="font-weight:bold;font-size:16px">
<a href="phonebook.php?page=AddEntryPage">Add Contact</a> | 
<com:THyperLink ID="ShowAll" Text="All" NavigateUrl="phonebook.php?page=HomePage" /> 
<com:TPlaceHolder ID="Filters" /> |
<com:THyperLink Text="Login" NavigateUrl="phonebook.php?page=LoginPage" Visible="#!$this->User->isAuthenticated()" />
<com:TLinkButton Text="Logout" Visible="#$this->User->isAuthenticated()" OnClick="onLogout" />
</p>
<com:TRepeater ID="EntryTable" OnItemCommand="onEntryAction">
<prop:HeaderTemplate>
<table border="0" cellspacing="0" cellpadding="5">
<tr><th>Name</th><th>Email</th><th>Phone Numbers</th><th>Address</th><th>Memo</th><th>Actions</th></tr>
</prop:HeaderTemplate>
<prop:ItemTemplate>
<tr class="row1">
  <td><%= htmlentities(ucfirst($this->Parent->Data['name'])) %></td>
  <td><com:THyperLink NavigateUrl="#'mailto:'.$this->Parent->Data['email']" Text="#$this->Parent->Data['email']" /></td>
  <td><%= htmlentities($this->Parent->Data['phone']) %></td>
  <td><%= htmlentities($this->Parent->Data['address']) %></td>
  <td><%= htmlentities($this->Parent->Data['memo']) %></td>
  <td>
  <com:THyperLink Text="update" NavigateUrl="#'phonebook.php?page=EditEntryPage&id='.$this->Parent->Data['id']" />
  <com:TLinkButton Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if(!confirm('Are you sure?')) return false;" />
  </td>
</tr>
</prop:ItemTemplate>
<prop:AlternatingItemTemplate>
<tr class="row2">
  <td><%= htmlentities(ucfirst($this->Parent->Data['name'])) %></td>
  <td><com:THyperLink NavigateUrl="#'mailto:'.$this->Parent->Data['email']" Text="#$this->Parent->Data['email']" /></td>
  <td><%= htmlentities($this->Parent->Data['phone']) %></td>
  <td><%= htmlentities($this->Parent->Data['address']) %></td>
  <td><%= htmlentities($this->Parent->Data['memo']) %></td>
  <td>
  <com:THyperLink Text="update" NavigateUrl="#'phonebook.php?page=EditEntryPage&id='.$this->Parent->Data['id']" />
  <com:TLinkButton Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if(!confirm('Are you sure?')) return false;" />
  </td>
</tr>
</prop:AlternatingItemTemplate>
<prop:FooterTemplate>
</table>
</prop:FooterTemplate>
</com:TRepeater>
</com:TForm>
<%include Pages.footer %>