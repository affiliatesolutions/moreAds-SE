<script type="text/javascript">
function HTMLDeliverManager(custom_config, html) {
"use strict";

var doc = document;
var w = window;

var config = custom_config || {},
mode = custom_config.mode || 'intent',
pixel_cb = custom_config.pixel_cb || false,
namespace = custom_config.namespace || gen_guid(),

width = setDefault(custom_config.width, 300),
height = setDefault(custom_config.width, 250),

intent_timeout  = setDefault(custom_config.intent_timeout, 5),
intent_sensitivity  = setDefault(custom_config.intent_sensitivity, 10),
delay = setDefault(custom_config.delay, 10),
timer        = setDefault(custom_config.timer, 10),
_delayTimer  = null,
interval_id = 0,
callback     = config.callback || function() { show(); },

_html = document.documentElement;

var disableKeydown = false;
var guid = gen_guid();
var content_fixed_id = 'cf'+guid;
var popbox_id = 'po'+guid;
var overlay_wrapper_id = "ow"+guid;
var overlay_id = "o"+guid;


function init() {
if(mode == 'float') {
interval_id = window.setInterval(function() {
if(sessionManager.isTimerExpired()) {
sessionManager.clearTimer();
callback();
}
}.bind(this), 100);
} else { // Intent Mode
on(_html, 'keydown', eventIntentManager.keydown);
on(_html, 'mouseleave', eventIntentManager.mouseleave);
on(_html, 'mouseenter', eventIntentManager.mouseenter);
}
}


function insertAndExecute(id, text) {
document.getElementById(id).innerHTML = text;
var scripts = document.getElementById(id).getElementsByTagName("script");
for (var i = 0; i < scripts.length; i++) {
if (scripts[i].src != "") {
var tag = document.createElement("script");
tag.src = scripts[i].src;
document.getElementsByTagName("head")[0].appendChild(tag);
}
else {
eval(scripts[i].innerHTML);
}
}
}

function show() {
if(document.getElementById(popbox_id)) return false;

var overlay = doc.createElement('div');
var content_fixed = doc.createElement('div');
var popbox = doc.createElement('div');
var overlay_wrapper = doc.createElement('div');
content_fixed.id = content_fixed_id;
content_fixed.setAttribute('style', 'position:fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);-webkit-transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);opacity:1;z-index:100000');
popbox.id = popbox_id;
overlay_wrapper.id = overlay_wrapper_id;
overlay_wrapper.setAttribute('style', 'position:absolute;top:0;bottom:0;left:0;right:0;');
overlay.id = overlay_id;
overlay.setAttribute('style', 'position:fixed;top:0;bottom:0;left:0;right:0;opacity:0.3;width:100%;height:100%;background-color:black;');

var close_span = doc.createElement('span');
close_span.setAttribute('style', 'cursor: pointer; border: 2px solid #c2c2c2; position: absolute; padding: 5px 10px; top: -15px; background-color: #605F61; right: -15px; border-radius: 20px;');
var close_a = doc.createElement('a');
close_a.href = '#';
close_a.textContent = 'X';
close_a.setAttribute('style', 'font-size: 20px; font-weight: bold; color: white; text-decoration: none;');
close_span.appendChild(close_a);

doc.body.appendChild(overlay_wrapper);
doc.body.appendChild(content_fixed);

overlay_wrapper.appendChild(overlay);
content_fixed.appendChild(close_span);
content_fixed.appendChild(popbox);

insertAndExecute(popbox_id, html);

if(pixel_cb) {
    var d=new Image(1,1);
    d.src=pixel_cb;
}

on(close_span, 'click', remove);

}

function on(el, eventName, handler) {
if (el.addEventListener) {
el.addEventListener(eventName, handler);
} else {
el.attachEvent('on' + eventName, function() {
handler.call(el);
});
}
}
function rmon(el, eventName, handler) {
if (el.addEventListener) {
el.removeEventListener(eventName, handler);
} else {
el.removeEvent('on' + eventName, function() {
handler.call(el);
});
}
}

function remove() {
var elem;
elem = doc.getElementById(overlay_wrapper_id); elem.parentElement.removeChild(elem);
elem = doc.getElementById(content_fixed_id); elem.parentElement.removeChild(elem);
}


var sessionManager = {
showIntentAd: function() {
if(window.sessionStorage.getItem(namespace)) {
if(sessionManager.isTimerExpired()) {
sessionManager.setIntentTimer();
return true;
} else {
return false;
}
} else {
sessionManager.setIntentTimer();
return true;
}
}.bind(this),
setIntentTimer: function() {
var new_timer = new Date().getTime() + (intent_timeout * 1000);
window.sessionStorage.setItem(namespace, new_timer);
}.bind(this),
isIntentTimerExpired: function() {
return new Date().getTime() >= sessionManager.getTimer();
}.bind(this),
getTimer: function() {
if(window.sessionStorage.getItem(namespace)) {
return parseInt(window.sessionStorage.getItem(namespace));
} else {
var new_timer = new Date().getTime() + (timer * 1000);
window.sessionStorage.setItem(namespace, new_timer);
return new_timer;
}
}.bind(this),
isTimerExpired: function() {
return new Date().getTime() >= sessionManager.getTimer();
}.bind(this),
clearTimer: function() {
window.sessionStorage.setItem(namespace, new Date().getTime() + (timer * 1000));
}.bind(this)
};

var eventIntentManager =  {
mouseleave: function(e) {
if (e.clientY > intent_sensitivity) { return; }
_delayTimer = setTimeout(fire, delay);
},
mouseenter: function(e) {
if (_delayTimer) {
clearTimeout(_delayTimer);
_delayTimer = null;
}
},
keydown: function(e) {
if (disableKeydown) { return; }
else if(!e.metaKey || e.keyCode !== 76) { return; }

disableKeydown = true;
_delayTimer = setTimeout(fire, delay);
}

};

function fire() {
if(mode == 'intent') {
if(sessionManager.showIntentAd()) {
callback();
//disable();
}
}
}


function disable() {
rmon(_html, 'keydown', eventIntentManager.keydown);
rmon(_html, 'mouseleave', eventIntentManager.mouseleave);
rmon(_html, 'mouseenter', eventIntentManager.mouseenter);
}


function setDefault(_property, _default) {
return typeof _property === 'undefined' ? _default : _property;
}

function gen_guid() {
return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
s4() + '-' + s4() + s4() + s4();
}

function s4() {
return Math.floor((1 + Math.random()) * 0x10000)
.toString(16)
.substring(1);
}


    init();
}


var E = {};
E.code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
E.decode = function(str, utf8decode) {
    utf8decode =  (typeof utf8decode == 'undefined') ? false : utf8decode;
    var o1, o2, o3, h1, h2, h3, h4, bits, d=[], plain, coded;
    var b64 = E.code;

    coded = utf8decode ? Utf8.decode(str) : str;

    for (var c=0; c<coded.length; c+=4) {
        h1 = b64.indexOf(coded.charAt(c));
        h2 = b64.indexOf(coded.charAt(c+1));
        h3 = b64.indexOf(coded.charAt(c+2));
        h4 = b64.indexOf(coded.charAt(c+3));

        bits = h1<<18 | h2<<12 | h3<<6 | h4;

        o1 = bits>>>16 & 0xff;
        o2 = bits>>>8 & 0xff;
        o3 = bits & 0xff;

        d[c/4] = String.fromCharCode(o1, o2, o3);
// check for padding
        if (h4 == 0x40) d[c/4] = String.fromCharCode(o1, o2);
        if (h3 == 0x40) d[c/4] = String.fromCharCode(o1);
    }
    plain = d.join('');

    return utf8decode ? Utf8.decode(plain) : plain;
};
</script>