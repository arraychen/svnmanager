<div>
<a href="blog.php?page=Blog:ViewAllPage">Home</a>
<a href="blog.php?page=Blog:NewPage">New Blog</a>
<com:THyperLink Text="Login" NavigateUrl="blog.php?page=User:LoginPage" Visible="#!$this->User->isAuthenticated()" />
<com:THyperLink Text="Register" NavigateUrl="blog.php?page=User:NewPage" Visible="#!$this->User->isAuthenticated()" />
<com:THyperLink Text="Account" NavigateUrl="blog.php?page=User:EditPage" Visible="#$this->User->isAuthenticated()" />
<com:TLinkButton Text="#'Logout ('.$this->User->getUsername().')'" Visible="#$this->User->isAuthenticated()" OnClick="onClickLogoutBtn" CausesValidation="false" />
</div>