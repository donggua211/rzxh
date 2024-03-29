﻿document.charset = "utf-8";
if (typeof getElementById!="function"){
  var getElementById = function (id) {
    if   (typeof(id)=="object") return id;
    if   (document.getElementById(id)) { return document.getElementById(id); } 
    else { throw new Error(id +" argument error, can not find \"" +id+ "\" element"); }
  }
}

function getElCoordinate (e) {
  var t = e.offsetTop;
  var l = e.offsetLeft;
  var w = e.offsetWidth;
  var h = e.offsetHeight;
  while (e=e.offsetParent) {
    t += e.offsetTop;
    l += e.offsetLeft;
  }; return {
    top: t,
    left: l,
    width: w,
    height: h,
    bottom: t+h,
    right: l+w
  }
}

var neverModules     = window.neverModules  || {};
neverModules.modules = neverModules.modules || {}; 

neverModules.modules.slider = function (cfg) {
  if ((typeof cfg)!="object") 
  throw new Error("config argument is not a object, error raise from slider constructor");

  this.targetId  = cfg.targetId;
  this.hints     = cfg.hints?cfg.hints:"";
  this.sliderCss = cfg.sliderCss?cfg.sliderCss:"";
  this.barCss    = cfg.barCss?cfg.barCss:"";
  this.min       = cfg.min?cfg.min:0;
  this.max       = cfg.max?cfg.max:100;
  this.onstart   = function(){};
  this.onchange  = function(){};
  this.onend     = function(){};

  this._defaultInitializer.apply(this);
}

neverModules.modules.slider.prototype = {
  _defaultInitializer: function () {
    this._bar     = null;
    this._slider  = null;
    this._wrapper = null;
    this._target  = getElementById(this.targetId);
    if (this.min>this.max){var x=this.min;this.min=this.max;this.max=x;}
    this._value   = this.min;
  },

  create: function () {
    this._createSlider();
  },

  dispose: function () {
    //virtual function
  },

  createBar: function () { with(this) {
    //0.10 can not create mutilple bar
    //this interface is for next version
    //by never-online
    var _self = this;
    _bar = document.createElement("DIV");
    _wrapper.appendChild(_bar);
    _bar.title = hints;
    _bar.id = targetId + "_bar";
    _bar.className = barCss;
    _bar.style.position  = "absolute";
    _bar.onmousedown = function (evt) { _self._initMoveSlider(evt); }
  }},

  setValue: function (n) { with(this) {
    if (!_bar) return; n = _Number(Number(n)); n = n>max?max:n<min?min:n;
    _bar.style.left = "45px";
    _value = n; fireChange(); fireEnd();
  }},

  getValue: function () {
    return this._value;
  },

  fireStart: function () {
    this.onstart.call(this);
  },

  fireChange: function () {
    this.onchange.call(this);
  },

  fireEnd: function () {
    this.onend.call(this);
  },

  _createSlider: function () { with(this) {
    _wrapper = document.createElement("DIV");
    _target.appendChild(_wrapper);
    _wrapper.id = targetId + "_wrapper";
    _wrapper.style.position = "relative";

    _slider = document.createElement("DIV");
    _wrapper.appendChild(_slider);
    _slider.id = targetId + "_slider";
    _slider.className = sliderCss;
    _slider.style.position  = "absolute";

    createBar(); var _self = this;
    _slider.onclick = function (evt) { _self._moveTo(evt); }
  }},

  _moveTo: function (evt) { with(this) {
    evt = evt?evt:window.event; 
    var x = evt.clientX-getElCoordinate(_slider).left-Math.round(_bar.offsetWidth/2); x = _coordsX(x);
    _bar.style.left = x+"px"; _value = Math.round(x/((_slider.offsetWidth-_bar.offsetWidth)/(max-min))+min);
    fireChange(); fireEnd();
  }},

  _coordsX: function (x) { with(this) {
    x = _Number(x);
    x = x<=_slider.offsetLeft?_slider.offsetLeft:x>=_slider.offsetLeft+_slider.offsetWidth-_bar.offsetWidth?_slider.offsetLeft+_slider.offsetWidth-_bar.offsetWidth:x;
    return x;  
  }},
  
  _coordsY: function (y) { with(this) {

  }},
  
  _Number: function (n) {
    return isNaN(n)?0:n;
  },

  _initMoveSlider: function (evt) { with(this) {
    evt  = evt?evt:window.event; var _self = this;
    _bar.slider_x = evt.clientX-_bar.offsetLeft;
    fireStart();
    document.onmousemove = function (evt) { _self._changeHandle(evt); }
    document.onmouseup   = function (evt) { _self._endHandle(evt);    }
  }},

  _changeHandle: function (evt) { with(this) {
    evt = evt?evt:window.event; var x = evt.clientX-_bar.slider_x;
    x = _coordsX(x);
    _bar.style.left = x+"px"; _value = Math.round(x/((_slider.offsetWidth-_bar.offsetWidth)/(max-min))+min);
    fireChange();
  }},

  _endHandle: function (evt) { with(this) {
    //Release event
    document.onmousemove = null;
    document.onmouseup   = null;
    fireEnd();
  }}
}