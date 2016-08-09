<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Editor Dialog</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
	/*<![CDATA[*/
	.textarea textarea { width: 100%; background-color: White; border: 1px solid #ccf; color: black;}
	.buttons { text-align: center; } 
	.buttons .button { margin: 0.5em; }
	body{ width: 99%; margin: 0.5em auto; }
	.hidden { display: none; }
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
		}
	};
	
	function setText(sourceText, targetText, commentText, IDString)
	{
		var source = DOM.find('Source');
		var source2 = DOM.find('SourceText');
		var target = DOM.find('Target');
		var comment = DOM.find('Comments');
		var sid = DOM.find('SID');
		source.value = sourceText;
		source2.value = sourceText
		target.value = targetText;
		comment.value = commentText;
		sid.value = IDString;

	}

	function update()
	{
		if(typeof window == 'undefined' || typeof window.top == 'undefined')
			return;

		var List = window.top.MessageList;

		if(typeof List == 'undefined')
			return;

		var source = DOM.find('Source');
		var target = DOM.find('Target');
		var comment = DOM.find('Comments');
		var sid = DOM.find('SID');
		var result = DOM.find('UpdateList');

		if(sid.value.length <= 0) return;
		if(source.value.length <= 0) return;

		if(result.value == 'true')
			List.update(sid.value, target.value, comment.value);
		else if (result.value == 'false')
			alert('<com:TTranslate>System error, unable to update translation.</com:TTranslate>');

	}
</script>
</head>

<body onload="update()">
<com:TForm>
<div class="textarea">
<com:TTextBox class="textarea" ID="SourceText" TextMode="MultiLine" Rows="4"/>
<com:TTextBox class="textarea" ID="Target" TextMode="MultiLine" Rows="4" />
<com:TTextBox class="textarea" ID="Comments" TextMode="MultiLine" Rows="2" />
<com:TTextBox class="hidden" ID="SID" />
<com:TTextBox class="hidden" ID="UpdateList" />
<com:TTextBox class="hidden" ID="Source" />
</div>
<div class="buttons">
<com:TButton class="button" Text="#localize('Update Translation')" OnClick="updateTranslation" />
</com:TForm>
</div>
</body>
</html>