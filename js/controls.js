var Autocompleter={};
Autocompleter.Base=function(){
};
Autocompleter.Base.prototype={baseInitialize:function(_1,_2,_3){
this.element=$(_1);
this.update=$(_2);
this.hasFocus=false;
this.changed=false;
this.active=false;
this.index=0;
this.entryCount=0;
if(this.setOptions){
this.setOptions(_3);
}else{
this.options=_3||{};
}
this.options.paramName=this.options.paramName||this.element.name;
this.options.tokens=this.options.tokens||[];
this.options.frequency=this.options.frequency||0.4;
this.options.minChars=this.options.minChars||1;
this.options.onShow=this.options.onShow||function(_1,_2){
if(!_2.style.position||_2.style.position=="absolute"){
_2.style.position="absolute";
Position.clone(_1,_2,{setHeight:false,offsetTop:_1.offsetHeight});
}
Effect.Appear(_2,{duration:0.15});
};
this.options.onHide=this.options.onHide||function(_4,_5){
new Effect.Fade(_5,{duration:0.15});
};
if(typeof (this.options.tokens)=="string"){
this.options.tokens=new Array(this.options.tokens);
}
this.observer=null;
this.element.setAttribute("autocomplete","off");
Element.hide(this.update);
Event.observe(this.element,"blur",this.onBlur.bindAsEventListener(this));
Event.observe(this.element,"keypress",this.onKeyPress.bindAsEventListener(this));
},show:function(){
if(Element.getStyle(this.update,"display")=="none"){
this.options.onShow(this.element,this.update);
}
if(!this.iefix&&(navigator.appVersion.indexOf("MSIE")>0)&&(Element.getStyle(this.update,"position")=="absolute")){
new Insertion.After(this.update,"<iframe id=\""+this.update.id+"_iefix\" "+"style=\"display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);\" "+"src=\"javascript:false;\" frameborder=\"0\" scrolling=\"no\"></iframe>");
this.iefix=$(this.update.id+"_iefix");
}
if(this.iefix){
setTimeout(this.fixIEOverlapping.bind(this),50);
}
},fixIEOverlapping:function(){
Position.clone(this.update,this.iefix);
this.iefix.style.zIndex=1;
this.update.style.zIndex=2;
Element.show(this.iefix);
},hide:function(){
this.stopIndicator();
if(Element.getStyle(this.update,"display")!="none"){
this.options.onHide(this.element,this.update);
}
if(this.iefix){
Element.hide(this.iefix);
}
},startIndicator:function(){
if(this.options.indicator){
Element.show(this.options.indicator);
}
},stopIndicator:function(){
if(this.options.indicator){
Element.hide(this.options.indicator);
}
},onKeyPress:function(_6){
if(this.active){
switch(_6.keyCode){
case Event.KEY_TAB:
case Event.KEY_RETURN:
this.selectEntry();
Event.stop(_6);
case Event.KEY_ESC:
this.hide();
this.active=false;
Event.stop(_6);
return;
case Event.KEY_LEFT:
case Event.KEY_RIGHT:
return;
case Event.KEY_UP:
this.markPrevious();
this.render();
if(navigator.appVersion.indexOf("AppleWebKit")>0){
Event.stop(_6);
}
return;
case Event.KEY_DOWN:
this.markNext();
this.render();
if(navigator.appVersion.indexOf("AppleWebKit")>0){
Event.stop(_6);
}
return;
}
}else{
if(_6.keyCode==Event.KEY_TAB||_6.keyCode==Event.KEY_RETURN){
return;
}
}
this.changed=true;
this.hasFocus=true;
if(this.observer){
clearTimeout(this.observer);
}
this.observer=setTimeout(this.onObserverEvent.bind(this),this.options.frequency*1000);
},onHover:function(_7){
var _8=Event.findElement(_7,"LI");
if(this.index!=_8.autocompleteIndex){
this.index=_8.autocompleteIndex;
this.render();
}
Event.stop(_7);
},onClick:function(_9){
var _10=Event.findElement(_9,"LI");
this.index=_10.autocompleteIndex;
this.selectEntry();
this.hide();
},onBlur:function(_11){
setTimeout(this.hide.bind(this),250);
this.hasFocus=false;
this.active=false;
},render:function(){
if(this.entryCount>0){
for(var i=0;i<this.entryCount;i++){
this.index==i?Element.addClassName(this.getEntry(i),"selected"):Element.removeClassName(this.getEntry(i),"selected");
}
if(this.hasFocus){
this.show();
this.active=true;
}
}else{
this.hide();
}
},markPrevious:function(){
if(this.index>0){
this.index--;
}else{
this.index=this.entryCount-1;
}
},markNext:function(){
if(this.index<this.entryCount-1){
this.index++;
}else{
this.index=0;
}
},getEntry:function(_13){
return this.update.firstChild.childNodes[_13];
},getCurrentEntry:function(){
return this.getEntry(this.index);
},selectEntry:function(){
this.active=false;
this.updateElement(this.getCurrentEntry());
},updateElement:function(_14){
if(this.options.updateElement){
this.options.updateElement(_14);
return;
}
var _15=Element.collectTextNodesIgnoreClass(_14,"informal");
var _16=this.findLastToken();
if(_16!=-1){
var _17=this.element.value.substr(0,_16+1);
var _18=this.element.value.substr(_16+1).match(/^\s+/);
if(_18){
_17+=_18[0];
}
this.element.value=_17+_15;
}else{
this.element.value=_15;
}
this.element.focus();
if(this.options.afterUpdateElement){
this.options.afterUpdateElement(this.element,_14);
}
},updateChoices:function(_19){
if(!this.changed&&this.hasFocus){
this.update.innerHTML=_19;
Element.cleanWhitespace(this.update);
Element.cleanWhitespace(this.update.firstChild);
if(this.update.firstChild&&this.update.firstChild.childNodes){
this.entryCount=this.update.firstChild.childNodes.length;
for(var i=0;i<this.entryCount;i++){
var _20=this.getEntry(i);
_20.autocompleteIndex=i;
this.addObservers(_20);
}
}else{
this.entryCount=0;
}
this.stopIndicator();
this.index=0;
this.render();
}
},addObservers:function(_21){
Event.observe(_21,"mouseover",this.onHover.bindAsEventListener(this));
Event.observe(_21,"click",this.onClick.bindAsEventListener(this));
},onObserverEvent:function(){
this.changed=false;
if(this.getToken().length>=this.options.minChars){
this.startIndicator();
this.getUpdatedChoices();
}else{
this.active=false;
this.hide();
}
},getToken:function(){
var _22=this.findLastToken();
if(_22!=-1){
var ret=this.element.value.substr(_22+1).replace(/^\s+/,"").replace(/\s+$/,"");
}else{
var ret=this.element.value;
}
return /\n/.test(ret)?"":ret;
},findLastToken:function(){
var _24=-1;
for(var i=0;i<this.options.tokens.length;i++){
var _25=this.element.value.lastIndexOf(this.options.tokens[i]);
if(_25>_24){
_24=_25;
}
}
return _24;
}};
Autocompleter.Local=Class.create();
Autocompleter.Local.prototype=Object.extend(new Autocompleter.Base(),{initialize:function(_26,_27,_28,_29){
this.baseInitialize(_26,_27,_29);
this.options.array=_28;
},getUpdatedChoices:function(){
this.updateChoices(this.options.selector(this));
},setOptions:function(_30){
this.options=Object.extend({choices:10,partialSearch:true,partialChars:2,ignoreCase:true,fullSearch:false,selector:function(_31){
var ret=[];
var _32=[];
var _33=_31.getToken();
var _34=0;
for(var i=0;i<_31.options.array.length&&ret.length<_31.options.choices;i++){
var _35=_31.options.array[i];
var _36=_31.options.ignoreCase?_35.toLowerCase().indexOf(_33.toLowerCase()):_35.indexOf(_33);
while(_36!=-1){
if(_36==0&&_35.length!=_33.length){
ret.push("<li><strong>"+_35.substr(0,_33.length)+"</strong>"+_35.substr(_33.length)+"</li>");
break;
}else{
if(_33.length>=_31.options.partialChars&&_31.options.partialSearch&&_36!=-1){
if(_31.options.fullSearch||/\s/.test(_35.substr(_36-1,1))){
_32.push("<li>"+_35.substr(0,_36)+"<strong>"+_35.substr(_36,_33.length)+"</strong>"+_35.substr(_36+_33.length)+"</li>");
break;
}
}
}
_36=_31.options.ignoreCase?_35.toLowerCase().indexOf(_33.toLowerCase(),_36+1):_35.indexOf(_33,_36+1);
}
}
if(_32.length){
ret=ret.concat(_32.slice(0,_31.options.choices-ret.length));
}
return "<ul>"+ret.join("")+"</ul>";
}},_30||{});
}});

var Droppables={drops:[],remove:function(_1){
this.drops=this.drops.reject(function(d){
return d.element==_1;
});
},add:function(_3){
_3=$(_3);
var _4=Object.extend({greedy:true,hoverclass:null},arguments[1]||{});
if(_4.containment){
_4._containers=[];
var _5=_4.containment;
if((typeof _5=="object")&&(_5.constructor==Array)){
_5.each(function(c){
_4._containers.push($(c));
});
}else{
_4._containers.push($(_5));
}
}
Element.makePositioned(_3);
_4.element=_3;
this.drops.push(_4);
},isContained:function(_7,_8){
var _9=_7.parentNode;
return _8._containers.detect(function(c){
return _9==c;
});
},isAffected:function(pX,pY,_12,_13){
return ((_13.element!=_12)&&((!_13._containers)||this.isContained(_12,_13))&&((!_13.accept)||(Element.Class.has_any(_12,_13.accept)))&&Position.within(_13.element,pX,pY));
},deactivate:function(_14){
if(_14.hoverclass){
Element.Class.remove(_14.element,_14.hoverclass);
}
this.last_active=null;
},activate:function(_15){
if(this.last_active){
this.deactivate(this.last_active);
}
if(_15.hoverclass){
Element.Class.add(_15.element,_15.hoverclass);
}
this.last_active=_15;
},show:function(_16,_17){
if(!this.drops.length){
return;
}
var pX=Event.pointerX(_16);
var pY=Event.pointerY(_16);
Position.prepare();
var i=this.drops.length-1;
do{
var _19=this.drops[i];
if(this.isAffected(pX,pY,_17,_19)){
if(_19.onHover){
_19.onHover(_17,_19.element,Position.overlap(_19.overlap,_19.element));
}
if(_19.greedy){
this.activate(_19);
return;
}
}
}while(i--);
if(this.last_active){
this.deactivate(this.last_active);
}
},fire:function(_20,_21){
if(!this.last_active){
return;
}
Position.prepare();
if(this.isAffected(Event.pointerX(_20),Event.pointerY(_20),_21,this.last_active)){
if(this.last_active.onDrop){
this.last_active.onDrop(_21,this.last_active.element,_20);
}
}
},reset:function(){
if(this.last_active){
this.deactivate(this.last_active);
}
}};
var Draggables={observers:[],addObserver:function(_22){
this.observers.push(_22);
},removeObserver:function(_23){
this.observers=this.observers.reject(function(o){
return o.element==_23;
});
},notify:function(_25,_26){
this.observers.invoke(_25,_26);
}};
var Draggable=Class.create();
Draggable.prototype={initialize:function(_27){
this.element=$(_27);
var _28=Object.extend({handle:false,starteffect:function(_27){
new Effect.Opacity(_27,{duration:0.2,from:1,to:0.7});
},reverteffect:function(_29,_30,_31){
var dur=Math.sqrt(Math.abs(_30^2)+Math.abs(_31^2))*0.02;
new Effect.MoveBy(_29,-_30,-_31,{duration:dur});
},endeffect:function(_33){
new Effect.Opacity(_33,{duration:0.2,from:0.7,to:1});
},zindex:1000,revert:false},arguments[1]||{});
if(_28.handle&&(typeof _28.handle=="string")){
this.handle=Element.Class.childrenWith(this.element,_28.handle)[0];
}
if(!this.handle){
this.handle=$(_28.handle);
}
if(!this.handle){
this.handle=this.element;
}
Element.makePositioned(this.element);
this.offsetX=0;
this.offsetY=0;
this.originalLeft=this.currentLeft();
this.originalTop=this.currentTop();
this.originalX=this.element.offsetLeft;
this.originalY=this.element.offsetTop;
this.options=_28;
this.active=false;
this.dragging=false;
this.eventMouseDown=this.startDrag.bindAsEventListener(this);
this.eventMouseUp=this.endDrag.bindAsEventListener(this);
this.eventMouseMove=this.update.bindAsEventListener(this);
this.eventKeypress=this.keyPress.bindAsEventListener(this);
this.registerEvents();
},destroy:function(){
Event.stopObserving(this.handle,"mousedown",this.eventMouseDown);
this.unregisterEvents();
},registerEvents:function(){
Event.observe(document,"mouseup",this.eventMouseUp);
Event.observe(document,"mousemove",this.eventMouseMove);
Event.observe(document,"keypress",this.eventKeypress);
Event.observe(this.handle,"mousedown",this.eventMouseDown);
},unregisterEvents:function(){
},currentLeft:function(){
return parseInt(this.element.style.left||"0");
},currentTop:function(){
return parseInt(this.element.style.top||"0");
},startDrag:function(_34){
if(Event.isLeftClick(_34)){
var src=Event.element(_34);
if(src.tagName&&(src.tagName=="INPUT"||src.tagName=="SELECT"||src.tagName=="BUTTON"||src.tagName=="TEXTAREA")){
return;
}
this.active=true;
var _36=[Event.pointerX(_34),Event.pointerY(_34)];
var _37=Position.cumulativeOffset(this.element);
this.offsetX=(_36[0]-_37[0]);
this.offsetY=(_36[1]-_37[1]);
Event.stop(_34);
}
},finishDrag:function(_38,_39){
this.active=false;
this.dragging=false;
if(this.options.ghosting){
Position.relativize(this.element);
Element.remove(this._clone);
this._clone=null;
}
if(_39){
Droppables.fire(_38,this.element);
}
Draggables.notify("onEnd",this);
var _40=this.options.revert;
if(_40&&typeof _40=="function"){
_40=_40(this.element);
}
if(_40&&this.options.reverteffect){
this.options.reverteffect(this.element,this.currentTop()-this.originalTop,this.currentLeft()-this.originalLeft);
}else{
this.originalLeft=this.currentLeft();
this.originalTop=this.currentTop();
}
if(this.options.zindex){
this.element.style.zIndex=this.originalZ;
}
if(this.options.endeffect){
this.options.endeffect(this.element);
}
Droppables.reset();
},keyPress:function(_41){
if(this.active){
if(_41.keyCode==Event.KEY_ESC){
this.finishDrag(_41,false);
Event.stop(_41);
}
}
},endDrag:function(_42){
if(this.active&&this.dragging){
this.finishDrag(_42,true);
Event.stop(_42);
}
this.active=false;
this.dragging=false;
},draw:function(_43){
var _44=[Event.pointerX(_43),Event.pointerY(_43)];
var _45=Position.cumulativeOffset(this.element);
_45[0]-=this.currentLeft();
_45[1]-=this.currentTop();
var _46=this.element.style;
if((!this.options.constraint)||(this.options.constraint=="horizontal")){
_46.left=(_44[0]-_45[0]-this.offsetX)+"px";
}
if((!this.options.constraint)||(this.options.constraint=="vertical")){
_46.top=(_44[1]-_45[1]-this.offsetY)+"px";
}
if(_46.visibility=="hidden"){
_46.visibility="";
}
},update:function(_47){
if(this.active){
if(!this.dragging){
var _48=this.element.style;
this.dragging=true;
if(Element.getStyle(this.element,"position")==""){
_48.position="relative";
}
if(this.options.zindex){
this.options.originalZ=parseInt(Element.getStyle(this.element,"z-index")||0);
_48.zIndex=this.options.zindex;
}
if(this.options.ghosting){
this._clone=this.element.cloneNode(true);
Position.absolutize(this.element);
this.element.parentNode.insertBefore(this._clone,this.element);
}
Draggables.notify("onStart",this);
if(this.options.starteffect){
this.options.starteffect(this.element);
}
}
Droppables.show(_47,this.element);
this.draw(_47);
if(this.options.change){
this.options.change(this);
}
if(navigator.appVersion.indexOf("AppleWebKit")>0){
window.scrollBy(0,0);
}
Event.stop(_47);
}
}};
var SortableObserver=Class.create();
SortableObserver.prototype={initialize:function(_49,_50){
this.element=$(_49);
this.observer=_50;
this.lastValue=Sortable.serialize(this.element);
},onStart:function(){
this.lastValue=Sortable.serialize(this.element);
},onEnd:function(){
Sortable.unmark();
if(this.lastValue!=Sortable.serialize(this.element)){
this.observer(this.element);
}
}};
var Sortable={sortables:new Array(),options:function(_51){
_51=$(_51);
return this.sortables.detect(function(s){
return s.element==_51;
});
},destroy:function(_53){
_53=$(_53);
this.sortables.findAll(function(s){
return s.element==_53;
}).each(function(s){
Draggables.removeObserver(s.element);
s.droppables.each(function(d){
Droppables.remove(d);
});
s.draggables.invoke("destroy");
});
this.sortables=this.sortables.reject(function(s){
return s.element==_53;
});
},create:function(_54){
_54=$(_54);
var _55=Object.extend({element:_54,tag:"li",dropOnEmpty:false,tree:false,overlap:"vertical",constraint:"vertical",containment:_54,handle:false,only:false,hoverclass:null,ghosting:false,format:null,onChange:function(){
},onUpdate:function(){
}},arguments[1]||{});
this.destroy(_54);
var _56={revert:true,ghosting:_55.ghosting,constraint:_55.constraint,handle:_55.handle};
if(_55.starteffect){
_56.starteffect=_55.starteffect;
}
if(_55.reverteffect){
_56.reverteffect=_55.reverteffect;
}else{
if(_55.ghosting){
_56.reverteffect=function(_54){
_54.style.top=0;
_54.style.left=0;
};
}
}
if(_55.endeffect){
_56.endeffect=_55.endeffect;
}
if(_55.zindex){
_56.zindex=_55.zindex;
}
var _57={overlap:_55.overlap,containment:_55.containment,hoverclass:_55.hoverclass,onHover:Sortable.onHover,greedy:!_55.dropOnEmpty};
Element.cleanWhitespace(element);
_55.draggables=[];
_55.droppables=[];
if(_55.dropOnEmpty){
Droppables.add(element,{containment:_55.containment,onHover:Sortable.onEmptyHover,greedy:false});
_55.droppables.push(element);
}
(this.findElements(element,_55)||[]).each(function(e){
var _59=_55.handle?Element.Class.childrenWith(e,_55.handle)[0]:e;
_55.draggables.push(new Draggable(e,Object.extend(_56,{handle:_59})));
Droppables.add(e,_57);
_55.droppables.push(e);
});
this.sortables.push(_55);
Draggables.addObserver(new SortableObserver(element,_55.onUpdate));
},findElements:function(_60,_61){
if(!_60.hasChildNodes()){
return null;
}
var _62=[];
$A(_60.childNodes).each(function(e){
if(e.tagName&&e.tagName==_61.tag.toUpperCase()&&(!_61.only||(Element.Class.has(e,_61.only)))){
_62.push(e);
}
if(_61.tree){
var _63=this.findElements(e,_61);
if(_63){
_62.push(_63);
}
}
});
return (_62.length>0?_62.flatten():null);
},onHover:function(_64,_65,_66){
if(_66>0.5){
Sortable.mark(_65,"before");
if(_65.previousSibling!=_64){
var _67=_64.parentNode;
_64.style.visibility="hidden";
_65.parentNode.insertBefore(_64,_65);
if(_65.parentNode!=_67){
Sortable.options(_67).onChange(_64);
}
Sortable.options(_65.parentNode).onChange(_64);
}
}else{
Sortable.mark(_65,"after");
var _68=_65.nextSibling||null;
if(_68!=_64){
var _67=_64.parentNode;
_64.style.visibility="hidden";
_65.parentNode.insertBefore(_64,_68);
if(_65.parentNode!=_67){
Sortable.options(_67).onChange(_64);
}
Sortable.options(_65.parentNode).onChange(_64);
}
}
},onEmptyHover:function(_69,_70){
if(_69.parentNode!=_70){
_70.appendChild(_69);
}
},unmark:function(){
if(Sortable._marker){
Element.hide(Sortable._marker);
}
},mark:function(_71,_72){
var _73=Sortable.options(_71.parentNode);
if(_73&&!_73.ghosting){
return;
}
if(!Sortable._marker){
Sortable._marker=$("dropmarker")||document.createElement("DIV");
Element.hide(Sortable._marker);
Element.Class.add(Sortable._marker,"dropmarker");
Sortable._marker.style.position="absolute";
document.getElementsByTagName("body").item(0).appendChild(Sortable._marker);
}
var _74=Position.cumulativeOffset(_71);
Sortable._marker.style.top=_74[1]+"px";
if(_72=="after"){
Sortable._marker.style.top=(_74[1]+_71.clientHeight)+"px";
}
Sortable._marker.style.left=_74[0]+"px";
Element.show(Sortable._marker);
},serialize:function(_75){
_75=$(_75);
var _76=this.options(_75);
var _77=Object.extend({tag:_76.tag,only:_76.only,name:_75.id,format:_76.format||/^[^_]*_(.*)$/},arguments[1]||{});
return $(this.findElements(_75,_77)||[]).collect(function(_78){
return (encodeURIComponent(_77.name)+"[]="+encodeURIComponent(_78.id.match(_77.format)?_78.id.match(_77.format)[1]:""));
}).join("&");
}};

Prado.AutoCompleter=Class.create();
Prado.AutoCompleter.prototype=Object.extend(new Autocompleter.Base(),{initialize:function(_1,_2,_3){
this.baseInitialize(_1,_2,_3);
},onUpdateReturn:function(_4){
if(isString(_4)&&_4.length>0){
this.updateChoices(_4);
}
},getUpdatedChoices:function(){
Prado.Callback(this.element.id,this.getToken(),this.onUpdateReturn.bind(this));
}});
Prado.ActivePanel={callbacks:{},register:function(id,_6){
Prado.ActivePanel.callbacks[id]=_6;
},update:function(id,_7){
var _8=new Prado.ActivePanel.Request(id,Prado.ActivePanel.callbacks[id]);
_8.callback(_7);
}};
Prado.ActivePanel.Request=Class.create();
Prado.ActivePanel.Request.prototype={initialize:function(_9,_10){
this.element=_9;
this.setOptions(_10);
},setOptions:function(_11){
this.options={onSuccess:this.onSuccess.bind(this)};
Object.extend(this.options,_11||{});
},callback:function(_12){
this.options.params=[_12];
new Prado.AJAX.Callback(this.element,this.options);
},onSuccess:function(_13,_14){
if(this.options.update){
var _15=$(this.options.update);
if(_15){
_15.innerHTML=_14;
}
}
}};
Prado.DropContainer=Class.create();
Prado.DropContainer.prototype=Object.extend(new Prado.ActivePanel.Request(),{initialize:function(_16,_17){
this.element=_16;
this.setOptions(_17);
Object.extend(this.options,{onDrop:this.onDrop.bind(this),evalScripts:true,onSuccess:_17.onSuccess||this.update.bind(this)});
Droppables.add(_16,this.options);
},onDrop:function(_18,_19){
this.callback(_18.id);
},update:function(_20,_21){
this.onSuccess(_20,_21);
if(this.options.evalScripts){
Prado.AJAX.EvalScript(_21);
}
}});

