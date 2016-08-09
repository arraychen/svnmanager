<?xml version="1.0" encoding="UTF-8"?>
<application ID="hangman" state="debug">
	<request default="HomePage" />
	<cache enabled="true" />
    <alias name="Pages" path="." />
    <using namespace="System.Web.UI.WebControls" />
    <using namespace="Pages" />
    <parameter name="wordFile">hangman/guesswords.txt</parameter>
</application>