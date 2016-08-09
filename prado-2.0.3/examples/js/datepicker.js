Prado.Calendar=Class.create();
Prado.Calendar.Util=Class.create();
Object.extend(Prado.Calendar.Util,{pad:function(_1,X){
X=(!X?2:X);
_1=""+_1;
while(_1.length<X){
_1="0"+_1;
}
return _1;
},FormatDate:function(_3,_4){
if(!isObject(_3)){
return "";
}
if(_4.indexOf("%")>-1){
alert("Please use the new SimpleDateFormat pattern, e.g. yyyy-MM-dd");
return this.FormatDateDepr(_3,_4);
}else{
return this.SimpleFormatDate(_3,_4);
}
},ParseDate:function(_5,_6){
val=String(_5);
_6=String(_6);
if(val.length<=0){
return null;
}
if(_6.length<=0){
return new Date(_5);
}
if(_6.indexOf("%")>-1){
return this.ParseDateDepr(_5,_6);
}else{
return this.SimpleParseDate(_5,_6);
}
},FormatDateDepr:function(_7,_8){
var m=_7.getMonth();
var d=_7.getDate();
var y=_7.getFullYear();
var s={};
s["%d"]=this.pad(d);
s["%e"]=d;
s["%m"]=this.pad(m+1);
s["%y"]=(""+y).substr(2,2);
s["%Y"]=y;
var re=/%./g;
var a=_8.match(re);
for(var i=0;i<a.length;i++){
var tmp=s[a[i]];
if(tmp){
re=new RegExp(a[i],"g");
_8=_8.replace(re,tmp);
}
}
return _8;
},ParseDateDepr:function(_17,_18){
var y=0;
var m=-1;
var d=0;
var a=_17.split(/\W+/);
var b=_18.match(/%./g);
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
var _22=new Date(y,m,d,hr,min,0);
return (isObject(_22)&&y==_22.getFullYear()&&m==_22.getMonth()&&d==_22.getDate())?_22:null;
}
return null;
},SimpleFormatDate:function(_23,_24){
if(!isObject(_23)){
return "";
}
var _25=new Array();
_25["d"]=_23.getDate();
_25["dd"]=this.pad(_23.getDate(),2);
_25["M"]=_23.getMonth()+1;
_25["MM"]=this.pad(_23.getMonth()+1,2);
var _26=""+_23.getFullYear();
_26=(_26.length==2)?"19"+_26:_26;
_25["yyyy"]=_26;
_25["yy"]=_25["yyyy"].toString().substr(2,2);
var frm=new String(_24);
for(var _28 in _25){
var reg=new RegExp("\\b"+_28+"\\b","g");
frm=frm.replace(reg,_25[_28]);
}
return frm;
},SimpleParseDate:function(_30,_31){
val=String(_30);
_31=String(_31);
if(val.length<=0){
return null;
}
if(_31.length<=0){
return new Date(_30);
}
var _32=function(val){
var _34="1234567890";
for(var i=0;i<val.length;i++){
if(_34.indexOf(val.charAt(i))==-1){
return false;
}
}
return true;
};
var _35=function(str,i,_37,_38){
for(var x=_38;x>=_37;x--){
var _40=str.substring(i,i+x);
if(_40.length<_37){
return null;
}
if(_32(_40)){
return _40;
}
}
return null;
};
var _41=0;
var _42=0;
var c="";
var _44="";
var _45="";
var x,y;
var now=new Date();
var _47=now.getFullYear();
var _48=now.getMonth()+1;
var _49=1;
while(_42<_31.length){
c=_31.charAt(_42);
_44="";
while((_31.charAt(_42)==c)&&(_42<_31.length)){
_44+=_31.charAt(_42++);
}
if(_44=="yyyy"||_44=="yy"||_44=="y"){
if(_44=="yyyy"){
x=4;
y=4;
}
if(_44=="yy"){
x=2;
y=2;
}
if(_44=="y"){
x=2;
y=4;
}
_47=_35(val,_41,x,y);
if(_47==null){
return null;
}
_41+=_47.length;
if(_47.length==2){
if(_47>70){
_47=1900+(_47-0);
}else{
_47=2000+(_47-0);
}
}
}else{
if(_44=="MM"||_44=="M"){
_48=_35(val,_41,_44.length,2);
if(_48==null||(_48<1)||(_48>12)){
return null;
}
_41+=_48.length;
}else{
if(_44=="dd"||_44=="d"){
_49=_35(val,_41,_44.length,2);
if(_49==null||(_49<1)||(_49>31)){
return null;
}
_41+=_49.length;
}else{
if(val.substring(_41,_41+_44.length)!=_44){
return null;
}else{
_41+=_44.length;
}
}
}
}
}
if(_41!=val.length){
return null;
}
if(_48==2){
if(((_47%4==0)&&(_47%100!=0))||(_47%400==0)){
if(_49>29){
return null;
}
}else{
if(_49>28){
return null;
}
}
}
if((_48==4)||(_48==6)||(_48==9)||(_48==11)){
if(_49>30){
return null;
}
}
var _50=new Date(_47,_48-1,_49,0,0,0);
return _50;
},IsLeapYear:function(_51){
return ((_51%4==0)&&((_51%100!=0)||(_51%400==0)));
},yearLength:function(_52){
if(this.isLeapYear(_52)){
return 366;
}else{
return 365;
}
},dayOfYear:function(_53){
var a=this.isLeapYear(_53.getFullYear())?Calendar.LEAP_NUM_DAYS:Calendar.NUM_DAYS;
return a[_53.getMonth()]+_53.getDate();
},ISODate:function(_54){
var y=_54.getFullYear();
var m=this.pad(_54.getMonth()+1);
var d=this.pad(_54.getDate());
return String(y)+String(m)+String(d);
},browser:function(){
var _55={Version:"1.0"};
var _56=parseInt(navigator.appVersion);
_55.nver=_56;
_55.ver=navigator.appVersion;
_55.agent=navigator.userAgent;
_55.dom=document.getElementById?1:0;
_55.opera=window.opera?1:0;
_55.ie5=(_55.ver.indexOf("MSIE 5")>-1&&_55.dom&&!_55.opera)?1:0;
_55.ie6=(_55.ver.indexOf("MSIE 6")>-1&&_55.dom&&!_55.opera)?1:0;
_55.ie4=(document.all&&!_55.dom&&!_55.opera)?1:0;
_55.ie=_55.ie4||_55.ie5||_55.ie6;
_55.mac=_55.agent.indexOf("Mac")>-1;
_55.ns6=(_55.dom&&parseInt(_55.ver)>=5)?1:0;
_55.ie3=(_55.ver.indexOf("MSIE")&&(_56<4));
_55.hotjava=(_55.agent.toLowerCase().indexOf("hotjava")!=-1)?1:0;
_55.ns4=(document.layers&&!_55.dom&&!_55.hotjava)?1:0;
_55.bw=(_55.ie6||_55.ie5||_55.ie4||_55.ns4||_55.ns6||_55.opera);
_55.ver3=(_55.hotjava||_55.ie3);
_55.opera7=((_55.agent.toLowerCase().indexOf("opera 7")>-1)||(_55.agent.toLowerCase().indexOf("opera/7")>-1));
_55.operaOld=_55.opera&&!_55.opera7;
return _55;
},ImportCss:function(doc,_58){
if(this.browser().ie){
var _59=doc.createStyleSheet(_58);
}else{
var elm=doc.createElement("link");
elm.rel="stylesheet";
elm.href=_58;
if(headArr=doc.getElementsByTagName("head")){
headArr[0].appendChild(elm);
}
}
}});
Object.extend(Prado.Calendar,{NUM_DAYS:[0,31,59,90,120,151,181,212,243,273,304,334],LEAP_NUM_DAYS:[0,31,60,91,121,152,182,213,244,274,305,335]});
Prado.Calendar.prototype={monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],shortWeekDayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],format:"yyyy-MM-dd",css:"calendar_system.css",initialize:function(_61,_62){
this.attr=_62||[];
this.control=$(_61);
this.dateSlot=new Array(42);
this.weekSlot=new Array(6);
this.firstDayOfWeek=1;
this.minimalDaysInFirstWeek=4;
this.currentDate=new Date();
this.selectedDate=null;
this.className="Prado_Calendar";
this.trigger=this.attr.trigger?$(this.attr.trigger):this.control;
Event.observe(this.trigger,"click",this.show.bind(this));
Prado.Calendar.Util.ImportCss(document,this.css);
if(this.attr.format){
this.format=this.attr.format;
}
this.create();
this.hookEvents();
},create:function(){
var div;
var _64;
var _65;
var tr;
var td;
this._calDiv=document.createElement("div");
this._calDiv.className=this.className;
this._calDiv.style.display="none";
div=document.createElement("div");
div.className="calendarHeader";
this._calDiv.appendChild(div);
_64=document.createElement("table");
_64.style.cellSpacing=0;
div.appendChild(_64);
_65=document.createElement("tbody");
_64.appendChild(_65);
tr=document.createElement("tr");
_65.appendChild(tr);
td=document.createElement("td");
td.className="prevMonthButton";
this._previousMonth=document.createElement("button");
this._previousMonth.appendChild(document.createTextNode("<<"));
td.appendChild(this._previousMonth);
tr.appendChild(td);
td=document.createElement("td");
td.className="labelContainer";
tr.appendChild(td);
this._monthSelect=document.createElement("select");
for(var i=0;i<this.monthNames.length;i++){
var opt=document.createElement("option");
opt.innerHTML=this.monthNames[i];
opt.value=i;
if(i==this.currentDate.getMonth()){
opt.selected=true;
}
this._monthSelect.appendChild(opt);
}
td.appendChild(this._monthSelect);
td=document.createElement("td");
td.className="labelContainer";
tr.appendChild(td);
this._yearSelect=document.createElement("select");
for(var i=1920;i<2050;++i){
var opt=document.createElement("option");
opt.innerHTML=i;
opt.value=i;
if(i==this.currentDate.getFullYear()){
opt.selected=false;
}
this._yearSelect.appendChild(opt);
}
td.appendChild(this._yearSelect);
td=document.createElement("td");
td.className="nextMonthButton";
this._nextMonth=document.createElement("button");
this._nextMonth.appendChild(document.createTextNode(">>"));
td.appendChild(this._nextMonth);
tr.appendChild(td);
div=document.createElement("div");
div.className="calendarBody";
this._calDiv.appendChild(div);
this._table=div;
var _69;
_64=document.createElement("table");
_64.className="grid";
div.appendChild(_64);
var _70=document.createElement("thead");
_64.appendChild(_70);
tr=document.createElement("tr");
_70.appendChild(tr);
for(i=0;i<7;++i){
td=document.createElement("th");
_69=document.createTextNode(this.shortWeekDayNames[(i+this.firstDayOfWeek)%7]);
td.appendChild(_69);
td.className="weekDayHead";
tr.appendChild(td);
}
_65=document.createElement("tbody");
_64.appendChild(_65);
for(week=0;week<6;++week){
tr=document.createElement("tr");
_65.appendChild(tr);
for(day=0;day<7;++day){
td=document.createElement("td");
td.className="calendarDate";
_69=document.createTextNode(String.fromCharCode(160));
td.appendChild(_69);
tr.appendChild(td);
var tmp=new Object();
tmp.tag="DATE";
tmp.value=-1;
tmp.data=_69;
this.dateSlot[(week*7)+day]=tmp;
Event.observe(td,"mouseover",this.hover.bind(this));
Event.observe(td,"mouseout",this.hover.bind(this));
}
}
div=document.createElement("div");
div.className="calendarFooter";
this._calDiv.appendChild(div);
_64=document.createElement("table");
_64.className="footerTable";
div.appendChild(_64);
_65=document.createElement("tbody");
_64.appendChild(_65);
tr=document.createElement("tr");
_65.appendChild(tr);
td=document.createElement("td");
td.className="todayButton";
this._todayButton=document.createElement("button");
var _71=new Date();
var _72=_71.getDate()+" "+this.monthNames[_71.getMonth()]+", "+_71.getFullYear();
this._todayButton.appendChild(document.createTextNode(_72));
td.appendChild(this._todayButton);
tr.appendChild(td);
td=document.createElement("td");
td.className="clearButton";
this._clearButton=document.createElement("button");
var _71=new Date();
_72="Clear";
this._clearButton.appendChild(document.createTextNode(_72));
td.appendChild(this._clearButton);
tr.appendChild(td);
document.body.appendChild(this._calDiv);
this.update();
this.updateHeader();
return this._calDiv;
},hookEvents:function(){
this._previousMonth.hideFocus=true;
this._nextMonth.hideFocus=true;
this._todayButton.hideFocus=true;
Event.observe(this._previousMonth,"click",this.prevMonth.bind(this));
Event.observe(this._nextMonth,"click",this.nextMonth.bind(this));
Event.observe(this._todayButton,"click",this.selectToday.bind(this));
Event.observe(this._clearButton,"click",this.clearSelection.bind(this));
Event.observe(this._monthSelect,"change",this.monthSelect.bind(this));
Event.observe(this._yearSelect,"change",this.yearSelect.bind(this));
Event.observe(this._calDiv,"mousewheel",this.mouseWheelChange.bind(this));
Event.observe(this._table,"click",this.selectDate.bind(this));
Event.observe(this._calDiv,"keydown",this.keyPressed.bind(this));
},keyPressed:function(ev){
if(!ev){
ev=document.parentWindow.event;
}
var kc=ev.keyCode!=null?ev.keyCode:ev.charCode;
if(kc=Event.KEY_RETURN){
this.setSelectedDate(this.currentDate);
this.hide();
return false;
}
if(kc<37||kc>40){
return true;
}
var d=new Date(this.currentDate).valueOf();
if(kc==Event.KEY_LEFT){
d-=86400000;
}else{
if(kc==Event.KEY_RIGHT){
d+=86400000;
}else{
if(kc==Event.KEY_UP){
d-=604800000;
}else{
if(kc==Event.KEY_DOWN){
d+=604800000;
}
}
}
}
this.setCurrentDate(new Date(d));
return false;
},selectDate:function(ev){
var el=Event.element(ev);
while(el.nodeType!=1){
el=el.parentNode;
}
while(el!=null&&el.tagName&&el.tagName.toLowerCase()!="td"){
el=el.parentNode;
}
if(el==null||el.tagName==null||el.tagName.toLowerCase()!="td"){
return;
}
var d=new Date(this.currentDate);
var n=Number(el.firstChild.data);
if(isNaN(n)||n<=0||n==null){
return;
}
d.setDate(n);
this.setSelectedDate(d);
this.hide();
},selectToday:function(){
this.setSelectedDate(new Date());
this.hide();
},clearSelection:function(){
this.selectedDate=null;
if(isFunction(this.onchange)){
this.onchange();
}
this.hide();
},monthSelect:function(ev){
this.setMonth(Form.Element.getValue(Event.element(ev)));
},yearSelect:function(ev){
this.setYear(Form.Element.getValue(Event.element(ev)));
},mouseWheelChange:function(e){
if(e==null){
e=document.parentWindow.event;
}
var n=-e.wheelDelta/120;
var d=new Date(this.currentDate);
var m=this.getMonth()+n;
this.setMonth(m);
this.setCurrentDate(d);
return false;
},onchange:function(){
this.control.value=this.formatDate();
},formatDate:function(){
return Prado.Calendar.Util.FormatDate(this.selectedDate,this.format);
},setCurrentDate:function(_78){
if(_78==null){
return;
}
if(isString(_78)||isNumber(_78)){
_78=new Date(_78);
}
if(this.currentDate.getDate()!=_78.getDate()||this.currentDate.getMonth()!=_78.getMonth()||this.currentDate.getFullYear()!=_78.getFullYear()){
this.currentDate=new Date(_78);
this.updateHeader();
this.update();
}
},setSelectedDate:function(_79){
this.selectedDate=new Date(_79);
this.setCurrentDate(this.selectedDate);
if(isFunction(this.onchange)){
this.onchange();
}
},getElement:function(){
return this._calDiv;
},getSelectedDate:function(){
return isNull(this.selectedDate)?null:new Date(this.selectedDate);
},setYear:function(_80){
var d=new Date(this.currentDate);
d.setFullYear(_80);
this.setCurrentDate(d);
},setMonth:function(_81){
var d=new Date(this.currentDate);
d.setMonth(_81);
this.setCurrentDate(d);
},nextMonth:function(){
this.setMonth(this.currentDate.getMonth()+1);
},prevMonth:function(){
this.setMonth(this.currentDate.getMonth()-1);
},show:function(){
if(!this.showing){
var pos=Position.cumulativeOffset(this.control);
pos[1]+=this.control.offsetHeight;
this._calDiv.style.display="block";
this._calDiv.style.top=pos[1]+"px";
this._calDiv.style.left=pos[0]+"px";
Event.observe(document.body,"click",this.hideOnClick.bind(this));
var _83=Prado.Calendar.Util.ParseDate(Form.Element.getValue(this.control),this.format);
if(!isNull(_83)){
this.selectedDate=_83;
this.setCurrentDate(_83);
}
this.showing=true;
}
},hideOnClick:function(ev){
if(!this.showing){
return;
}
var el=Event.element(ev);
var _84=false;
do{
_84=_84||el.className==this.className;
_84=_84||el==this.trigger;
_84=_84||el==this.control;
if(_84){
break;
}
el=el.parentNode;
}while(el);
if(!_84){
this.hide();
}
},hide:function(){
if(this.showing){
this._calDiv.style.display="none";
this.showing=false;
Event.stopObserving(document.body,"click",this.hideOnClick.bind(this));
}
},update:function(){
var _85=Prado.Calendar.Util;
var _86=this.currentDate;
var _87=_85.ISODate(new Date());
var _88=isNull(this.selectedDate)?"":_85.ISODate(this.selectedDate);
var _89=_85.ISODate(_86);
var d1=new Date(_86.getFullYear(),_86.getMonth(),1);
var d2=new Date(_86.getFullYear(),_86.getMonth()+1,1);
var _92=Math.round((d2-d1)/(24*60*60*1000));
var _93=(d1.getDay()-this.firstDayOfWeek)%7;
if(_93<0){
_93+=7;
}
var _94=0;
while(_94<_93){
this.dateSlot[_94].value=-1;
this.dateSlot[_94].data.data=String.fromCharCode(160);
this.dateSlot[_94].data.parentNode.className="empty";
_94++;
}
for(i=1;i<=_92;i++,_94++){
var _95=this.dateSlot[_94];
var _96=_95.data.parentNode;
_95.value=i;
_95.data.data=i;
_96.className="date";
if(_85.ISODate(d1)==_87){
_96.className+=" today";
}
if(_85.ISODate(d1)==_89){
_96.className+=" current";
}
if(_85.ISODate(d1)==_88){
_96.className+=" selected";
}
d1=new Date(d1.getFullYear(),d1.getMonth(),d1.getDate()+1);
}
var _97=_94;
while(_94<42){
this.dateSlot[_94].value=-1;
this.dateSlot[_94].data.data=String.fromCharCode(160);
this.dateSlot[_94].data.parentNode.className="empty";
++_94;
}
},hover:function(ev){
Element.condClassName(Event.element(ev),"hover",ev.type=="mouseover");
},updateHeader:function(){
var _98=this._monthSelect.options;
var m=this.currentDate.getMonth();
for(var i=0;i<_98.length;++i){
_98[i].selected=false;
if(_98[i].value==m){
_98[i].selected=true;
}
}
_98=this._yearSelect.options;
var _99=this.currentDate.getFullYear();
for(var i=0;i<_98.length;++i){
_98[i].selected=false;
if(_98[i].value==_99){
_98[i].selected=true;
}
}
}};

