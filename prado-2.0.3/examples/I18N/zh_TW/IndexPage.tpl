<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PRADO 國際化</title>
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
<h1>PRADO 國際化</h1>
 <p>本演示將介紹如何用PRADO中的一些部件對您的Web應用進行國際化和本地化。
 本网頁的顯示是根据您的當地設置從多個不同語言的模板中選擇進行的。
 如果你想查看其它語言版本，請更改瀏覽器的語言設置。</p>
 
 <p>
 您目前的語言設置是
 <com:TLabel Text="#$this->Page->cultureName()" />.
 <div class="links">
 可查看的語言版本包括：
 	<com:LanguageList />
 </div>
 </p>
 
 <p>
 除了能自動偵知語言設置的模板外，文本也可以使用不同的翻譯源進行本地化。
 PRADO提供了gettext，XML，SQLite以及MySQL等手段用于存放消息的各种翻譯。
 下面連接所指向的例子展示了如何使用TTranslate部件對文本進行本地化。
 該例子是一個簡單的消息翻譯編輯器。
 <div class="links">
   <a href="i18n.php?page=Translation:Editor">PRADO翻譯編輯器</a>
 </div>
 </p>
 <p>祝您在使用PRADO進行本地化時好運。<br />
 &#8212; Wei Zhuo &lt; weizhuo [at] gmail [dot] com &gt;
 </p>
 <com:BrowserHelp />
 <hr />
 <h2>簡介</h2>
 <p>開發和維護多語言的站點對Web開發者而言是一個很常見的問題。</p>
 <dl>
 <dt><strong>國際化（I18N）</strong></dt>
 <dd>產品開發的一個步驟，它使得產品能接受不同語言的數据，并且在沒有更改設計
 的情況下使產品能用于不同的市場。</dd>
 <dt><strong>L本地化（L10N）</strong></dt>
 <dd>國際化的一個細節步驟，它針對某個特定的市場文化對產品進行翻譯和調整。</dd>
 </dl>
 <p>PRADO支持以下功能：</p>
 <ul>
   <li>文本翻譯</li>
   <li>屬性翻譯（尚未完成）</li>
   <li>參數替換（如需要可進行翻譯）</li>
   <li>日期，數字和貨幣的格式化</li>
   </ul>
 <h2>例子</h2>
 <h3>日期：完整格式，en_GB語言設置</h3>
 <h4>例: </h4>
 <pre>&lt;com:TDateFormat ID=&quot;Time1&quot; Pattern=&quot;<span class="string">full</span>&quot; Culture=&quot;<span class="string">en_GB</span>&quot;/&gt;</pre>
 <h4>結果: </h4>
 <com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB"/> <br />
 <hr />
 
 <h3>日期：短格式</h3>
 <h4>例：</h4>
 <pre>&lt;com:TDateFormat ID=&quot;Time2&quot; Pattern=&quot;<span class="string">short</span>&quot; /&gt;</pre>
 <h4>結果：</h4>
 <com:TDateFormat ID="Time2" Pattern="short" /> <br />
 <hr />
 
 <h3>日期：<%= $this->Page->getCulture(); %>的缺省模式</h3>
 <h4>例：</h4>
 <pre>&lt;com:TDateFormat ID=&quot;Time3&quot;&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
 <h4>結果：</h4>
 <com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat> <br />
 <hr />
 
 <h3>貨幣，缺省格式</h3>
 <h4>例：</h4>
 <pre>&lt;com:TNumberFormat Type=&quot;currency&quot;&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
 <h4>結果：</h4>
 <com:TNumberFormat Type="currency">12.4</com:TNumberFormat> <br />
 <hr />
 <h3>貨幣，de_DE語言以及歐幣</h3>
 <h4>例：</h4>
 <pre>&lt;com:TNumberFormat ID=&quot;Number2&quot; Type=&quot;currency&quot; Culture=&quot;de_DE&quot; Currency=&quot;EUR&quot;/&gt;</pre>
 <h4>結果：</h4>
 <com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/> <br />
 
 <hr />
 
 <h3>貨幣, 美國格式以及歐幣</h3>
 <h4>例：</h4>
 <pre>&lt;com:TNumberFormat Type=&quot;currency&quot; Culture=&quot;en_US&quot; Currency=&quot;EUR&quot; Value=&quot;<span class="string">100</span>&quot; /&gt;</pre>
 <h4>結果：</h4>
 <com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" /> <br />
 <hr />
 
 <h3>翻譯實例</h3>
 <h4>例：</h4>
 <pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
 <h4>結果：</h4>
 <com:TTranslate>Hello</com:TTranslate> <br />
 <h4>例：</h4>
 <pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot; /&gt;</pre>
 <h4>結果：</h4>
 <com:TTranslate Text="Goodbye" /> <br />
 
 <hr />
 <h3>用不同目錄進行翻譯</h3>
 <h4>例：</h4>
 <pre>&lt;com:TTranslate Text=&quot;<span class="string">Goodbye</span>&quot;&gt; 
     &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
 &lt;/com:TTranslate&gt;</pre>
 <h4>結果：</h4>
 <com:TTranslate Text="Goodbye"> 
 	<prop:Catalogue>tests</prop:Catalogue>
 </com:TTranslate>
 <br />
 
 
 <hr />
 <h3>用參數替換進行翻譯</h3>
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
 
 <h4>結果：</h4>
 
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