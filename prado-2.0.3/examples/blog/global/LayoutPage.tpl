<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>PRADO Blog</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/blog.css">
</head>
<body>
<div id="page">
<h1>PRADO Blog</h1>
<p>
This demo shows a basic blog application exploiting PRADO's module support
and master/content page feature. The application consists
of two modules named <i>User</i> and <i>Blog</i>. Their pages are stored under directory 
<i>UserModule</i> and <i>BlogModule</i>, respectively. They all use the same master page
<i>LayoutPage</i> under the <i>global</i> directory.
</p>
<p>
A PRADO module is a collection of PRADO pages serving for a common goal (e.g. user management).
Pages within a common module can communicate or share data via module object. A module object
can have part of its data persistent across pages. Using module also introduces partition of
namespaces (e.g. you can have different HomePage in different modules).
</p>
<p>
A master page specifies the common outlook of a collection of content pages.
It reserves a couple of places using TContentPlaceHolder to insert the rendering
result of the requested content page. In content pages, TContent is used in their templates
whose ID is used to match against that of TContentPlaceHolder in the master page.
Both master and content pages can have controls in their templates, and they all
respond to events as usual. Master pages can be nested, i.e., a master page can 
be a master of another master page. A content page specifies a master page using the directive
&lt;%@Page Master="master page name" %&gt; in its template.
</p>
<com:TForm>
<div class="menubar"><com:MenuBar /></div>
<com:TContentPlaceHolder ID="content" />
</com:TForm>
<p class="footer">&copy;2004 <a href="http://www.xisc.com">XISC.COM</a>. All right reserved.</p>
</div>
</body>
</html>