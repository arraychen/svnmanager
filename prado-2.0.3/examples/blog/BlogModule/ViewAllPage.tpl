<%@Page Master="LayoutPage" %>
<com:TContent ID="content">
<com:TRepeater ID="BlogTable">
<prop:ItemTemplate>
<div class="blog">
  <div class="title"><%= ucfirst($this->Parent->Data['title']) %></div>
  <div class="content"><%= $this->Parent->Data['content'] %></div>
  <div class="author">
	last modified on <%= date('M d, Y h:ia',$this->Parent->Data['wtime']) %>
    by <%= $this->Parent->Data['author'] %>.
	[ <a href="blog.php?page=Blog:EditPage&id=<%= $this->Parent->Data['id'] %>">edit</a> ]
	[ <com:TLinkButton Text="delete" CommandParameter="#$this->Parent->Data['id']" OnCommand="Page.onClickDeleteBtn" onclick="if(!confirm('Are you sure?')) return false;"/> ]
  </div>
</div>
</prop:ItemTemplate>
</com:TRepeater>
</com:TContent>