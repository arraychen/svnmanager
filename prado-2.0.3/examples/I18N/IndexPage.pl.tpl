<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Internacjonalizacja w PRADO</title>
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
<h1>Internacjonalizacja w PRADO</h1>
<p>Ten przykład pokazuje podstawowe komponenty PRADO używane w procesach Internacjonalizacji i Lokalizacji aplikacji webowych. Ta strona używa wielu zlokalizowanych, pod względem językowo-kulturowym, szablonów aby wyświetlić właściwe tłumaczenie. Aby zobaczyć inne lokalizacje, musisz zmienić ustawienia językowe w swojej przeglądarce.
</p>

<p>
Twoim obecnym językiem jest: <com:TLabel Text="#$this->Page->cultureName()" />.
<div class="links">
	Dostępne lokalizacje: 
	<com:LanguageList />
</div>
</p>

<p>
	Nawiązanie do szablonów kulturowo-językowych. Tekst może zostać zlokalizowany przy użyciu wielu źródeł tłumaczeń. PRADO umożliwia przechowywanie tłumaczeń przy pomocy następujących modułów: gettext, XML, SQLite oraz MySQL. Przykład do którego prowadzi poniższy odnośnik pokazuje jak można zlokalizować treść używając kontrolki TTranslate. Przykładowa aplikacja jest prostym edytorem tłumaczeń.
<div class="links">
	<a href="i18n.php?page=Translation:Editor">Edytor Tłumaczeń PRADO</a>
</div>
</p>
<p>
Powodzenia przy lokalizowaniu aplikacji w PRADO. <br />&#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
</p>
	<com:BrowserHelp />
<hr />
<h2>Wstęp</h2>
<p> Tworzenie i utrzymywanie wielojęzykowych serwisów to problem który trapi wielu programistów. </p>
<dl>
<dt><strong>Internacjonalizacja (I18N)</strong></dt>
<dd> To proces takiego przygotowania produktu aby mógł operować na danych w różnych językach, na wielu rynkach bez konieczności zmian w nim samym. </dd>
<dt><strong>Lokalizacja (L10N)</strong></dt>
<dd> To kolejny etap, jest to proces tłumaczenia i dostosowywania produktu do konkretnych wymagań językowo-kulturowych.</dd>
</dl>
<p>PRADO obsługuje następujące mechanizmy: </p>
<ul>
  <li>Tłumaczenie tekstu </li>
  <li>Tłumaczenie atrybutów (todo) </li>
  <li>Podstawianie parametrów (wraz z koniecznym tłumaczeniem) </li>
  <li>Formatowanie dat, liczb i walut </li>
</ul>
<h2>Przykłady</h2>
<h3>Data: pełny format językowo-kulturowy: en_GB</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
<h4>Wynik:</h4>
<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
<hr />

<h3>Data: format krótki</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
<h4>Wynik:</h4>
<com:TDateFormat ID="Time2" Pattern="short" /> <br />
<hr />

<h3>Domyślny format daty dla języka: <%= $this->Page->getCulture(); %> </h3>
<h4>Przykład:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
<h4>Wynik:</h4>
<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
<hr />

<h3>Domyślny format waluty</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
<h4>Wynik:</h4>
<com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />
<hr />
<h3>Waluty, format językowo-kulturowy de_DE w Euro</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
<h4>Wynik:</h4>
<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />

<hr />

<h3>Waluty, format Amerykański w Euro</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
<h4>Wynik</h4>
<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
<hr />

<h3>Tłumaczenia</h3>
<h4>Przykład:</h4>
<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
<h4>Wynik:</h4>
<com:TTranslate>Hello</com:TTranslate> <br />
<h4>Przykład:</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
<h4>Wynik:</h4>
<com:TTranslate Text="Goodbye" /> <br />

<hr />
<h3>Tłumaczenie z wykorzystaniem innego katalogu </h3>
<h4>Przykład:</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
<h4>Wynik:</h4>
<com:TTranslate Text="Goodbye"> 
	<prop:Catalogue>tests</prop:Catalogue>
</com:TTranslate>
<br />


<hr />
<h3>Tłumaczenie z wykorzystanie podstawiania parametru</h3>
<h4>Przykład:</h4>
<pre>
&lt;com:TTranslate time=&quot;<span class="component">#time()</span>&quot;&gt;
    <span class="string">{greeting} {name}!, The unix-time is &quot;{time}&quot;.</span>
    &lt;com:TParam Key=&quot;name&quot;&gt;
        &lt;com:TTranslate Catalogue=&quot;tests&quot; Text=&quot;<span class="string">World</span>&quot; /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key=&quot;greeting&quot;&gt;<span class="string">Hello</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>

<h4>Wynik:</h4>

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