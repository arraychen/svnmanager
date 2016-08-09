<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Internationalisierung  in PRADO</title>
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
<h1>Internationalisierung in PRADO </h1>
<p>Diese Demo zeigt die grundlegenden Bestandteile, die in PRADO für den Prozess der Internationalisierung und regionalen Anpassung von Web-Anwendungen vorhanden sind. Das Beispiel verwendet mehrere sprachlich (bzw. kulturell) angepasste Templates, um eine lokalisierte Seite anzuzeigen. Um eine anderssprachige Version anzusehen, ändern Sie bitte die Spracheinstellungen in Ihrem Webbrowser.</p>

<p>
 Ihre aktuelle Kultur ist
<com:TLabel Text="#$this->Page->cultureName()" />.
<div class="links">
 Vorhandene Sprachversionen:
	<com:LanguageList />
</div>
</p>

<p>
Weiter mit dem Thema kulturell angepasster Templates. Text kann unter Zuhilfenahme vieler unterschiedlicher Übersetzungsquellen lokalisiert werden. PRADO stellt gettext-, XML-, SQLite- und MySQL-Container für die Speicherung von Nachrichtenübersetzungen zur Verfügung. Das Beispiel im folgenden Link zeigt, wie Text mit der TTranslate-Komponente lokalisiert werden kann. Die Demoanwendung ist ein einfacher NachrichtenÜbersetzungs-Editor.
<div class="links">
  <a href="i18n.php?page=Translation:Editor">PRADO Übersetzungseditor</a>
</div>
</p>
<p>
 Viel Erfolg bei der Lokalisation mit PRADO.
<br />&#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
</p>
	<com:BrowserHelp />
<hr />
<h2>Einleitung </h2>
<p> Entwicklung und Wartung mehrsprachiger Sites ist ein weitverbreitetes Problem fÜr Web-Entwickler.</p>
<dl>
<dt><strong>Internationalisierung (I18N)</strong></dt>
<dd> Prozess der Entwicklung eines Produktes, so dass es mit Daten in unterschiedlichen Sprachen funktioniert und an verschiedene Zielmärkte ohne Technikänderungen angepasst werden kann. </dd>
<dt><strong>Lokalisation (L10N)</strong></dt>
<dd>Nachfolgender Prozess der Übersetzung und Anpassung eines Produktes an die kulturellen Konventionen eines gegebenen Marktes.</dd>
</dl>
<p> Die folgenden Merkmale werden von PRADO unterstützt: </p>
<ul>
  <li>Textübersetzung </li>
  <li>Attributübersetzung (noch zu implementieren) </li>
  <li>Parameteraustausch (falls erforderlich mit Übersetzung) </li>
  <li>Datums-, Zahlen- und Währungsformatierung</li>
  </ul>
<h2>Beispiele </h2>
<h3>Datum: volles Muster, Kultur en_GB</h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
<h4>Resultat: </h4>
<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
<hr />

<h3>Datum: kurzes Muster</h3>
<h4>Beispiel:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
<h4>Resultat: </h4>
<com:TDateFormat ID="Time2" Pattern="short" /> <br />
<hr />

<h3>Datum: Standardmuster von <%= $this->Page->getCulture(); %> </h3>
<h4>Beispiel:</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
<h4>Resultat: </h4>
<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
<hr />

<h3>Währung Standardformat </h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
<h4>Resultat: </h4>
<com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />

<hr />

<h3>Währung, Kultur de_DE mit Euro </h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
<h4>Resultat: </h4>
<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />

<hr />

<h3>Währung, US-Format mit Euro </h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
<h4>Resultat: </h4>
<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
<hr />

<h3>Übersetzungsbeispiele </h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
<h4>Resultat: </h4>
<com:TTranslate>Hello</com:TTranslate> <br />
<h4>Beispiel: </h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
<h4>Resultat: </h4>
<com:TTranslate Text="Goodbye" /> <br />

<hr />
<h3>Übersetzung mit Verwendung eines anderen Kataloges</h3>
<h4>Beispiel: </h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
<h4>Resultat: </h4>
<com:TTranslate Text="Goodbye"> 
	<prop:Catalogue>tests</prop:Catalogue>
</com:TTranslate>
<br />


<hr />
<h3>Übersetzung mit Parameteraustausch </h3>
<h4>Beispiel: </h4>
<pre>
&lt;com:TTranslate time=&quot;<span class="component">#time()</span>&quot;&gt;
    <span class="string">{greeting} {name}!, The unix-time is &quot;{time}&quot;.</span>
    &lt;com:TParam Key=&quot;name&quot;&gt;
        &lt;com:TTranslate Catalogue=&quot;tests&quot; Text=&quot;<span class="string">World</span>&quot; /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key=&quot;greeting&quot;&gt;<span class="string">Hallo</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>

<h4>Resultat: </h4>

<com:TTranslate time="#time()">
	{greeting} {name}!, The unix-time is "{time}". 
	<com:TParam Key="name">
		<com:TTranslate Catalogue="tests" Text="World" />
	</com:TParam>	
	<com:TParam Key="greeting">Hallo</com:TParam>
</com:TTranslate>


</com:TForm>
</body>
</html>