<?php

class MASE_Banner_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            MASE_PREFIX.'banner_widget', // Base ID
            __( 'moreAds SE Banner Zone', MASE_TEXT_DOMAIN ),
            array( 'description' => __( 'moreAds SE Banner Zone', MASE_TEXT_DOMAIN ), )
        );
    }

    public function widget( $args, $instance ) {
        $ad_block = isset($args['ad_block']) ? (bool) $args['ad_block'] : false;
        $ad_block_bypass = isset($instance['adblock_bypass']) && !empty($instance['adblock_bypass']) ? true : false;
        $prefer_html = isset($instance['prefer_html']) && !empty($instance['prefer_html']) ? true : false;

        if(MASE::isWidgetJSDeliveryActive() && !isset($args['xhr']) ) {
            echo '<section id="'.$this->id.'" class="widgets"></section>';
            MASE::$WIDGET_IDS[] = $this->id;
        } else {
            if(isset($args['override_widget_id'])) $this->number = $args['override_widget_id'];
            $query_args = array();
            $query_args['disabled'] = 0;
            $zone_identifier = MASE_PREFIX.'banner_zone_ads_'.$this->number;
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

                    $padding = !empty($instance['padding']) ? absint($instance['padding']) : 0;
                    $padding_css = $padding > 0 ? 'padding-top: '.$padding.'px; padding-bottom: '.$padding.'px;' : '';
                    switch($instance['alignment']) {
                        case 'left':
                            $alignment = 'left';
                            break;
                        case 'right':
                            $alignment = 'right';
                            break;
                        default:
                            $alignment = 'center';
                    }

                    $ads = MASE_Ads_Generic::GetAds($query_args);


                    if($prefer_html && !$ad_block) {
                        $html_ads = array();
                        foreach($ads as $ad) {
                            switch($ad['media_type']) {
                                case 'html':
                                    $html_ads[] = $ad;
                                    break;
                            }
                        }

                        if(count($html_ads) > 0) {
                            $ads = $html_ads;
                        }
                    }

                    if(!empty($ads)) {
                        $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);
                        if(empty($ad)) return;

                        switch($ad['media_type']) {
                            case 'banner':
                                if($ad['show_real_link']) {
                                    $click_url = $ad['target_url'];
                                    $click_url_redirect = get_admin_url(null, 'admin-ajax.php')."?action=mase_redirect&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                                    $onclick = 'onclick="window.location.href = \''.$click_url_redirect.'\'; return false;"';
                                } else {
                                    $click_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_redirect&id=".$ad['id'].'&ab='.(int)$ad_block.'&c='.(int)$connection_id;
                                    $onclick = '';
                                }
                                echo $args['before_widget'];
                                ?><div style="text-align: <?php echo $alignment; ?>;<?php echo $padding_css; ?>"><?php
                                if(!empty($instance['title'])) {
                                    echo $args['before_title'] . utf8_decode($instance['title']) . $args['after_title'];
                                }

                                $no_follow=!$instance['nofollow'] ? 'rel="nofollow"' : '';

                                echo '<a '.$onclick.' '.$no_follow.' href="'.$click_url.'" target="_blank"><img src="'.$ad['media_url'].'" /></a>';


                                ?></div><?php
                                echo $args['after_widget'];
                                break;
                            case 'html':
                                if($ad['iframe_mode']) {
                                    MASE::$WIDGET_TMP_DATA = array(
                                        'm' => 'iframe',
                                        'w' => $ad['media_width'],
                                        'h' => $ad['media_height'],
                                    );
                                } else {
                                    MASE::$WIDGET_TMP_DATA = array();
                                }

                                echo $args['before_widget'];
                                ?><div style="text-align: <?php echo $alignment; ?>;<?php echo $padding_css; ?>"><?php
                                if(!empty($instance['title']) && !$ad['iframe_mode']) {
                                    echo $args['before_title'] . utf8_decode($instance['title']) . $args['after_title'];
                                }
                                echo get_post_field('post_content', $ad['id']);
                                ?></div><?php
                                if(!$ad['iframe_mode']) echo $args['after_widget'];
                                break;
                        }

                        if(MASE_Pro::isStatisticsActive()) MASE_Pro_Log::view($ad['pro_id'], $ad['media_type'], $device_id, $country, $connection_id, $ad_block);
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
        $alignment = ! empty( $instance['alignment']) ? $instance['alignment'] : '';
        $padding = ! empty( $instance['padding']) ? $instance['padding'] : '';
        $adblock_bypass = !empty($instance['adblock_bypass']) ? $instance['adblock_bypass'] : false;
        $prefer_html = !empty($instance['prefer_html']) ? $instance['prefer_html'] : false;
        $nofollow = !empty($instance['nofollow']) ? true : false;

        $zone_ads_count = MASE::GetZoneCount(MASE_PREFIX.'banner_zone_ads_'.$this->number);

        ?>
        <div class="mase-bs">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', MASE_TEXT_DOMAIN); ?></label>
                <input class="form-control" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>


            <p>
                <label for="<?php echo $this->get_field_id( 'devices' ); ?>"><?php _e( 'Display on devices:', MASE_TEXT_DOMAIN ); ?></label>
                <select multiple="multiple" name="<?php echo $this->get_field_name( 'devices' ); ?>[]" data-width="100%" class="form-control mase_select2_simple" id="<?php echo $this->get_field_id( 'device' ); ?>">
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
                <label><?php _e('Alignment:', MASE_TEXT_DOMAIN) ?></label>
                <select style="width: 100%;" class="form-control wpma2_select2_simple" name="<?php echo $this->get_field_name( 'alignment' ); ?>" id="<?php echo $this->get_field_id( 'alignment' ); ?>">
                    <option <?php echo $alignment == 'center' ? 'selected="SELECTED"' : ''; ?> value="center"><?php _e('Centered', MASE_TEXT_DOMAIN); ?></option>
                    <option <?php echo $alignment == 'left' ? 'selected="SELECTED"' : ''; ?> value="left"><?php _e('Left', MASE_TEXT_DOMAIN); ?></option>
                    <option <?php echo $alignment == 'right' ? 'selected="SELECTED"' : ''; ?> value="right"><?php _e('Right', MASE_TEXT_DOMAIN); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('padding'); ?>"><?php _e('Padding Top/Bottom', MASE_TEXT_DOMAIN); ?></label><br/>
                <input type="text" name="<?php echo $this->get_field_name('padding'); ?>" id="<?php echo $this->get_field_id('padding'); ?>" value="<?php echo $padding; ?>" class="form-control" style="width: 100px; display: inline-block;" /> px
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('adblock_bypass'); ?>"><?php _e('AdBlock Bypass'); ?>:</label><br/>
                <input type="checkbox" name="<?php echo $this->get_field_name('adblock_bypass'); ?>" id="<?php echo $this->get_field_id('adblock_bypass'); ?>" <?php echo $adblock_bypass ? 'checked="CHECKED"' : ''; ?> />
                <small><?php _e('Serve to AdBlock Users Banner Ads only and skip blockable HTML ads', MASE_TEXT_DOMAIN); ?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('prefer_html'); ?>"><?php _e('Prefer HTML Ads', MASE_TEXT_DOMAIN); ?>:</label><br/>
                <input type="checkbox" name="<?php echo $this->get_field_name('prefer_html'); ?>" id="<?php echo $this->get_field_id('prefer_html'); ?>" <?php echo $prefer_html ? 'checked="CHECKED"' : ''; ?> />
                <small><?php _e('Prefer HTML Ads for Users without AdBlock Software (Banner Ads will be ignored for Users without AdBlock Software)', MASE_TEXT_DOMAIN); ?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('nofollow'); ?>"><?php _e('Remove rel="nofollow" from the link', MASE_TEXT_DOMAIN); ?>:</label><br/>
                <input type="checkbox" name="<?php echo $this->get_field_name('nofollow'); ?>" id="<?php echo $this->get_field_id('nofollow'); ?>" <?php echo $nofollow ? 'checked="CHECKED"' : ''; ?> />
                <small><?php _e('Works only with Banner Ads', MASE_TEXT_DOMAIN); ?></small>
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
        $instance['padding'] = ( !empty( $new_instance['padding'] ) ) ? absint($new_instance['padding']) : 0;
        $instance['alignment'] = ( !empty( $new_instance['alignment'] ) ) ? $new_instance['alignment'] : '';
        $instance['adblock_bypass'] = !empty($new_instance['adblock_bypass']) ? true : false;
        $instance['prefer_html'] = !empty($new_instance['prefer_html']) ? true : false;
        $instance['nofollow'] = !empty($new_instance['nofollow']) ? true : false;

        return $instance;
    }
}