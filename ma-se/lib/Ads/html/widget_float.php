(function(){

var jQCH= $.noConflict(true);

jQCH(window).on("message onmessage", function(e) {
if(e.originalEvent.data == 'ad_not_found') {
jQCH('.mfp-close').trigger('click');
}
});

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

var f = {
prop: {
d: '<?php echo $instance['delay']; ?>',
lt: '<?php echo $instance['display_again']; ?>',
z: '<?php echo $ckey; ?>flot',
w: '<?php echo $ad['media_width']; ?>',
h: '<?php echo $ad['media_height']; ?>'
},
trigger: false,
ls: function() {
var ts = localStorage.getItem('z'+this.prop.z+'ts');

if(ts) {
if(new Date().getTime() > ts) {
localStorage.setItem('z'+this.prop.z+'ts', new Date().getTime() + (this.prop.lt * 1000));
this.trigger = true;
this.event();
}
} else {
localStorage.setItem('z'+this.prop.z+'ts', new Date().getTime() + (this.prop.lt * 1000));
this.trigger = true;
this.event();
}
},
event: function() {
if(this.trigger) {
this.trigger = false;
if(this.prop.d != 0) {
this.countdown();
localStorage.setItem('z'+this.prop.z+'countdown', 1);
} else {
this.float_open();
}
}

},
callback: function() {
var d=new Image(1,1);d.src="<?php echo $cb_pixel_view_url; ?>";
},
countdown: function() {
// window delay this.float_open();
var _this = this;
var delay = 'z'+this.prop.z+'delay';
if(!localStorage.getItem(delay)) {
localStorage.setItem(delay, new Date().getTime() + (this.prop.d * 1000));
}

window.interval_id_<?php echo $rng; ?> = window.setInterval(function() {
if(new Date().getTime() >= parseInt(localStorage.getItem(delay)) && localStorage.getItem(delay)) {
localStorage.removeItem(delay);
localStorage.removeItem('z'+_this.prop.z+'countdown');
_this.float_open();
clearInterval(window.interval_id_<?php echo $rng; ?>);
}
}, 3000);
},
init: function() {
if(localStorage.getItem('z'+this.prop.z+'countdown')) {
this.countdown();
}

var _this = this;
this.ls();
window.setInterval(function() {
_this.ls();
}, 5000);
},
float_open: function() {
this.callback();
jQCH.magnificPopup.open({
items: {
src: E.decode('<?php echo base64_encode('<div id="mfp-pop-'.$ckey.'" class="mfp-pop" style="width: '.($ad['media_width']+40).'px;"><button class="mfp-close">X</button><div style="width: '.$ad['media_width'].'px; height: '.$ad['media_height'].'px;">'.$ad_html.'</div></div>'); ?>'),
type: 'inline'
},
alignTop: false,
fixedContentPos: true,
closeBtnInside: true,
closeOnBgClick: false
});
}
};
f.init();

})();