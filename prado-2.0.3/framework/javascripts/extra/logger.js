/*

Created By: Corey Johnson
E-mail: probablyCorey@gmail.com

Requires: Prototype Javascript library (http://prototype.conio.net/)

Use it all you want. Just remember to give me some credit :)

*/

// ------------
// Custom Event
// ------------

CustomEvent = Class.create()
CustomEvent.prototype = {
  initialize : function() {
  	this.listeners = []
  },

	addListener : function(method) {
		this.listeners.push(method)
	},

	removeListener : function(method) {
		var foundIndexes = this._findListenerIndexes(method)

		for(var i = 0; i < foundIndexes.length; i++) {
			this.listeners.splice(foundIndexes[i], 1)
		}
	},

	dispatch : function(handler) {
		for(var i = 0; i < this.listeners.length; i++) {
			try {
				this.listeners[i](handler)
			}
			catch (e) {
				alert("Could not run the listener " + this.listeners[i] + ". " + e)
			}
		}
	},

	// Private Methods
	// ---------------
	_findListenerIndexes : function(method) {
		var indexes = []
		for(var i = 0; i < this.listeners.length; i++) {			
			if (this.listeners[i] == method) {
				indexes.push(i)
			}
		}

		return indexes
	}
}

// ------
// Cookie
// ------

var Cookie = {
	set : function(name, value, expirationInDays, path) {
		var cookie = escape(name) + "=" + escape(value)

		if (expirationInDays) {
			var date = new Date()
			date.setDate(date.getDate() + expirationInDays)
			cookie += "; expires=" + date.toGMTString()
		} 

		if (path) {
			cookie += ";path=" + path
		}

		document.cookie = cookie

		if (value && (expirationInDays == undefined || expirationInDays > 0) && !this.get(name)) {
			Logger.error("Cookie (" + name + ") was not set correctly... The value was " + value.toString().length + " charachters long (This may be over the cookie limit)");
		}
	},

	get : function(name) {
		var pattern = "(^|;)\\s*" + escape(name) + "=([^;]+)"

		var m = document.cookie.match(pattern)
		if (m && m[2]) {			
			return unescape(m[2])
		}
		else return null
	},

	getAll : function() {
		var cookies = document.cookie.split(';')
		var cookieArray = []				

		for (var i = 0; i < cookies.length; i++) {			
			try {
				var name = unescape(cookies[i].match(/^\s*([^=]+)/m)[1])
				var value = unescape(cookies[i].match(/=(.*$)/m)[1])
			}
			catch (e) {
				continue
			}

			cookieArray.push({name : name, value : value})

			if (cookieArray[name] != undefined) {
				Logger.waring("Trying to retrieve cookie named(" + name + "). There appears to be another property with this name though.");
			}			

			cookieArray[name] = value
		}

		return cookieArray
	},

	clear : function(name) {
		this.set(name, "", -1)
	},

	clearAll : function() {
		var cookies = this.getAll()

		for(var i = 0; i < cookies.length; i++) {
			this.clear(cookies[i].name)
		}

	}
}   

// ------
// Logger
// -----        

Logger = {
	logEntries : [],

	onupdate : new CustomEvent(),
	onclear : new CustomEvent(),
    

	// Logger output    
  log : function(message, tag) {
	  var logEntry = new LogEntry(message, tag || "info")
		this.logEntries.push(logEntry)
		this.onupdate.dispatch(logEntry)
	},

	info : function(message) {
		this.log(message, 'info')
	}, 

	debug : function(message) {
		this.log(message, 'debug')
	},  

	warn : function(message) {
	  this.log(message, 'warning')
	},

	error : function(message, error) {
	  this.log(message + ": \n" + error, 'error')
	},

	clear : function () {
		this.logEntries = []
		this.onclear.dispatch()
	}
}  

LogEntry = Class.create()
LogEntry.prototype = {  
    initialize : function(message, tag) {
      this.message = message
      this.tag = tag
    }
}

LogConsole = Class.create()
LogConsole.prototype = {  

  // Properties
  // ----------
  commandHistory : [],
  commandIndex : 0,

  // Methods
  // -------

  initialize : function() {
    this.outputCount = 0
    this.tagPattern = Cookie.get('tagPattern') || ".*"
  
  	// I hate writing javascript in HTML... but what's a better alternative
    this.logElement = document.createElement('div')
    document.body.appendChild(this.logElement)
    Element.hide(this.logElement)

		this.logElement.style.position = "absolute"
    this.logElement.style.left = '0px'
    this.logElement.style.width = '100%'

    this.logElement.style.textAlign = "left"
    this.logElement.style.fontFamily = "lucida console"
    this.logElement.style.fontSize = "100%"
    this.logElement.style.backgroundColor = 'darkgray'      
    this.logElement.style.opacity = 0.9 
    this.logElement.style.zIndex = 2000 

    // Add toolbarElement
    this.toolbarElement = document.createElement('div')
    this.logElement.appendChild(this.toolbarElement)     
    this.toolbarElement.style.padding = "0 0 0 2px"

    // Add buttons        
    this.buttonsContainerElement = document.createElement('span')
    this.toolbarElement.appendChild(this.buttonsContainerElement) 

	this.buttonsContainerElement.innerHTML += '<button onclick="logConsole.toggle()" style="float:right;color:black">close</button>'
    this.buttonsContainerElement.innerHTML += '<button onclick="Logger.clear()" style="float:right;color:black">clear</button>'        
	if(!Prado.Inspector.disabled)
		this.buttonsContainerElement.innerHTML += '<button onclick="Prado.Inspector.inspect()" style="float:right;color:black; margin-right:15px;">Object Tree</button>'


		//Add Tag Filter
		this.tagFilterContainerElement = document.createElement('span')
    this.toolbarElement.appendChild(this.tagFilterContainerElement) 
    this.tagFilterContainerElement.style.cssFloat = 'left'
    this.tagFilterContainerElement.appendChild(document.createTextNode("Log Filter"))
    
    this.tagFilterElement = document.createElement('input')
    this.tagFilterContainerElement.appendChild(this.tagFilterElement)  
    this.tagFilterElement.style.width = '200px'                    
    this.tagFilterElement.value = this.tagPattern    
    this.tagFilterElement.setAttribute('autocomplete', 'off') // So Firefox doesn't flip out
    
    Event.observe(this.tagFilterElement, 'keyup', this.updateTags.bind(this))
    Event.observe(this.tagFilterElement, 'click', function() {this.tagFilterElement.select()}.bind(this))    
    
    // Add outputElement
    this.outputElement = document.createElement('div')
    this.logElement.appendChild(this.outputElement)  
    this.outputElement.style.overflow = "auto"              
    this.outputElement.style.clear = "both"
    this.outputElement.style.height = "200px"
    this.outputElement.style.backgroundColor = 'black' 
          
    this.inputContainerElement = document.createElement('div')
    this.inputContainerElement.style.width = "100%"
    this.logElement.appendChild(this.inputContainerElement)      
    
    this.inputElement = document.createElement('input')
    this.inputContainerElement.appendChild(this.inputElement)  
    this.inputElement.style.width = '100%'                    
    this.inputElement.style.borderWidth = '0px' // Inputs with 100% width always seem to be too large (I HATE THEM) they only work if the border, margin and padding are 0
    this.inputElement.style.margin = '0px'
    this.inputElement.style.padding = '0px'
    this.inputElement.value = 'Type command here' 
    this.inputElement.setAttribute('autocomplete', 'off') // So Firefox doesn't flip out

    Event.observe(this.inputElement, 'keyup', this.handleInput.bind(this))
    Event.observe(this.inputElement, 'click', function() {this.inputElement.select()}.bind(this))    

		window.setInterval(this.repositionWindow.bind(this), 500)
		this.repositionWindow()
		
    // Listen to the logger....
    Logger.onupdate.addListener(this.logUpdate.bind(this))
    Logger.onclear.addListener(this.clear.bind(this))		

    // Preload log element with the log entries that have been entered
		for (var i = 0; i < Logger.logEntries.length; i++) {
  		this.logUpdate(Logger.logEntries[i])
  	}   
  	
  	// Feed all errors into the logger (For some unknown reason I can only get this to work
  	// with an inline event declaration)
  	Event.observe(window, 'error', function(msg, url, lineNumber) {Logger.error("Error in (" + (url || location) + ") on line "+lineNumber+"", msg)})

    // Allow acess key link          
    var accessElement = document.createElement('span')
    accessElement.innerHTML = '<button style="position:absolute;top:-100px" onclick="javascript:logConsole.toggle()" accesskey="d"></button>'
  	document.body.appendChild(accessElement)

  	if (Cookie.get('ConsoleVisible') == 'true') {
		  this.toggle()
		}
	},

	toggle : function() {
	  if (this.logElement.style.display == 'none') {
		  this.show()
		}
		else {
			this.hide()
		}
	}, 
	
	show : function() {
	  Element.show(this.logElement)
	  this.outputElement.scrollTop = this.outputElement.scrollHeight // Scroll to bottom when toggled
	  Cookie.set('ConsoleVisible', 'true')
 	  this.inputElement.select()
	}, 
	
	hide : function() {
	  Element.hide(this.logElement)
	  Cookie.set('ConsoleVisible', 'false')
	},  
	
	output : function(message, style) {
			// If we are at the bottom of the window, then keep scrolling with the output			
			var shouldScroll = (this.outputElement.scrollTop + (2 * this.outputElement.clientHeight)) >= this.outputElement.scrollHeight
	
			this.outputCount++
	  	style = (style ? style += ';' : '')	  	
	  	style += 'padding:1px;margin:0 0 5px 0'	     
		  
		  if (this.outputCount % 2 == 0) style += ";background-color:#101010"
	  	
	  	message = message || "undefined"
	  	message = message.toString().escapeHTML()
	  	
	  	this.outputElement.innerHTML += "<pre style='" + style + "'>" + message + "</pre>"
	  	
	  	if (shouldScroll) {				
				this.outputElement.scrollTop = this.outputElement.scrollHeight
			}
	},
	
	updateTags : function() {
		var pattern = this.tagFilterElement.value
	
		if (this.tagPattern == pattern) return

		try {
			new RegExp(pattern)
		}
		catch (e) {
			return
		}
		
		this.tagPattern = pattern
		Cookie.set('tagPattern', this.tagPattern)

		this.outputElement.innerHTML = ""
		
		// Go through each log entry again
		this.outputCount = 0;
		for (var i = 0; i < Logger.logEntries.length; i++) {
  		this.logUpdate(Logger.logEntries[i])
  	}  
	},
	
	repositionWindow : function() {
		var offset = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		var pageHeight = self.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
		this.logElement.style.top = (offset + pageHeight - Element.getHeight(this.logElement)) + "px"
	},

	// Event Handlers
	// --------------

	logUpdate : function(logEntry) {
		if (logEntry.tag.search(new RegExp(this.tagPattern, 'igm')) == -1) return
		var style = ''
	  if (logEntry.tag.search(/error/) != -1) style += 'color:red'
	  else if (logEntry.tag.search(/warning/) != -1) style += 'color:orange'
	  else if (logEntry.tag.search(/debug/) != -1) style += 'color:green'
 	  else if (logEntry.tag.search(/info/) != -1) style += 'color:white'
	  else style += 'color:yellow'

		this.output(logEntry.message, style)
	},

	clear : function(e) {
		this.outputElement.innerHTML = ""
	},

	handleInput : function(e) {
		if (e.keyCode == Event.KEY_RETURN ) {      
	  	var command = this.inputElement.value
	  	
	  	switch(command) {
	    	case "clear":
	      		Logger.clear()  
	      		break
		    	
	    	default:        
	      	var consoleOutput = "" 
	      	
	      	try {
	        	consoleOutput = eval(this.inputElement.value)	        	
	      	}
	      	catch (e) {  
	        	Logger.error("Problem parsing input <" + command + ">", e)	        	
	        	break
					}
					
					Logger.log(consoleOutput)	      	
	      	break
			}        
	
	  	if (this.inputElement.value != "" && this.inputElement.value != this.commandHistory[0]) {
	    	this.commandHistory.unshift(this.inputElement.value)
	  	}
	  
	  	this.commandIndex = 0 
	  	this.inputElement.value = ""                                                        
		}
    else if (e.keyCode == Event.KEY_UP && this.commandHistory.length > 0) {
    	this.inputElement.value = this.commandHistory[this.commandIndex]

			if (this.commandIndex < this.commandHistory.length - 1) {
      	this.commandIndex += 1
      }
    }     
    else if (e.keyCode == Event.KEY_DOWN && this.commandHistory.length > 0) {
    	if (this.commandIndex > 0) {                                      
      	this.commandIndex -= 1
	    }                       

			this.inputElement.value = this.commandHistory[this.commandIndex]
	  } 
 		else {
    		this.commandIndex = 0
    }
	}
}         

// Load the Console when the window loads
Event.observe(window, "load", function() {logConsole = new LogConsole()}) 

// -------------------------
// Helper Functions And Junk
// -------------------------
function inspect(element, hideProperties, showMethods) {
	var properties = []
	var methods = [] 
	

	for(var internal in element) {
		if(internal == '______array') continue;
		try {
			if (element[internal] instanceof Function) {
				if (showMethods) 
					methods.push(internal + ":\t" + element[internal] )
			}
			else if(element[internal] instanceof Object)
			{
				methods.push(internal + ":\t" + inspect(element[internal], hideProperties, showMethods));
			}
			else {
				if (!hideProperties) 
					properties.push(internal + ":\t" + element[internal] )
			}
		}
		catch (e) {
			Logger.error("Excetion thrown while inspecting object.", e)
		}
	}

	properties.sort()
	methods.sort()

	var internals = properties.concat(methods)
	var output = ""
	for (var i = 0; i < internals.length; i++) {
		output += (internals[i] + "\n")
	}
		
	return output
}   

Array.prototype.contains = function(object) {
	for(var i = 0; i < this.length; i++) {
		if (object == this[i]) return true
	}

	return false
}  

// Helper Alias for simple logging
var puts = function() {return Logger.log(arguments[0], arguments[1])}      

/*************************************

	Javascript Object Tree
	version 1.0
	last revision:04.11.2004
	steve@slayeroffice.com
	http://slayeroffice.com
	
	(c)2004 S.G. Chipman

	Please notify me of any modifications
	you make to this code so that I can 
	update the version hosted on slayeroffice.com


************************************/
if(typeof Prado == "undefined")
	var Prado = {};
Prado.Inspector = 
{
	d : document,	
	types : new Array(),
	objs : new Array(),
	hidden : new Array(),	
	opera : window.opera,
	displaying : '',

	format : function(str) {
		str=str.replace(/</g,"&lt;");
		str=str.replace(/>/g,"&gt;");
		return str;
	},

	parseJS : function(obj) {
		var name;
		if(typeof obj == "string") {  name = obj; obj = eval(obj); }
		win= typeof obj == 'undefined' ? window : obj;
		this.displaying = name ? name : win.toString();
		for(js in win) {			
			try {
				if(win[js] && js.toString().indexOf("Inspector")==-1 && win[js].toString().indexOf("[native code]")==-1) {
					t=typeof(win[js]);
					if(!this.objs[t.toString()]) {						
						this.types[this.types.length]=t;;
						this.objs[t]=new Array();
					}
					index=this.objs[t].length
					this.objs[t][index]=new Array();
					this.objs[t][index][0]=js;
					this.objs[t][index][1]=this.format(win[js].toString());					
				}
			} catch(err) { }
		}
		
	},

	show : function(objID) {
		this.d.getElementById(objID).style.display=this.hidden[objID]?"none":"block";
		this.hidden[objID]=this.hidden[objID]?0:1;
	},

	changeSpan : function(spanID) {
		if(this.d.getElementById(spanID).innerHTML.indexOf("+")>-1){
			this.d.getElementById(spanID).innerHTML="[-]";
		} else {
			this.d.getElementById(spanID).innerHTML="[+]";
		}
	},

	buildInspectionLevel : function()
	{
		var display = this.displaying;
		var list = display.split(".");
		var links = ["<a href=\"javascript:var_dump()\">[object Window]</a>"];
		var name = '';
		if(display.indexOf("[object ") >= 0) return links.join(".");
		for(var i = 0; i < list.length; i++)
		{
			name += (name.length ? "." : "") + list[i];
			links[i+1] = "<a href=\"javascript:var_dump('"+name+"')\">"+list[i]+"</a>"; 
		}
		return links.join(".");
	},

	buildTree : function() {
		mHTML = "<div>Inspecting "+this.buildInspectionLevel()+"</div>";
		mHTML +="<ul class=\"topLevel\">";
		this.types.sort();
		var so_objIndex=0;
		for(i=0;i<this.types.length;i++) {
			mHTML+="<li style=\"cursor:pointer;\" onclick=\"Prado.Inspector.show('ul"+i+"');Prado.Inspector.changeSpan('sp" + i + "')\"><span id=\"sp" + i + "\">[+]</span><b>" + this.types[i] + "</b> (" + this.objs[this.types[i]].length + ")</li><ul style=\"display:none;\" id=\"ul"+i+"\">";
			this.hidden["ul"+i]=0;
			for(e=0;e<this.objs[this.types[i]].length;e++) {
				var more = "";
				if(this.objs[this.types[i]][e][1].indexOf("[object ") >= 0 && /^[a-zA-Z_]/.test(this.objs[this.types[i]][e][0][0]))
				{
					if(this.displaying.indexOf("[object ") < 0)
						more = " <a href=\"javascript:var_dump('"+this.displaying+"."+this.objs[this.types[i]][e][0]+"')\"><b>more</b></a>";
					else if(this.displaying.indexOf("[object Window]") >= 0)
						more = " <a href=\"javascript:var_dump('"+this.objs[this.types[i]][e][0]+"')\"><b>more</b></a>";	
				}
				mHTML+="<li style=\"cursor:pointer;\" onclick=\"Prado.Inspector.show('mul" + so_objIndex + "');Prado.Inspector.changeSpan('sk" + so_objIndex + "')\"><span id=\"sk" + so_objIndex + "\">[+]</span>" + this.objs[this.types[i]][e][0] + "</li><ul id=\"mul" + so_objIndex + "\" style=\"display:none;\"><li style=\"list-style-type:none;\"><pre>" + this.objs[this.types[i]][e][1] + more + "</pre></li></ul>";
				this.hidden["mul"+so_objIndex]=0;
				so_objIndex++;
			}
			mHTML+="</ul>";
		}
		mHTML+="</ul>";
		this.d.getElementById("so_mContainer").innerHTML =mHTML;
	},

	handleKeyEvent : function(e) {
		keyCode=document.all?window.event.keyCode:e.keyCode;
		if(keyCode==27) {
			this.cleanUp();
		}
	},

	cleanUp : function() 
	{
		if(this.d.getElementById("so_mContainer"))
		{
			this.d.body.removeChild(this.d.getElementById("so_mContainer"));
			this.d.body.removeChild(this.d.getElementById("so_mStyle"));
			if(typeof Event != "undefined")
				Event.stopObserving(this.d, "keydown", this.handleKeyEvent.bind(this));
			this.types = new Array();
			this.objs = new Array();
			this.hidden = new Array();
		}
	},

	disabled : document.all && !this.opera,

	inspect : function(obj) 
	{
		if(this.disabled)return alert("Sorry, this only works in Mozilla and Firefox currently.");
		this.cleanUp();
		mObj=this.d.body.appendChild(this.d.createElement("div"));
		mObj.id="so_mContainer";
		sObj=this.d.body.appendChild(this.d.createElement("style"));
		sObj.id="so_mStyle";
		sObj.type="text/css";
		sObj.innerHTML = this.style;
		if(typeof Event != "undefined")
			Event.observe(this.d, "keydown", this.handleKeyEvent.bind(this));

		this.parseJS(obj);
		this.buildTree();

		cObj=mObj.appendChild(this.d.createElement("div"));
		cObj.className="credits";
		cObj.innerHTML = "<b>[esc] to <a href=\"javascript:Prado.Inspector.cleanUp();\">close</a></b><br />Javascript Object Tree V2.0, <a target=\"_blank\" href=\"http://slayeroffice.com/?c=/content/tools/js_tree.html\">more info</a>.";

		window.scrollTo(0,0);
	},

	style : "#so_mContainer { position:absolute; top:5px; left:5px; background-color:#E3EBED; text-align:left; font:9pt verdana; width:85%; border:2px solid #000; padding:5px; z-index:1000;  color:#000; } " + 
			"#so_mContainer ul { padding-left:20px; } " + 
			"#so_mContainer ul li { display:block; list-style-type:none; list-style-image:url(); line-height:2em; -moz-border-radius:.75em; font:10px verdana; padding:0; margin:2px; color:#000; } " +
			"#so_mContainer li:hover { background-color:#E3EBED; } " + 
			"#so_mContainer ul li span { position:relative; width:15px; height:15px; margin-right:4px; } " +
			"#so_mContainer pre { background-color:#F9FAFB; border:1px solid #638DA1; height:auto; padding:5px; font:9px verdana; color:#000; } " + 
			"#so_mContainer .topLevel { margin:0; padding:0; } " +
			"#so_mContainer .credits { float:left; width:200px; font:6.5pt verdana; color:#000; padding:2px; margin-left:5px; text-align:left; border-top:1px solid #000; margin-top:15px; width:75%; } " +
			"#so_mContainer .credits a { font:9px verdana; font-weight:bold; color:#004465; text-decoration:none; background-color:transparent; }"
}

function var_dump(obj)
{
	Prado.Inspector.inspect(obj);
}