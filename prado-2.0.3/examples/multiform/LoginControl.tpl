<com:TForm ID="loginForm">
	<com:TPanel ID="loginControls">
		<div>
		Username
		</div>
		<div>
		<com:TTextBox ID="Username" Width="110"/>
		</div>
		<div>
		Password
		</div>
		<div>
		<com:TTextBox ID="Password" Width="110"/>
		</div>
		<com:TButton Text="Login" ID="loginButton" OnClick="doLogin"/>
	</com:TPanel>
	<com:TPanel ID="logoutControls" Visible="false">
		<com:TLinkButton ID="logoutButton" Text="Logout" OnClick="doLogout"/>
	</com:TPanel>
</com:TForm>
