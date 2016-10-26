<div class="mase-bs" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <h4 class="panel-title" style="line-height: 35px; font-size: 15px; font-weight: bold;"><?php _e('Statistics <span style="display: inline-block">for Global Ads</span>', MASE_TEXT_DOMAIN); ?> <small>(<?php _e('Hourly updates', MASE_TEXT_DOMAIN) ?>)</small></h4>
                    </div>
                    <div class="col-xs-9 text-right" style="padding: 0 0;">
                        <form id="mase_stats_form">
                        <div style="display: inline-block; margin-right: 3px;">
                            <select class="mase_select2_simple" style="width:130px;" id="MASE_selectTimerangeTable" name="timerange">
                                <option value="today"><?php _e('Today', MASE_TEXT_DOMAIN); ?></option>
                                <option value="yesterday"><?php _e('Yesterday', MASE_TEXT_DOMAIN); ?></option>
                                <option value="thisweek"><?php _e('This Week', MASE_TEXT_DOMAIN); ?></option>
                                <option value="lastweek"><?php _e('Last Week', MASE_TEXT_DOMAIN); ?></option>
                                <option selected="SELECTED" value="thismonth"><?php _e('This Month', MASE_TEXT_DOMAIN); ?></option>
                                <option value="lastmonth"><?php _e('Last Month', MASE_TEXT_DOMAIN); ?></option>
                                <option value="last30days"><?php _e('Last 30 Days', MASE_TEXT_DOMAIN); ?></option>
                                <option value="last60days"><?php _e('Last 60 Days', MASE_TEXT_DOMAIN); ?></option>
                                <option value="last90days"><?php _e('Last 90 Days', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>

                        <div style="display: inline-block; margin-right: 3px; margin-top: 5px;">
                            <select name="domain_id" id="MASE_selectDomainIdTable" class="form-control" style="width: 300px;">
                                <option value="-1"><?php _e('All Domains', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>

                        <div style="display: inline-block; margin-right: 3px; margin-top: 5px;">
                            <select class="form-control" style="width:130px" id="MASE_selectDeviceIdTable" name="device_id">
                                <option value="-1"><?php _e('All Devices', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>

                        <div style="display: inline-block; margin-right: 3px;  margin-top: 5px;">
                            <select class="form-control" style="width:130px" id="MASE_selectUserIdTable" name="adblock_id">
                                <option value="-1"><?php _e('All Users', MASE_TEXT_DOMAIN); ?></option>
                                <option value="0"><?php _e('Normal Users', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>

                        <div style="display: inline-block; margin-right: 3px; margin-top: 5px;">
                            <select class="form-control" style="width:130px" id="MASE_selectConnectionIdTable" name="connection_id">
                                <option value="-1"><?php _e('All Connections', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>

                        <div style="display: inline-block; margin-right: 3px;  margin-top: 5px;">
                            <select class="select2" style="width: 100px;" id="MASE_selectCountryIdTable" name="country_id">
                                <option value="-1"><?php _e('All Countries', MASE_TEXT_DOMAIN); ?></option>
                            </select>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-9 pull-right" style="padding: 0 0; margin-top: 5px;">
                        <div style="float:right; class="dt-buttons">
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group active" data-column="1" data-field="ad_title" id="ad_title">
                                <?php _e('Ad Title', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group active" data-column="2" data-field="ad_tags" id="ad_tags">
                                <?php _e('Ad Tags', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group active" data-column="3" data-field="ad_size" id="ad_size">
                                <?php _e('Ad Size', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group active" data-column="4" data-field="ad_type" id="ad_type">
                                <?php _e('Ad Type', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group clicked" data-column="5" data-field="domain" id="domain">
                                <?php _e('Domain', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group clicked" data-column="6" data-field="device_id" id="device_id">
                                <?php _e('Device', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group clicked" data-column="7" data-field="adblock_id" id="adblock_id">
                                <?php _e('Visitors', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group clicked" data-column="8" data-field="connection_id" id="connection_id">
                                <?php _e('Connection', MASE_TEXT_DOMAIN); ?>
                            </a>
                            <a class="dt-button buttons-columnVisibility toggle-vis toogle-group clicked" data-column="9" data-field="cc" id="cc">
                                <?php _e('Country', MASE_TEXT_DOMAIN); ?>
                            </a>
                        </div>
                        <span style="float:right; margin-right: 10px;" class=""><b><?php _e('Group By Columns', MASE_TEXT_DOMAIN); ?> </b></span>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="tablewrapper">

                    <table id="mase_datatable_stats" class="table table-condensed table-bordered table-responsive" style="font-size: 12px">
                        <thead>
                        <tr>
                            <th>Ad Id</th>
                            <th><?php _e('Ad Title', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Ad Tags', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Ad Size', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Ad Type', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Domain', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Device', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Visitors', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Connection', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Country', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Impressions', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('Clicks', MASE_TEXT_DOMAIN); ?></th>
                            <th><?php _e('CTR', MASE_TEXT_DOMAIN); ?></th>
                            <th style="width: 25px !important;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tfoot>
                        <tr>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:center;"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>