<%include SVNManagerApp.global.header %>
<h1>Create New Repository</h1>
<com:TPanel ID="CreatePanel" Visible="true" >
<table>
	<tr>
		<th>Name</th>
		<td>
			<com:TTextBox ID="Name" MaxLength="32" Columns="16"/>
			<com:TRequiredFieldValidator ControlToValidate="Name" Display="Dynamic">
			Please enter a name for the new repository!
			</com:TRequiredFieldValidator>
			<com:TRegularExpressionValidator ControlToValidate="Name" Display="Dynamic" RegularExpression='[^.<>|"\\\/:*?][^<>|"\\\/:*?]*'>Invalid repository name.  The name may not begin with a period (.) and may not contain the following characters: &lt; &gt; | &quot; \ / : * ?</com:TRegularExpressionValidator>
			<com:TCustomValidator ControlToValidate="Name" OnServerValidate="isNotTaken" Display="Dynamic" >
			This repository name is taken, please choose another!
			</com:TCustomValidator>
		</td>
	</tr>
	<tr>
		<th>Description</th>
		<td>
			<com:TTextBox ID="Description" MaxLength="127" Columns="40"/>
			<com:TRequiredFieldValidator ControlToValidate="Description" Display="Dynamic">
			Please enter a description for the new repository!
			</com:TRequiredFieldValidator>
		</td>
	</tr>
	<tr>
		<td>
			<com:TButton ID="Confirm" Text="Confirm" OnClick="onConfirmBtn" />
		</td>
		<td align="right">
			<com:TButton ID="Cancel" Text="Cancel" CausesValidation="false" OnClick="onCancelBtn" />
		</td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">Repository created!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<com:TPanel ID="FailedPanel" Visible="false" >
	<h3 class="warning">Repository not created!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<%include SVNManagerApp.global.footer %>
