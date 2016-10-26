<?php

class MASE_Popup_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            MASE_PREFIX.'popup_widget', // Base ID
            __( 'moreAds SE Popup Zone', MASE_TEXT_DOMAIN ),
            array( 'description' => __( 'moreAds SE Popup Zone', MASE_TEXT_DOMAIN ), )
        );
    }

    public function widget( $args, $instance ) {
        $ad_block = isset($args['ad_block']) ? (bool) $args['ad_block'] : false;
        if(MASE::isWidgetJSDeliveryActive() && !isset($args['xhr']) ) {
            echo '<section id="' . $this->id . '" class="widgets"></section>';
            MASE::$WIDGET_IDS[] = $this->id;
        } else {
            if(isset($args['override_widget_id'])) $this->number = $args['override_widget_id'];
            $query_args = array();
            $query_args['disabled'] = 0;
            $zone_identifier = MASE_PREFIX.'popup_zone_ads_'.$this->number;
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

                    $ads = MASE_Ads_Generic::GetAds($query_args);
                    if(!empty($ads)) {
                        $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);
                        if(empty($ad)) return;
                        $ckey = $this->id;


                        if(MASE_Pro::isFPOPActive() && $ad_block && MASE_Pro::isSubscriptionActive()) {
                            $target = get_admin_url(null, 'admin-ajax.php')."?action=mase_redirect_cst&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                        } else {
                            $target = $ad['target_url'];
                        }

                        $cb_pixel_view_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_pxcb&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                        ob_start();
                        require MASE_DIR.'/lib/Ads/html/widget_popup.php';
                        $js = ob_get_contents();
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
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $devices = ! empty( $instance['devices'] ) ?  array_map('intval', $instance['devices']) : array(MASE_DEVICE_DESKTOP, MASE_DEVICE_TABLET, MASE_DEVICE_MOBILE);
        $width = ! empty( $instance['width']) ? absint($instance['width']) : 1024;
        $height = ! empty( $instance['height']) ? absint($instance['height']) : 768;
        $lifetime = ! empty( $instance['lifetime']) ? absint($instance['lifetime']) : 5;
        $zone_ads_count = MASE::GetZoneCount(MASE_PREFIX.'popup_zone_ads_'.$this->number);
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

            <p style="margin: 0;">
                <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Popup Size:', MASE_TEXT_DOMAIN ); ?></label>
                <div class="input-group-wrapper" style="display: inline-block; margin-right: 10px;">
                    <div class="input-group" style="width: 175px">
                        <span class="input-group-addon" style="width: 75px;"><?php _e( 'Width:', MASE_TEXT_DOMAIN); ?></span>
                        <input class="form-control" type="text" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $width ?>" />
                        <span class="input-group-addon">px</span>
                    </div>
                </div>
                <div class="input-group-wrapper" style="display: inline-block;">
                    <div class="input-group" style="width: 175px">
                        <span class="input-group-addon" style="width: 75px;"><?php _e( 'Height:', MASE_TEXT_DOMAIN); ?></span>
                        <input class="form-control" type="text" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height ?>" />
                        <span class="input-group-addon">px</span>
                    </div>
                </div>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('lifetime'); ?>"><?php _e('Display Popup again after', MASE_TEXT_DOMAIN); ?></label>
                <input type="text" name="<?php echo $this->get_field_name('lifetime'); ?>" id="<?php echo $this->get_field_id('lifetime'); ?>" value="<?php echo $lifetime; ?>" class="form-control" style="width: 50px; display: inline-block;" /> <?php _e('minutes', MASE_TEXT_DOMAIN) ?>
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
        $instance['devices'] = ! empty( $new_instance['devices'] ) ?  array_map('intval', $new_instance['devices']) : array(MASE_DEVICE_DESKTOP, MASE_DEVICE_TABLET, MASE_DEVICE_MOBILE);
        $instance['lifetime'] = ( !empty( $new_instance['lifetime'] ) ) ? absint($new_instance['lifetime']) : 5;
        $instance['width'] = ( !empty( $new_instance['width'] ) ) ? absint($new_instance['width']) : 0;
        $instance['height'] = ( !empty( $new_instance['height'] ) ) ? absint($new_instance['height']) : 0;

        return $instance;
    }
}