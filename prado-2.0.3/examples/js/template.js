var TrimPath;
(function(){
var _1=eval;
if(TrimPath==null){
TrimPath=new Object();
}
if(TrimPath.evalEx==null){
TrimPath.evalEx=function(_2){
return _1(_2);
};
}
TrimPath.parseTemplate=function(_3,_4,_5){
if(_5==null){
_5=TrimPath.parseTemplate_etc;
}
var _6=parse(_3,_4,_5);
var _7=TrimPath.evalEx(_6,_4,1);
if(_7!=null){
return new _5.Template(_4,_3,_6,_7,_5);
}
return null;
};
try{
String.prototype.process=function(_8,_9){
var _10=TrimPath.parseTemplate(this,null);
if(_10!=null){
return _10.process(_8,_9);
}
return this;
};
}
catch(e){
}
TrimPath.parseTemplate_etc={};
TrimPath.parseTemplate_etc.statementTag="forelse|for|if|elseif|else|var|macro";
TrimPath.parseTemplate_etc.statementDef={"if":{delta:1,prefix:"if (",suffix:") {",paramMin:1},"else":{delta:0,prefix:"} else {"},"elseif":{delta:0,prefix:"} else { if (",suffix:") {",paramDefault:"true"},"/if":{delta:-1,prefix:"}"},"for":{delta:1,paramMin:3,prefixFunc:function(_11,_12,_13,etc){
if(_11[2]!="in"){
throw new etc.ParseError(_13,_12.line,"bad for loop statement: "+_11.join(" "));
}
var _15=_11[1];
var _16="__LIST__"+_15;
return ["var ",_16," = ",_11[3],";","if ((",_16,") != null && (",_16,").length > 0) { for (var ",_15,"_index in ",_16,") { var ",_15," = ",_16,"[",_15,"_index];"].join("");
}},"forelse":{delta:0,prefix:"} } else { if (",suffix:") {",paramDefault:"true"},"/for":{delta:-1,prefix:"} }"},"var":{delta:0,prefix:"var ",suffix:";"},"macro":{delta:1,prefix:"function ",suffix:"{ var _OUT_arr = []; var _OUT = { write: function(m) { if (m) _OUT_arr.push(m); }, }; "},"/macro":{delta:-1,prefix:" return _OUT_arr.join(''); }"}};
TrimPath.parseTemplate_etc.modifierDef={"eat":function(v){
return "";
},"escape":function(s){
return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
},"capitalize":function(s){
return String(s).toUpperCase();
},"default":function(s,d){
return s!=null?s:d;
}};
TrimPath.parseTemplate_etc.modifierDef.h=TrimPath.parseTemplate_etc.modifierDef.escape;
TrimPath.parseTemplate_etc.Template=function(_20,_21,_22,_23,etc){
this.process=function(_24,_25){
if(_24==null){
_24={};
}
if(_24._MODIFIERS==null){
_24._MODIFIERS={};
}
for(var k in etc.modifierDef){
if(_24._MODIFIERS[k]==null){
_24._MODIFIERS[k]=etc.modifierDef[k];
}
}
if(_25==null){
_25={};
}
var _27=[];
var _28={write:function(m){
if(m){
_27.push(m);
}
}};
try{
_23(_28,_24,_25);
}
catch(e){
if(_25.throwExceptions==true){
throw e;
}
var _30=new String(_27.join("")+"[ERROR: "+e.toString()+"]");
_30["exception"]=e;
return _30;
}
return _27.join("");
};
this.name=_20;
this.source=_21;
this.sourceFunc=_22;
this.toString=function(){
return "TrimPath.Template ["+_20+"]";
};
};
TrimPath.parseTemplate_etc.ParseError=function(_31,_32,_33){
this.name=_31;
this.line=_32;
this.message=_33;
};
TrimPath.parseTemplate_etc.ParseError.prototype.toString=function(){
return ("TrimPath template ParseError in "+this.name+": line "+this.line+", "+this.msg);
};
var _34=function(_35,_36,etc){
_35=cleanWhiteSpace(_35);
var _37=["var TrimPath_Template_TEMP = function(_OUT, _CONTEXT, _FLAGS) { with (_CONTEXT) {"];
var _38={stack:[],line:1};
var _39=-1;
while(_39+1<_35.length){
var _40=_39;
_40=_35.indexOf("{",_40+1);
while(_40>=0){
if(_35.charAt(_40-1)!="$"&&_35.charAt(_40-1)!="\\"){
var _41=(_35.charAt(_40+1)=="/"?2:1);
if(_35.substring(_40+_41,_40+10+_41).search(TrimPath.parseTemplate_etc.statementTag)==0){
break;
}
}
_40=_35.indexOf("{",_40+1);
}
if(_40<0){
break;
}
var _42=_35.indexOf("}",_40+1);
if(_42<0){
break;
}
emitSectionText(_35.substring(_39+1,_40),_37);
emitStatement(_35.substring(_40,_42+1),_38,_37,_36,etc);
_39=_42;
}
emitSectionText(_35.substring(_39+1),_37);
if(_38.stack.length!=0){
throw new etc.ParseError(_36,_38.line,"unclosed, unmatched statement(s): "+_38.stack.join(","));
}
_37.push("}}; TrimPath_Template_TEMP");
return _37.join("");
};
var _43=function(_44,_45,_46,_47,etc){
var _48=_44.slice(1,-1).split(" ");
var _49=etc.statementDef[_48[0]];
if(_49==null){
emitSectionText(_44,_46);
return;
}
if(_49.delta<0){
if(_45.stack.length<=0){
throw new etc.ParseError(_47,_45.line,"close tag does not match any previous statement: "+_44);
}
_45.stack.pop();
}
if(_49.delta>0){
_45.stack.push(_44);
}
if(_49.paramMin!=null&&_49.paramMin>=_48.length){
throw new etc.ParseError(_47,_45.line,"statement needs more parameters: "+_44);
}
if(_49.prefixFunc!=null){
_46.push(_49.prefixFunc(_48,_45,_47,etc));
}else{
_46.push(_49.prefix);
}
if(_49.suffix!=null){
if(_48.length<=1){
if(_49.paramDefault!=null){
_46.push(_49.paramDefault);
}
}else{
for(var i=1;i<_48.length;i++){
if(i>1){
_46.push(" ");
}
_46.push(_48[i]);
}
}
_46.push(_49.suffix);
}
};
var _51=function(_52,_53){
if(_52.length<=0){
return;
}
var _54=0;
var _55=_52.length-1;
while(_54<_52.length&&(_52.charAt(_54)=="\n")){
_54++;
}
while(_55>=0&&(_52.charAt(_55)==" "||_52.charAt(_55)=="\t")){
_55--;
}
if(_55<_54){
_55=_54;
}
if(_54>0){
_53.push("if (_FLAGS.keepWhitespace == true) _OUT.write(\"");
_53.push(_52.substring(0,_54).replace("\n","\\n"));
_53.push("\");");
}
var _56=_52.substring(_54,_55+1).split("\n");
for(var i=0;i<_56.length;i++){
emitSectionTextLine(_56[i],_53);
if(i<_56.length-1){
_53.push("_OUT.write(\"\\n\");\n");
}
}
if(_55+1<_52.length){
_53.push("if (_FLAGS.keepWhitespace == true) _OUT.write(\"");
_53.push(_52.substring(_55+1).replace("\n","\\n"));
_53.push("\");");
}
};
var _57=function(_58,_59){
var _60=-1;
while(_60+1<_58.length){
var _61=_58.indexOf("${",_60+1);
if(_61<0){
break;
}
var _62=_58.indexOf("}",_61+2);
if(_62<0){
break;
}
emitText(_58.substring(_60+1,_61),_59);
var _63=_58.substring(_61+2,_62).replace(/\|\|/g,"#@@#").split("|");
for(var k in _63){
_63[k]=_63[k].replace(/#@@#/g,"||");
}
_59.push("_OUT.write(");
emitExpression(_63,_63.length-1,_59);
_59.push(");");
_60=_62;
}
emitText(_58.substring(_60+1),_59);
};
var _64=function(_65,_66){
if(_65==null||_65.length<=0){
return;
}
_65=_65.replace(/\\/g,"\\\\");
_65=_65.replace(/"/g,"\\\"");
_66.push("_OUT.write(\"");
_66.push(_65);
_66.push("\");");
};
var _67=function(_68,_69,_70){
var _71=_68[_69];
if(_69<=0){
_70.push(_71);
return;
}
var _72=_71.split(":");
_70.push("_MODIFIERS[\"");
_70.push(_72[0]);
_70.push("\"](");
_67(_68,_69-1,_70);
if(_72.length>1){
_70.push(",");
_70.push(_72[1]);
}
_70.push(")");
};
var _73=function(_74){
_74=_74.replace(/\t/g,"    ");
_74=_74.replace(/\r\n/g,"\n");
_74=_74.replace(/\r/g,"\n");
_74=_74.replace(/^(.*\S)[ \t]+$/gm,"$1");
return _74;
};
TrimPath.parseDOMTemplate=function(_75,_76,_77){
if(_76==null){
_76=document;
}
var _78=_76.getElementById(_75);
var _79=_78.value;
if(_79==null){
_79=_78.innerHTML;
}
_79=_79.replace(/&lt;/g,"<").replace(/&gt;/g,">");
return TrimPath.parseTemplate(_79,_75,_77);
};
TrimPath.processDOMTemplate=function(_80,_81,_82,_83,_84){
return TrimPath.parseDOMTemplate(_80,_83,_84).process(_81,_82);
};
})();

