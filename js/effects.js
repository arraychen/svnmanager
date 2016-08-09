var Effect={tagifyText:function(_1){
var _2="position:relative";
if(/MSIE/.test(navigator.userAgent)){
_2+=";zoom:1";
}
_1=$(_1);
$A(_1.childNodes).each(function(_3){
if(_3.nodeType==3){
_3.nodeValue.toArray().each(function(_4){
_1.insertBefore(Builder.node("span",{style:_2},_4==" "?String.fromCharCode(160):_4),_3);
});
Element.remove(_3);
}
});
},multiple:function(_5,_6){
var _7;
if(((typeof _5=="object")||(typeof _5=="function"))&&(_5.length)){
_7=_5;
}else{
_7=$(_5).childNodes;
}
var _8=Object.extend({speed:0.1,delay:0},arguments[2]||{});
var _9=_8.speed;
var _10=_8.delay;
$A(_7).each(function(_5,_11){
new _6(_5,Object.extend(_8,{delay:_10+_11*_9}));
});
}};
var Effect2=Effect;
Effect.Transitions={};
Effect.Transitions.linear=function(pos){
return pos;
};
Effect.Transitions.sinoidal=function(pos){
return (-Math.cos(pos*Math.PI)/2)+0.5;
};
Effect.Transitions.reverse=function(pos){
return 1-pos;
};
Effect.Transitions.flicker=function(pos){
return ((-Math.cos(pos*Math.PI)/4)+0.75)+Math.random()/4;
};
Effect.Transitions.wobble=function(pos){
return (-Math.cos(pos*Math.PI*(9*pos))/2)+0.5;
};
Effect.Transitions.pulse=function(pos){
return (Math.floor(pos*10)%2==0?(pos*10-Math.floor(pos*10)):1-(pos*10-Math.floor(pos*10)));
};
Effect.Transitions.none=function(pos){
return 0;
};
Effect.Transitions.full=function(pos){
return 1;
};
Effect.Queue={effects:[],interval:null,add:function(_13){
var _14=new Date().getTime();
switch(_13.options.queue){
case "front":
this.effects.findAll(function(e){
return e.state=="idle";
}).each(function(e){
e.startOn+=_13.finishOn;
e.finishOn+=_13.finishOn;
});
break;
case "end":
_14=this.effects.pluck("finishOn").max()||_14;
break;
}
_13.startOn+=_14;
_13.finishOn+=_14;
this.effects.push(_13);
if(!this.interval){
this.interval=setInterval(this.loop.bind(this),40);
}
},remove:function(_16){
this.effects=this.effects.reject(function(e){
return e==_16;
});
if(this.effects.length==0){
clearInterval(this.interval);
this.interval=null;
}
},loop:function(){
var _17=new Date().getTime();
this.effects.invoke("loop",_17);
}};
Effect.Base=function(){
};
Effect.Base.prototype={position:null,setOptions:function(_18){
this.options=Object.extend({transition:Effect.Transitions.sinoidal,duration:1,fps:25,sync:false,from:0,to:1,delay:0,queue:"parallel"},_18||{});
},start:function(_19){
this.setOptions(_19||{});
this.currentFrame=0;
this.state="idle";
this.startOn=this.options.delay*1000;
this.finishOn=this.startOn+(this.options.duration*1000);
this.event("beforeStart");
if(!this.options.sync){
Effect.Queue.add(this);
}
},loop:function(_20){
if(_20>=this.startOn){
if(_20>=this.finishOn){
this.render(1);
this.cancel();
this.event("beforeFinish");
if(this.finish){
this.finish();
}
this.event("afterFinish");
return;
}
var pos=(_20-this.startOn)/(this.finishOn-this.startOn);
var _21=Math.round(pos*this.options.fps*this.options.duration);
if(_21>this.currentFrame){
this.render(pos);
this.currentFrame=_21;
}
}
},render:function(pos){
if(this.state=="idle"){
this.state="running";
this.event("beforeSetup");
if(this.setup){
this.setup();
}
this.event("afterSetup");
}
if(this.options.transition){
pos=this.options.transition(pos);
}
pos*=(this.options.to-this.options.from);
pos+=this.options.from;
this.position=pos;
this.event("beforeUpdate");
if(this.update){
this.update(pos);
}
this.event("afterUpdate");
},cancel:function(){
if(!this.options.sync){
Effect.Queue.remove(this);
}
this.state="finished";
},event:function(_22){
if(this.options[_22+"Internal"]){
this.options[_22+"Internal"](this);
}
if(this.options[_22]){
this.options[_22](this);
}
}};
Effect.Parallel=Class.create();
Object.extend(Object.extend(Effect.Parallel.prototype,Effect.Base.prototype),{initialize:function(_23){
this.effects=_23||[];
this.start(arguments[1]);
},update:function(_24){
this.effects.invoke("render",_24);
},finish:function(_25){
this.effects.each(function(_26){
_26.render(1);
_26.cancel();
_26.event("beforeFinish");
if(_26.finish){
_26.finish(_25);
}
_26.event("afterFinish");
});
}});
Effect.Opacity=Class.create();
Object.extend(Object.extend(Effect.Opacity.prototype,Effect.Base.prototype),{initialize:function(_27){
this.element=$(_27);
if(/MSIE/.test(navigator.userAgent)&&(!this.element.hasLayout)){
this.element.style.zoom=1;
}
var _28=Object.extend({from:Element.getOpacity(this.element)||0,to:1},arguments[1]||{});
this.start(_28);
},update:function(_29){
Element.setOpacity(this.element,_29);
}});
Effect.MoveBy=Class.create();
Object.extend(Object.extend(Effect.MoveBy.prototype,Effect.Base.prototype),{initialize:function(_30,_31,_32){
this.element=$(_30);
this.toTop=_31;
this.toLeft=_32;
this.start(arguments[3]);
},setup:function(){
Element.makePositioned(this.element);
this.originalTop=parseFloat(Element.getStyle(this.element,"top")||"0");
this.originalLeft=parseFloat(Element.getStyle(this.element,"left")||"0");
},update:function(_33){
var _34=this.toTop*_33+this.originalTop;
var _35=this.toLeft*_33+this.originalLeft;
this.setPosition(_34,_35);
},setPosition:function(_36,_37){
this.element.style.top=_36+"px";
this.element.style.left=_37+"px";
}});
Effect.Scale=Class.create();
Object.extend(Object.extend(Effect.Scale.prototype,Effect.Base.prototype),{initialize:function(_38,_39){
this.element=$(_38);
var _40=Object.extend({scaleX:true,scaleY:true,scaleContent:true,scaleFromCenter:false,scaleMode:"box",scaleFrom:100,scaleTo:_39},arguments[2]||{});
this.start(_40);
},setup:function(){
var _41=this;
this.restoreAfterFinish=this.options.restoreAfterFinish||false;
this.elementPositioning=Element.getStyle(this.element,"position");
_41.originalStyle={};
["top","left","width","height","fontSize"].each(function(k){
_41.originalStyle[k]=_41.element.style[k];
});
this.originalTop=this.element.offsetTop;
this.originalLeft=this.element.offsetLeft;
var _43=Element.getStyle(this.element,"font-size")||"100%";
["em","px","%"].each(function(_44){
if(_43.indexOf(_44)>0){
_41.fontSize=parseFloat(_43);
_41.fontSizeType=_44;
}
});
this.factor=(this.options.scaleTo-this.options.scaleFrom)/100;
this.dims=null;
if(this.options.scaleMode=="box"){
this.dims=[this.element.clientHeight,this.element.clientWidth];
}
if(this.options.scaleMode=="content"){
this.dims=[this.element.scrollHeight,this.element.scrollWidth];
}
if(!this.dims){
this.dims=[this.options.scaleMode.originalHeight,this.options.scaleMode.originalWidth];
}
},update:function(_45){
var _46=(this.options.scaleFrom/100)+(this.factor*_45);
if(this.options.scaleContent&&this.fontSize){
this.element.style.fontSize=this.fontSize*_46+this.fontSizeType;
}
this.setDimensions(this.dims[0]*_46,this.dims[1]*_46);
},finish:function(_47){
if(this.restoreAfterFinish){
var _48=this;
["top","left","width","height","fontSize"].each(function(k){
_48.element.style[k]=_48.originalStyle[k];
});
}
},setDimensions:function(_49,_50){
var els=this.element.style;
if(this.options.scaleX){
els.width=_50+"px";
}
if(this.options.scaleY){
els.height=_49+"px";
}
if(this.options.scaleFromCenter){
var _52=(_49-this.dims[0])/2;
var _53=(_50-this.dims[1])/2;
if(this.elementPositioning=="absolute"){
if(this.options.scaleY){
els.top=this.originalTop-_52+"px";
}
if(this.options.scaleX){
els.left=this.originalLeft-_53+"px";
}
}else{
if(this.options.scaleY){
els.top=-_52+"px";
}
if(this.options.scaleX){
els.left=-_53+"px";
}
}
}
}});
Effect.Highlight=Class.create();
Object.extend(Object.extend(Effect.Highlight.prototype,Effect.Base.prototype),{initialize:function(_54){
this.element=$(_54);
var _55=Object.extend({startcolor:"#ffff99"},arguments[1]||{});
this.start(_55);
},setup:function(){
this.oldBgImage=this.element.style.backgroundImage;
this.element.style.backgroundImage="none";
if(!this.options.endcolor){
this.options.endcolor=Element.getStyle(this.element,"background-color").parseColor("#ffffff");
}
if(typeof this.options.restorecolor=="undefined"){
this.options.restorecolor=this.element.style.backgroundColor;
}
this.colors_base=[parseInt(this.options.startcolor.slice(1,3),16),parseInt(this.options.startcolor.slice(3,5),16),parseInt(this.options.startcolor.slice(5),16)];
this.colors_delta=[parseInt(this.options.endcolor.slice(1,3),16)-this.colors_base[0],parseInt(this.options.endcolor.slice(3,5),16)-this.colors_base[1],parseInt(this.options.endcolor.slice(5),16)-this.colors_base[2]];
},update:function(_56){
var _57=this;
var _58=$R(0,2).map(function(i){
return Math.round(_57.colors_base[i]+(_57.colors_delta[i]*_56));
});
this.element.style.backgroundColor="#"+_58[0].toColorPart()+_58[1].toColorPart()+_58[2].toColorPart();
},finish:function(){
this.element.style.backgroundColor=this.options.restorecolor;
this.element.style.backgroundImage=this.oldBgImage;
}});
Effect.ScrollTo=Class.create();
Object.extend(Object.extend(Effect.ScrollTo.prototype,Effect.Base.prototype),{initialize:function(_60){
this.element=$(_60);
this.start(arguments[1]||{});
},setup:function(){
Position.prepare();
var _61=Position.cumulativeOffset(this.element);
var max=window.innerHeight?window.height-window.innerHeight:document.body.scrollHeight-(document.documentElement.clientHeight?document.documentElement.clientHeight:document.body.clientHeight);
this.scrollStart=Position.deltaY;
this.delta=(_61[1]>max?max:_61[1])-this.scrollStart;
},update:function(_63){
Position.prepare();
window.scrollTo(Position.deltaX,this.scrollStart+(_63*this.delta));
}});
Effect.Fade=function(_64){
var _65=Element.getInlineOpacity(_64);
var _66=Object.extend({from:Element.getOpacity(_64)||1,to:0,afterFinishInternal:function(_67){
if(_67.options.to==0){
Element.hide(_67.element);
Element.setInlineOpacity(_67.element,_65);
}
}},arguments[1]||{});
return new Effect.Opacity(_64,_66);
};
Effect.Appear=function(_68){
var _69=Object.extend({from:(Element.getStyle(_68,"display")=="none"?0:Element.getOpacity(_68)||0),to:1,beforeSetup:function(_70){
Element.setOpacity(_70.element,_70.options.from);
Element.show(_70.element);
}},arguments[1]||{});
return new Effect.Opacity(_68,_69);
};
Effect.Puff=function(_71){
_71=$(_71);
var _72=Element.getInlineOpacity(_71);
var _73=_71.style.position;
return new Effect.Parallel([new Effect.Scale(_71,200,{sync:true,scaleFromCenter:true,scaleContent:true,restoreAfterFinish:true}),new Effect.Opacity(_71,{sync:true,to:0})],Object.extend({duration:1,beforeSetupInternal:function(_74){
_74.effects[0].element.style.position="absolute";
},afterFinishInternal:function(_75){
Element.hide(_75.effects[0].element);
_75.effects[0].element.style.position=_73;
Element.setInlineOpacity(_75.effects[0].element,_72);
}},arguments[1]||{}));
};
Effect.BlindUp=function(_76){
_76=$(_76);
Element.makeClipping(_76);
return new Effect.Scale(_76,0,Object.extend({scaleContent:false,scaleX:false,restoreAfterFinish:true,afterFinishInternal:function(_77){
Element.hide(_77.element);
Element.undoClipping(_77.element);
}},arguments[1]||{}));
};
Effect.BlindDown=function(_78){
_78=$(_78);
var _79=_78.style.height;
var _80=Element.getDimensions(_78);
return new Effect.Scale(_78,100,Object.extend({scaleContent:false,scaleX:false,scaleFrom:0,scaleMode:{originalHeight:_80.height,originalWidth:_80.width},restoreAfterFinish:true,afterSetup:function(_81){
Element.makeClipping(_81.element);
_81.element.style.height="0px";
Element.show(_81.element);
},afterFinishInternal:function(_82){
Element.undoClipping(_82.element);
_82.element.style.height=_79;
}},arguments[1]||{}));
};
Effect.SwitchOff=function(_83){
_83=$(_83);
var _84=Element.getInlineOpacity(_83);
return new Effect.Appear(_83,{duration:0.4,from:0,transition:Effect.Transitions.flicker,afterFinishInternal:function(_85){
new Effect.Scale(_85.element,1,{duration:0.3,scaleFromCenter:true,scaleX:false,scaleContent:false,restoreAfterFinish:true,beforeSetup:function(_85){
Element.makePositioned(_85.element);
Element.makeClipping(_85.element);
},afterFinishInternal:function(_86){
Element.hide(_86.element);
Element.undoClipping(_86.element);
Element.undoPositioned(_86.element);
Element.setInlineOpacity(_86.element,_84);
}});
}});
};
Effect.DropOut=function(_87){
_87=$(_87);
var _88=_87.style.top;
var _89=_87.style.left;
var _90=Element.getInlineOpacity(_87);
return new Effect.Parallel([new Effect.MoveBy(_87,100,0,{sync:true}),new Effect.Opacity(_87,{sync:true,to:0})],Object.extend({duration:0.5,beforeSetup:function(_91){
Element.makePositioned(_91.effects[0].element);
},afterFinishInternal:function(_92){
Element.hide(_92.effects[0].element);
Element.undoPositioned(_92.effects[0].element);
_92.effects[0].element.style.left=_89;
_92.effects[0].element.style.top=_88;
Element.setInlineOpacity(_92.effects[0].element,_90);
}},arguments[1]||{}));
};
Effect.Shake=function(_93){
_93=$(_93);
var _94=_93.style.top;
var _95=_93.style.left;
return new Effect.MoveBy(_93,0,20,{duration:0.05,afterFinishInternal:function(_96){
new Effect.MoveBy(_96.element,0,-40,{duration:0.1,afterFinishInternal:function(_96){
new Effect.MoveBy(_96.element,0,40,{duration:0.1,afterFinishInternal:function(_96){
new Effect.MoveBy(_96.element,0,-40,{duration:0.1,afterFinishInternal:function(_96){
new Effect.MoveBy(_96.element,0,40,{duration:0.1,afterFinishInternal:function(_96){
new Effect.MoveBy(_96.element,0,-20,{duration:0.05,afterFinishInternal:function(_96){
Element.undoPositioned(_96.element);
_96.element.style.left=_95;
_96.element.style.top=_94;
}});
}});
}});
}});
}});
}});
};
Effect.SlideDown=function(_97){
_97=$(_97);
Element.cleanWhitespace(_97);
var _98=_97.firstChild.style.bottom;
var _99=Element.getDimensions(_97);
return new Effect.Scale(_97,100,Object.extend({scaleContent:false,scaleX:false,scaleFrom:0,scaleMode:{originalHeight:_99.height,originalWidth:_99.width},restoreAfterFinish:true,afterSetup:function(_100){
Element.makePositioned(_100.element.firstChild);
if(window.opera){
_100.element.firstChild.style.top="";
}
Element.makeClipping(_100.element);
_97.style.height="0";
Element.show(_97);
},afterUpdateInternal:function(_101){
_101.element.firstChild.style.bottom=(_101.originalHeight-_101.element.clientHeight)+"px";
},afterFinishInternal:function(_102){
Element.undoClipping(_102.element);
Element.undoPositioned(_102.element.firstChild);
_102.element.firstChild.style.bottom=_98;
}},arguments[1]||{}));
};
Effect.SlideUp=function(_103){
_103=$(_103);
Element.cleanWhitespace(_103);
var _104=_103.firstChild.style.bottom;
return new Effect.Scale(_103,0,Object.extend({scaleContent:false,scaleX:false,scaleMode:"box",scaleFrom:100,restoreAfterFinish:true,beforeStartInternal:function(_105){
Element.makePositioned(_105.element.firstChild);
if(window.opera){
_105.element.firstChild.style.top="";
}
Element.makeClipping(_105.element);
Element.show(_103);
},afterUpdateInternal:function(_106){
_106.element.firstChild.style.bottom=(_106.originalHeight-_106.element.clientHeight)+"px";
},afterFinishInternal:function(_107){
Element.hide(_107.element);
Element.undoClipping(_107.element);
Element.undoPositioned(_107.element.firstChild);
_107.element.firstChild.style.bottom=_104;
}},arguments[1]||{}));
};
Effect.Squish=function(_108){
return new Effect.Scale(_108,window.opera?1:0,{restoreAfterFinish:true,beforeSetup:function(_109){
Element.makeClipping(_109.element);
},afterFinishInternal:function(_110){
Element.hide(_110.element);
Element.undoClipping(_110.element);
}});
};
Effect.Grow=function(_111){
_111=$(_111);
var _112=arguments[1]||{};
var _113=Element.getDimensions(_111);
var _114=_113.width;
var _115=_113.height;
var _116=_111.style.top;
var _117=_111.style.left;
var _118=_111.style.height;
var _119=_111.style.width;
var _120=Element.getInlineOpacity(_111);
var _121=_112.direction||"center";
var _122=_112.moveTransition||Effect.Transitions.sinoidal;
var _123=_112.scaleTransition||Effect.Transitions.sinoidal;
var _124=_112.opacityTransition||Effect.Transitions.full;
var _125,initialMoveY;
var _126,moveY;
switch(_121){
case "top-left":
_125=initialMoveY=_126=moveY=0;
break;
case "top-right":
_125=_114;
initialMoveY=moveY=0;
_126=-_114;
break;
case "bottom-left":
_125=_126=0;
initialMoveY=_115;
moveY=-_115;
break;
case "bottom-right":
_125=_114;
initialMoveY=_115;
_126=-_114;
moveY=-_115;
break;
case "center":
_125=_114/2;
initialMoveY=_115/2;
_126=-_114/2;
moveY=-_115/2;
break;
}
return new Effect.MoveBy(_111,initialMoveY,_125,{duration:0.01,beforeSetup:function(_127){
Element.hide(_127.element);
Element.makeClipping(_127.element);
Element.makePositioned(_127.element);
},afterFinishInternal:function(_128){
new Effect.Parallel([new Effect.Opacity(_128.element,{sync:true,to:1,from:0,transition:_124}),new Effect.MoveBy(_128.element,moveY,_126,{sync:true,transition:_122}),new Effect.Scale(_128.element,100,{scaleMode:{originalHeight:_115,originalWidth:_114},sync:true,scaleFrom:window.opera?1:0,transition:_123,restoreAfterFinish:true})],Object.extend({beforeSetup:function(_128){
_128.effects[0].element.style.height=0;
Element.show(_128.effects[0].element);
},afterFinishInternal:function(_129){
var el=_129.effects[0].element;
var els=el.style;
Element.undoClipping(el);
Element.undoPositioned(el);
els.top=_116;
els.left=_117;
els.height=_118;
els.width=_114;
Element.setInlineOpacity(el,_120);
}},_112));
}});
};
Effect.Shrink=function(_131){
_131=$(_131);
var _132=arguments[1]||{};
var _133=_131.clientWidth;
var _134=_131.clientHeight;
var _135=_131.style.top;
var _136=_131.style.left;
var _137=_131.style.height;
var _138=_131.style.width;
var _139=Element.getInlineOpacity(_131);
var _140=_132.direction||"center";
var _141=_132.moveTransition||Effect.Transitions.sinoidal;
var _142=_132.scaleTransition||Effect.Transitions.sinoidal;
var _143=_132.opacityTransition||Effect.Transitions.none;
var _144,moveY;
switch(_140){
case "top-left":
_144=moveY=0;
break;
case "top-right":
_144=_133;
moveY=0;
break;
case "bottom-left":
_144=0;
moveY=_134;
break;
case "bottom-right":
_144=_133;
moveY=_134;
break;
case "center":
_144=_133/2;
moveY=_134/2;
break;
}
return new Effect.Parallel([new Effect.Opacity(_131,{sync:true,to:0,from:1,transition:_143}),new Effect.Scale(_131,window.opera?1:0,{sync:true,transition:_142,restoreAfterFinish:true}),new Effect.MoveBy(_131,moveY,_144,{sync:true,transition:_141})],Object.extend({beforeStartInternal:function(_145){
Element.makePositioned(_145.effects[0].element);
Element.makeClipping(_145.effects[0].element);
},afterFinishInternal:function(_146){
var el=_146.effects[0].element;
var els=el.style;
Element.hide(el);
Element.undoClipping(el);
Element.undoPositioned(el);
els.top=_135;
els.left=_136;
els.height=_137;
els.width=_138;
Element.setInlineOpacity(el,_139);
}},_132));
};
Effect.Pulsate=function(_147){
_147=$(_147);
var _148=arguments[1]||{};
var _149=Element.getInlineOpacity(_147);
var _150=_148.transition||Effect.Transitions.sinoidal;
var _151=function(pos){
return _150(1-Effect.Transitions.pulse(pos));
};
_151.bind(_150);
return new Effect.Opacity(_147,Object.extend(Object.extend({duration:3,from:0,afterFinishInternal:function(_152){
Element.setInlineOpacity(_152.element,_149);
}},_148),{transition:_151}));
};
Effect.Fold=function(_153){
_153=$(_153);
var _154=_153.style.top;
var _155=_153.style.left;
var _156=_153.style.width;
var _157=_153.style.height;
Element.makeClipping(_153);
return new Effect.Scale(_153,5,Object.extend({scaleContent:false,scaleX:false,afterFinishInternal:function(_158){
new Effect.Scale(_153,1,{scaleContent:false,scaleY:false,afterFinishInternal:function(_158){
Element.hide(_158.element);
Element.undoClipping(_158.element);
_158.element.style.top=_154;
_158.element.style.left=_155;
_158.element.style.width=_156;
_158.element.style.height=_157;
}});
}},arguments[1]||{}));
};

