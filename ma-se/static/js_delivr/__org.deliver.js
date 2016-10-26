(function(funcName, baseObj) {
    // The public function name defaults to window.docReady
    // but you can pass in your own object and own function name and those will be used
    // if you want to put them in a different namespace
    funcName = funcName || "docReady";
    baseObj = baseObj || window;
    var readyList = [];
    var readyFired = false;
    var readyEventHandlersInstalled = false;

    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (var i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }

    function readyStateChange() {
        if ( document.readyState === "complete" ) {
            ready();
        }
    }

    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete") {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("docReady", window);

var X = {};
X.code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
X.decode = function(str, utf8decode) {
    utf8decode =  (typeof utf8decode == 'undefined') ? false : utf8decode;
    var o1, o2, o3, h1, h2, h3, h4, bits, d=[], plain, coded;
    var b64 = X.code;

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
        if (h4 == 0x40) d[c/4] = String.fromCharCode(o1, o2);
        if (h3 == 0x40) d[c/4] = String.fromCharCode(o1);
    }
    plain = d.join('');

    return utf8decode ? Utf8.decode(plain) : plain;
};
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

function deliver_handler(blk, ids) {
    window.dom_ready = false;
    docReady(function() { window.dom_ready = true; });

    ids = JSON.parse(X.decode(ids));
    atomic.setContentType('application/json');
    atomic.post(mase_ajaxurl+'?action=mase_get_widgets&ab='+blk.toString(), JSON.stringify(ids))
        .success(function (data, xhr) {
            var my_interval_id = window.setInterval(function() {
                if(window.dom_ready) {
                    clearInterval(my_interval_id);
                    try {
                        for(var k in data) {
                            if(data[k]['m'] == 'iframe') {
                                var iframe = document.createElement('iframe');
                                iframe.setAttribute('scrolling', 'no');
                                iframe.setAttribute('frameborder', '0');
                                iframe.setAttribute('allowtransparency', 'true');
                                iframe.setAttribute('allowfullscreen', 'true');
                                iframe.setAttribute('marginwidth', '0');
                                iframe.setAttribute('marginheight', '0');
                                iframe.setAttribute('vspace', '0');
                                iframe.setAttribute('hspace', '0');
                                iframe.setAttribute('width', data[k]['mo']['w']);
                                iframe.setAttribute('height', data[k]['mo']['h']);
                                iframe.src="about:blank";
                                iframe.onload = function() {
                                    var domdoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
                                    domdoc.write(X.decode(data[this.k]['d']));
                                }.bind({k: k, iframe: iframe});
                                document.getElementById(k).appendChild(iframe);
                            } else {
                                document.getElementById(k).innerHTML = X.decode(data[k]['d']);
                                var scripts = document.getElementById(k).getElementsByTagName("script");
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
                        }
                    } catch(error) {

                    }
                }
            }, 10);
        });
}
deliver_handler(adb, IDS);