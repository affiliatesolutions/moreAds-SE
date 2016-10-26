jQuery(function ($) {
    var MASEZONEMGR = {
        body: $('body'),
        modal_selector: '#mase_zone_modal',
        init: function() {
            $.ajaxSetup ({ cache: false });
            this.register_event_handlers();
            this.responsive_modal_init();

        },
        responsive_modal_init: function() {
            var that = this;
            $(window).on('show.bs.modal', function() {
                that.setModalMaxHeight();
                setTimeout(function() { that.setModalMaxHeight(); }, 500);
                setTimeout(function() { that.setModalMaxHeight(); }, 1500);
                setTimeout(function() { that.setModalMaxHeight(); }, 3500);
            });

            $(window).on('shown.bs.modal', function() {
                that.setModalMaxHeight();
                setTimeout(function() { that.setModalMaxHeight(); }, 500);
                setTimeout(function() { that.setModalMaxHeight(); }, 1500);
                setTimeout(function() { that.setModalMaxHeight(); }, 3500);
            });

            $(window).resize(function() {
                if ($('.modal.in').length != 0) {
                    that.setModalMaxHeight();
                }
            });
        },
        setModalMaxHeight: function() {
            this.$element     = $('#mase_zone_modal');
            this.$content     = this.$element.find('.modal-content');
            var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
            var dialogMargin  = $(window).width() > 767 ? 60 : 20;
            var contentHeight = $(window).height() - (dialogMargin + borderWidth);
            var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
            var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
            var maxHeight     = contentHeight - (headerHeight + footerHeight);

            this.$content.css({
                'overflow': 'hidden'
            });

            this.$element.find('.modal-body').css({
                    'max-height': maxHeight,
                    'overflow-y': 'auto'
                });

        },
        toggle_vis_event: function(e) {
            e.preventDefault();
            this.toogle_vis($(e.target).attr('data-column'));
        },
        toggle_vis: function(name) {
            var column = table.column( name );
            column.visible( ! column.visible() );
        },
        register_event_handlers: function() {
            this.body.on('submit', 'form.mase_zone_configurator_form', this.update_zone.bind(this));
            this.body.on('click', '.zone_search_term', this.searchTerm.bind(this));
            this.body.on('click', '.mase_ad_select_chkbox', this.ad_select_handler.bind(this));
            $(window).on('zonemgr_draw_event', this.zonemgr_draw_event.bind(this));
        },
        zonemgr_draw_event: function(e, tbl) {
            $.each($(tbl).find('.mase_ad_select_chkbox'), function(idx, chkbox) {
                if($(chkbox).prop('checked')) {
                    this.ad_checkbox_handler(chkbox);
                }
            }.bind(this));
        },
        ad_select_handler: function(e) {
            this.ad_checkbox_handler(e.target);
        },
        ad_checkbox_handler: function(checkbox) {
            if($(checkbox).prop('checked')) {

                $.each($(checkbox).closest('tr').find('.mase_hours_of_day_select'), function(idx, element) {
                    $(element).multiselect({
                        maxHeight: 200,
                        includeSelectAllOption: true,
                        selectAllText: mase_app.hod_select_all,
                        buttonText: function(options, select) {
                            if (options.length == 0) {
                                return mase_app.none;
                            }
                            else if (options.length > 3) {
                                return options.length + ' ' + mase_app.selected + ' ';
                            }
                            else {
                                var selected = '';
                                options.each(function() {
                                    selected += $(this).text() + ', ';
                                });
                                return selected.substr(0, selected.length -2) + ' ';
                            }
                        }
                    });
                });

                $.each($(checkbox).closest('tr').find('.mase_days_of_week_select'), function(idx, element) {
                    $(element).multiselect({
                        maxHeight: 200,
                        includeSelectAllOption: true,
                        selectAllText: mase_app.dow_select_all,
                        buttonText: function(options, select) {
                            if (options.length == 0) {
                                return mase_app.none + '';
                            }
                            else if (options.length > 3) {
                                return options.length + ' ' + mase_app.selected + '';
                            }
                            else {
                                var selected = '';
                                options.each(function() {
                                    selected += $(this).text() + ', ';
                                });
                                return selected.substr(0, selected.length -2) + '';
                            }
                        }
                    });
                });

                $.each($(checkbox).closest('tr').find('.mase_ad_weight_input'), function(idx, element) {
                    $(element).show();
                });

            } else {
                $.each($(checkbox).closest('tr').find('.mase_days_of_week_select'), function(idx, element) {
                    $(element).multiselect('destroy').hide();
                });
                $.each($(checkbox).closest('tr').find('.mase_hours_of_day_select'), function(idx, element) {
                    $(element).multiselect('destroy').hide();
                });

                $.each($(checkbox).closest('tr').find('.mase_ad_weight_input'), function(idx, element) {
                    $(element).hide();
                });

            }
        },
        searchTerm: function(e) {
            var datatable = $('#mase_zone_configurator_tbl').MaseTable();
            datatable.search($(e.target).data('value')).draw();
        },
        update_zone: function(e) {
            var datatable = $('#mase_zone_configurator_tbl').MaseTable();
            datatable.search('').draw();
            datatable.page.len( -1 ).draw();
            var zone_data = $(e.target).serializeArray().reduce(function(obj, item) {

                if(obj[item.name]) {
                    obj[item.name] = obj[item.name] + ',' + item.value;
                } else {
                    obj[item.name] = item.value;
                }

                return obj;
            }, {});


            $.ajax({
                method: "POST",
                url: mase_app.ajax_url+'?action='+zone_data.save_action,
                data: zone_data
            }).done(function() {
                var widget_save_btn_id = '#widget-'+zone_data.widget_id+'-savewidget';
                $(this.modal_selector).modal('hide');
                $(widget_save_btn_id).trigger('click');
            }.bind(this));

            return false;
        }
    };

    MASEZONEMGR.init();
});