<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PRADO 国际化</title>
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
<h1>PRADO 国际化</h1>
<p>本演示将介绍如何用PRADO中的一些部件对您的Web应用进行国际化和本地化。
本网页的显示是根据您的当地设置从多个不同语言的模板中选择进行的。
如果你想查看其它语言版本，请更改浏览器的语言设置。</p>

<p>
您目前的语言设置是
<com:TLabel Text="#$this->Page->cultureName()" />.
<div class="links">
可查看的语言版本包括：
	<com:LanguageList />
</div>
</p>

<p>
除了能自动侦知语言设置的模板外，文本也可以使用不同的翻译源进行本地化。
PRADO提供了gettext，XML，SQLite以及MySQL等手段用于存放消息的各种翻译。
下面连接所指向的例子展示了如何使用TTranslate部件对文本进行本地化。
该例子是一个简单的消息翻译编辑器。
<div class="links">
  <a href="i18n.php?page=Translation:Editor">PRADO翻译编辑器</a>
</div>
</p>
<p>祝您在使用PRADO进行本地化时好运。<br />
&#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
</p>
<com:BrowserHelp />
<hr />
<h2>简介</h2>
<p>开发和维护多语言的站点对Web开发者而言是一个很常见的问题。</p>
<dl>
<dt><strong>国际化（I18N）</strong></dt>
<dd>产品开发的一个步骤，它使得产品能接受不同语言的数据，并且在没有更改设计
的情况下使产品能用于不同的市场。</dd>
<dt><strong>L本地化（L10N）</strong></dt>
<dd>国际化的一个细节步骤，它针对某个特定的市场文化对产品进行翻译和调整。</dd>
</dl>
<p>PRADO支持以下功能：</p>
<ul>
  <li>文本翻译</li>
  <li>属性翻译（尚未完成）</li>
  <li>参数替换（如需要可进行翻译）</li>
  <li>日期，数字和货币的格式化</li>
  </ul>
<h2>例子</h2>
<h3>日期：完整格式，en_GB语言设置</h3>
<h4>例: </h4>
<pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
<h4>结果: </h4>
<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
<hr />

<h3>日期：短格式</h3>
<h4>例：</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
<h4>结果：</h4>
<com:TDateFormat ID="Time2" Pattern="short" /> <br />
<hr />

<h3>日期：<%= $this->Page->getCulture(); %>的缺省模式</h3>
<h4>例：</h4>
<pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
<h4>结果：</h4>
<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
<hr />

<h3>货币，缺省格式</h3>
<h4>例：</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
<h4>结果：</h4>
<com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />
<hr />
<h3>货币，de_DE语言以及欧币</h3>
<h4>例：</h4>
<pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
<h4>结果：</h4>
<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />

<hr />

<h3>货币, 美国格式以及欧币</h3>
<h4>例：</h4>
<pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
<h4>结果：</h4>
<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
<hr />

<h3>翻译实例</h3>
<h4>例：</h4>
<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
<h4>结果：</h4>
<com:TTranslate>Hello</com:TTranslate> <br />
<h4>例：</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
<h4>结果：</h4>
<com:TTranslate Text="Goodbye" /> <br />

<hr />
<h3>用不同目录进行翻译</h3>
<h4>例：</h4>
<pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
<h4>结果：</h4>
<com:TTranslate Text="Goodbye"> 
	<prop:Catalogue>tests</prop:Catalogue>
</com:TTranslate>
<br />


<hr />
<h3>用参数替换进行翻译</h3>
<h4>例：</h4>
<pre>
&lt;com:TTranslate time=&quot;<span class="component">#time()</span>&quot;&gt;
    <span class="string">{greeting} {name}!, The unix-time is &quot;{time}&quot;.</span>
    &lt;com:TParam Key=&quot;name&quot;&gt;
        &lt;com:TTranslate Catalogue=&quot;tests&quot; Text=&quot;<span class="string">World</span>&quot; /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key=&quot;greeting&quot;&gt;<span class="string">Hello</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>

<h4>结果：</h4>

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