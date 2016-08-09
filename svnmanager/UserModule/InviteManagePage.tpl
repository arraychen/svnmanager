<%include SVNManagerApp.global.header %>
<h1>Invitation Management</h1>
<com:TPanel ID="MainPanel" Visible="true">
<table cellspacing="0" cellpadding="5">
	<tr><th>Email</th></tr>
<com:TRepeater ID="UserTable">
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><com:TLabel ID="emailField" Text="#$this->Parent->Data['email']" /></td>
			<td><com:TButton Text="Delete" CommandName="deleteInvite" OnCommand="Page.deleteInvite" /></td>
			<td><com:TButton Text="Send Again" CommandName="sendInviteAgain" OnCommand="Page.sendInviteAgain" /></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><com:TLabel ID="emailField" Text="#$this->Parent->Data['email']" /></td>
			<td><com:TButton Text="Delete" CommandName="deleteInvite" OnCommand="Page.deleteInvite" /></td>
			<td><com:TButton Text="Send Again" CommandName="sendInviteAgain" OnCommand="Page.sendInviteAgain" /></td>
		</tr>
	</prop:AlternatingItemTemplate>
	<prop:FooterTemplate>
	</prop:FooterTemplate>		
</com:TRepeater>
<tr>
	<td></td>
	<td></td>
	<td align="right">
		<com:TButton Text="Cancel" OnClick="onCancelBtn" CausesValidation="false" />
	</td>
</tr>
</table>
</com:TPanel>
<com:TPanel ID="SendAgainPanel" Visible="false">
	<h3 class="message">Invitation sent again!</h4>
	<com:TLinkButton Text="Go back to Invitation Management" OnClick="onGoBack"/>
</com:TPanel>
<com:TPanel ID="SendAgainErrorPanel" Visible="false">
	<h3 class="message">Error emailing invitation sent again!</h4>
	<com:TLinkButton Text="Go back to Invitation Management" OnClick="onGoBack"/>
</com:TPanel>
<com:TPanel ID="DeletePanel" Visible="false">
	<h3 class="message">Invitation deleted!</h4>
	<com:TLinkButton Text="Go back to Invitation Management" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>