<table class="menu">
	<tr><th> ---[ Menu v1.10 ]--- </th></tr>
	<com:TPanel ID="MenuPanel" Visible="#$this->User->isAuthenticated()" >
		<tr><td><com:TLinkButton ID="UserAdminButton" Text="User Admin" CausesValidation="false" OnClick="onClickUserBtn" class="menu" /></td></tr>
		<tr><td><com:TLinkButton ID="GroupAdminButton" Text="Group Admin" CausesValidation="false" OnClick="onClickGroupBtn" class="menu" Visible="#$this->User->isAdmin()&&!$this->User->isConfigAdmin()" /></td></tr>
		<tr><td><com:TLinkButton ID="RepositoryAdminButton" Text="Repository Admin" CausesValidation="false" OnClick="onClickRepositoryBtn" class="menu" Visible="#$this->User->needsRepositoryMenu()" /></td></tr>
		<tr><td><com:TLinkButton ID="LogoutButton" Text="Logout" CausesValidation="false" OnClick="onClickLogoutBtn" class="menu" /></td></tr>
	</com:TPanel>
	<tr><td><com:TLinkButton ID="LoginButton" Text="Login" CausesValidation="false" OnClick="onClickLoginBtn" Visible="#!$this->User->isAuthenticated()" class="menu" /></td></tr>
</table>
