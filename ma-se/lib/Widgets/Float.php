<?php

class MASE_Float_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            MASE_PREFIX.'float_widget', // Base ID
            __( 'moreAds SE Floating Banner Zone', MASE_TEXT_DOMAIN ),
            array( 'description' => __( 'moreAds SE Floating Banner Zone', MASE_TEXT_DOMAIN ), )
        );
    }

    public function widget( $args, $instance ) {
        $ad_block = isset($args['ad_block']) ? (bool) $args['ad_block'] : false;
        $ad_block_bypass = isset($instance['adblock_bypass']) && !empty($instance['adblock_bypass']) ? true : false;
        if(MASE::isWidgetJSDeliveryActive() && !isset($args['xhr']) ) {
            echo '<section id="' . $this->id . '" class="widgets"></section>';
            MASE::$WIDGET_IDS[] = $this->id;
        } else {
            if(isset($args['override_widget_id'])) $this->number = $args['override_widget_id'];
            $query_args = array();
            $query_args['disabled'] = 0;
            $zone_identifier = MASE_PREFIX.'float_zone_ads_'.$this->number;
            $zone_ads = get_option($zone_identifier);
            $device_id = MASE::get_user_device();

            if(in_array($device_id, $instance['devices'])) {

                if(!empty($zone_ads)) {
                    $query_args['device_id'] = $device_id;

                    $country = MASE::get_user_country();
                    if($country) $query_args['country'] = $country;
                    $query_args['ids'] = array_keys($zone_ads);

                    $connection_id = MASE_Pro::get_user_connection();
                    if($connection_id) $query_args['connection_id'] = $connection_id;

                    if($ad_block_bypass && $ad_block) $query_args['post_types'] = array('mase_banner_ads');

                    $ads = MASE_Ads_Generic::GetAds($query_args);
                    if(!empty($ads)) {
                        $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);
                        if(empty($ad)) return;
                        $ckey = $this->id;


                        ob_start();
                        require MASE_DIR.'/lib/Ads/html/widget_float_deps.php';
                        $ad_deps = ob_get_contents();
                        ob_end_clean();


                        if(!MASE::$float_deps_loaded && isset($args['xhr'])) {
                            MASE::$float_deps_loaded = true;
                            echo $ad_deps;
                        } else {
                            MASE::$JS_ZONES['dep'] = array('html' => $ad_deps);
                        }


                        ob_start();
                        switch($ad['media_type']) {
                            case 'banner':
                                $click_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_redirect&pid=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                                echo '<a href="'.$click_url.'" target="_blank"><img style="width: '.$ad['media_width'].'px !important; height: '.$ad['media_height'].'px !important;" src="'.$ad['media_url'].'" /></a>';
                                break;
                            case 'html':
                                if($ad['iframe_mode']) {
                                    MASE::$WIDGET_TMP_DATA = array();
                                    $identifier = 'idfl'.sha1(time().rand(1, 100000));
                                    echo '  <div id="'.$identifier.'"></div>
                                            <script type="text/javascript">
                                            var iframe = document.createElement("iframe");
                                            iframe.setAttribute("scrolling", "no");
                                            iframe.setAttribute("frameborder", "0");
                                            iframe.setAttribute("allowtransparency", "true");
                                            iframe.setAttribute("allowfullscreen", "true");
                                            iframe.setAttribute("marginwidth", "0");
                                            iframe.setAttribute("marginheight", "0");
                                            iframe.setAttribute("vspace", "0");
                                            iframe.setAttribute("hspace", "0");
                                            iframe.setAttribute("width", "'.$ad['media_width'].'");
                                            iframe.setAttribute("height", "'.$ad['media_height'].'");
                                            iframe.src="about:blank";
                                            iframe.onload = function() {
                                                var domdoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
                                                domdoc.write(X.decode("'.base64_encode(get_post_field('post_content', $ad['id'])).'"));
                                            }.bind({iframe: iframe});
                                            document.getElementById("'.$identifier.'").appendChild(iframe);
                                            </script>';
                                } else {
                                    MASE::$WIDGET_TMP_DATA = array();
                                    echo get_post_field('post_content', $ad['id']);
                                }
                                break;
                        }

                        $ad_html = ob_get_contents();
                        ob_end_clean();
                        $rnd = time();
                        $cb_pixel_view_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_pxcb&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                        ob_start();
                        require MASE_DIR.'/lib/Ads/html/widget_float.php';
                        $js= ob_get_contents();
                        ob_end_clean();

                        if(isset($args['xhr'])) {
                            echo '<script type="text/javascript">';
                            echo $js;
                            echo '</script>';
                        } else {
                            MASE::$JS_ZONES[] = array('js' => $js);
                        }

                    }
                }
            }
        }
    }

    public function form( $instance ) {
        $sizes = MASE_Ads_Generic::getAllBannerSizes();
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $selected_size = ! empty( $instance['size'] ) ? $instance['size'] : false;
        $devices = ! empty( $instance['devices'] ) ?  array_map('intval', $instance['devices']) : array(MASE_DEVICE_DESKTOP, MASE_DEVICE_TABLET, MASE_DEVICE_MOBILE);
        $delay = !empty($instance['delay']) ? (int) $instance['delay']: 0;
        $display_again = !empty($instance['display_again'] ) ? (int) $instance['display_again'] : 600;
        $adblock_bypass = !empty($instance['adblock_bypass']) ? $instance['adblock_bypass'] : false;

        $zone_ads_count = MASE::GetZoneCount(MASE_PREFIX.'float_zone_ads_'.$this->number);

        ?>
        <div class="mase-bs">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', MASE_TEXT_DOMAIN ); ?></label>
                <input class="form-control" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'devices' ); ?>"><?php _e( 'Display on devices:', MASE_TEXT_DOMAIN ); ?></label>
                <select multiple="multiple" name="<?php echo $this->get_field_name( 'devices' ); ?>[]" data-width="100%" class="widefat mase_select2_simple" id="<?php echo $this->get_field_id( 'device' ); ?>">
                    <option <?php if(in_array(MASE_DEVICE_DESKTOP, $devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_DESKTOP; ?>"><?php _e('Desktop', MASE_TEXT_DOMAIN); ?></option>
                    <option <?php if(in_array(MASE_DEVICE_TABLET, $devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_TABLET; ?>"><?php _e('Tablet', MASE_TEXT_DOMAIN); ?></option>
                    <option <?php if(in_array(MASE_DEVICE_MOBILE, $devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_MOBILE; ?>"><?php _e('Smartphone', MASE_TEXT_DOMAIN); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size:', MASE_TEXT_DOMAIN ); ?></label>
                <select class="form-control" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
                    <?php foreach($sizes as $size) { ?>
                        <option value="<?php echo $size ?>" <?php selected($size, $selected_size) ?>><?php echo $size ?></option>
                    <?php } ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('delay'); ?>"><?php _e('Delay the display of the Ad for X Seconds', MASE_TEXT_DOMAIN); ?>:</label><br/>
                <input type="text" name="<?php echo $this->get_field_name('delay'); ?>" id="<?php echo $this->get_field_id('delay'); ?>" value="<?php echo !empty($delay) ? (int) $delay : 0; ?>" class="form-control" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('display_again'); ?>"><?php _e('Cookie Lifetime', MASE_TEXT_DOMAIN); ?>:</label><br/>
                <input type="text" name="<?php echo $this->get_field_name('display_again'); ?>" id="<?php echo $this->get_field_id('display_again'); ?>" value="<?php echo !empty($display_again) ? (int) $display_again : 600; ?>" class="form-control" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('adblock_bypass'); ?>"><?php _e('AdBlock Bypass'); ?>:</label><br/>
                <input type="checkbox" name="<?php echo $this->get_field_name('adblock_bypass'); ?>" id="<?php echo $this->get_field_id('adblock_bypass'); ?>" <?php echo $adblock_bypass ? 'checked="CHECKED"' : ''; ?> />
                <small><?php _e('Serve to AdBlock Users Banner Ads only and skip blockable HTML ads', MASE_TEXT_DOMAIN); ?></small>
            </p>

            <p>
                <button class="button button-primary mase_zone_configurator">
                    <?php _e('Start Zone Configurator', MASE_TEXT_DOMAIN); ?>
                </button>

                <?php if($zone_ads_count == 1) {
                    echo '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ad in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
                } elseif($zone_ads_count > 1) {
                    echo '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ads in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
                }
                ?>
            </p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['size'] = ( ! empty( $new_instance['size'] ) ) ? strip_tags( $new_instance['size'] ) : '';
        $instance['devices'] = ! empty( $new_instance['devices'] ) ?  array_map('intval', $new_instance['devices']) : array(MASE_DEVICE_DESKTOP, MASE_DEVICE_TABLET, MASE_DEVICE_MOBILE);
        $instance['delay'] = ( ! empty( $new_instance['delay'] ) ) ? (int) $new_instance['delay']: 0;
        $instance['display_again'] = ( ! empty( $new_instance['display_again'] ) ) ? (int) $new_instance['display_again'] : 600;
        $instance['adblock_bypass'] = !empty($new_instance['adblock_bypass']) ? true : false;

        return $instance;
    }
}