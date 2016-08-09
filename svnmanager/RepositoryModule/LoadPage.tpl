<%include SVNManagerApp.global.header %>
<h1>Load Repository</h1>
<p style="color:red"><com:TLabel ID="MessageLabel" /> </p> 
<com:TPanel ID="FormPanel">
<table>
	<tr>
		<th>Repository Name</th>
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
		<th>Dump file</th>
		<td>
			<com:TFileUpload ID="FileUpload" OnFileUpload="onFileUpload" OnFileUploadFailed="onFileUploadFailed" />
			<com:TRequiredFieldValidator ControlToValidate="FileUpload" ErrorMessage="Dump file required."/> 
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
<com:TPanel ID="SuccessPanel" Visible="false">
	<h3 class="message">Repository loaded!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<%include SVNManagerApp.global.footer %>
