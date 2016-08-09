var Rico={Version:"1.1-beta"};
Rico.ArrayExtensions=new Array();
if(Object.prototype.extend){
Rico.ArrayExtensions[Rico.ArrayExtensions.length]=Object.prototype.extend;
}
if(Array.prototype.push){
Rico.ArrayExtensions[Rico.ArrayExtensions.length]=Array.prototype.push;
}
if(!Array.prototype.remove){
Array.prototype.remove=function(dx){
if(isNaN(dx)||dx>this.length){
return false;
}
for(var i=0,n=0;i<this.length;i++){
if(i!=dx){
this[n++]=this[i];
}
}
this.length-=1;
};
Rico.ArrayExtensions[Rico.ArrayExtensions.length]=Array.prototype.remove;
}
if(!Array.prototype.removeItem){
Array.prototype.removeItem=function(_3){
for(var i=0;i<this.length;i++){
if(this[i]==_3){
this.remove(i);
break;
}
}
};
Rico.ArrayExtensions[Rico.ArrayExtensions.length]=Array.prototype.removeItem;
}
if(!Array.prototype.indices){
Array.prototype.indices=function(){
var _4=new Array();
for(index in this){
var _5=false;
for(var i=0;i<Rico.ArrayExtensions.length;i++){
if(this[index]==Rico.ArrayExtensions[i]){
_5=true;
break;
}
}
if(!_5){
_4[_4.length]=index;
}
}
return _4;
};
Rico.ArrayExtensions[Rico.ArrayExtensions.length]=Array.prototype.indices;
}
if(window.DOMParser&&window.XMLSerializer&&window.Node&&Node.prototype&&Node.prototype.__defineGetter__){
if(!Document.prototype.loadXML){
Document.prototype.loadXML=function(s){
var _7=(new DOMParser()).parseFromString(s,"text/xml");
while(this.hasChildNodes()){
this.removeChild(this.lastChild);
}
for(var i=0;i<_7.childNodes.length;i++){
this.appendChild(this.importNode(_7.childNodes[i],true));
}
};
}
Document.prototype.__defineGetter__("xml",function(){
return (new XMLSerializer()).serializeToString(this);
});
}
document.getElementsByTagAndClassName=function(_8,_9){
if(_8==null){
_8="*";
}
var _10=document.getElementsByTagName(_8)||document.all;
var _11=new Array();
if(_9==null){
return _10;
}
for(var i=0;i<_10.length;i++){
var _12=_10[i];
var _13=_12.className.split(" ");
for(var j=0;j<_13.length;j++){
if(_13[j]==_9){
_11.push(_12);
break;
}
}
}
return _11;
};
Rico.Accordion=Class.create();
Rico.Accordion.prototype={initialize:function(_15,_16){
this.container=$(_15);
this.lastExpandedTab=null;
this.accordionTabs=new Array();
this.setOptions(_16);
this._attachBehaviors();
this.container.style.borderBottom="1px solid "+this.options.borderColor;
for(var i=1;i<this.accordionTabs.length;i++){
this.accordionTabs[i].collapse();
this.accordionTabs[i].content.style.display="none";
}
this.lastExpandedTab=this.accordionTabs[0];
this.lastExpandedTab.content.style.height=this.options.panelHeight+"px";
this.lastExpandedTab.showExpanded();
this.lastExpandedTab.titleBar.style.fontWeight=this.options.expandedFontWeight;
},setOptions:function(_17){
this.options={expandedBg:"#63699c",hoverBg:"#63699c",collapsedBg:"#6b79a5",expandedTextColor:"#ffffff",expandedFontWeight:"bold",hoverTextColor:"#ffffff",collapsedTextColor:"#ced7ef",collapsedFontWeight:"normal",hoverTextColor:"#ffffff",borderColor:"#1f669b",panelHeight:200,onHideTab:null,onShowTab:null}.extend(_17||{});
},showTabByIndex:function(_18,_19){
var _20=arguments.length==1?true:_19;
this.showTab(this.accordionTabs[_18],_20);
},showTab:function(_21,_22){
var _23=arguments.length==1?true:_22;
if(this.options.onHideTab){
this.options.onHideTab(this.lastExpandedTab);
}
this.lastExpandedTab.showCollapsed();
var _24=this;
var _25=this.lastExpandedTab;
this.lastExpandedTab.content.style.height=(this.options.panelHeight-1)+"px";
_21.content.style.display="";
_21.titleBar.style.fontWeight=this.options.expandedFontWeight;
if(_23){
new Effect.AccordionSize(this.lastExpandedTab.content,_21.content,1,this.options.panelHeight,100,10,{complete:function(){
_24.showTabDone(_25);
}});
this.lastExpandedTab=_21;
}else{
this.lastExpandedTab.content.style.height="1px";
_21.content.style.height=this.options.panelHeight+"px";
this.lastExpandedTab=_21;
this.showTabDone(_25);
}
},showTabDone:function(_26){
_26.content.style.display="none";
this.lastExpandedTab.showExpanded();
if(this.options.onShowTab){
this.options.onShowTab(this.lastExpandedTab);
}
},_attachBehaviors:function(){
var _27=this._getDirectChildrenByTag(this.container,"DIV");
for(var i=0;i<_27.length;i++){
var _28=this._getDirectChildrenByTag(_27[i],"DIV");
if(_28.length!=2){
continue;
}
var _29=_28[0];
var _30=_28[1];
this.accordionTabs.push(new Rico.Accordion.Tab(this,_29,_30));
}
},_getDirectChildrenByTag:function(e,_32){
var _33=new Array();
var _34=e.childNodes;
for(var i=0;i<_34.length;i++){
if(_34[i]&&_34[i].tagName&&_34[i].tagName==_32){
_33.push(_34[i]);
}
}
return _33;
}};
Rico.Accordion.Tab=Class.create();
Rico.Accordion.Tab.prototype={initialize:function(_35,_36,_37){
this.accordion=_35;
this.titleBar=_36;
this.content=_37;
this._attachBehaviors();
},collapse:function(){
this.showCollapsed();
this.content.style.height="1px";
},showCollapsed:function(){
this.expanded=false;
this.titleBar.style.backgroundColor=this.accordion.options.collapsedBg;
this.titleBar.style.color=this.accordion.options.collapsedTextColor;
this.titleBar.style.fontWeight=this.accordion.options.collapsedFontWeight;
this.content.style.overflow="hidden";
},showExpanded:function(){
this.expanded=true;
this.titleBar.style.backgroundColor=this.accordion.options.expandedBg;
this.titleBar.style.color=this.accordion.options.expandedTextColor;
this.content.style.overflow="visible";
},titleBarClicked:function(e){
if(this.accordion.lastExpandedTab==this){
return;
}
this.accordion.showTab(this);
},hover:function(e){
this.titleBar.style.backgroundColor=this.accordion.options.hoverBg;
this.titleBar.style.color=this.accordion.options.hoverTextColor;
},unhover:function(e){
if(this.expanded){
this.titleBar.style.backgroundColor=this.accordion.options.expandedBg;
this.titleBar.style.color=this.accordion.options.expandedTextColor;
}else{
this.titleBar.style.backgroundColor=this.accordion.options.collapsedBg;
this.titleBar.style.color=this.accordion.options.collapsedTextColor;
}
},_attachBehaviors:function(){
this.content.style.border="1px solid "+this.accordion.options.borderColor;
this.content.style.borderTopWidth="0px";
this.content.style.borderBottomWidth="0px";
this.content.style.margin="0px";
this.titleBar.onclick=this.titleBarClicked.bindAsEventListener(this);
this.titleBar.onmouseover=this.hover.bindAsEventListener(this);
this.titleBar.onmouseout=this.unhover.bindAsEventListener(this);
}};
Rico.AjaxEngine=Class.create();
Rico.AjaxEngine.prototype={initialize:function(){
this.ajaxElements=new Array();
this.ajaxObjects=new Array();
this.requestURLS=new Array();
},registerAjaxElement:function(_38,_39){
if(arguments.length==1){
_39=$(_38);
}
this.ajaxElements[_38]=_39;
},registerAjaxObject:function(_40,_41){
this.ajaxObjects[_40]=_41;
},registerRequest:function(_42,_43){
this.requestURLS[_42]=_43;
},sendRequest:function(_44){
var _45=this.requestURLS[_44];
if(_45==null){
return;
}
var _46="";
if(arguments.length>1){
_46=this._createQueryString(arguments,1);
}
new Ajax.Request(_45,this._requestOptions(_46));
},sendRequestWithData:function(_47,_48){
var _49=this.requestURLS[_47];
if(_49==null){
return;
}
var _50="";
if(arguments.length>2){
_50=this._createQueryString(arguments,2);
}
new Ajax.Request(_49+"?"+_50,this._requestOptions(null,_48));
},sendRequestAndUpdate:function(_51,_52,_53){
var _54=this.requestURLS[_51];
if(_54==null){
return;
}
var _55="";
if(arguments.length>3){
_55=this._createQueryString(arguments,3);
}
var _56=this._requestOptions(_55);
_56.onComplete=null;
_56.extend(_53);
new Ajax.Updater(_52,_54,_56);
},sendRequestWithDataAndUpdate:function(_57,_58,_59,_60){
var _61=this.requestURLS[_57];
if(_61==null){
return;
}
var _62="";
if(arguments.length>4){
_62=this._createQueryString(arguments,4);
}
var _63=this._requestOptions(_62,_58);
_63.onComplete=null;
_63.extend(_60);
new Ajax.Updater(_59,_61+"?"+_62,_63);
},_requestOptions:function(_64,_65){
var _66=this;
var _67=["X-Rico-Version",Rico.Version];
var _68="post";
if(arguments[1]){
_67.push("Content-type","text/xml");
}else{
_68="get";
}
return {requestHeaders:_67,parameters:_64,postBody:arguments[1]?_65:null,method:_68,onComplete:_66._onRequestComplete.bind(_66)};
},_createQueryString:function(_69,_70){
var _71="";
for(var i=_70;i<_69.length;i++){
if(i!=_70){
_71+="&";
}
var _72=_69[i];
if(_72.name!=undefined&&_72.value!=undefined){
_71+=_72.name+"="+escape(_72.value);
}else{
var _73=_72.indexOf("=");
var _74=_72.substring(0,_73);
var _75=_72.substring(_73+1);
_71+=_74+"="+escape(_75);
}
}
return _71;
},_onRequestComplete:function(_76){
if(_76.status!=200){
return;
}
var _77=_76.responseXML.getElementsByTagName("ajax-response");
if(_77==null||_77.length!=1){
return;
}
this._processAjaxResponse(_77[0].childNodes);
},_processAjaxResponse:function(_78){
for(var i=0;i<_78.length;i++){
var _79=_78[i];
if(_79.nodeType!=1){
continue;
}
var _80=_79.getAttribute("type");
var _81=_79.getAttribute("id");
if(_80=="object"){
this._processAjaxObjectUpdate(this.ajaxObjects[_81],_79);
}else{
if(_80=="element"){
this._processAjaxElementUpdate(this.ajaxElements[_81],_79);
}else{
alert("unrecognized AjaxResponse type : "+_80);
}
}
}
},_processAjaxObjectUpdate:function(_82,_83){
_82.ajaxUpdate(_83);
},_processAjaxElementUpdate:function(_84,_85){
if(_85.xml!=undefined){
this._processAjaxElementUpdateIE(_84,_85);
}else{
this._processAjaxElementUpdateMozilla(_84,_85);
}
},_processAjaxElementUpdateIE:function(_86,_87){
var _88="";
for(var i=0;i<_87.childNodes.length;i++){
_88+=_87.childNodes[i].xml;
}
_86.innerHTML=_88;
},_processAjaxElementUpdateMozilla:function(_89,_90){
var _91=new XMLSerializer();
var _92="";
for(var i=0;i<_90.childNodes.length;i++){
_92+=_91.serializeToString(_90.childNodes[i]);
}
_89.innerHTML=_92;
}};
var ajaxEngine=new Rico.AjaxEngine();
Rico.Color=Class.create();
Rico.Color.prototype={initialize:function(red,_94,_95){
this.rgb={r:red,g:_94,b:_95};
},setRed:function(r){
this.rgb.r=r;
},setGreen:function(g){
this.rgb.g=g;
},setBlue:function(b){
this.rgb.b=b;
},setHue:function(h){
var hsb=this.asHSB();
hsb.h=h;
this.rgb=Rico.Color.HSBtoRGB(hsb.h,hsb.s,hsb.b);
},setSaturation:function(s){
var hsb=this.asHSB();
hsb.s=s;
this.rgb=Rico.Color.HSBtoRGB(hsb.h,hsb.s,hsb.b);
},setBrightness:function(b){
var hsb=this.asHSB();
hsb.b=b;
this.rgb=Rico.Color.HSBtoRGB(hsb.h,hsb.s,hsb.b);
},darken:function(_101){
var hsb=this.asHSB();
this.rgb=Rico.Color.HSBtoRGB(hsb.h,hsb.s,Math.max(hsb.b-_101,0));
},brighten:function(_102){
var hsb=this.asHSB();
this.rgb=Rico.Color.HSBtoRGB(hsb.h,hsb.s,Math.min(hsb.b+_102,1));
},blend:function(_103){
this.rgb.r=Math.floor((this.rgb.r+_103.rgb.r)/2);
this.rgb.g=Math.floor((this.rgb.g+_103.rgb.g)/2);
this.rgb.b=Math.floor((this.rgb.b+_103.rgb.b)/2);
},isBright:function(){
var hsb=this.asHSB();
return this.asHSB().b>0.5;
},isDark:function(){
return !this.isBright();
},asRGB:function(){
return "rgb("+this.rgb.r+","+this.rgb.g+","+this.rgb.b+")";
},asHex:function(){
return "#"+this.rgb.r.toColorPart()+this.rgb.g.toColorPart()+this.rgb.b.toColorPart();
},asHSB:function(){
return Rico.Color.RGBtoHSB(this.rgb.r,this.rgb.g,this.rgb.b);
},toString:function(){
return this.asHex();
}};
Rico.Color.createFromHex=function(_104){
if(_104.indexOf("#")==0){
_104=_104.substring(1);
}
var red=_104.substring(0,2);
var _105=_104.substring(2,4);
var blue=_104.substring(4,6);
return new Rico.Color(parseInt(red,16),parseInt(_105,16),parseInt(blue,16));
};
Rico.Color.createColorFromBackground=function(elem){
var _108=RicoUtil.getElementsComputedStyle($(elem),"backgroundColor","background-color");
if(_108=="transparent"&&elem.parent){
return Rico.Color.createColorFromBackground(elem.parent);
}
if(_108==null){
return new Rico.Color(255,255,255);
}
if(_108.indexOf("rgb(")==0){
var _109=_108.substring(4,_108.length-1);
var _110=_109.split(",");
return new Rico.Color(parseInt(_110[0]),parseInt(_110[1]),parseInt(_110[2]));
}else{
if(_108.indexOf("#")==0){
var _111=parseInt(_108.substring(1,3),16);
var _112=parseInt(_108.substring(3,5),16);
var _113=parseInt(_108.substring(5),16);
return new Rico.Color(_111,_112,_113);
}else{
return new Rico.Color(255,255,255);
}
}
};
Rico.Color.HSBtoRGB=function(hue,_115,_116){
var red=0;
var _117=0;
var blue=0;
if(_115==0){
red=parseInt(_116*255+0.5);
_117=red;
blue=red;
}else{
var h=(hue-Math.floor(hue))*6;
var f=h-Math.floor(h);
var p=_116*(1-_115);
var q=_116*(1-_115*f);
var t=_116*(1-(_115*(1-f)));
switch(parseInt(h)){
case 0:
red=(_116*255+0.5);
_117=(t*255+0.5);
blue=(p*255+0.5);
break;
case 1:
red=(q*255+0.5);
_117=(_116*255+0.5);
blue=(p*255+0.5);
break;
case 2:
red=(p*255+0.5);
_117=(_116*255+0.5);
blue=(t*255+0.5);
break;
case 3:
red=(p*255+0.5);
_117=(q*255+0.5);
blue=(_116*255+0.5);
break;
case 4:
red=(t*255+0.5);
_117=(p*255+0.5);
blue=(_116*255+0.5);
break;
case 5:
red=(_116*255+0.5);
_117=(p*255+0.5);
blue=(q*255+0.5);
break;
}
}
return {r:parseInt(red),g:parseInt(_117),b:parseInt(blue)};
};
Rico.Color.RGBtoHSB=function(r,g,b){
var hue;
var _122;
var _123;
var cmax=(r>g)?r:g;
if(b>cmax){
cmax=b;
}
var cmin=(r<g)?r:g;
if(b<cmin){
cmin=b;
}
_123=cmax/255;
if(cmax!=0){
saturation=(cmax-cmin)/cmax;
}else{
saturation=0;
}
if(saturation==0){
hue=0;
}else{
var redc=(cmax-r)/(cmax-cmin);
var _127=(cmax-g)/(cmax-cmin);
var _128=(cmax-b)/(cmax-cmin);
if(r==cmax){
hue=_128-_127;
}else{
if(g==cmax){
hue=2+redc-_128;
}else{
hue=4+_127-redc;
}
}
hue=hue/6;
if(hue<0){
hue=hue+1;
}
}
return {h:hue,s:saturation,b:_123};
};
Rico.Corner={round:function(e,_129){
var e=$(e);
this._setOptions(_129);
var _130=this.options.color;
if(this.options.color=="fromElement"){
_130=this._background(e);
}
var _131=this.options.bgColor;
if(this.options.bgColor=="fromParent"){
_131=this._background(e.offsetParent);
}
this._roundCornersImpl(e,_130,_131);
},_roundCornersImpl:function(e,_132,_133){
if(this.options.border){
this._renderBorder(e,_133);
}
if(this._isTopRounded()){
this._roundTopCorners(e,_132,_133);
}
if(this._isBottomRounded()){
this._roundBottomCorners(e,_132,_133);
}
},_renderBorder:function(el,_135){
var _136="1px solid "+this._borderColor(_135);
var _137="border-left: "+_136;
var _138="border-right: "+_136;
var _139="style='"+_137+";"+_138+"'";
el.innerHTML="<div "+_139+">"+el.innerHTML+"</div>";
},_roundTopCorners:function(el,_140,_141){
var _142=this._createCorner(_141);
for(var i=0;i<this.options.numSlices;i++){
_142.appendChild(this._createCornerSlice(_140,_141,i,"top"));
}
el.style.paddingTop=0;
el.insertBefore(_142,el.firstChild);
},_roundBottomCorners:function(el,_143,_144){
var _145=this._createCorner(_144);
for(var i=(this.options.numSlices-1);i>=0;i--){
_145.appendChild(this._createCornerSlice(_143,_144,i,"bottom"));
}
el.style.paddingBottom=0;
el.appendChild(_145);
},_createCorner:function(_146){
var _147=document.createElement("div");
_147.style.backgroundColor=(this._isTransparent()?"transparent":_146);
return _147;
},_createCornerSlice:function(_148,_149,n,_151){
var _152=document.createElement("span");
var _153=_152.style;
_153.backgroundColor=_148;
_153.display="block";
_153.height="1px";
_153.overflow="hidden";
_153.fontSize="1px";
var _154=this._borderColor(_148,_149);
if(this.options.border&&n==0){
_153.borderTopStyle="solid";
_153.borderTopWidth="1px";
_153.borderLeftWidth="0px";
_153.borderRightWidth="0px";
_153.borderBottomWidth="0px";
_153.height="0px";
_153.borderColor=_154;
}else{
if(_154){
_153.borderColor=_154;
_153.borderStyle="solid";
_153.borderWidth="0px 1px";
}
}
if(!this.options.compact&&(n==(this.options.numSlices-1))){
_153.height="2px";
}
this._setMargin(_152,n,_151);
this._setBorder(_152,n,_151);
return _152;
},_setOptions:function(_155){
this.options={corners:"all",color:"fromElement",bgColor:"fromParent",blend:true,border:false,compact:false}.extend(_155||{});
this.options.numSlices=this.options.compact?2:4;
if(this._isTransparent()){
this.options.blend=false;
}
},_whichSideTop:function(){
if(this._hasString(this.options.corners,"all","top")){
return "";
}
if(this.options.corners.indexOf("tl")>=0&&this.options.corners.indexOf("tr")>=0){
return "";
}
if(this.options.corners.indexOf("tl")>=0){
return "left";
}else{
if(this.options.corners.indexOf("tr")>=0){
return "right";
}
}
return "";
},_whichSideBottom:function(){
if(this._hasString(this.options.corners,"all","bottom")){
return "";
}
if(this.options.corners.indexOf("bl")>=0&&this.options.corners.indexOf("br")>=0){
return "";
}
if(this.options.corners.indexOf("bl")>=0){
return "left";
}else{
if(this.options.corners.indexOf("br")>=0){
return "right";
}
}
return "";
},_borderColor:function(_156,_157){
if(_156=="transparent"){
return _157;
}else{
if(this.options.border){
return this.options.border;
}else{
if(this.options.blend){
return this._blend(_157,_156);
}else{
return "";
}
}
}
},_setMargin:function(el,n,_158){
var _159=this._marginSize(n);
var _160=_158=="top"?this._whichSideTop():this._whichSideBottom();
if(_160=="left"){
el.style.marginLeft=_159+"px";
el.style.marginRight="0px";
}else{
if(_160=="right"){
el.style.marginRight=_159+"px";
el.style.marginLeft="0px";
}else{
el.style.marginLeft=_159+"px";
el.style.marginRight=_159+"px";
}
}
},_setBorder:function(el,n,_161){
var _162=this._borderSize(n);
var _163=_161=="top"?this._whichSideTop():this._whichSideBottom();
if(_163=="left"){
el.style.borderLeftWidth=_162+"px";
el.style.borderRightWidth="0px";
}else{
if(_163=="right"){
el.style.borderRightWidth=_162+"px";
el.style.borderLeftWidth="0px";
}else{
el.style.borderLeftWidth=_162+"px";
el.style.borderRightWidth=_162+"px";
}
}
},_marginSize:function(n){
if(this._isTransparent()){
return 0;
}
var _164=[5,3,2,1];
var _165=[3,2,1,0];
var _166=[2,1];
var _167=[1,0];
if(this.options.compact&&this.options.blend){
return _167[n];
}else{
if(this.options.compact){
return _166[n];
}else{
if(this.options.blend){
return _165[n];
}else{
return _164[n];
}
}
}
},_borderSize:function(n){
var _168=[5,3,2,1];
var _169=[2,1,1,1];
var _170=[1,0];
var _171=[0,2,0,0];
if(this.options.compact&&(this.options.blend||this._isTransparent())){
return 1;
}else{
if(this.options.compact){
return _170[n];
}else{
if(this.options.blend){
return _169[n];
}else{
if(this.options.border){
return _171[n];
}else{
if(this._isTransparent()){
return _168[n];
}
}
}
}
}
return 0;
},_hasString:function(str){
for(var i=1;i<arguments.length;i++){
if(str.indexOf(arguments[i])>=0){
return true;
}
}
return false;
},_blend:function(c1,c2){
var cc1=Rico.Color.createFromHex(c1);
cc1.blend(Rico.Color.createFromHex(c2));
return cc1;
},_background:function(el){
try{
return Rico.Color.createColorFromBackground(el).asHex();
}
catch(err){
return "#ffffff";
}
},_isTransparent:function(){
return this.options.color=="transparent";
},_isTopRounded:function(){
return this._hasString(this.options.corners,"all","top","tl","tr");
},_isBottomRounded:function(){
return this._hasString(this.options.corners,"all","bottom","bl","br");
},_hasSingleTextChild:function(el){
return el.childNodes.length==1&&el.childNodes[0].nodeType==3;
}};
Rico.DragAndDrop=Class.create();
Rico.DragAndDrop.prototype={initialize:function(){
this.dropZones=new Array();
this.draggables=new Array();
this.currentDragObjects=new Array();
this.dragElement=null;
this.lastSelectedDraggable=null;
this.currentDragObjectVisible=false;
this.interestedInMotionEvents=false;
},registerDropZone:function(_176){
this.dropZones[this.dropZones.length]=_176;
},deregisterDropZone:function(_177){
var _178=new Array();
var j=0;
for(var i=0;i<this.dropZones.length;i++){
if(this.dropZones[i]!=_177){
_178[j++]=this.dropZones[i];
}
}
this.dropZones=_178;
},clearDropZones:function(){
this.dropZones=new Array();
},registerDraggable:function(_179){
this.draggables[this.draggables.length]=_179;
this._addMouseDownHandler(_179);
},clearSelection:function(){
for(var i=0;i<this.currentDragObjects.length;i++){
this.currentDragObjects[i].deselect();
}
this.currentDragObjects=new Array();
this.lastSelectedDraggable=null;
},hasSelection:function(){
return this.currentDragObjects.length>0;
},setStartDragFromElement:function(e,_180){
this.origPos=RicoUtil.toDocumentPosition(_180);
this.startx=e.screenX-this.origPos.x;
this.starty=e.screenY-this.origPos.y;
this.interestedInMotionEvents=this.hasSelection();
this._terminateEvent(e);
},updateSelection:function(_181,_182){
if(!_182){
this.clearSelection();
}
if(_181.isSelected()){
this.currentDragObjects.removeItem(_181);
_181.deselect();
if(_181==this.lastSelectedDraggable){
this.lastSelectedDraggable=null;
}
}else{
this.currentDragObjects[this.currentDragObjects.length]=_181;
_181.select();
this.lastSelectedDraggable=_181;
}
},_mouseDownHandler:function(e){
if(arguments.length==0){
e=event;
}
var _183=e.which!=undefined;
if((_183&&e.which!=1)||(!_183&&e.button!=1)){
return;
}
var _184=e.target?e.target:e.srcElement;
var _185=_184.draggable;
this.updateSelection(_185,e.ctrlKey);
if(this.hasSelection()){
for(var i=0;i<this.dropZones.length;i++){
this.dropZones[i].clearPositionCache();
}
}
this.setStartDragFromElement(e,_185.getMouseDownHTMLElement());
},_mouseMoveHandler:function(e){
var _186=e.which!=undefined;
if(!this.interestedInMotionEvents){
this._terminateEvent(e);
return;
}
if(!this.hasSelection()){
return;
}
if(!this.currentDragObjectVisible){
this._startDrag(e);
}
if(!this.activatedDropZones){
this._activateRegisteredDropZones();
}
this._updateDraggableLocation(e);
this._updateDropZonesHover(e);
this._terminateEvent(e);
},_makeDraggableObjectVisible:function(e){
if(!this.hasSelection()){
return;
}
var _187;
if(this.currentDragObjects.length>1){
_187=this.currentDragObjects[0].getMultiObjectDragGUI(this.currentDragObjects);
}else{
_187=this.currentDragObjects[0].getSingleObjectDragGUI();
}
if(RicoUtil.getElementsComputedStyle(_187,"position")!="absolute"){
_187.style.position="absolute";
}
if(_187.parentNode==null||_187.parentNode.nodeType==11){
document.body.appendChild(_187);
}
this.dragElement=_187;
this._updateDraggableLocation(e);
this.currentDragObjectVisible=true;
},_updateDraggableLocation:function(e){
var _188=this.dragElement.style;
_188.left=(e.screenX-this.startx)+"px";
_188.top=(e.screenY-this.starty)+"px";
},_updateDropZonesHover:function(e){
var n=this.dropZones.length;
for(var i=0;i<n;i++){
if(!this._mousePointInDropZone(e,this.dropZones[i])){
this.dropZones[i].hideHover();
}
}
for(var i=0;i<n;i++){
if(this._mousePointInDropZone(e,this.dropZones[i])){
if(this.dropZones[i].canAccept(this.currentDragObjects)){
this.dropZones[i].showHover();
}
}
}
},_startDrag:function(e){
for(var i=0;i<this.currentDragObjects.length;i++){
this.currentDragObjects[i].startDrag();
}
this._makeDraggableObjectVisible(e);
},_mouseUpHandler:function(e){
if(!this.hasSelection()){
return;
}
var _189=e.which!=undefined;
if((_189&&e.which!=1)||(!_189&&e.button!=1)){
return;
}
this.interestedInMotionEvents=false;
if(this.dragElement==null){
this._terminateEvent(e);
return;
}
if(this._placeDraggableInDropZone(e)){
this._completeDropOperation(e);
}else{
this._terminateEvent(e);
new Effect.Position(this.dragElement,this.origPos.x,this.origPos.y,200,20,{complete:this._doCancelDragProcessing.bind(this)});
}
},_completeDropOperation:function(e){
if(this.dragElement!=this.currentDragObjects[0].getMouseDownHTMLElement()){
if(this.dragElement.parentNode!=null){
this.dragElement.parentNode.removeChild(this.dragElement);
}
}
this._deactivateRegisteredDropZones();
this._endDrag();
this.clearSelection();
this.dragElement=null;
this.currentDragObjectVisible=false;
this._terminateEvent(e);
},_doCancelDragProcessing:function(){
this._cancelDrag();
if(this.dragElement!=this.currentDragObjects[0].getMouseDownHTMLElement()){
if(this.dragElement.parentNode!=null){
this.dragElement.parentNode.removeChild(this.dragElement);
}
}
this._deactivateRegisteredDropZones();
this.dragElement=null;
this.currentDragObjectVisible=false;
},_placeDraggableInDropZone:function(e){
var _190=false;
var n=this.dropZones.length;
for(var i=0;i<n;i++){
if(this._mousePointInDropZone(e,this.dropZones[i])){
if(this.dropZones[i].canAccept(this.currentDragObjects)){
this.dropZones[i].hideHover();
this.dropZones[i].accept(this.currentDragObjects);
_190=true;
break;
}
}
}
return _190;
},_cancelDrag:function(){
for(var i=0;i<this.currentDragObjects.length;i++){
this.currentDragObjects[i].cancelDrag();
}
},_endDrag:function(){
for(var i=0;i<this.currentDragObjects.length;i++){
this.currentDragObjects[i].endDrag();
}
},_mousePointInDropZone:function(e,_191){
var _192=_191.getAbsoluteRect();
return e.clientX>_192.left&&e.clientX<_192.right&&e.clientY>_192.top&&e.clientY<_192.bottom;
},_addMouseDownHandler:function(_193){
var _194=_193.getMouseDownHTMLElement();
if(_194!=null){
_194.draggable=_193;
this._addMouseDownEvent(_194);
}
},_activateRegisteredDropZones:function(){
var n=this.dropZones.length;
for(var i=0;i<n;i++){
var _195=this.dropZones[i];
if(_195.canAccept(this.currentDragObjects)){
_195.activate();
}
}
this.activatedDropZones=true;
},_deactivateRegisteredDropZones:function(){
var n=this.dropZones.length;
for(var i=0;i<n;i++){
this.dropZones[i].deactivate();
}
this.activatedDropZones=false;
},_addMouseDownEvent:function(_196){
if(typeof document.implementation!="undefined"&&document.implementation.hasFeature("HTML","1.0")&&document.implementation.hasFeature("Events","2.0")&&document.implementation.hasFeature("CSS","2.0")){
_196.addEventListener("mousedown",this._mouseDownHandler.bindAsEventListener(this),false);
}else{
_196.attachEvent("onmousedown",this._mouseDownHandler.bindAsEventListener(this));
}
},_terminateEvent:function(e){
if(e.stopPropagation!=undefined){
e.stopPropagation();
}else{
if(e.cancelBubble!=undefined){
e.cancelBubble=true;
}
}
if(e.preventDefault!=undefined){
e.preventDefault();
}else{
e.returnValue=false;
}
},initializeEventHandlers:function(){
if(typeof document.implementation!="undefined"&&document.implementation.hasFeature("HTML","1.0")&&document.implementation.hasFeature("Events","2.0")&&document.implementation.hasFeature("CSS","2.0")){
document.addEventListener("mouseup",this._mouseUpHandler.bindAsEventListener(this),false);
document.addEventListener("mousemove",this._mouseMoveHandler.bindAsEventListener(this),false);
}else{
document.attachEvent("onmouseup",this._mouseUpHandler.bindAsEventListener(this));
document.attachEvent("onmousemove",this._mouseMoveHandler.bindAsEventListener(this));
}
}};
var dndMgr=new Rico.DragAndDrop();
dndMgr.initializeEventHandlers();
Rico.Draggable=Class.create();
Rico.Draggable.prototype={initialize:function(type,_198){
this.type=type;
this.htmlElement=$(_198);
this.selected=false;
},getMouseDownHTMLElement:function(){
return this.htmlElement;
},select:function(){
this.selected=true;
if(this.showingSelected){
return;
}
var _199=this.getMouseDownHTMLElement();
var _200=Rico.Color.createColorFromBackground(_199);
_200.isBright()?_200.darken(0.033):_200.brighten(0.033);
this.saveBackground=RicoUtil.getElementsComputedStyle(_199,"backgroundColor","background-color");
_199.style.backgroundColor=_200.asHex();
this.showingSelected=true;
},deselect:function(){
this.selected=false;
if(!this.showingSelected){
return;
}
var _201=this.getMouseDownHTMLElement();
_201.style.backgroundColor=this.saveBackground;
this.showingSelected=false;
},isSelected:function(){
return this.selected;
},startDrag:function(){
},cancelDrag:function(){
},endDrag:function(){
},getSingleObjectDragGUI:function(){
return this.htmlElement;
},getMultiObjectDragGUI:function(_202){
return this.htmlElement;
},getDroppedGUI:function(){
return this.htmlElement;
},toString:function(){
return this.type+":"+this.htmlElement+":";
}};
Rico.Dropzone=Class.create();
Rico.Dropzone.prototype={initialize:function(_203){
this.htmlElement=$(_203);
this.absoluteRect=null;
},getHTMLElement:function(){
return this.htmlElement;
},clearPositionCache:function(){
this.absoluteRect=null;
},getAbsoluteRect:function(){
if(this.absoluteRect==null){
var _204=this.getHTMLElement();
var pos=RicoUtil.toViewportPosition(_204);
this.absoluteRect={top:pos.y,left:pos.x,bottom:pos.y+_204.offsetHeight,right:pos.x+_204.offsetWidth};
}
return this.absoluteRect;
},activate:function(){
var _206=this.getHTMLElement();
if(_206==null||this.showingActive){
return;
}
this.showingActive=true;
this.saveBackgroundColor=_206.style.backgroundColor;
var _207="#ffea84";
var _208=Rico.Color.createColorFromBackground(_206);
if(_208==null){
_206.style.backgroundColor=_207;
}else{
_208.isBright()?_208.darken(0.2):_208.brighten(0.2);
_206.style.backgroundColor=_208.asHex();
}
},deactivate:function(){
var _209=this.getHTMLElement();
if(_209==null||!this.showingActive){
return;
}
_209.style.backgroundColor=this.saveBackgroundColor;
this.showingActive=false;
this.saveBackgroundColor=null;
},showHover:function(){
var _210=this.getHTMLElement();
if(_210==null||this.showingHover){
return;
}
this.saveBorderWidth=_210.style.borderWidth;
this.saveBorderStyle=_210.style.borderStyle;
this.saveBorderColor=_210.style.borderColor;
this.showingHover=true;
_210.style.borderWidth="1px";
_210.style.borderStyle="solid";
_210.style.borderColor="#ffff00";
},hideHover:function(){
var _211=this.getHTMLElement();
if(_211==null||!this.showingHover){
return;
}
_211.style.borderWidth=this.saveBorderWidth;
_211.style.borderStyle=this.saveBorderStyle;
_211.style.borderColor=this.saveBorderColor;
this.showingHover=false;
},canAccept:function(_212){
return true;
},accept:function(_213){
var _214=this.getHTMLElement();
if(_214==null){
return;
}
n=_213.length;
for(var i=0;i<n;i++){
var _215=_213[i].getDroppedGUI();
if(RicoUtil.getElementsComputedStyle(_215,"position")=="absolute"){
_215.style.position="static";
_215.style.top="";
_215.style.top="";
}
_214.appendChild(_215);
}
}};
Effect.SizeAndPosition=Class.create();
Effect.SizeAndPosition.prototype={initialize:function(_216,x,y,w,h,_220,_221,_222){
this.element=$(_216);
this.x=x;
this.y=y;
this.w=w;
this.h=h;
this.duration=_220;
this.steps=_221;
this.options=arguments[7]||{};
this.sizeAndPosition();
},sizeAndPosition:function(){
if(this.isFinished()){
if(this.options.complete){
this.options.complete(this);
}
return;
}
if(this.timer){
clearTimeout(this.timer);
}
var _223=Math.round(this.duration/this.steps);
var _224=this.element.offsetLeft;
var _225=this.element.offsetTop;
var _226=this.element.offsetWidth;
var _227=this.element.offsetHeight;
this.x=(this.x)?this.x:_224;
this.y=(this.y)?this.y:_225;
this.w=(this.w)?this.w:_226;
this.h=(this.h)?this.h:_227;
var difX=this.steps>0?(this.x-_224)/this.steps:0;
var difY=this.steps>0?(this.y-_225)/this.steps:0;
var difW=this.steps>0?(this.w-_226)/this.steps:0;
var difH=this.steps>0?(this.h-_227)/this.steps:0;
this.moveBy(difX,difY);
this.resizeBy(difW,difH);
this.duration-=_223;
this.steps--;
this.timer=setTimeout(this.sizeAndPosition.bind(this),_223);
},isFinished:function(){
return this.steps<=0;
},moveBy:function(difX,difY){
var _232=this.element.offsetLeft;
var _233=this.element.offsetTop;
var _234=parseInt(difX);
var _235=parseInt(difY);
var _236=this.element.style;
if(_234!=0){
_236.left=(_232+_234)+"px";
}
if(_235!=0){
_236.top=(_233+_235)+"px";
}
},resizeBy:function(difW,difH){
var _237=this.element.offsetWidth;
var _238=this.element.offsetHeight;
var _239=parseInt(difW);
var _240=parseInt(difH);
var _241=this.element.style;
if(_239!=0){
_241.width=(_237+_239)+"px";
}
if(_240!=0){
_241.height=(_238+_240)+"px";
}
}};
Effect.Size=Class.create();
Effect.Size.prototype={initialize:function(_242,w,h,_243,_244,_245){
new Effect.SizeAndPosition(_242,null,null,w,h,_243,_244,_245);
}};
Effect.Position=Class.create();
Effect.Position.prototype={initialize:function(_246,x,y,_247,_248,_249){
new Effect.SizeAndPosition(_246,x,y,null,null,_247,_248,_249);
}};
Effect.Round=Class.create();
Effect.Round.prototype={initialize:function(_250,_251,_252){
var _253=document.getElementsByTagAndClassName(_250,_251);
for(var i=0;i<_253.length;i++){
Rico.Corner.round(_253[i],_252);
}
}};
Effect.FadeTo=Class.create();
Effect.FadeTo.prototype={initialize:function(_254,_255,_256,_257,_258){
this.element=$(_254);
this.opacity=_255;
this.duration=_256;
this.steps=_257;
this.options=arguments[4]||{};
this.fadeTo();
},fadeTo:function(){
if(this.isFinished()){
if(this.options.complete){
this.options.complete(this);
}
return;
}
if(this.timer){
clearTimeout(this.timer);
}
var _259=Math.round(this.duration/this.steps);
var _260=this.getElementOpacity();
var _261=this.steps>0?(this.opacity-_260)/this.steps:0;
this.changeOpacityBy(_261);
this.duration-=_259;
this.steps--;
this.timer=setTimeout(this.fadeTo.bind(this),_259);
},changeOpacityBy:function(v){
var _263=this.getElementOpacity();
var _264=Math.max(0,Math.min(_263+v,1));
this.element.ricoOpacity=_264;
this.element.style.filter="alpha(opacity:"+Math.round(_264*100)+")";
this.element.style.opacity=_264;
},isFinished:function(){
return this.steps<=0;
},getElementOpacity:function(){
if(this.element.ricoOpacity==undefined){
var _265;
if(this.element.currentStyle){
_265=this.element.currentStyle.opacity;
}else{
if(document.defaultView.getComputedStyle!=undefined){
var _266=document.defaultView.getComputedStyle;
_265=_266(this.element,null).getPropertyValue("opacity");
}
}
this.element.ricoOpacity=_265!=undefined?_265:1;
}
return parseFloat(this.element.ricoOpacity);
}};
Effect.AccordionSize=Class.create();
Effect.AccordionSize.prototype={initialize:function(e1,e2,_269,end,_271,_272,_273){
this.e1=$(e1);
this.e2=$(e2);
this.start=_269;
this.end=end;
this.duration=_271;
this.steps=_272;
this.options=arguments[6]||{};
this.accordionSize();
},accordionSize:function(){
if(this.isFinished()){
this.e1.style.height=this.start+"px";
this.e2.style.height=this.end+"px";
if(this.options.complete){
this.options.complete(this);
}
return;
}
if(this.timer){
clearTimeout(this.timer);
}
var _274=Math.round(this.duration/this.steps);
var diff=this.steps>0?(parseInt(this.e1.offsetHeight)-this.start)/this.steps:0;
this.resizeBy(diff);
this.duration-=_274;
this.steps--;
this.timer=setTimeout(this.accordionSize.bind(this),_274);
},isFinished:function(){
return this.steps<=0;
},resizeBy:function(diff){
var _276=this.e1.offsetHeight;
var _277=this.e2.offsetHeight;
var _278=parseInt(diff);
if(diff!=0){
this.e1.style.height=(_276-_278)+"px";
this.e2.style.height=(_277+_278)+"px";
}
}};
Rico.LiveGridMetaData=Class.create();
Rico.LiveGridMetaData.prototype={initialize:function(_279,_280,_281){
this.pageSize=_279;
this.totalRows=_280;
this.setOptions(_281);
this.scrollArrowHeight=16;
},setOptions:function(_282){
this.options={largeBufferSize:7,smallBufferSize:1,nearLimitFactor:0.2}.extend(_282||{});
},getPageSize:function(){
return this.pageSize;
},getTotalRows:function(){
return this.totalRows;
},setTotalRows:function(n){
this.totalRows=n;
},getLargeBufferSize:function(){
return parseInt(this.options.largeBufferSize*this.pageSize);
},getSmallBufferSize:function(){
return parseInt(this.options.smallBufferSize*this.pageSize);
},getLimitTolerance:function(){
return parseInt(this.getLargeBufferSize()*this.options.nearLimitFactor);
},getBufferSize:function(_283){
return _283?this.getLargeBufferSize():this.getSmallBufferSize();
}};
Rico.LiveGridScroller=Class.create();
Rico.LiveGridScroller.prototype={initialize:function(_284){
this.isIE=navigator.userAgent.toLowerCase().indexOf("msie")>=0;
this.liveGrid=_284;
this.metaData=_284.metaData;
this.createScrollBar();
this.scrollTimeout=null;
this.lastScrollPos=0;
},isUnPlugged:function(){
return this.scrollerDiv.onscroll==null;
},plugin:function(){
this.scrollerDiv.onscroll=this.handleScroll.bindAsEventListener(this);
},unplug:function(){
this.scrollerDiv.onscroll=null;
},sizeIEHeaderHack:function(){
if(!this.isIE){
return;
}
var _285=$(this.liveGrid.tableId+"_header");
if(_285){
_285.rows[0].cells[0].style.width=(_285.rows[0].cells[0].offsetWidth+1)+"px";
}
},createScrollBar:function(){
var _286=this.liveGrid.table;
var _287=_286.offsetHeight;
this.scrollerDiv=document.createElement("div");
var _288=this.scrollerDiv.style;
_288.borderRight="1px solid #ababab";
_288.position="relative";
_288.left=this.isIE?"-6px":"-3px";
_288.width="19px";
_288.height=_287+"px";
_288.overflow="auto";
this.heightDiv=document.createElement("div");
this.heightDiv.style.width="1px";
this.heightDiv.style.height=parseInt(_287*this.metaData.getTotalRows()/this.metaData.getPageSize())+"px";
this.lineHeight=_287/this.metaData.getPageSize();
this.scrollerDiv.appendChild(this.heightDiv);
this.scrollerDiv.onscroll=this.handleScroll.bindAsEventListener(this);
_286.parentNode.insertBefore(this.scrollerDiv,_286.nextSibling);
},updateSize:function(){
var _289=this.liveGrid.table;
var _290=_289.offsetHeight;
this.heightDiv.style.height=parseInt(_290*this.metaData.getTotalRows()/this.metaData.getPageSize())+"px";
},adjustScrollTop:function(){
this.unplug();
var rem=this.scrollerDiv.scrollTop%this.lineHeight;
if(rem!=0){
if(this.lastScrollPos<this.scrollerDiv.scrollTop){
this.scrollerDiv.scrollTop=this.scrollerDiv.scrollTop+this.lineHeight-rem;
}else{
this.scrollerDiv.scrollTop=this.scrollerDiv.scrollTop-rem;
}
}
this.lastScrollPos=this.scrollerDiv.scrollTop;
this.plugin();
},handleScroll:function(){
if(this.scrollTimeout){
clearTimeout(this.scrollTimeout);
}
var _292=parseInt(this.scrollerDiv.scrollTop*this.metaData.getTotalRows()/this.heightDiv.offsetHeight);
this.liveGrid.requestContentRefresh(_292);
if(this.metaData.options.onscroll){
this.metaData.options.onscroll(_292,this.metaData);
}
this.scrollTimeout=setTimeout(this.scrollIdle.bind(this),1200);
},scrollIdle:function(){
if(this.metaData.options.onscrollidle){
this.metaData.options.onscrollidle();
}
}};
Rico.LiveGridBuffer=Class.create();
Rico.LiveGridBuffer.prototype={initialize:function(_293){
this.startPos=0;
this.size=0;
this.metaData=_293;
this.rows=new Array();
this.updateInProgress=false;
},update:function(_294,_295){
this.startPos=_295;
this.rows=new Array();
var _296=_294.getElementsByTagName("rows")[0];
this.updateUI=_296.getAttribute("update_ui")=="true";
var trs=_296.getElementsByTagName("tr");
for(var i=0;i<trs.length;i++){
var row=this.rows[i]=new Array();
var _299=trs[i].getElementsByTagName("td");
for(var j=0;j<_299.length;j++){
var cell=_299[j];
var _301=cell.getAttribute("convert_spaces")=="true";
var _302=cell.text!=undefined?cell.text:cell.textContent;
row[j]=_301?this.convertSpaces(_302):_302;
}
}
this.size=trs.length;
},isFullP:function(){
return this.metaData.pageSize!=this.size;
},isClose:function(_303){
return (_303<this.startPos+this.size+(this.size/2))&&(_303+this.size+(this.size/2)>this.startPos);
},isInRange:function(_304,_305){
return (_304<this.startPos+this.size)&&(_304+_305>this.startPos);
},isFullyInRange:function(_306){
return (_306>=this.startPos)&&(_306+this.metaData.getPageSize())<=(this.startPos+this.size);
},isNearingTopLimit:function(_307){
return _307-this.startPos<this.metaData.getLimitTolerance();
},isNearingBottomLimit:function(_308){
var _309=_308+this.metaData.getPageSize();
var _310=this.startPos+this.size;
return _310-_309<this.metaData.getLimitTolerance();
},isAtTop:function(){
return this.startPos==0;
},isAtBottom:function(){
return this.startPos+this.size==this.metaData.getTotalRows();
},isNearingLimit:function(_311){
return (!this.isAtTop()&&this.isNearingTopLimit(_311))||(!this.isAtBottom()&&this.isNearingBottomLimit(_311));
},getRows:function(_312,_313){
var _314=_312-this.startPos;
var _315=_314+_313;
if(_315>this.size){
_315=this.size;
}
var _316=new Array();
var _317=0;
for(var i=_314;i<_315;i++){
_316[_317++]=this.rows[i];
}
return _316;
},convertSpaces:function(s){
return s.split(" ").join("&nbsp;");
}};
Rico.LiveGridRequest=Class.create();
Rico.LiveGridRequest.prototype={initialize:function(_318,_319){
this.requestOffset=_318;
}};
Rico.LiveGrid=Class.create();
Rico.LiveGrid.prototype={initialize:function(_320,_321,_322,url,_324){
if(_324==null){
_324={};
}
this.tableId=_320;
this.table=$(_320);
this.metaData=new Rico.LiveGridMetaData(_321,_322,_324);
this.buffer=new Rico.LiveGridBuffer(this.metaData);
this.scroller=new Rico.LiveGridScroller(this);
this.lastDisplayedStartPos=0;
this.timeoutHander=null;
this.additionalParms=_324.requestParameters||[];
this.processingRequest=null;
this.unprocessedRequest=null;
this.initAjax(url);
if(_324.prefetchBuffer){
this.fetchBuffer(0,true);
}
},setRequestParams:function(){
this.additionalParms=[];
for(var i=0;i<arguments.length;i++){
this.additionalParms[i]=arguments[i];
}
},setTotalRows:function(_325){
this.metaData.setTotalRows(_325);
this.scroller.updateSize();
},initAjax:function(url){
ajaxEngine.registerRequest(this.tableId+"_request",url);
ajaxEngine.registerAjaxObject(this.tableId+"_updater",this);
},invokeAjax:function(){
},largeBufferWindowStart:function(_326){
val=_326-((0.5*this.metaData.getLargeBufferSize())-(0.5*this.metaData.getPageSize()));
return Math.max(parseInt(val),0);
},handleTimedOut:function(){
this.processingRequest=null;
},fetchBuffer:function(_327,_328){
if(this.processingRequest){
this.unprocessedRequest=new Rico.LiveGridRequest(_327);
return;
}
var _329=this.metaData.getBufferSize(_328);
bufferStartPos=Math.max(0,_328?this.largeBufferWindowStart(_327):_327);
this.processingRequest=new Rico.LiveGridRequest(_327);
this.processingRequest.bufferOffset=bufferStartPos;
var _330=[];
_330.push(this.tableId+"_request");
_330.push("id="+this.tableId);
_330.push("page_size="+_329);
_330.push("offset="+bufferStartPos);
for(var i=0;i<this.additionalParms.length;i++){
_330.push(this.additionalParms[i]);
}
ajaxEngine.sendRequest.apply(ajaxEngine,_330);
this.timeoutHandler=setTimeout(this.handleTimedOut.bind(this),4000);
},requestContentRefresh:function(_331){
if(this.buffer&&this.buffer.isFullyInRange(_331)){
this.updateContent(_331);
if(this.buffer.isNearingLimit(_331)){
this.fetchBuffer(_331,true);
}
}else{
if(this.buffer&&this.buffer.isClose(_331)){
this.fetchBuffer(_331,true);
}else{
this.fetchBuffer(_331,false);
}
}
},ajaxUpdate:function(_332){
try{
clearTimeout(this.timeoutHandler);
this.buffer=new Rico.LiveGridBuffer(this.metaData);
this.buffer.update(_332,this.processingRequest.bufferOffset);
if(this.unprocessedRequest==null){
offset=this.processingRequest.requestOffset;
this.updateContent(offset);
}
this.processingRequest=null;
if(this.unprocessedRequest!=null){
this.requestContentRefresh(this.unprocessedRequest.requestOffset);
this.unprocessedRequest=null;
}
}
catch(err){
}
},updateContent:function(_333){
this.replaceCellContents(this.buffer,_333);
},replaceCellContents:function(_334,_335){
if(_335==this.lastDisplayedStartPos){
return;
}
this.lastDisplayedStartPos=_335;
var rows=_334.getRows(_335,this.metaData.getPageSize());
for(var i=0;i<rows.length;i++){
var row=rows[i];
for(var j=0;j<row.length;j++){
this.table.rows[i].cells[j].innerHTML=rows[i][j];
}
}
}};
var RicoUtil={getElementsComputedStyle:function(_337,_338,_339){
if(arguments.length==2){
_339=_338;
}
var el=$(_337);
if(el.currentStyle){
return el.currentStyle[_338];
}else{
return document.defaultView.getComputedStyle(el,null).getPropertyValue(_339);
}
},createXmlDocument:function(){
if(document.implementation&&document.implementation.createDocument){
var doc=document.implementation.createDocument("","",null);
if(doc.readyState==null){
doc.readyState=1;
doc.addEventListener("load",function(){
doc.readyState=4;
if(typeof doc.onreadystatechange=="function"){
doc.onreadystatechange();
}
},false);
}
return doc;
}
if(window.ActiveXObject){
return Try.these(function(){
return new ActiveXObject("MSXML2.DomDocument");
},function(){
return new ActiveXObject("Microsoft.DomDocument");
},function(){
return new ActiveXObject("MSXML.DomDocument");
},function(){
return new ActiveXObject("MSXML3.DomDocument");
})||false;
}
return null;
},toViewportPosition:function(_341){
return this._toAbsolute(_341,true);
},toDocumentPosition:function(_342){
return this._toAbsolute(_342,false);
},_toAbsolute:function(_343,_344){
if(navigator.userAgent.toLowerCase().indexOf("msie")==-1){
return this._toAbsoluteMozilla(_343,_344);
}
var x=0;
var y=0;
var _345=_343;
while(_345){
var _346=0;
var _347=0;
if(_345!=_343){
var _346=parseInt(this.getElementsComputedStyle(_345,"borderLeftWidth"));
var _347=parseInt(this.getElementsComputedStyle(_345,"borderTopWidth"));
_346=isNaN(_346)?0:_346;
_347=isNaN(_347)?0:_347;
}
x+=_345.offsetLeft-_345.scrollLeft+_346;
y+=_345.offsetTop-_345.scrollTop+_347;
_345=_345.offsetParent;
}
if(_344){
x-=this.docScrollLeft();
y-=this.docScrollTop();
}
return {x:x,y:y};
},_toAbsoluteMozilla:function(_348,_349){
var x=0;
var y=0;
var _350=_348;
while(_350){
x+=_350.offsetLeft;
y+=_350.offsetTop;
_350=_350.offsetParent;
}
_350=_348;
while(_350&&_350!=document.body&&_350!=document.documentElement){
if(_350.scrollLeft){
x-=_350.scrollLeft;
}
if(_350.scrollTop){
y-=_350.scrollTop;
}
_350=_350.parentNode;
}
if(_349){
x-=this.docScrollLeft();
y-=this.docScrollTop();
}
return {x:x,y:y};
},docScrollLeft:function(){
if(window.pageXOffset){
return window.pageXOffset;
}else{
if(document.documentElement&&document.documentElement.scrollLeft){
return document.documentElement.scrollLeft;
}else{
if(document.body){
return document.body.scrollLeft;
}else{
return 0;
}
}
}
},docScrollTop:function(){
if(window.pageYOffset){
return window.pageYOffset;
}else{
if(document.documentElement&&document.documentElement.scrollTop){
return document.documentElement.scrollTop;
}else{
if(document.body){
return document.body.scrollTop;
}else{
return 0;
}
}
}
}};

