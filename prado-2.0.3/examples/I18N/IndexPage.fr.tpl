<%@ Page Catalogue="index" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
	<HEAD>
		<title>Internationalisation dans PRADO</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<style type="text/css"> <!-- .string {color: #CC0000}
	.component {color: #003399}
	html { background-color: #E5E8EA; }
	body { padding: 2em 3em 3em 3em; font-family: Georgia,"Times New Roman", Times, serif; width: 37em; margin: 0 auto; background-color: white; line-height: 125%; background-image: url(right.gif); background-repeat: repeat-y; background-position: right; }
	pre { font-size: 12px; font-family: "Courier New", Courier, monospace; border: 1px solid #DCDCDC; padding: 1.5em; background-color: #F0F8FF; }
	hr { margin: 2em 0; }
	dd { margin-bottom: 1em; }
	.links { background-color: #ECF2FD; padding: 0.5em 1em;}
	.links a:active, .links a:visited { color: blue; }
	.links a { margin-right: 0.5em; }
	.links a:hover { color:red; }
	--></style>
	</HEAD>
	<body>
		<com:TForm>
			<h1>Internationalisation&nbsp;avec PRADO
			</h1>
			<p>
				Cette démonstration présente&nbsp;les composants de base&nbsp;fournis 
				par&nbsp;PRADO pour le processus d'internationalisation et de localisation des 
				applications Web. Cette page utilise plusieurs gabarits en fonction de la 
				localisation (ou de la culture) pour afficher le contenu&nbsp;approprié. Pour 
				afficher une version localisée différente, veuillez changer la configuration 
				des langues dans votre navigateur.
			</p>
			<p>
				Votre culture actuelle est
				<com:TLabel Text="#$this->Page->cultureName()" />.
				<div class="links">
					Versions localisées disponibles :
					<com:LanguageList />
				</div>
			<P></P>
			<p>
				En plus du support de la culture dans les gabarits, le&nbsp;contenu peut être 
				localisé en utilisant différentes sources de traduction. PRADO permet 
				d'utiliser gettext, XML, SQLite et MySQL pour enregistrer les traductions des 
				messages. L'exemple suivant montre comment le texte peut être localisé en 
				utilisant le composant TTranslate. L'application de démonstration est un 
				éditeur des messages.
				<div class="links">
					<a href="i18n.php?page=Translation:Editor">Éditeur de traductions pour PRADO</a>
				</div>
			<P></P>
			<p>
				Bonne chance&nbsp;avec la localisation avec PRADO.
				<br>
				— Wei Zhuo &lt; weizhuo [à] gmail [point] com &gt;
			</p>
			<com:BrowserHelp />
			<hr>
			<h2>Introduction
			</h2>
			<p>
				Développer et mettre à jour un site multi-langues est un problème classique 
				pour les développeurs de sites Web.</p>
			<dl>
				<dt><strong>Internationalisation (I18N)</strong>
					<dd>
						Processus de développement d'un produit de telle manière qu'il fonctionne avec 
						des données dans différentes langues et puisse être adapté à 
						diverses&nbsp;cibles culturelles sans changement technique. <dt><strong>Localisation 
								(L10N)</strong>
							<dd>
								Processus ultérieur de traduction et d'adaptation d'un produit aux conventions 
								culturelles d'un marché donné.
							</dd>
			</dl>
			<p>
				PRADO offrent les fonctionnalités suivantes&nbsp; :
			</p>
			<ul>
				<li>
				Traduction de contenu
				<li>
				Traduction des attributs (à faire)
				<li>
				Substitution des paramètres (avec traduction si nécessaire)
				<li>
					Date, nombre et formatage des devises
				</li>
			</ul>
			<h2>Exemples</h2>
			<h3>Date : format long, culture en_GB</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TDateFormat ID="Time1" Pattern="<span class="string">full</span>" Culture="<span class="string">en_GB</span>"/&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TDateFormat ID="Time1" Pattern="full" Culture="en_GB" />
			<br>
			<hr>
			<h3>Date : format court
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TDateFormat ID="Time2" Pattern="<span class="string">short</span>" /&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TDateFormat ID="Time2" Pattern="short" />
			<br>
			<hr>
			<h3>Format par défaut d'une date pour la culture
				<%= $this->Page->getCulture(); %>
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TDateFormat ID="Time3"&gt;<span class="string">2004/12/06</span>&lt;/com:TDateFormat&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TDateFormat ID="Time3">2004/12/06</com:TDateFormat>
			<br>
			<hr>
			<h3>Format par défaut d'une devise
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TNumberFormat Type="currency"&gt;<span class="string">12.4</span>&lt;/com:TNumberFormat&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TNumberFormat Type="currency">12.4</com:TNumberFormat>
			<br>
			<hr>
			<h3>Devise :&nbsp;culture de_DE avec devise Euro
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR"/&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TNumberFormat ID="Number2" Type="currency" Culture="de_DE" Currency="EUR" />
			<br>
			<hr>
			<h3>Devise :&nbsp;culture en_US avec devise Euro
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="<span class="string">100</span>" /&gt;</pre>
			<h4>Résultat
			</h4>
			<com:TNumberFormat Type="currency" Culture="en_US" Currency="EUR" Value="100" />
			<br>
			<hr>
			<h3>Exemples de traduction
			</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TTranslate&gt;<span class="string">Hello</span>&lt;/com:TTranslate&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TTranslate>Hello</com:TTranslate>
			<br>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TTranslate Text="<span class="string">Goodbye</span>" /&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TTranslate Text="Goodbye" />
			<br>
			<hr>
			<h3>Traduction en utilisant un catalogue différent</h3>
			<h4>Exemple :
			</h4>
			<pre>&lt;com:TTranslate Text="<span class="string">Goodbye</span>"&gt; 
    &lt;prop:Catalogue&gt;<span class="component">tests</span>&lt;/prop:Catalogue&gt;
&lt;/com:TTranslate&gt;</pre>
			<h4>Résultat :
			</h4>
			<com:TTranslate Text="Goodbye">
				<prop:Catalogue>tests</prop:Catalogue>
			</com:TTranslate>
			<br>
			<hr>
			<h3>Traduction avec substitution de paramètres
			</h3>
			<h4>Exemple :
			</h4>
			<pre>
&lt;com:TTranslate time="<span class="component">#time()</span>"&gt;
    <span class="string">{greeting} {name}!, The unix-time is "{time}".</span>
    &lt;com:TParam Key="name"&gt;
        &lt;com:TTranslate Catalogue="tests" Text="<span class="string">World</span>" /&gt;
    &lt;/com:TParam&gt;
    &lt;com:TParam Key="greeting"&gt;<span class="string">Hello</span>&lt;/com:TParam&gt;
&lt;/com:TTranslate&gt;
</pre>
			<h4>Résultat :
			</h4>
			<com:TTranslate time="#time()">
	{greeting} {name}!, The unix-time is "{time}". 
	<com:TParam Key="name">
					<com:TTranslate Catalogue="tests" Text="World" />
				</com:TParam>	
	<com:TParam Key="greeting">Hello</com:TParam>
</com:TTranslate>
		</com:TForm>
	</body>
</HTML>
