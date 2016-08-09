<%include SVNManagerApp.global.header %>
<h1>Invite User</h1>
<com:TPanel ID="InvitationPanel" Visible="true">
	<table>
	<tr>
		<th align="right">E-mail adress:</th>
		<td align="leftt">
			<com:TTextBox ID="Email" Columns="64" />
			<com:TRequiredFieldValidator ControlToValidate="Email" Display="Dynamic"><br/>Please enter an e-mail address.</com:TRequiredFieldValidator>
		    <com:TEmailAddressValidator ControlToValidate="Email" Display="Dynamic"><br/>You entered an invalid email address.</com:TEmailAddressValidator>
		    <com:TCustomValidator ControlToValidate="Email" OnServerValidate="isEmailTaken" Display="Dynamic"><br/>User already exist with this e-mail!</com:TCustomValidator>
		    <com:TCustomValidator ControlToValidate="Email" OnServerValidate="hasTicket" Display="Dynamic"><br/>A ticket already has been send to this e-mail!</com:TCustomValidator>
		</td>
	</tr>
	<tr>
		<th align="right">May create n repositories:</th>
		<td align="left">
			<com:TTextBox ID="Repos" Columns="2" MaxLength="2" Text="0"/>
			<com:TRangeValidator ControlToValidate="Repos" MinValue="0" MaxValue="99" ValueType="Integer" Display="Dynamic"><br/>Please enter a valid number from 0 to 99!</com:TRangeValidator>
		</td>
	</tr>
	<tr>
		<td><com:TButton ID="Confirm" Text="Confirm" OnClick="onConfirmBtn"/></td>	
		<td align="right" ><com:TButton ID="Cancel" Text="Cancel" OnClick="onCancelBtn" CausesValidation="false" />
	</tr>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false">
	<h3 class="message">Invitation sent!</h4>
	<com:TLinkButton Text="Go back to User Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>