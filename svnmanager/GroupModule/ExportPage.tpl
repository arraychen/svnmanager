<%include SVNManagerApp.global.header %>
<h1>Export Mailer Info</h1>
<PRE>
####################################################################
## This data is intended to be cut and pasted into a mailer.conf 
## file used with the mailer.py script
####################################################################
## Created <% echo date("l, Y-F-d h:i:s A"); %>
####################################################################
[Authors]<com:TRepeater ID="AuthorTable">
<prop:ItemTemplate>
<%= htmlspecialchars($this->Parent->Data['username']) %>=<%= htmlspecialchars($this->Parent->Data['emailaddress']) %></prop:ItemTemplate>
</com:TRepeater>

[Group-list]<com:TRepeater ID="GroupList" OnItemCreated="setGroupMembers">
<prop:ItemTemplate>
<%= htmlspecialchars($this->Parent->Data['groupname']) %>=<com:TRepeater ID="GroupMembers">
<prop:ItemTemplate><%= htmlspecialchars($this->Parent->Data['emailaddress']) %> </prop:ItemTemplate></com:TRepeater></prop:ItemTemplate></com:TRepeater>

</PRE>
<%include SVNManagerApp.global.footer %>
