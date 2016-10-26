(function(){
var p = {
prop: {
l: '<?php echo $target; ?>',
t: 'n0z8h',
lt: parseInt('<?php echo $instance['lifetime']; ?>'),
z: '<?php echo $ckey; ?>',
pt: 1, // popunder popover??
w: parseInt('<?php echo $instance['width']; ?>'),
h: parseInt('<?php echo $instance['height']; ?>')
},
trigger: false,
ls_avail: function() {
var test = 'test';
try {
localStorage.setItem(test, test);
localStorage.removeItem(test);
return true;
} catch(e) {
return false;
}
},
callback: function() {
    var d=new Image(1,1);d.src="<?php echo $cb_pixel_view_url; ?>";
},
ls: function() {
var ts = localStorage.getItem('z'+this.prop.z+'ts');

if(ts) {
if(new Date().getTime() > ts) {
localStorage.setItem('z'+this.prop.z+'ts', new Date().getTime() + (this.prop.lt * 60 * 1000));
this.event(this.prop.t);
this.trigger = true;
localStorage.setItem('z'+this.prop.z+'t', this.prop.t);
}
} else {
localStorage.setItem('z'+this.prop.z+'ts', new Date().getTime() + (this.prop.lt * 60 * 1000));
this.event(this.prop.t);
this.trigger = true;
localStorage.setItem('z'+this.prop.z+'t', this.prop.t);
}
},
event: function(token) {
var _this = this;
var url = this.prop.l.replace('{TOKEN}', token);

// Chrome, IE
if(document.getElementsByTagName) onload = function(){
document.getElementsByTagName("BODY")[0].onclick = function () {
if(_this.trigger) {
_this.pop_open(url, _this.prop.pt, token);
}
}
};

// Firefox, Opera, Chrome
window.onclick = function() {
if(_this.trigger) {
_this.pop_open(url, _this.prop.pt);
}
}

},
c: function() { // cOOKIE VERSION
if(!this.check_cookie('z'+this.prop.z+'ts')) {
this.set_cookie('z'+this.prop.z+'ts', this.prop.lt);
this.event(this.prop.t);
this.trigger = true;
}
},
init: function() {
if(this.ls_avail()) {
var _this = this;
this.ls();
window.setInterval(function() {
_this.ls();
}, 10000);
} else {
this.c();
var _thiss = this;
window.setInterval(function() {
_thiss.c();
}, 10000);
}
},
pop_open: function(url, type, token) {
var padding;
(navigator.appName == "Microsoft Internet Explorer") ? (padding = 10) : (padding = 0);

this.callback();

var menubar = 'yes';
var locationbar = 'no';
var statusbar = 'yes';
var resizable = 'yes';
var toolbar = 'no';
var scrollbars = 'yes';
var w = this.prop.w;
var h = this.prop.h;

var screenw = screen.availWidth;
var screenh = screen.availHeight;
var winw = (w + 15 + padding);
var winh = (h + 15 + padding);
var posx = (screenw / 2) - (winw / 2);
var posy = (screenh / 2) - (winh / 2);

var MyWindow = window.open(url, '<?php echo $ckey ?>addakdasklasdjasdljasdjlasdjkladjkldasjklasdjk','top='+posy+',left='+posx+',width='+winw+',height='+winh+',menubar=' + menubar +',locationbar=' + locationbar + ',statusbar=' + statusbar + ',resizable=' + resizable + ',toolbar=' + toolbar + ',dependent=yes,scrollbars=' + scrollbars);
this.trigger = false;

if(type == 1) {
MyWindow.blur();
window.focus();
}

},
check_cookie: function(cookie_name) {
var nameEQ = cookie_name + "=";
var ca = document.cookie.split(';');
for(var i=0;i < ca.length;i++) {
var c = ca[i];
while (c.charAt(0)==' ') c = c.substring(1,c.length);
if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
}
return null;
},
set_cookie: function(cookie_name, cookie_lifetime) {
var a = new Date();
a = new Date(a.getTime() + 1000 * 60 * cookie_lifetime);
document.cookie = cookie_name + '=1; expires=' + a.toGMTString() + '; path=/';
}


};
p.init();
})();