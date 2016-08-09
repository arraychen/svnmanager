<html>
<head>
<title>Hangman</title>
</head>
<body>
<h1>PRADO Hangman</h1>
<com:TForm ID="form">
<com:TPanel ID="startPanel">
<p>This is the game of Hangman. You must guess a word, a letter at a time.
If you make too many mistakes, you lose the game!</p>
<com:TRadioButton ID="easyLevel" GroupName="level" Text="Easy game; you are allowed 10 misses." /><br/>
<com:TRadioButton ID="mediumLevel" GroupName="level" Text="Medium game; you are allowed 5 misses." /><br/>
<com:TRadioButton ID="hardLevel" GroupName="level" Text="Hard game; you are only allowed 3 misses." /><br/>
<com:TButton Text="Play!" OnClick="onSelectLevel" />
<com:TLabel ID="startError" Text="You must choose a difficulty level!" Style="color:red" Visible="false" />
</com:TPanel>
<com:TPanel ID="guessPanel" Visible="false">
<h2>Please make a guess</h2>
<h3 style="letter-spacing: 4px;"><%= $this->Page->GuessWord %></h3>
<p>You have made <%=$this->Page->Misses%> bad guesses
out of a maximum of <%= $this->Page->Level %>.</p>
<p>Guess:
<com:TLinkButton ID="guessA" Text="A" OnClick="onGuessWord" />
<com:TLinkButton ID="guessB" Text="B" OnClick="onGuessWord" />
<com:TLinkButton ID="guessC" Text="C" OnClick="onGuessWord" />
<com:TLinkButton ID="guessD" Text="D" OnClick="onGuessWord" />
<com:TLinkButton ID="guessE" Text="E" OnClick="onGuessWord" />
<com:TLinkButton ID="guessF" Text="F" OnClick="onGuessWord" />
<com:TLinkButton ID="guessG" Text="G" OnClick="onGuessWord" />
<com:TLinkButton ID="guessH" Text="H" OnClick="onGuessWord" />
<com:TLinkButton ID="guessI" Text="I" OnClick="onGuessWord" />
<com:TLinkButton ID="guessJ" Text="J" OnClick="onGuessWord" />
<com:TLinkButton ID="guessK" Text="K" OnClick="onGuessWord" />
<com:TLinkButton ID="guessL" Text="L" OnClick="onGuessWord" />
<com:TLinkButton ID="guessM" Text="M" OnClick="onGuessWord" />
<com:TLinkButton ID="guessN" Text="N" OnClick="onGuessWord" />
<com:TLinkButton ID="guessO" Text="O" OnClick="onGuessWord" />
<com:TLinkButton ID="guessP" Text="P" OnClick="onGuessWord" />
<com:TLinkButton ID="guessQ" Text="Q" OnClick="onGuessWord" />
<com:TLinkButton ID="guessR" Text="R" OnClick="onGuessWord" />
<com:TLinkButton ID="guessS" Text="S" OnClick="onGuessWord" />
<com:TLinkButton ID="guessT" Text="T" OnClick="onGuessWord" />
<com:TLinkButton ID="guessU" Text="U" OnClick="onGuessWord" />
<com:TLinkButton ID="guessV" Text="V" OnClick="onGuessWord" />
<com:TLinkButton ID="guessW" Text="W" OnClick="onGuessWord" />
<com:TLinkButton ID="guessX" Text="X" OnClick="onGuessWord" />
<com:TLinkButton ID="guessY" Text="Y" OnClick="onGuessWord" />
<com:TLinkButton ID="guessZ" Text="Z" OnClick="onGuessWord" />
</p>
<p><com:TLinkButton Text="Give up?" OnClick="onGiveUp" /></p>
</com:TPanel>

<com:TPanel ID="winPanel" Visible="false">
<h2>You Win!</h2>
<p>The word was: <%= $this->Page->Word %>.</p>
<p><com:TLinkButton Text="Start Again" OnClick="onStartAgain" /></p>
</com:TPanel>

<com:TPanel ID="losePanel" Visible="false">
<h2>You Lose!</h2>
<p>The word was: <%= $this->Page->Word %>.</p>
<p><com:TLinkButton Text="Start Again" OnClick="onStartAgain" /></p>
</com:TPanel>

</com:TForm>
</body>
</html>