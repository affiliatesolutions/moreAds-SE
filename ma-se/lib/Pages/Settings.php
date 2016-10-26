<div class="mase-bs" style="margin-top: 20px; width: 99%;">
    <div class="col-md-12">
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="stars" class="btn btn-info" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('General', MASE_TEXT_DOMAIN); ?></div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('Free Features', MASE_TEXT_DOMAIN); ?></div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('Premium Features', MASE_TEXT_DOMAIN); ?></div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="following" class="btn btn-default" href="#tab4" data-toggle="tab"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('Help', MASE_TEXT_DOMAIN); ?></div>
                </button>
            </div>
        </div>

        <div class="well">
            <div class="tab-content">
                <div class="tab-pane fade in active row" id="tab1">

                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('moreAds SE Status', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="min-height: 200px; font-size: 17px; padding: 0;">

                                    <table class="table table-striped" style="margin: 0;">
                                        <tbody>
                                        <tr>
                                            <td><?php _e('License', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <?php if(MASE_Pro::isPro()) { ?>
                                                    <span class="label label-success" style="font-size: 15px; "><?php _e('Premium Edition', MASE_TEXT_DOMAIN); ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-success" style="font-size: 15px; "><?php _e('Free Edition', MASE_TEXT_DOMAIN); ?></span>  [<a target="_blank" href="<?php _e('https://offer.affiliate-solutions.xyz/moreads-se-premium-en/', MASE_TEXT_DOMAIN); ?>"><?php _e('Upgrade now', MASE_TEXT_DOMAIN); ?></a>]
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Author', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                Affiliate solutions SLU
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Plugin URL', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <a href="<?php _e('https://wordpress.org/plugins/moreads-se/', MASE_TEXT_DOMAIN); ?>" target="_blank"><?php _e('https://wordpress.org/plugins/moreads-se/', MASE_TEXT_DOMAIN); ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Plugin Version', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <?php
                                                $data = get_plugin_data(MASE_PLUG_FILE);
                                                echo $data['Version'];
                                                ?>
                                            </td>
                                        </tr>

                                        <?php if(MASE_Pro::isSubscriptionActive()) { ?>
                                            <tr>
                                                <td><?php _e('Subscription Status', MASE_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <span class="label label-success" style="font-size: 15px;"><?php _e('Active', MASE_TEXT_DOMAIN); ?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><?php _e('Licenseable Domains', MASE_TEXT_DOMAIN); ?></td>
                                                <td>
                                                        <?php $sub = MASE_Pro::getSubscription(); ?>
                                                        <?php echo intval($sub->domain_limit) == 0 ? __('Unlimited', MASE_TEXT_DOMAIN) : intval($sub->domain_limit)  ?> (<?php echo intval($sub->domain_installed); ?> <?php _e(' in use', MASE_TEXT_DOMAIN); ?>)
                                                </td>
                                            </tr>
                                        <?php } elseif(MASE_Pro::isPro()) { ?>
                                            <tr>
                                                <td><?php _e('Subscription Status', MASE_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <span class="label label-danger" style="font-size: 15px;"><?php _e('Expired', MASE_TEXT_DOMAIN); ?></span>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <?php if(MASE_Pro::isSubscriptionActive()) { ?>
                                            <?php
                                            $sub = MASE_Pro::getSubscription();
                                            ?>
                                            <?php if(!empty($sub->subscription_end)) { ?>
                                                <tr>
                                                    <td><?php _e('Subscription Start', MASE_TEXT_DOMAIN); ?></td>
                                                    <td><?php echo $sub->subscription_start ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e('Subscription End', MASE_TEXT_DOMAIN); ?></td>
                                                    <td>
                                                        <?php if(empty($sub->subscription_end)) { ?>
                                                            <?php _e('Lifetime', MASE_TEXT_DOMAIN); ?>
                                                        <?php } else { ?>
                                                            <?php echo $sub->subscription_end ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if(MASE_Pro::isPro()) { ?>
                                            <tr>
                                                <td><?php _e('Remove License Key', MASE_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <button id="mase-remove-license" <?php if(!MASE_Pro::isPro() ) { echo 'disabled="DISABLED"'; } ?> name="enable_sync" value="0" class="btn btn-danger icon-btn btn-sm">
                                                        <span class="glyphicon btn-glyphicon glyphicon glyphicon-floppy-remove img-circle text-info"></span>
                                                        <?php _e('Remove License Key', MASE_TEXT_DOMAIN) ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr></tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6"<?php if(MASE_Pro::isPro()) { echo 'style="display: none;"';} ?>>
                        <form action="" method="post">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Activate Premium License', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <input type="hidden" name="license_form" value="1" />
                                    <div class="form-group">
                                        <textarea id="mase-license-key" name="license" class="form-control" style="min-height: 150px;"></textarea>
                                    </div>
                                    <?php if($license_failed) { ?>
                                        <div class="alert alert-danger" role="alert"><?php _e('The License could not be verified or the Domain Installations Limit has been reached. Please try again.', MASE_TEXT_DOMAIN); ?></div>
                                    <?php } ?>
                                    <?php if(!$allow_url_fopen) { ?>
                                        <div class="alert alert-danger" role="alert"><?php _e('The PHP.INI setting "allow_url_fopen" is disabled. To use the Pro version you have to enable the PHP function "allow_url_fopen" in your webserver.', MASE_TEXT_DOMAIN); ?></div>
                                    <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <button <?php if(!$allow_url_fopen || MASE_Pro::isPro() ) { echo 'disabled="DISABLED"'; } ?> name="enable_sync" value="0" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-chevron-right img-circle text-info"></span> <?php _e('Activate', MASE_TEXT_DOMAIN) ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $url = false;
                    if(MASE_Pro::$license_status && MASE_Pro::$license_status->upgrade_url_de) {
                        $url = get_locale() == 'de_DE' ? MASE_Pro::$license_status->upgrade_url_de : MASE_Pro::$license_status->upgrade_url_en;
                    }
                    ?>
                    <div class="col-md-6"<?php if(!$url) { echo 'style="display: none;"';} ?>>
                        <form action="" method="post">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Upgrade your License', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <h4><?php _e('You want to use the Plugin on more than 5 Domains?', MASE_TEXT_DOMAIN) ?></h4>

                                    <h5><?php _e('Upgrading your License to support unlimited Domains is very simple.', MASE_TEXT_DOMAIN) ?>
                                    <?php _e('Just click the "Upgrade Now" Button to upgrade your moreAds SE Premium 5 License to moreAds SE Ultimate.', MASE_TEXT_DOMAIN) ?>
                                    <?php _e('For the upgrade, your already spent money for your old moreAds SE license will be charged as a credit.', MASE_TEXT_DOMAIN) ?></h5>
                                    <h5><?php _e('Please note:', MASE_TEXT_DOMAIN) ?></h5>
                                    <h5><?php _e('After Upgrading you have to enter the new License Key in all WordPress Domains. This is done by at removing the license key via the "Help" Menu and then entering the new license key.', MASE_TEXT_DOMAIN) ?></h5>

                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <a target="_blank" href="<?php echo $url; ?>" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-chevron-right img-circle text-info"></span> <?php _e('Upgrade Now', MASE_TEXT_DOMAIN) ?></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="tab-pane fade in row" id="tab2">
                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Country Detection', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Free Feature', MASE_TEXT_DOMAIN); ?></span>
                                    <?php if(MASE::hasGeoIPDatabase()) { ?>
                                        <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <?php _e('The Country Detection Feature allows you to show ads/offers to users based on their country.', MASE_TEXT_DOMAIN) ?><br/>
                                    <br/>
                                    <?php _e('To enable Country Detection support you have to download and upload Maxmind\'s GeoIP Database.', MASE_TEXT_DOMAIN) ?><br/>
                                    <?php _e('Please download "GeoLite Country > Binary / gzip" from <a target="_blank" href="http://dev.maxmind.com/geoip/legacy/geolite/">here</a> and <b>unzip the file</b> so it has the name <b>"GeoIP.dat"</b>.', MASE_TEXT_DOMAIN) ?>
                                    <br/>
                                    <?php _e('Upload the file by clicking the Upload button.', MASE_TEXT_DOMAIN) ?>
                                    <br/>

                                    <br/><br/>
                                    <?php if($failed_geoip_upload) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php _e('Failed: The selected/uploaded file is not a valid MaxMind GeoIP Database File.', MASE_TEXT_DOMAIN); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <?php if(MASE::hasGeoIPDatabase()) { ?>
                                            <input name="_mase_geoip_media_id" type="hidden" class="media-id" value="" />
                                            <button disabled="DISABLED" id="mase_geoip" name="mase_geoip" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-upload img-circle text-info"></span> <?php _e('Upload', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } else { ?>
                                            <input name="_mase_geoip_media_id" type="hidden" class="media-id" value="" />
                                            <button id="mase_geoip" name="mase_geoip" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-upload img-circle text-info"></span> <?php _e('Upload', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Zone Features', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Free Feature', MASE_TEXT_DOMAIN); ?></span>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px; padding: 0;">
                                    <table class="table table-striped" style="margin: 0;">
                                        <tbody>
                                        <tr>
                                            <td><?php _e('Widget Ad Zones > Ad Weighting', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="enable_ad_weighting" value="1" <?php checked(MASE::$ZONE_WEIGHT); ?> />
                                                <?php _e('Enables the Ad Weighting Feature in the Widget Zones', MASE_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Widget Ad Zones > Hours of Day', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="enable_hours_of_day" value="1" <?php checked(MASE::$ZONE_HOURS_OF_DAY); ?> />
                                                <?php _e('Display ads only on chosen hours of a day', MASE_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Widget Ad Zones > Days of Week', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="enable_days_of_week" value="1" <?php checked(MASE::$ZONE_DAYS_OF_WEEK); ?> />
                                                <?php _e('Display ads only on chosen days of a week', MASE_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><?php _e('Menu Link Zones', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="enable_menu_zones" value="1" <?php checked(MASE::$ZONE_MENU); ?> />
                                                <?php _e('Use the menu entries as ad zones.', MASE_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?php if($failed_geoip_upload) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php _e('Failed: The selected/uploaded file is not a valid MaxMind GeoIP Database File.', MASE_TEXT_DOMAIN); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <button id="mase_geoip" name="mase_set_features" value="1" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Save', MASE_TEXT_DOMAIN) ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade in row" id="tab4" style="text-align: center; max-width: 1900px;">


                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: left;">
                                    <?php _e('German Video Tutorials', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 17px; padding: 0;">
                                    <a href="https://www.youtube.com/watch?v=H7DG6634sMQ&list=PLDJimwnKLBznB2zojuxlYSyPoL1HpiPs4" target="_blank">
                                        <img src="https://www.affiliate-solutions.xyz/wp-content/uploads/2016/02/moreads-se-video-tutorial-e1457005877371.jpg" style="width: 100%; max-width: 450px;" />
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" style="text-align: left;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Support', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 17px; padding: 0;">

                                    <?php if(isset($_GET['remove-lic-key'])) {
                                        if(MASE_Pro::isPro()) MASE_Pro::unregister();
                                        delete_option(MASE_PREFIX.'license');
                                        delete_option(MASE_PREFIX.'license_status');
                                        delete_option(MASE_PREFIX.'last_license_check');
                                        delete_option(MASE_PREFIX.'registered');
                                        ?>

                                        <script type="text/javascript">
                                            window.location.href = '<?php echo get_admin_url(null, 'admin.php')."?page=mase_menu"; ?>';
                                        </script>

                                        <div class="alert alert-info" role="alert"><?php _e('Your License Key was removed. Please reload this page.', MASE_TEXT_DOMAIN); ?></div>
                                    <?php die(); } ?>

                                    <table class="table table-striped" style="margin: 0;">
                                        <tbody>
                                        <tr>
                                            <td><?php _e('Support Forum:', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <a href="https://forum.affiliate-solutions.xyz/wordpress-plugin-moreads-single-edition-se--f34/" target="_blank">https://forum.affiliate-solutions.xyz/</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><?php _e('Facebook Group:', MASE_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <a href="https://www.facebook.com/groups/affiliatesolutions/" target="_blank">https://www.facebook.com/groups/affiliatesolutions/</a>
                                            </td>
                                        </tr>


                                        <tr></tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Social Networks', MASE_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 17px; padding: 66px;">

                                    <div class="onl_login">
                                        <div class="row onl_socialButtons" style="padding: 20px;">
                                            <div class="col-xs-3 col-sm-3">
                                                <a target="_blank" href="https://www.facebook.com/AffiliateSolutionsSL/" class="btn btn-lg btn-block onl_btn-facebook" data-toggle="tooltip" data-placement="top" title="Facebook">
                                                    <i class="fa fa-facebook fa-3x"></i>
                                                    <span class="hidden-xs"></span>
                                                </a>
                                            </div>
                                            <div class="col-xs-3 col-sm-3">
                                                <a target="_blank" href="https://plus.google.com/+Affiliate-solutionsBiz" class="btn btn-lg btn-block onl_btn-google-plus" data-toggle="tooltip" data-placement="top" title="Google Plus">
                                                    <i class="fa fa-google-plus fa-3x"></i>
                                                    <span class="hidden-xs"></span>
                                                </a>
                                            </div>
                                            <div class="col-xs-3 col-sm-3">
                                                <a target="_blank" href="https://twitter.com/affiliso" class="btn btn-lg btn-block onl_btn-twitter" data-toggle="tooltip" data-placement="top" title="Twitter">
                                                    <i class="fa fa-twitter fa-3x"></i>
                                                    <span class="hidden-xs"></span>
                                                </a>
                                            </div>
                                            <div class="col-xs-3 col-sm-3">
                                                <a target="_blank" href="https://www.youtube.com/channel/UCsEll2dxPgfekhxX-y8WMqA" class="btn btn-lg btn-block onl_btn-google-plus" data-toggle="tooltip" data-placement="top" title="Google Plus">
                                                    <i class="fa fa-youtube fa-3x"></i>
                                                    <span class="hidden-xs"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <script>
                                jQuery(function () {
                                    jQuery('[data-toggle="tooltip"]').tooltip()
                                })
                            </script>



                            <style type="text/css">
                                @import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
                                @import url(//fonts.googleapis.com/css?family=Titillium+Web&subset=latin,latin-ext);
                                @media (min-width: 768px) {
                                    .onl_row-sm-offset-3 div:first-child[class*="col-"] {
                                        margin-left: 25%;
                                    }
                                }


                                .onl_login .onl_socialButtons a {
                                    color: white; // In yourUse @body-bg
                                opacity:0.9;
                                }
                                .onl_login .onl_socialButtons a:hover {
                                    color: white;
                                    opacity:1;
                                }
                                .onl_login .onl_socialButtons .onl_btn-facebook {background: #3b5998;border-color:#172d5e}
                                .onl_login .onl_socialButtons .onl_btn-twitter {background: #00aced;border-color:#043d52}
                                .onl_login .onl_socialButtons .onl_btn-google-plus {background: #c32f10;border-color:#6b1301}
                                .onl_login .onl_socialButtons .onl_btn-soundcloud {background: #ff8800;border-color:#c73e04}
                                .onl_login .onl_socialButtons .onl_btn-github {background: #666666;border-color:#333333}
                                .onl_login .onl_socialButtons .onl_btn-steam {background: #878787;border-color:#292929}
                                .onl_login .onl_socialButtons .onl_btn-pinterest {background: #cc2127;border-color:#780004}
                                .onl_login .onl_socialButtons .onl_btn-vimeo {background: #1ab7ea;border-color:#162221}
                                .onl_login .onl_socialButtons .onl_btn-lastfm {background: #c3000d;border-color:#5e0208}
                                .onl_login .onl_socialButtons .onl_btn-yahoo {background: #400191;border-color:#230052}
                                .onl_login .onl_socialButtons .onl_btn-vk {background: #45668e;border-color:#1a3352}
                                .onl_login .onl_socialButtons .onl_btn-spotify {background: #7ab800;border-color:#3a5700}
                                .onl_login .onl_socialButtons .onl_btn-linkedin {background: #0976b4;border-color:#004269}
                                .onl_login .onl_socialButtons .onl_btn-stumbleupon {background: #eb4924;border-color:#943019}
                                .onl_login .onl_socialButtons .onl_btn-tumblr {background: #35465c;border-color:#142030}

                                }
                            </style>

                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: left">
                            <?php _e('Additional Plugins of Affiliate solutions', MASE_TEXT_DOMAIN) ?>
                        </div>
                        <div class="panel-body" style="font-size: 17px; padding: 40px;">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="img-thumbnail">
                                        <div class="panel-body" style="padding: 0;">
                                            <a href="<?php _e('https://offer.affiliate-solutions.xyz/bounce-jammer-en/', MASE_TEXT_DOMAIN); ?>" target="_blank">
                                                <img class="img-rounded" src="<?php echo MASE_URL; ?>/static/img/bj.png" style="width: 250px; max-width: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="img-thumbnail">
                                        <div class="panel-body" style="padding: 0;">
                                            <a href="<?php _e('https://offer.affiliate-solutions.xyz/digi2mailpoet-en/', MASE_TEXT_DOMAIN); ?>" target="_blank">
                                                <img class="img-rounded" src="<?php echo MASE_URL; ?>/static/img/d2m.jpg" style="width: 250px; max-width: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="img-thumbnail">
                                        <div class="panel-body" style="padding: 0;">
                                            <a href="<?php _e('https://offer.affiliate-solutions.xyz/customer-retargeting-en/', MASE_TEXT_DOMAIN); ?>" target="_blank">
                                                <img class="img-rounded" src="<?php echo MASE_URL; ?>/static/img/cr.png" style="width: 250px; max-width: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="img-thumbnail">
                                        <div class="panel-body" style="padding: 0;">
                                            <a href="<?php _e('https://wordpress.org/plugins/nm404/', MASE_TEXT_DOMAIN); ?>" target="_blank">
                                                <img class="img-rounded" src="<?php echo MASE_URL; ?>/static/img/nm404.png" style="width: 250px; max-width: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div
                    </div>
                    </div>
                </div>
                <div class="tab-pane fade in row" id="tab3">
                    <form action="" method="post">
                        <input type="hidden" name="features_form" value="1" />
                        <?php if(!MASE_Pro::isPro()) { ?>
                            <div class="col-lg-6 col-md-12 sol-sm-12" style="text-align: center;">
                                <div class="panel panel-default" style="width: 622px; display: inline-block; text-align: left;">
                                    <div class="panel-heading">
                                        <?php if(MASE_Pro::isVMTAPIActive()) { ?>
                                            <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="panel-body" style="font-size: 13px; max-height: 200px; padding: 0; text-align: center;">
                                        <div style=" display: inline-block; width: 620px; height: 200px; background-size: 620px 200px; background-image: url(<?php echo MASE_URL; ?>static/img/mabnr.jpg); position: relative; margin: 0; padding: 0">
                                            <div style="top: 0; background: transparent; -webkit-box-shadow: inset 0 0 50px 4px rgba( 0, 0, 0, 0.2 ), inset 0 -1px 0 rgba( 0, 0, 0, 0.1 ); -moz-box-shadow: inset 0 0 50px 4px rgba( 0, 0, 0, 0.2 ), inset 0 -1px 0 rgba( 0, 0, 0, 0.1 ); box-shadow: inset 0 0 50px 4px rgba( 0, 0, 0, 0.2 ), inset 0 -1px 0 rgba( 0, 0, 0, 0.1 ); position: absolute; left: 0; right: 0; bottom: 0; padding: 20px 30px;">
                                            </div>
                                            <h2 style="font-size: 30px; max-width: 682px; position: absolute; left: 25px; bottom: 15px; padding: 7px 15px; margin-bottom: 4px; color: #fff; background: rgba( 30, 30, 30, 0.9 ); text-shadow: 0 1px 3px rgba( 0, 0, 0, 0.4 ); -webkit-box-shadow: 0 0 30px rgba( 255, 255, 255, 0.1 ); -moz-box-shadow: 0 0 30px rgba( 255, 255, 255, 0.1 ); box-shadow: 0 0 30px rgba( 255, 255, 255, 0.1 ); -webkit-border-radius: 8px; border-radius: 8px;">
                                                <?php _e('Premium Edition', MASE_TEXT_DOMAIN) ?>
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="button-float-wrapper" style="min-height: 40px;">
                                            <a href="<?php _e('https://offer.affiliate-solutions.xyz/moreads-se-premium-en/', MASE_TEXT_DOMAIN); ?>" target="_blank" class="btn btn-success icon-btn btn-sm pull-right">
                                                <span class="glyphicon btn-glyphicon glyphicon glyphicon-shopping-cart img-circle text-info"></span> <?php _e('Upgrade now', MASE_TEXT_DOMAIN) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-lg-6 col-md-12 sol-sm-12" style="text-align: center;">
                            <div class="panel panel-default" style="width: 622px; display: inline-block; text-align: left;">
                                <div class="panel-heading">
                                    <?php _e('Global Ads', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Premium Feature', MASE_TEXT_DOMAIN); ?></span>
                                    <?php if(MASE_Pro::isSyncEnabled()) { ?>
                                        <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <?php _e('Global Ads is a feature which will automatically synchronize your created ads between all your WordPress Domains.', MASE_TEXT_DOMAIN); ?><br/>
                                    <?php _e('Please note, that our api server will receive your ads and will connect to all your WordPress Domains via HTTP to synchronize your ads..', MASE_TEXT_DOMAIN); ?><br/>
                                    <?php _e('Please make sure, no htaccess protection is enabled.', MASE_TEXT_DOMAIN); ?><br/>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <?php if(MASE_Pro::isSyncEnabled()) { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_sync" value="0" class="btn btn-warning icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-remove img-circle text-info"></span> <?php _e('Disable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } else { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_sync" value="1" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Enable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 sol-sm-12" style="text-align: center;">
                            <div class="panel panel-default" style="width: 622px; display: inline-block; text-align: left;">
                                <div class="panel-heading">
                                    <?php _e('Global Ads Statistics', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Premium Feature', MASE_TEXT_DOMAIN); ?></span>
                                    <?php if(MASE_Pro::isStatisticsActive()) { ?>
                                        <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <?php _e('Get statistics for your Global Ads like impressions, clicks and CTR over your whole WordPress Network which have moreAds SE installed.', MASE_TEXT_DOMAIN); ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <?php if(MASE_Pro::isStatisticsActive()) { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_statistics" value="0" class="btn btn-warning icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-remove img-circle text-info"></span> <?php _e('Disable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } else { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_statistics" value="1" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Enable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 sol-sm-12" style="text-align: center;">
                            <div class="panel panel-default" style="width: 622px; display: inline-block; text-align: left;">
                                <div class="panel-heading">
                                    <?php _e('Connection Detection API', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Premium Feature', MASE_TEXT_DOMAIN); ?></span>
                                    <?php if(MASE_Pro::isVMTAPIActive()) { ?>
                                        <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <?php _e('Display ads based on client\'s connection type.', MASE_TEXT_DOMAIN); ?><br/>
                                    <?php _e('This feature is mostly used by webmasters to display WAP billing offers to only mobile users, as wifi users can\'t use or pay the offer.', MASE_TEXT_DOMAIN); ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <?php if(MASE_Pro::isVMTAPIActive()) { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_vmt_api" value="0" class="btn btn-warning icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-remove img-circle text-info"></span> <?php _e('Disable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } else { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_vmt_api" value="1" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Enable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 sol-sm-12" style="text-align: center;">
                            <div class="panel panel-default" style="width: 622px; display: inline-block; text-align: left;">
                                <div class="panel-heading">
                                    <?php _e('AdBlock Jammer', MASE_TEXT_DOMAIN) ?>
                                    <span class="label label-default"><?php _e('Premium Feature', MASE_TEXT_DOMAIN); ?></span>
                                    <?php if(MASE_Pro::isFPOPActive()) { ?>
                                        <span class="label label-info"><?php _e('Activated', MASE_TEXT_DOMAIN); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px;">
                                    <?php _e('If you open explicitly blocked urls as a new window (target=_blank), the AdBlocker will block it.', MASE_TEXT_DOMAIN); ?><br/>
                                    <?php _e('The AdBlock Jammer bypasses this restriction.', MASE_TEXT_DOMAIN); ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <?php if(MASE_Pro::isFPOPActive()) { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_fpop" value="0" class="btn btn-warning icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-remove img-circle text-info"></span> <?php _e('Disable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } else { ?>
                                            <button <?php if(!MASE_Pro::isPro()) { echo 'disabled="DISABLED"'; } ?> name="enable_fpop" value="1" class="btn btn-info icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Enable', MASE_TEXT_DOMAIN) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>




    <script type="text/javascript">
        var gk_geoip_init = function(selector, button_selector)  {
            var clicked_button = false;

            jQuery(selector).each(function (i, input) {
                var button = jQuery(input).next(button_selector);
                button.click(function (event) {
                    event.preventDefault();
                    var selected_img;
                    clicked_button = jQuery(this);

                    // check for media manager instance
                    if(wp.media.frames.gk_frame) {
                        wp.media.frames.gk_frame.open();
                        return;
                    }
                    // configuration of the media manager new instance
                    wp.media.frames.gk_frame = wp.media({
                        title: '<?php _e('Select GeoIP.dat', MASE_TEXT_DOMAIN); ?>',
                        multiple: false,
                        button: {
                            text: '<?php _e('Use selected GeoIP.dat', MASE_TEXT_DOMAIN); ?>'
                        }
                    });

                    // Function used for the image selection and media manager closing
                    var gk_media_set_image = function() {
                        var selection = wp.media.frames.gk_frame.state().get('selection');

                        // no selection
                        if (!selection) {
                            return;
                        }

                        // iterate through selected elements
                        selection.each(function(attachment) {
                            clicked_button.prev(selector).val(attachment.attributes.id);
                            clicked_button.closest('form').submit();
                        });
                    };

                    // closing event for media manger
                    wp.media.frames.gk_frame.on('close', gk_media_set_image);
                    // image selection event
                    wp.media.frames.gk_frame.on('select', gk_media_set_image);
                    // showing media manager
                    wp.media.frames.gk_frame.open();
                });
            });
        };
        gk_geoip_init('.media-id', '.media-button');
    </script>

    <script type="text/javascript">
        jQuery('button[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            jQuery(".btn-pref .btn").removeClass("btn-info").addClass("btn-default");
            jQuery('button[href="' + activeTab + '"]').tab('show').removeClass("btn-default").addClass("btn-info");
        }
    </script>
</div>