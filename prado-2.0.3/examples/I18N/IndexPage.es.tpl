<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Internationlization en PRADO </title>
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
<h1>Internationlization en PRADO </h1>
<p> Esta versión parcial de programa muestra los componentes básicos disponibles en PRADO para el proceso de internacionalizar y de localizar aplicaciones del Web. Esta paginación utiliza modelos enterados múltiples del locale (o cultura) para mostrar la paginación localizada apropiada. Para visión una diversa versión localizada, cambie por favor sus configuraciones del lenguaje en su browser. </p>

<p>
 Su cultura actual es
<com:TLabel Text="#$this->Page->cultureName()" />.
<div class="links">
 Versiones localizadas disponibles:
	<com:LanguageList />
</div>
</p>

<p>
 Además de modelo enterado de la cultura. El texto se puede localizar usando muchas diversas fuentes de la traducción. PRADO proporciona al gettext, al envase de XML, de SQLite, y de MySQL para salvar traducciones del mensaje. El ejemplo en la conexión siguiente demuestra cómo el texto se puede localizar usando el componente de TTranslate. La aplicación de la versión parcial de programa es editor simple del mensaje de la traducción.
<div class="links">
  <a href="i18n.php?page=Translation:Editor">Editor De la Traducción de PRADO</a>
</div>
</p>
<p>
 Lo más mejor posible de la suerte en la localización con PRADO.
<br />&#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
</p>
	<com:BrowserHelp />
<hr />
<h2>Introducción </h2>
<p> Que desarrolla y que los mantiene el multi-lenguaje sitios es un problema común para los reveladores del Web.</p>
<dl>
<dt><strong>Internacionalización (I18N)</strong></dt>
<dd> Proceso de desarrollar un producto de una manera tal que trabaje con datos en diversos lenguajes y pueda ser adaptado a los varios mercados de blanco sin cambios de ingeniería. </dd>
<dt><strong>Localización (L10N)</strong></dt>
<dd> Proceso subsecuente de traducir y de adaptar un producto a las convenciones culturales de un mercado dado. </dd>
</dl>
<p> Las características siguientes son utilizadas por PRADO: </p>
<ul>
  <li>Traducción del texto </li>
  <li>Traducción del atributo (todo) </li>
  <li>Substitución de parámetro (con la traducción si es necesario) </li>
  <li>Fecha, número y formato de la modernidad </li>
  </ul>
<h2>Ejemplos </h2>
<h3>Fecha: modelo completo, cultura del en_GB </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
<h4>Resultado: </h4>
<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
<hr />

<h3>Fecha: modelo corto </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
<h4>Resultado: </h4>
<com:TDateFormat ID="Time2" Pattern="short" /> <br />
<hr />

<h3>Modelo del valor por defecto de la fecha del <%= $this->Page->getCulture(); %> </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
<h4>Resultado: </h4>
<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
<hr />

<h3>Formato del valor por defecto de la modernidad </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
<h4>Resultado: </h4>
<com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />
<hr />
<h3>Modernidad, cultura del de_DE con el Euro </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
<h4>Resultado: </h4>
<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />

<hr />

<h3>Modernidad, formato de los E.E.U.U. con el Euro </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
<h4>Resultado: </h4>
<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
<hr />

<h3>Ejemplos de la traducción </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
<h4>Resultado: </h4>
<com:TTranslate>Hello</com:TTranslate> <br />
<h4>Ejemplo: </h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
<h4>Resultado: </h4>
<com:TTranslate Text="Goodbye" /> <br />

<hr />
<h3>Traducción usando un diverso catálogo </h3>
<h4>Ejemplo: </h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
<h4>Resultado: </h4>
<com:TTranslate Text="Goodbye"> 
	<prop:Catalogue>tests</prop:Catalogue>
</com:TTranslate>
<br />


<hr />
<h3>Traducción con la substitución de parámetro </h3>
<h4>Ejemplo: </h4>
<pre>
&lt;com:TTranslate time=&quot;<span class="component">#time()</span>&quot;&gt;
    <span class="string">{greeting} {name}!, The unix-time is &quot;{time}&quot;.</span>
    &lt;com:TParam Key=&quot;name&quot;&gt;
        &lt;com:TTranslate Catalogue=&quot;tests&quot; Text=&quot;<span class="string">World</span>&quot; /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key=&quot;greeting&quot;&gt;<span class="string">Hello</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>

<h4>Resultado: </h4>

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