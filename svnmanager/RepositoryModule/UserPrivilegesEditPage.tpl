<%include SVNManagerApp.global.header %>
<h1>User Privileges</h1>
<table cellspacing="0" cellpadding="5">
<tr>
	<th>Repository name:</th>
	<td><com:TLabel ID="RepositoryName" /></td>
	<th>Owner:</th>
	<td><com:TLabel ID="RepositoryOwner" /></td>
</tr>
<tr>
<td> </td>
</tr>
<tr>
	<th align="left" >User</th>
	<th align="left" >Path</th>
	<th align="left" >Read</th>
	<th align="left" >Write</th>
</tr>		
<com:TRepeater ID="RightsTable" OnItemCommand="onRemovePrivileges" >
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['path']) %></td>
			<td><com:TLabel Text="#$this->Parent->Data['read']" /></td>
			<td><com:TLabel Text="#$this->Parent->Data['write']" /></td>
			<td><com:TLinkButton Text="remove" class="warning" CommandName="remove" CommandParameter="#$this->Parent->Data['id']" CausesValidation="false" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['path']) %></td>
			<td><com:TLabel Text="#$this->Parent->Data['read']" /></td>
			<td><com:TLabel Text="#$this->Parent->Data['write']" /></td>
			<td><com:TLinkButton Text="remove" class="warning" CommandName="remove" CommandParameter="#$this->Parent->Data['id']" CausesValidation="false" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:AlternatingItemTemplate>
	<prop:FooterTemplate>
	</prop:FooterTemplate>		
</com:TRepeater>
<tr>
	<td>
		<com:TListBox ID="UserSelector" Rows="1" />
	</td>
	<td>
		<com:TPlaceHolder ID="PathHolder" />
	</td>			
	<td>
		<com:TCheckBox ID="Read" />
	</td>
	<td>
		<com:TCheckBox ID="Write" />
	</td>
	<td>
		<com:TButton ID="AddButton" Text="Add" OnClick="onClickAddBtn" />
	</td>
</tr>
<tr>
	<td></td>
	<td></td>	
	<td></td>
	<td></td>	
	<td align="right">
		<com:TButton ID="DoneButton" Text="Done" OnClick="onClickDoneBtn" CausesValidation="false" />
	</td>
</tr>
</table>
<%include SVNManagerApp.global.footer %>
