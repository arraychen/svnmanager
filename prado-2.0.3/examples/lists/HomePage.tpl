<html>
<head>
<title>weee</title>
</head>
<body>

<com:TForm>

<p>
Select blog user, and number of posts to view from that user.
</p>
<com:TDropDownList AutoPostBack="True" Width="200" OnSelectionChanged="selectUser" ID="userDisplay"/>
<com:TDropDownList AutoPostBack="True" Width="100" OnSelectionChanged="selectNumber" ID="numberDisplay">
	<com:TListItem Value="0" Text=""/>
	<com:TListItem Value="1" Text="One"/>
	<com:TListItem Value="2" Text="Two"/>
	<com:TListItem Value="3" Text="Three"/>
	<com:TListItem Value="4" Text="Four"/>
</com:TDropDownList>

<p>Fix of bug 1088432</p> 
<com:TListBox ID="DBList" Width="300" OnSelectionChanged="changeDBList" Rows="5" SelectionMode="Multiple" />

<br /><br />
<com:TCheckBoxList ID="checkList" OnSelectionChanged="changeChecks"/>
<br />
<com:TPanel ID="actionOptions" Visible="false">With Selected
<com:TDropDownList ID="selectAction" OnSelectionChanged="performAction">
<com:TListItem Text=""/>
<com:TListItem Value="delete" Text="Delete"/>
</com:TDropDownList>
</com:TPanel>
<p>
<com:TButton ID="myButton" Text="Submit" />
</p>
</com:TForm>

</body>
</html>