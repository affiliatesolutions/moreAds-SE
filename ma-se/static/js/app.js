jQuery(function ($) {
    var MASE_DECODE = {};
    MASE_DECODE.code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    MASE_DECODE.decode = function(str, utf8decode) {
        utf8decode =  (typeof utf8decode == 'undefined') ? false : utf8decode;
        var o1, o2, o3, h1, h2, h3, h4, bits, d=[], plain, coded;
        var b64 = MASE_DECODE.code;

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

    var MASE = {
        modal: '<div class="mase-bs">' +
                    '<div id="mase_zone_modal" class="modal large fade in" aria-hidden="false" style="z-index: 10000;">' +
                        '<div class="modal-dialog" style="width: 95%;">' +
                            '<div class="modal-content">' +
                                '<div class="modal-header">' +
                                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                                    '<h4 class="modal-title">Confirmation</h4>' +
                                '</div>' +
                                '<div class="modal-body">' +
                                    '<p>Loading ...</p>' +
                                '</div>' +
                                '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                                    '<button type="button" class="btn btn-primary">Save changes</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>',
        body: $('body'),
        country_selector: '.mase_country_select',
        country_select2_options: { tags: true, width: '100%' },
        country_quickselect_dachplus_selector: '.mase_quickselect_dachplus',
        country_quickselect_dachminus_selector: '.mase_quickselect_dachminus',
        country_quickselect_all_selector: '.mase_quickselect_all',
        country_quickselect_none_selector: '.mase_quickselect_none',
        country_quickselect_admin_selector: '.mase_quickselect_admin',
        tooltip_html_selector: '.mase_html_tooltip',
        select2_simple_selector: '.mase_select2_simple',
        select2_simple_options: {minimumResultsForSearch: Infinity },
        modal_selector: '#mase_zone_modal',
        init: function() {
            $.ajaxSetup ({ cache: false });
            this.register_event_handlers();

        },
        onready: function() {
            $(this.country_selector).mase_s2(this.country_select2_options);

            $(this.select2_simple_selector).each(function(idx, element) {
                if($(element).parents('div#widgets-left').length == 0) {
                    $(element).mase_s2(this.select2_simple_options);
                }
            }.bind(this));

            $(this.body).on('click', this.tooltip_html_selector, function(e){
                e.preventDefault();
            });

            $(this.body).popover({
                template: '<div class="popover mase-bs" style="max-width: none !important;" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
                content: function() {
                    return MASE_DECODE.decode($(this).data('html'));
                },
                title: mase_app.t.preview,
                trigger: 'hover',
                html: true,
                placement: 'left',
                selector: this.tooltip_html_selector
            });

            $(document).ready(function() {
                $(".btn-pref .btn").click(function () {
                    $(".btn-pref .btn").removeClass("btn-info").addClass("btn-default");
                    $(this).removeClass("btn-default").addClass("btn-info");
                });

                $(window).on('hashchange', function() {
                    if(window.location.hash == '#tab2' && jQuery('button[href="'+window.location.hash+'"]')) {
                        jQuery('button[href="'+window.location.hash+'"]').trigger('click');
                    }
                });
                if(window.location.hash == '#tab2' && jQuery('button[href="'+window.location.hash+'"]')) {
                    jQuery('button[href="'+window.location.hash+'"]').trigger('click');
                }


                $(".mase-menu-zone-chkbox:checked").each(function(idx, element) {
                    $('#edit-menu-item-url-'+$(element).data('id')).attr('disabled', 'DISABLED');
                });

            });

        },
        register_event_handlers: function() {
            $(document).ready(this.onready.bind(this));
            this.body.on('click', '.mase_zone_configurator', this.open_zone_configurator.bind(this));
            this.body.on('hidden.bs.modal', this.modal_selector, function() { $('body').css('overflow', 'auto'); $(this.remove()) });
            this.body.on('hide.bs.modal', this.modal_selector, function() { $('body').css('overflow', 'auto'); $(this.remove()) });
            this.body.on('click', this.country_quickselect_dachplus_selector, this.event_country_quickselect_dachplus.bind(this));
            this.body.on('click', this.country_quickselect_dachminus_selector, this.event_country_quickselect_dachminus.bind(this));
            this.body.on('click', this.country_quickselect_all_selector, this.event_country_quickselect_all.bind(this));
            this.body.on('click', this.country_quickselect_none_selector, this.event_country_quickselect_none.bind(this));
            this.body.on('click', this.country_quickselect_admin_selector, this.event_country_quickselect_admin.bind(this));
            this.body.on('change', '.mase-menu-zone-chkbox', this.event_menu_chkbox.bind(this));
            this.body.on('click', '#mase-remove-license', this.event_remove_license.bind(this));
            $(document).on('widget-updated widget-added', this.handle_wp_widget_events.bind(this));
        },
        event_remove_license: function(e) {
            if(confirm(mase_app.remove_license_key)) {
                window.location.href = "?page=mase_menu&remove-lic-key=1";
                return false;
            }

        },
        event_menu_chkbox: function(e) {
            var id = $(e.target).data('id');

            $(this.select2_simple_selector).each(function(idx, element) {
                if($(element).parents('div#widgets-left').length == 0) {
                    $(element).mase_s2(this.select2_simple_options);
                }
            }.bind(this));

            if($(e.target).is(':checked')) {
                $('.mase-menu-element-'+id.toString()).show();
                //$('#edit-menu-item-url-'+id.toString()).attr('disabled', 'DISABLED');
            } else {
                $('.mase-menu-element-'+id.toString()).hide();
                //$('#edit-menu-item-url-'+id.toString()).removeAttr('disabled');
            }
        },

        handle_wp_widget_events: function(event, widget_element) {
            $(widget_element).find(this.select2_simple_selector).mase_s2(this.select2_simple_options);
        },
        event_country_quickselect_dachplus: function(e) {
            var select = $(e.target).closest('div.mase-container').find('select'+this.country_selector);
            var options = $(e.target).closest('div.mase-container').find('select'+this.country_selector+' option');

            $.each(options, function(idx, element) {
                if($(element).val() == 'DE' || $(element).val() == 'AT' || $(element).val() == 'CH') {
                    $(element).attr('selected', 'SELECTED');
                } else {
                    $(element).removeAttr('selected');
                }
            });
            select.trigger('change');
        },
        event_country_quickselect_dachminus: function(e) {
            var select = $(e.target).closest('div.mase-container').find('select'+this.country_selector);
            var options = $(e.target).closest('div.mase-container').find('select'+this.country_selector+' option');

            $.each(options, function(idx, element) {
                if($(element).val() == 'DE' || $(element).val() == 'AT' || $(element).val() == 'CH') {
                    $(element).removeAttr('selected');
                } else {
                    $(element).attr('selected', 'SELECTED');
                }
            });
            select.trigger('change');
        },
        event_country_quickselect_all: function(e) {
            var select = $(e.target).closest('div.mase-container').find('select'+this.country_selector);
            var options = $(e.target).closest('div.mase-container').find('select'+this.country_selector+' option');

            $.each(options, function(idx, element) {
                $(element).attr('selected', 'SELECTED');

            });
            select.trigger('change');
        },
        event_country_quickselect_none: function(e) {
            var select = $(e.target).closest('div.mase-container').find('select'+this.country_selector);
            var options = $(e.target).closest('div.mase-container').find('select'+this.country_selector+' option');

            $.each(options, function(idx, element) {
                $(element).removeAttr('selected');

            });
            select.trigger('change');
        },
        event_country_quickselect_admin: function(e) {
            var select = $(e.target).closest('div.mase-container').find('select'+this.country_selector);
            var options = $(e.target).closest('div.mase-container').find('select'+this.country_selector+' option');

            $.each(options, function(idx, element) {
                if($(element).val() == mase_app.admin_cc) {
                    $(element).attr('selected', 'SELECTED');
                } else {
                    $(element).removeAttr('selected');
                }
            });
            select.trigger('change');
        },
        open_zone_configurator: function(e) {
            var widget = $(e.target.form).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            var query;

            if($(e.target).data('boja')) {

                this.body.append(this.modal);
                query = {
                    action: 'boja_zone',
                    id: $(e.target).data('id')
                };
                $(this.modal_selector).modal({show:true, remote: mase_app.ajax_url+'?'+jQuery.param(query)});
                $('.modal-backdrop').removeClass("modal-backdrop");
            } else if($(e.target).data('uare')) {

                this.body.append(this.modal);
                query = {
                    action: 'uare_zone',
                    id: $(e.target).data('id')
                };
                $(this.modal_selector).modal({show:true, remote: mase_app.ajax_url+'?'+jQuery.param(query)});
                $('.modal-backdrop').removeClass("modal-backdrop");

            } else if($(e.target).data('id')) {

                this.body.append(this.modal);
                query = {
                    menu_item_id: $(e.target).data('id'),
                    action: 'mase_menu_zone'
                };
                $(this.modal_selector).modal({show:true, remote: mase_app.ajax_url+'?'+jQuery.param(query)});

            } else {
                if(widget.widget_number == "-1") widget.widget_number = widget.multi_number;
                if(parseInt(widget.multi_number) > parseInt(widget.widget_number)) widget.widget_number = widget.multi_number;
                this.body.append(this.modal);
                query = {
                    widget_number: widget.widget_number,
                    widget_id: widget['widget-id']
                };

                if(widget.id_base =="mase_popup_widget") {
                    query.action = 'mase_popup_zone';
                } else if(widget.id_base =="mase_textlink_widget") {
                    query.action = 'mase_textlink_zone';
                } else if(widget.id_base =="mase_exitintent_widget") {
                    query.action = 'mase_exitintent_zone';
                } else if(widget.id_base =="mase_float_widget") {
                    query.action = 'mase_float_zone';
                    query.size = widget['widget-'+widget.id_base+'['+widget.widget_number+'][size]'];
                } else {
                    query.action = 'mase_banner_zone';
                    query.size = widget['widget-'+widget.id_base+'['+widget.widget_number+'][size]'];
                }

                $(this.modal_selector).modal({show:true, remote: mase_app.ajax_url+'?'+jQuery.param(query)});
            }
            return false;
        }
    };

    MASE.init();
});

jQuery(document).on( 'click', '.mase-country-notice .notice-dismiss', function() {

    jQuery.ajax({
        url: mase_app.ajax_url,
        data: {
            action: 'mase_dismiss_country_notice'
        }
    })
});