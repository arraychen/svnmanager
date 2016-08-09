Prado.Validation=Class.create();
Prado.Validation.Util=Class.create();
Prado.Validation.Util.toInteger=function(_1){
var _2=/^\s*[-\+]?\d+\s*$/;
if(_1.match(_2)==null){
return null;
}
var _3=parseInt(_1,10);
return (isNaN(_3)?null:_3);
};
Prado.Validation.Util.toDouble=function(_4,_5){
_5=undef(_5)?".":_5;
var _6=new RegExp("^\\s*([-\\+])?(\\d+)?(\\"+_5+"(\\d+))?\\s*$");
var m=_4.match(_6);
if(m==null){
return null;
}
var _8=m[1]+(m[2].length>0?m[2]:"0")+"."+m[4];
var _9=parseFloat(_8);
return (isNaN(_9)?null:_9);
};
Prado.Validation.Util.toCurrency=function(_10,_11,_12,_13){
_11=undef(_11)?",":_11;
_13=undef(_13)?".":_13;
_12=undef(_12)?2:_12;
var exp=new RegExp("^\\s*([-\\+])?(((\\d+)\\"+_11+")*)(\\d+)"+((_12>0)?"(\\"+_13+"(\\d{1,"+_12+"}))?":"")+"\\s*$");
var m=_10.match(exp);
if(m==null){
return null;
}
var _15=m[2]+m[5];
var _16=m[1]+_15.replace(new RegExp("(\\"+_11+")","g"),"")+((_12>0)?"."+m[7]:"");
var num=parseFloat(_16);
return (isNaN(num)?null:num);
};
Prado.Validation.Util.toDate=function(_18,_19){
var y=0;
var m=-1;
var d=0;
var a=_18.split(/\W+/);
var b=_19.match(/%./g);
var i=0,j=0;
var hr=0;
var min=0;
for(i=0;i<a.length;++i){
if(!a[i]){
continue;
}
switch(b[i]){
case "%d":
case "%e":
d=parseInt(a[i],10);
break;
case "%m":
m=parseInt(a[i],10)-1;
break;
case "%Y":
case "%y":
y=parseInt(a[i],10);
(y<100)&&(y+=(y>29)?1900:2000);
break;
case "%H":
case "%I":
case "%k":
case "%l":
hr=parseInt(a[i],10);
break;
case "%P":
case "%p":
if(/pm/i.test(a[i])&&hr<12){
hr+=12;
}
break;
case "%M":
min=parseInt(a[i],10);
break;
}
}
if(y!=0&&m!=-1&&d!=0){
var _27=new Date(y,m,d,hr,min,0);
return (isObject(_27)&&y==_27.getFullYear()&&m==_27.getMonth()&&d==_27.getDate())?_27.valueOf():null;
}
return null;
};
Prado.Validation.Util.trim=function(_28){
if(undef(_28)){
return "";
}
return _28.replace(/^\s+|\s+$/g,"");
};
Prado.Validation.Util.focus=function(_29){
var obj=$(_29);
if(isObject(obj)&&isdef(obj.focus)){
setTimeout(function(){
obj.focus();
},100);
}
};
Prado.Validation.validators=[];
Prado.Validation.forms=[];
Prado.Validation.summaries=[];
Prado.Validation.groups=[];
Prado.Validation.ActiveTarget=null;
Prado.Validation.IsGroupValidation=false;
Prado.Validation.AddForm=function(id){
Prado.Validation.forms.push($(id));
};
Prado.Validation.AddTarget=function(id){
var _32=$(id);
Event.observe(_32,"click",function(){
Prado.Validation.ActiveTarget=_32;
});
};
Prado.Validation.AddGroup=function(_33,_34){
_33.active=false;
_33.target=$(_33.target);
_33.validators=_34;
Prado.Validation.groups.push(_33);
Event.observe(_33.target,"click",Prado.Validation.UpdateActiveGroup);
};
Prado.Validation.UpdateActiveGroup=function(ev){
var _36=Prado.Validation.groups;
for(var i=0;i<_36.length;i++){
_36[i].active=(isdef(ev)&&_36[i].target==Event.element(ev));
}
Prado.Validation.IsGroupValidation=isdef(ev);
};
Prado.Validation.IsValid=function(_37){
var _38=true;
var _39=Prado.Validation.validators;
for(var i=0;i<_39.length;i++){
_39[i].enabled=!_39[i].control||undef(_39[i].control.form)||_39[i].control.form==_37;
_39[i].visible=Prado.Validation.IsGroupValidation?_39[i].inActiveGroup():true;
_38&=_39[i].validate();
}
Prado.Validation.ShowSummary(_37);
Prado.Validation.UpdateActiveGroup();
return _38;
};
Prado.Validation.prototype={initialize:function(_40,_41){
this.evaluateIsValid=_40;
this.attr=undef(_41)?[]:_41;
this.message=$(_41.id);
this.control=$(_41.controltovalidate);
this.enabled=isdef(_41.enabled)?_41.enabled:true;
this.visible=isdef(_41.visible)?_41.visible:true;
this.isValid=true;
Prado.Validation.validators.push(this);
if(this.evaluateIsValid){
this.evaluateIsValid.bind(this);
}
},validate:function(){
if(this.visible&&this.enabled&&this.evaluateIsValid){
this.isValid=this.evaluateIsValid();
}else{
this.isValid=true;
}
this.observe();
this.update();
return this.isValid;
},update:function(){
if(this.attr.display=="Dynamic"){
this.isValid?Element.hide(this.message):Element.show(this.message);
}
if(this.message){
this.message.style.visibility=this.isValid?"hidden":"visible";
}
var _42=this.attr.controlcssclass;
if(this.control&&isString(_42)&&_42.length>0){
Element.condClassName(this.control,_42,!this.isValid);
}
Prado.Validation.ShowSummary();
},setValid:function(_43){
this.isValid=_43;
this.update();
},observe:function(){
if(undef(this.observing)){
if(this.control&&this.control.form){
Event.observe(this.control,"blur",this.validate.bind(this));
}
this.observing=true;
}
},convert:function(_44,_45){
if(undef(_45)){
_45=Form.Element.getValue(this.control);
}
switch(_44){
case "Integer":
return Prado.Validation.Util.toInteger(_45);
case "Double":
case "Float":
return Prado.Validation.Util.toDouble(_45,this.attr.decimalchar);
case "Currency":
return Prado.Validation.Util.toCurrency(_45,this.attr.groupchar,this.attr.digits,this.attr.decimalchar);
case "Date":
return Prado.Validation.Util.toDate(_45,this.attr.dateformat);
}
return _45.toString();
},inActiveGroup:function(){
var _46=Prado.Validation.groups;
for(var i=0;i<_46.length;i++){
if(_46[i].active&&_46[i].validators.contains(this.attr.id)){
return true;
}
}
return false;
}};
Prado.Validation.Summary=Class.create();
Prado.Validation.Summary.prototype={initialize:function(_47){
this.attr=_47;
this.div=$(_47.id);
this.visible=false;
this.enabled=false;
Prado.Validation.summaries.push(this);
},show:function(_48){
var _49=_48||this.attr.refresh=="1";
var _50=this.getMessages();
if(_50.length<=0||!this.visible||!this.enabled){
if(_49){
Element.hide(this.div);
}
return;
}
if(this.attr.showsummary!="False"&&_49){
this.div.style.display="block";
while(this.div.childNodes.length>0){
this.div.removeChild(this.div.lastChild);
}
new Insertion.Bottom(this.div,this.formatSummary(_50));
}
if(_48){
window.scrollTo(this.div.offsetLeft-20,this.div.offsetTop-20);
}
var _51=this;
if(_48&&this.attr.showmessagebox=="True"&&_49){
setTimeout(function(){
alert(_51.formatMessageBox(_50));
},20);
}
},getMessages:function(){
var _52=Prado.Validation.validators;
var _53=[];
for(var i=0;i<_52.length;i++){
if(_52[i].isValid==false&&isString(_52[i].attr.errormessage)&&_52[i].attr.errormessage.length>0){
_53.push(_52[i].attr.errormessage);
}
}
return _53;
},formats:function(_54){
switch(_54){
case "List":
return {header:"<br />",first:"",pre:"",post:"<br />",last:""};
case "SingleParagraph":
return {header:" ",first:"",pre:"",post:" ",last:"<br />"};
case "BulletList":
default:
return {header:"",first:"<ul>",pre:"<li>",post:"</li>",last:"</ul>"};
}
},formatSummary:function(_55){
var _56=this.formats(this.attr.displaymode);
var _57=isdef(this.attr.headertext)?this.attr.headertext+_56.header:"";
_57+=_56.first;
for(var i=0;i<_55.length;i++){
_57+=(_55[i].length>0)?_56.pre+_55[i]+_56.post:"";
}
_57+=_56.last;
return _57;
},formatMessageBox:function(_58){
var _59=isdef(this.attr.headertext)?this.attr.headertext+"\n":"";
for(var i=0;i<_58.length;i++){
switch(this.attr.displaymode){
case "List":
_59+=_58[i]+"\n";
break;
case "BulletList":
default:
_59+="  - "+_58[i]+"\n";
break;
case "SingleParagraph":
_59+=_58[i]+" ";
break;
}
}
return _59;
},inActiveGroup:function(){
var _60=Prado.Validation.groups;
for(var i=0;i<_60.length;i++){
if(_60[i].active&&_60[i].id==this.attr.group){
return true;
}
}
return false;
}};
Prado.Validation.ShowSummary=function(_61){
var _62=Prado.Validation.summaries;
for(var i=0;i<_62.length;i++){
if(isdef(_61)){
if(Prado.Validation.IsGroupValidation){
_62[i].visible=_62[i].inActiveGroup();
}else{
_62[i].visible=undef(_62[i].attr.group);
}
_62[i].enabled=$(_62[i].attr.form)==_61;
}
_62[i].show(_61);
}
};
Prado.Validation.OnSubmit=function(ev){
if(typeof tinyMCE!="undefined"){
tinyMCE.triggerSave();
}
if(!Prado.Validation.ActiveTarget){
return true;
}
var _63=Prado.Validation.IsValid(Event.element(ev)||ev);
if(Event.element(ev)&&!_63){
Event.stop(ev);
}
Prado.Validation.ActiveTarget=null;
return _63;
};
Prado.Validation.OnLoad=function(){
Event.observe(Prado.Validation.forms,"submit",Prado.Validation.OnSubmit);
};
Event.OnLoad(Prado.Validation.OnLoad);

Prado.Validation.TRequiredFieldValidator=function(){
var _1=this.control.getAttribute("type");
if(_1=="file"){
return true;
}else{
var _2=Prado.Validation.Util.trim;
var a=_2(Form.Element.getValue(this.control));
var b=_2(this.attr.initialvalue);
return (a!=b);
}
};
Prado.Validation.TRegularExpressionValidator=function(){
var _5=Prado.Validation.Util.trim;
var _6=_5(Form.Element.getValue(this.control));
if(_6==""){
return true;
}
var rx=new RegExp(this.attr.validationexpression);
var _8=rx.exec(_6);
return (_8!=null&&_6==_8[0]);
};
Prado.Validation.TEmailAddressValidator=Prado.Validation.TRegularExpressionValidator;
Prado.Validation.TCustomValidator=function(){
var _9=Prado.Validation.Util.trim;
var _10=isNull(this.control)?"":_9(Form.Element.getValue(this.control));
var _11=true;
var _12=this.attr.clientvalidationfunction;
if(isString(_12)&&_12!=""){
eval("valid = ("+_12+"(this, value) != false);");
}
return _11;
};
Prado.Validation.TRangeValidator=function(){
var _13=Prado.Validation.Util.trim;
var _14=_13(Form.Element.getValue(this.control));
if(_14==""){
return true;
}
var _15=this.attr.minimumvalue;
var _16=this.attr.maximumvalue;
if(undef(_15)&&undef(_16)){
return true;
}
if(_15==""){
_15=0;
}
if(_16==""){
_16=0;
}
var _17=this.attr.type;
if(undef(_17)){
return (parseFloat(_14)>=parseFloat(_15))&&(parseFloat(_14)<=parseFloat(_16));
}
var min=this.convert(_17,_15);
var max=this.convert(_17,_16);
_14=this.convert(_17,_14);
return _14>=min&&_14<=max;
};
Prado.Validation.TCompareValidator=function(){
var _20=Prado.Validation.Util.trim;
var _21=_20(Form.Element.getValue(this.control));
if(_21.length==0){
return true;
}
var _22;
var _23=$(this.attr.controlhookup);
if(_23){
_22=_20(Form.Element.getValue(_23));
}else{
_22=isString(this.attr.valuetocompare)?this.attr.valuetocompare:"";
}
var _24=Prado.Validation.TCompareValidator.compare;
var _25=_24.bind(this)(_21,_22);
if(_23){
var _26=this.attr.controlcssclass;
if(isString(_26)&&_26.length>0){
Element.condClassName(_23,_26,!_25);
}
if(undef(this.observingComparee)){
Event.observe(_23,"change",this.validate.bind(this));
this.observingComparee=true;
}
}
return _25;
};
Prado.Validation.TCompareValidator.compare=function(_27,_28){
var op1,op2;
if((op1=this.convert(this.attr.type,_27))==null){
return false;
}
if(this.attr.operator=="DataTypeCheck"){
return true;
}
if((op2=this.convert(this.attr.type,_28))==null){
return true;
}
switch(this.attr.operator){
case "NotEqual":
return (op1!=op2);
case "GreaterThan":
return (op1>op2);
case "GreaterThanEqual":
return (op1>=op2);
case "LessThan":
return (op1<op2);
case "LessThanEqual":
return (op1<=op2);
default:
return (op1==op2);
}
};
Prado.Validation.TRequiredListValidator=function(){
var min=undef(this.attr.min)?Number.NEGATIVE_INFINITY:parseInt(this.attr.min);
var max=undef(this.attr.max)?Number.POSITIVE_INFINITY:parseInt(this.attr.max);
var _30=document.getElementsByName(this.attr.selector);
if(_30.length<=0){
_30=document.getElementsBySelector(this.attr.selector);
}
if(_30.length<=0){
return true;
}
var _31=new Array();
if(isString(this.attr.required)&&this.attr.required.length>0){
_31=this.attr.required.split(/,\s* /);
}
var _32=true;
var _33=Prado.Validation.TRequiredListValidator;
switch(_30[0].type){
case "radio":
case "checkbox":
_32=_33.IsValidRadioList(_30,min,max,_31);
break;
case "select-multiple":
_32=_33.IsValidSelectMultipleList(_30,min,max,_31);
break;
}
var _34=this.attr.elementcssclass;
if(isString(_34)&&_34.length>0){
map(_30,function(_35){
condClass(_35,_34,!_32);
});
}
if(undef(this.observingRequiredList)){
Event.observe(_30,"change",this.validate.bind(this));
this.observingRequiredList=true;
}
return _32;
};
Prado.Validation.TRequiredListValidator.IsValidRadioList=function(_36,min,max,_37){
var _38=0;
var _39=new Array();
for(var i=0;i<_36.length;i++){
if(_36[i].checked){
_38++;
_39.push(_36[i].value);
}
}
return Prado.Validation.TRequiredListValidator.IsValidList(_38,_39,min,max,_37);
};
Prado.Validation.TRequiredListValidator.IsValidSelectMultipleList=function(_41,min,max,_42){
var _43=0;
var _44=new Array();
for(var i=0;i<_41.length;i++){
var _45=_41[i];
for(var j=0;j<_45.options.length;j++){
if(_45.options[j].selected){
_43++;
_44.push(_45.options[j].value);
}
}
}
return Prado.Validation.TRequiredListValidator.IsValidList(_43,_44,min,max,_42);
};
Prado.Validation.TRequiredListValidator.IsValidList=function(_47,_48,min,max,_49){
var _50=true;
if(_49.length>0){
if(_48.length<_49.length){
return false;
}
for(var k=0;k<_49.length;k++){
_50=_50&&_48.contains(_49[k]);
}
}
return _50&&_47>=min&&_47<=max;
};

