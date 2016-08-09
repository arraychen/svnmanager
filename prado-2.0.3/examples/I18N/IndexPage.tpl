<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Internationlization  in PRADO</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
<!--
.string {color: #CC0000}
.component {color: #003399}
html { background-color: #E5E8EA; }
body { 
	padding: 2em 3em 3em 3em;
	font-family: Georgia,"Times New Roman", Times, serif;
	width: 37em;
	margin: 0 auto;
	background-color: white;
	line-height: 125%;
	background-image: url(right.gif);
	background-repeat: repeat-y;
	background-position: right;
}

pre
{
	font-size: 12px;
	font-family: "Courier New", Courier, monospace;
	border: 1px solid #DCDCDC;
	padding: 1.5em;
	background-color: #F0F8FF;
}
hr { margin: 2em 0; }
dd { margin-bottom: 1em; }
.links { background-color: #ECF2FD; padding: 0.5em 1em;}
.links a:active, .links a:visited { color: blue; }
.links a { margin-right: 0.5em; }
.links a:hover { color:red; }
-->
</style>
</head>
<body>
<com:TForm>
<h1>Internationlization  in PRADO</h1>
<p>This demo shows the basic components available in PRADO for the process of Internationalizing and Localizing web applications. This page utilize multiple locale (or culture) aware templates to show the appropriate localized page. To view a different localized version, please change your language settings in your browser.</p>

<p>
Your current culture is <com:TLabel Text="#$this->Page->cultureName()" />.
<div class="links">
	Available localized versions: 
	<com:LanguageList />
</div>
</p>

<p>
	In addition to culture aware template. Text can be localized using many different translation sources. PRADO provides gettext, XML, SQLite, and MySQL container for storing message translations. The example in the following link demonstrates how text can be localized using the TTranslate component. The demo application is a simple translation message editor.
<div class="links">
	<a href="i18n.php?page=Translation:Editor">PRADO Translation Editor</a>
</div>
</p>
<p>
Best of luck in localization with PRADO. <br />&#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
</p>
	<com:BrowserHelp />
<hr />
<h2>Introduction</h2>
<p> Developing and maintaining multi-language sites is a common problem for web developers. </p>
<dl>
<dt><strong>Internationalization (I18N)</strong></dt>
<dd>Process of developing a product in such a way that it works with data in different languages and can be adapted to various target markets without engineering changes. </dd>
<dt><strong>Localization (L10N)</strong></dt>
<dd>Subsequent process of translating and adapting a product to a given market's cultural conventions.</dd>
</dl>
<p>The following features are supported by PRADO: </p>
<ul>
  <li>Text translation</li>
  <li>Attribute translation (todo) </li>
  <li>Parameter substitution (with translation if needed) </li>
  <li>Date, number and currency formatting </li>
</ul>
<h2>Examples</h2>
<h3>Date: full pattern, en_GB culture</h3>
<h4>Example:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
<h4>Result:</h4>
<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
<hr />

<h3>Date: short pattern</h3>
<h4>Example:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
<h4>Result:</h4>
<com:TDateFormat ID="Time2" Pattern="short" /> <br />
<hr />

<h3>Date default pattern of <%= $this->Page->getCulture(); %> </h3>
<h4>Example:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
<h4>Result:</h4>
<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
<hr />

<com:TDateFormat Pattern="dd.MM.yyyy hh:mm:ss" />

<h3>Currency default format</h3>
<h4>Example:</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
<h4>Result:</h4>
<com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />
<hr />
<h3>Currency, de_DE culture with Euro</h3>
<h4>Example:</h4>
<pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
<h4>Result:</h4>
<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />

<hr />

<h3>Currency, US format with Euro</h3>
<h4>Example:</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
<h4>Result</h4>
<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
<hr />

<h3>Translation examples</h3>
<h4>Example:</h4>
<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
<h4>Result:</h4>
<com:TTranslate>Hello</com:TTranslate> <br />
<h4>Example:</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
<h4>Result:</h4>
<com:TTranslate Text="Goodbye" /> <br />

<hr />
<h3>Translation using a different catalogue </h3>
<h4>Example:</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
<h4>Result:</h4>
<com:TTranslate Text="Goodbye"> 
	<prop:Catalogue>tests</prop:Catalogue>
</com:TTranslate>
<br />


<hr />
<h3>Translation with parameter substitution</h3>
<h4>Example:</h4>
<pre>
&lt;com:TTranslate time=&quot;<span class="component">#time()</span>&quot;&gt;
    <span class="string">{greeting} {name}!, The unix-time is &quot;{time}&quot;.</span>
    &lt;com:TParam Key=&quot;name&quot;&gt;
        &lt;com:TTranslate Catalogue=&quot;tests&quot; Text=&quot;<span class="string">World</span>&quot; /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key=&quot;greeting&quot;&gt;<span class="string">Hello</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>

<h4>Result:</h4>

<com:TTranslate time="#time()">
	{greeting} {name}!, The unix-time is "{time}". 
	<com:TParam Key="name">
		<com:TTranslate Catalogue="tests" Text="World" />
	</com:TParam>	
	<com:TParam Key="greeting">Hello</com:TParam>
</com:TTranslate>


</com:TForm>
</body>
</html>