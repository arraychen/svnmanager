<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><com:TTranslate>PRADO Translation Editor</com:TTranslate></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
	/*<![CDATA[*/
	html { background-color: #E5E8EA; }
	.msglist, .editor { width: 100%; height: 250px; border: 1px solid #ccc;	}
	.editor { width: 100%; height: 35ex; border: none; }
    body {	padding: 1em; width: 45em; margin: 0 auto; background-color: white; text-align: center; font-family: Georgia,"Times New Roman", Times, serif; }
	.colHeader { font-weight: bold; padding: 0.3em; text-align: left; width: 100%; margin: 0 auto; }
	.colHeader span { width: 47%; float: left; }
	.user { background-color: #ECF2FD; padding: 0.5em 1em; text-align: right; }
	.user .welcome { float: left; font-weight: bold; }
	.options { border: 1px solid #C6CCD2; text-align: right; padding: 0.5em; margin: 1em 0; background-color: #F5F5F5; }
	.source { float: left; }
	.user a { text-decoration: none; }
	.user a:hover { color: red;}
	.links a { margin-left: 0.5em; }
	.copyrights span { display:block; margin: 1em; }
	/*]]>*/
</style>
</head>

<body>
<com:TForm>
<div class="user">
	<span class="welcome"><com:TTranslate>PRADO Translation Editor</com:TTranslate></span>
	<span class="links">
		<com:TLinkButton Text="English" OnCommand="changeLanguage" CommandParameter="en" CausesValidation="false" />
		<com:TLinkButton Text="中文简体" OnCommand="changeLanguage" CommandParameter="zh" CausesValidation="false" /> 
		<com:TLinkButton Text="Deutsch" OnCommand="changeLanguage" CommandParameter="de" CausesValidation="false" />
		<com:TLinkButton Text="Español" OnCommand="changeLanguage" CommandParameter="es" CausesValidation="false" /> 
		<com:TLinkButton Text="Français" OnCommand="changeLanguage" CommandParameter="fr" CausesValidation="false" /> 
		<com:TLinkButton Text="Polski" OnCommand="changeLanguage" CommandParameter="pl" CausesValidation="false" /> 
	</span>
</div>
<div class="options">
	<div class="source">
		<com:TTranslate>Source:</com:TTranslate> <com:TTextBox ID="Source" />
		<com:TTranslate>Type:</com:TTranslate> 
		<com:TDropDownList ID="Type">
			<com:TListItem Value="XLIFF" Text="XLIFF (XML)" />
			<com:TListItem Value="SQLite" Text="SQLite" />
			<com:TListItem Value="MySQL" Text="MySQL" />
			<com:TListItem Value="gettext" Text="Gettext" />			
		</com:TDropDownList>
		<com:TButton Text="#localize('Reload')" OnClick="reloadSource" />
	</div>
	<span class="catalouge">
		<com:TTranslate>Catalogue:</com:TTranslate> <com:TDropDownList ID="CatalogueList" AutoPostBack="true"/>
	</span>
</div>

<div class="colHeader">
	<span><com:TTranslate>Original string</com:TTranslate></span>
	<span><com:TTranslate>Translation</com:TTranslate></span>
	<div style="clear:both;"></div>
</div>
<iframe id="MessageList" name="MessageList" src="<%= $this->Page->getEditorURL('MessageList'); %>" class="msglist" scrolling="yes" frameborder="0"></iframe>
<iframe id="Editor" name="Editor" src="<%= $this->Page->getEditorURL('Dialog'); %>" class="editor" scrolling="no" frameborder="0"></iframe>
</com:TForm>
<div class="copyrights">
	<com:TTranslate>Copyrights 2005 Xiang Wei Zhuo. All right reserved.</com:TTranslate>
	<span>
	<a href="i18n.php?page=IndexPage"><com:TTranslate>Internationlization in PRADO</com:TTranslate></a>
	</span>
</div>
</body>
</html>