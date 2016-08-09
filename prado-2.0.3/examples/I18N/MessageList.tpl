<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Message Translations</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
	/*<![CDATA[*/
	body { margin: 0 auto;	width: 99%; margin-bottom: 2em;}
	.transunit { border-bottom: 1px dashed #ccc; }	
    .source, .target { width: 45%; float: left; }
    .transunit a { color: black; text-decoration: none; padding: 0.5em; display: block; }
    .transunit a:hover { background-color: #ECF2FD; }
    .alt1 a { background-color: #ffe; }
	.spacer, .status { width: 4px;  float: left; font-size: 4px; margin: 3px 5px 0 0; }
	.status { border: 3px double white;}
	.alt1 .status { border-color: #ffe; }
	a:hover .status { border-color: #ECF2FD; }
	div.transunit a .edit { border-color: #fc0; background-color: white; }
	div.transunit a:hover .edit { border-color: #FF8C00; }
	div.transunit .new { border-color: #A52A2A; background-color: #FBF2F2; }
	div.transunit a:hover .new  { border-color: Red; }
	a.active {	background-color: #fdf; }
	.active .status, a.active:hover .status { border-color: blue; }
	.comments { display: none; }
	
	/*]]>*/
</style>
<script type="text/javascript">
	
	var DOM = 
	{
		find : function(n, d) { //v4.01
			var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
			if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
			for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=DOM.find(n,d.layers[i].document);
			if(!x && d.getElementById) x=d.getElementById(n); return x;
		},
		unescape : function(text)
		{
			text = text.replace(/&gt;/g,'>');
			text = text.replace(/&lt;/g,'<');
			return text;
		},
		escape : function(text)
		{
			text = text.replace(/>/g,'&gt;');
			text = text.replace(/</g,'&lt;');	
			return text;
		}
	};
	
	function edit(id)
	{		
		if(typeof window == 'undefined' || typeof window.top == 'undefined')
			return;

		var Editor = window.top.Editor;

		if(typeof Editor == 'undefined')
			return;

		var sid = 's_'+id;
		obj = DOM.find(sid);
		var source, target, comments;
		
		for(var i = 0; i < obj.childNodes.length; i++)
		{
			if(obj.childNodes[i].className == "source")			
				source = obj.childNodes[i].innerHTML;
			if(obj.childNodes[i].className == "target")
				target = obj.childNodes[i].innerHTML;
			if(obj.childNodes[i].className == "comments")
				comments = obj.childNodes[i].innerHTML;
		}

		Editor.setText(	DOM.unescape(source), 
						DOM.unescape(target), 
						DOM.unescape(comments), sid);

		setLinkStatus(sid);
		
	}

	function setLinkStatus(id)
	{
		var links = document.links

		for(var i = 0; i < links.length; i++)
		{
			var link = links[i];
			if(link.id == id)
			{
				link.className += ' active';
			}
			else
			{
				if(typeof link.className != 'undefined')
					link.className = link.className.replace(/active|( active)/g, '');
			}
		}
	}

	function setStatus(id)
	{
		var link = document.links
		var active = DOM.find(id);
		for(var k = 0; k < active.childNodes.length; k++)
		{
			var status = active.childNodes[k];
			
			if(typeof status.className != 'undefined'
				&& status.className.indexOf('status') >= 0)
			{
				status.className = status.className.replace(/new|( new)/g, '');
				status.className += ' edit';
				break;
			}
		}
	}
	

	function update(sid, targetText, commentText)
	{
		obj = DOM.find(sid);
		var target, comments;
		
		for(var i = 0; i < obj.childNodes.length; i++)
		{
			if(obj.childNodes[i].className == "target")
				target = obj.childNodes[i];
			if(obj.childNodes[i].className == "comments")
				comments = obj.childNodes[i];
		}
		target.innerHTML = DOM.escape(targetText);
		comments.innerHTML = DOM.escape(commentText);
		setStatus(sid);		
	}

</script>
</head>

<body>
<com:TRepeater ID="MessageList">
<prop:ItemTemplate>
<div class="transunit alt1">
	<a href="javascript:edit('<%= $this->Parent->Data[1] %>');" id="s_<%= $this->Parent->Data[1] %>">
	<span class="status <% if(strlen($this->Parent->Data[0])<=0) echo 'new'; %>">&nbsp;</span>
	<tt class="source"><%= htmlspecialchars($this->Parent->Index) %></tt>
	<span class="spacer">&nbsp;</span>
	<tt class="target"><%= htmlspecialchars($this->Parent->Data[0]) %></tt>
	<span class="comments"><%= htmlspecialchars($this->Parent->Data[2]) %></span>
	<div style="clear:both;"></div>
	</a>
</div>
</prop:ItemTemplate>
<prop:AlternatingItemTemplate>
<div class="transunit alt2">
	<a href="javascript:edit('<%= $this->Parent->Data[1] %>');" id="s_<%= $this->Parent->Data[1] %>">
	<span class="status <% if(strlen($this->Parent->Data[0])<=0) echo 'new'; %>">&nbsp;</span>
	<tt class="source"><%= htmlspecialchars($this->Parent->Index) %></tt>
	<span class="spacer">&nbsp;</span>
	<tt class="target"><%= htmlspecialchars($this->Parent->Data[0]) %></tt>
	<span class="comments"><%= htmlspecialchars($this->Parent->Data[2]) %></span>
	<div style="clear:both;"></div>
	</a>
</div>
</prop:AlternatingItemTemplate>
</com:TRepeater>

</body>
</html>
