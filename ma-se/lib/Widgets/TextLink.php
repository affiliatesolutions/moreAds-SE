<?php

class MASE_TextLink_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            MASE_PREFIX.'textlink_widget', // Base ID
            __( 'moreAds SE TextLink Zone', MASE_TEXT_DOMAIN ),
            array( 'description' => __( 'moreAds SE TextLink Zones are using Popup Ads', MASE_TEXT_DOMAIN ), )
        );
    }

    public function widget( $args, $instance ) {
        $ad_block = isset($args['ad_block']) ? (bool) $args['ad_block'] : false;
        if(MASE::isWidgetJSDeliveryActive() && !isset($args['xhr']) ) {
            echo '<i style="font-style: normal;" id="' . $this->id . '"></i>';
            MASE::$WIDGET_IDS[] = $this->id;
        } else {
            if(isset($args['override_widget_id'])) $this->number = $args['override_widget_id'];
            $query_args = array();
            $query_args['disabled'] = 0;
            $zone_identifier = MASE_PREFIX.'textlink_zone_ads_'.$this->number;
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
                        $no_follow=!$instance['nofollow'] ? 'rel="nofollow"' : '';

                        $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);
                        if(empty($ad)) return;
                        $click_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_redirect&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                        echo '<a '.$no_follow.' href="'.$click_url.'" target="_blank">'.$instance['title'].'</a>';

                    }
                }
            }
        }
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $devices = ! empty( $instance['devices'] ) ?  array_map('intval', $instance['devices']) : array(MASE_DEVICE_DESKTOP, MASE_DEVICE_TABLET, MASE_DEVICE_MOBILE);
        $nofollow = !empty($instance['nofollow']) ? true : false;
        $zone_ads_count = MASE::GetZoneCount(MASE_PREFIX.'textlink_zone_ads_'.$this->number);
        ?>

        <div class="mase-bs">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Link Text:', MASE_TEXT_DOMAIN ); ?></label>
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
                <label for="<?php echo $this->get_field_id('nofollow'); ?>"><?php _e('Remove rel="nofollow" from the link', MASE_TEXT_DOMAIN); ?>:</label><br/>
                <input type="checkbox" name="<?php echo $this->get_field_name('nofollow'); ?>" id="<?php echo $this->get_field_id('nofollow'); ?>" <?php echo $nofollow ? 'checked="CHECKED"' : ''; ?> />
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
        $instance['nofollow'] = !empty($new_instance['nofollow']) ? true : false;

        return $instance;
    }
}