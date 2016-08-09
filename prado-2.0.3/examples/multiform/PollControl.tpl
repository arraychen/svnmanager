<com:TForm ID="pollForm">
	<div>
		Do you like polls?
	</div>
	<com:TRadioButtonList ID="pollOptions">
		<com:TListItem Value="value1" Text="Yes"/>
		<com:TListItem Value="value2" Text="No"/>
	</com:TRadioButtonList>
	<com:TButton ID="submitPoll" OnClick="recordVote" Text="Vote!"/>
</com:TForm>