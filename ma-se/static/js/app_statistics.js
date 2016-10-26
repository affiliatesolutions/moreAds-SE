jQuery(function ($) {
    var STATISTICS = {
        body: $('body'),
        datatable_selector: '#mase_datatable_stats',
        datatable_search_selector: '#mase_datatable_stats_wrapper input[type=search]',
        datatable_search_update_selector: '.mase_stats_update',
        datatable_element: '',
        form_selector: '#mase_stats_form',
        select2_device_selector: '#MASE_selectDeviceIdTable',
        select2_user_selector: '#MASE_selectUserIdTable',
        select2_connection_selector: '#MASE_selectConnectionIdTable',
        select2_country_selector: '#MASE_selectCountryIdTable',
        select2_domain_selector: '#MASE_selectDomainIdTable',
        select2_time_selector: '#MASE_selectTimerangeTable',
        select2_domain_options: {
            minimumInputLength: 0,
            delay: 550,
            ajax: {
                url: mase_app.ajax_url,
                dataType: 'json',
                type: 'GET',
                debug: true,
                data: function (params) {
                    return {
                        action: 'mase_log_domains',
                        q: params.term
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.domain,
                                id: item.id
                            }
                        })
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.domain,
                                id: item.id
                            }
                        })
                    };
                }
            }
        },
        select2_device_options: {
            minimumInputLength: 0,
            delay: 550,
            ajax: {
                url: mase_app.ajax_url,
                dataType: 'json',
                type: 'GET',
                debug: true,
                data: function (params) {
                    return {
                        action: 'mase_log_devices',
                        q: params.term
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                }
            }
        },
        select2_connection_options: {
            minimumInputLength: 0,
            delay: 550,
            ajax: {
                url: mase_app.ajax_url,
                dataType: 'json',
                type: 'GET',
                debug: true,
                data: function (params) {
                    return {
                        action: 'mase_log_connections',
                        q: params.term
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                }
            }
        },
        select2_user_options: {
            minimumInputLength: 0,
            delay: 550,
            ajax: {
                url: mase_app.ajax_url,
                dataType: 'json',
                type: 'GET',
                debug: true,
                data: function (params) {
                    return {
                        action: 'mase_log_users',
                        q: params.term
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                }
            }
        },
        select2_country_options: {
            minimumInputLength: 0,
            delay: 550,
            ajax: {
                url: mase_app.ajax_url,
                dataType: 'json',
                type: 'GET',
                debug: true,
                data: function (params) {
                    return {
                        action: 'mase_log_countries',
                        q: params.term
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            var key = 'name';
                            if(mase_app.lng == 'de_DE') {
                                key = 'name_de';
                            }
                            if(item["cc"]=="XX"){
                                option=mase_app.unknown
                            }
                            else{
                                option=item["cc"]
                            }
                            return {
                                text: option,
                                id: item.id
                            }
                        })
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            var key = 'name';
                            if(mase_app.lng == 'de_DE') {
                                key = 'name_de';
                            }
                            if(item["cc"]=="XX"){
                                option=mase_app.unknown
                            }
                            else{
                                option=item["cc"]
                            }
                            return {
                                text: option,
                                id: item.id
                            }
                        })
                    };
                }
            }
        },
        datatable_options: {
            dom: 'Bfrtlp',
            buttons: [
                {
                    extend: 'csv',
                    text: 'CSV Export',
                }
            ],
            "search": { "caseInsensitive": true},
            paging: true,
            "iDisplayLength": 10,
            "order": [[ 0, "desc" ]],
            "aoColumns": [
                null,
                null,
                null,
                null,
                null,
                { "orderSequence": [ "asc", "desc"] }, // 5
                { "orderSequence": [ "desc", "asc" ] }, // 6
                { "orderSequence": [ "asc", "desc"] }, // 7
                { "orderSequence": [ "asc", "desc" ] }, // 8
                { "orderSequence": [ "desc", "asc" ] }, // 9
                { "orderSequence": [ "desc", "asc" ] }, // 10
                { "orderSequence": [ "desc", "asc" ] }, // 11
                { "orderSequence": [ "desc", "asc"] }, // 12
                { "bSortable": false }, // 13 actions
            ],
            "columnDefs": [
                { "orderSequence": ["desc","asc"], "targets":[ 0,1,2,3,4 ] },
                { "searchable": false, "targets": [9,10,11,13] },
                { "className": "dt-right", "targets": [ 2,3,4,5,6,7,8,9,10,11,12 ] },
                { "className": "dt-center", "targets": [ 13 ] },
                { "targets": [0,5,6,7,8,9], "visible": false }
            ],
            "processing": true,
            "serverSide": true,
            "ajax": mase_app.ajax_url+'?action=mase_stats_query&'+$(this.form_selector).serialize(),
            "footerCallback": function ( row, data, start, end, display ) {
                Number.prototype.formatMoney = function(c, d, t){
                    var n = this,
                        c = isNaN(c = Math.abs(c)) ? 2 : c,
                        d = d == undefined ? "," : d,
                        t = t == undefined ? "." : t,
                        s = n < 0 ? "-" : "",
                        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                        j = (j = i.length) > 3 ? j % 3 : 0;
                    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
                };
                var intVal = function (i) {
                    return typeof i === 'string' ?
                    i.replace(/[\€]/g, '').replace(",",".") * 1 :
                        typeof i === 'number' ?
                            parseFloat(i) : 0;
                };



                var api = this.api(), data;
                var totals = new Array();
                var pagetotals = new Array();
                var fields = new Array();
                fields[9]="sumViews";
                fields[10]="sumClicks";
                fields[11]="sumCtr";

                for (i = 9; i < 13; i++) {
                    // Total over this page
                    j=i-1;
                    if (api.column(i).data().length) {
                        pagetotals[j] = api
                            .column(i, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(intVal(a) + intVal(b));
                            }, 0);
                    } else {
                        pagetotals[j] = 0;
                    }
                    if(isNaN(pagetotals[j])){
                        pagetotals[j]=0;
                    }
                }
                for (i = 9; i < 12; i++) {
                    j=i+1;
                    $(api.column(j).footer()).html(
                        '' + pagetotals[i].formatMoney(0) + ' <br />(' + api.ajax.json()[fields[i]].formatMoney(0) + '&nbsp;total)'
                    );
                }


                $(api.column(12).footer()).html(
                    '' + ((pagetotals[10] / pagetotals[9])*100).toFixed(2) + '% <br />(' + api.ajax.json()[fields[11]].toFixed(2) + '%&nbsp;total)'
                );

            }
        },
        init: function() {
            if(mase_app.lng = 'de_DE') {
                this.datatable_options["lengthMenu"] = [[10, 25, 50, -1], [10, 25, 50, 'Alle']];
            } else {
                this.datatable_options["lengthMenu"] = [[10, 25, 50, -1], [10, 25, 50, 'All']];
            }

            $(this.select2_domain_selector).mase_s2(this.select2_domain_options).on('change', this.updateView.bind(this));
            $(this.select2_device_selector).mase_s2(this.select2_device_options).on('change', this.updateView.bind(this));
            $(this.select2_user_selector).mase_s2(this.select2_user_options).on('change', this.updateView.bind(this));
            $(this.select2_connection_selector).mase_s2(this.select2_connection_options).on('change', this.updateView.bind(this));
            $(this.select2_country_selector).mase_s2(this.select2_country_options).on('change', this.updateView.bind(this));
            $(this.select2_time_selector).on('change', this.updateView.bind(this));
            $('body').on('click', this.datatable_search_update_selector, this.updateSearchEvent.bind(this));


            if(mase_app.lng == "de_DE") {
                this.datatable_options['language'] = $.parseJSON('{"sEmptyTable":"Keine Daten in der Tabelle vorhanden","sInfo":"_START_ bis _END_ von _TOTAL_ Einträgen","sInfoEmpty":"0 bis 0 von 0 Einträgen","sInfoFiltered":"(gefiltert von _MAX_ Einträgen)","sInfoPostFix":"","sInfoThousands":".","sLengthMenu":"_MENU_ Einträge anzeigen","sLoadingRecords":"Wird geladen...","sProcessing":"Bitte warten...","sSearch":"Suchen","sZeroRecords":"Keine Einträge vorhanden.","oPaginate":{"sFirst":"Erste","sPrevious":"Zurück","sNext":"Nächste","sLast":"Letzte"},"oAria":{"sSortAscending":": aktivieren, um Spalte aufsteigend zu sortieren","sSortDescending":": aktivieren, um Spalte absteigend zu sortieren"}}')
            }

            this.datatable_options['language']['sProcessing'] = '<img src="'+mase_app.mase_url+'static/img/ajax-loader.gif" /> Loading ...';

            this.datatable_element = $(this.datatable_selector).MaseTable(this.datatable_options);
            this.datatable_element.buttons().container().appendTo( $('.col-sm-6:eq(0)', this.datatable_element.table().container() ) );

            $('a.toggle-vis').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('clicked');
                $(this).toggleClass('active');



                // Get the column API object
                var column = STATISTICS.datatable_element.column($(this).attr('data-column'));

                // Toggle the visibility
                column.visible(!column.visible());
                STATISTICS.selectGroupAndFetchData();
            });

        },
        updateView: function() {
            STATISTICS.selectGroupAndFetchData();
        },
        updateSearchEvent: function(e) {
            var value = $(e.target).data('value');
            if(value != "") $(this.datatable_search_selector).val(value);
            $(this.datatable_search_selector).trigger('keyup');
        },
        selectGroupAndFetchData: function(){
            group =Array();
            $('a.toogle-group').each(function(index, elem){
                if(!$(elem).hasClass("clicked")){
                    group.push($(elem).data("field"))
                }
            });
            url=mase_app.ajax_url+'?action=mase_stats_query&'+
                $(this.form_selector).serialize()+"&group=" +group.join(),
            this.datatable_element.ajax.url(url).load();
        }
    };

    STATISTICS.init();
});