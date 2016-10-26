<?php

class MASE_Ads_CustomColumns {
    public static function init() {

        // Size
        add_filter('manage_'.MASE_PREFIX.'banner_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns_img'));
        add_filter('manage_'.MASE_PREFIX.'html_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns_html'));
        add_filter('manage_'.MASE_PREFIX.'popup_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns_pop'));
        add_filter('manage_'.MASE_PREFIX.'banner_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns_size'));
        add_filter('manage_'.MASE_PREFIX.'html_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns_size'));

        // Add Generic Ad Columns
        add_filter('manage_'.MASE_PREFIX.'banner_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns'));
        add_filter('manage_'.MASE_PREFIX.'html_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns'));
        add_filter('manage_'.MASE_PREFIX.'popup_ads'.'_posts_columns', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_columns'));

        // Generic Ad Columns
        add_action('manage_'.MASE_PREFIX.'banner_ads'.'_posts_custom_column', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_custom_columns'), 10, 2);
        add_action('manage_'.MASE_PREFIX.'html_ads'.'_posts_custom_column', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_custom_columns'), 10, 2);
        add_action('manage_'.MASE_PREFIX.'popup_ads'.'_posts_custom_column', array('MASE_Ads_CustomColumns', 'wp_action_manage_generic_ad_custom_columns'), 10, 2);
        // Filter by Custom Fields
        add_action('restrict_manage_posts', array('MASE_Ads_CustomColumns', 'wp_action_restrict_manage_posts'));

        add_filter('post_row_actions', array('MASE_Ads_CustomColumns', 'remove_row_actions'), 9, 2 );

        add_filter('parse_query', array('MASE_Ads_CustomColumns', 'custom_query_filters'));
    }

    public static function custom_query_filters(\WP_Query $query) {
        global $typenow;
        global $pagenow;

        $meta_querys = array();

        if( $pagenow == 'edit.php' && ($typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads') && $_GET['_media_size'] ) {
            $meta_querys[] = array('key' => '_media_size', 'value' =>  $_GET['_media_size']);
        }

        if( $pagenow == 'edit.php' && ($typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' || $typenow == MASE_PREFIX.'popup_ads') && $_GET['_country'] ) {
            $meta_querys[] = array('key' => '_geoip',
                'value' => $_GET['_country'],
                'compare' => 'LIKE'
            );
        }


        if( $pagenow == 'edit.php' && ($typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' || $typenow == MASE_PREFIX.'popup_ads') && $_GET['_device'] ) {
            $meta_querys[] = array('key' => '_devices',
                'value' => $_GET['_device'],
                'compare' => 'LIKE'
            );
        }

        if(!empty($meta_querys)) set_query_var('meta_query', $meta_querys);

    }

    public static function wp_action_manage_generic_ad_columns_img($columns) {
        $columns['img'] = '';
        return $columns;
    }

    public static function wp_action_manage_generic_ad_columns_html($columns) {
        $columns['html'] = '';
        return $columns;
    }

    public static function wp_action_manage_generic_ad_columns_pop($columns) {
        $columns['pop'] = '';
        return $columns;
    }


    public static function wp_action_manage_generic_ad_columns_size($columns) {
        $columns['size'] = __('Size', MASE_TEXT_DOMAIN);
        return $columns;
    }

    public static function wp_action_manage_generic_ad_columns_disabled($columns) {
        $columns['size'] = __('Disabled', MASE_TEXT_DOMAIN);
        return $columns;
    }


    public static function wp_action_manage_generic_ad_columns($columns) {
        unset($columns['date']);

        $size_was_set = false;
        if(isset($columns['size'])) {
            $size_was_set = true;
            unset($columns['size']);
        }


        $columns['ad_tags'] = __('Tags', MASE_TEXT_DOMAIN);

        if($size_was_set) $columns['size'] = __('Size', MASE_TEXT_DOMAIN);

        $columns['countries'] = __('Countries', MASE_TEXT_DOMAIN);
        $columns['devices'] = __('Devices', MASE_TEXT_DOMAIN);
        if(MASE_Pro::isPro()) $columns['sync'] = __('Global Ad', MASE_TEXT_DOMAIN);
        $columns['disabled'] = __('Disabled', MASE_TEXT_DOMAIN);
        $columns['date'] = __('Date', MASE_TEXT_DOMAIN);
        return $columns;
    }

    public static function wp_action_manage_generic_ad_custom_columns($column, $post_id) {
        switch( $column ) {
            case 'sync':
                $type = get_post_meta($post_id, '_sync', true);
                if($type) {
                    echo '<div class="mase-bs" style=""><span class="glyphicon glyphicon-ok-circle" title="'.__('Enabled', MASE_TEXT_DOMAIN).'"></span></div>';
                } else {
                    echo '<div class="mase-bs"><span class="glyphicon glyphicon-remove-circle" title="'.__('Inactive', MASE_TEXT_DOMAIN).'"></span></div>';
                }
                break;
            case 'ad_tags':
                $tags = wp_get_post_terms($post_id, MASE_PREFIX.'ad_tags', array('fields' => 'names'));

                foreach($tags as &$tag) {
                    $type = get_post_meta($post_id, '_media_type', true);
                    $url = get_admin_url(null, '/edit.php?post_type='.MASE_PREFIX.$type.'_ads');
                    $tag = '<a href="'.$url.'&mase_ad_tags='.$tag.'">'.$tag.'</a>';
                }
                echo implode(", ", $tags);
                break;
            case 'countries':
                $_countries = explode(",",get_post_meta($post_id, '_geoip', true));
                foreach($_countries as $id => $country) {
                    $type = get_post_meta($post_id, '_media_type', true);
                    $url = get_admin_url(null, '/edit.php?post_type='.MASE_PREFIX.$type.'_ads');
                    $_countries[$id] = '<a href="'.$url.'&_country='.$country.'">'.$country.'</a>';
                }
                echo implode(", ", array_slice($_countries, 0, 15));
                if(count($_countries) > 15) echo ', ...';
                break;
            case 'devices':
                $type = get_post_meta($post_id, '_media_type', true);
                $url = get_admin_url(null, '/edit.php?post_type='.MASE_PREFIX.$type.'_ads');
                $_devices = explode(",", get_post_meta($post_id, '_devices', true));
                $devices_str = array();
                if(in_array(MASE_DEVICE_DESKTOP, $_devices)) $devices_str[] = '<a href="'.$url.'&_device='.MASE_DEVICE_DESKTOP.'">'.__('Desktop', MASE_TEXT_DOMAIN).'</a>';
                if(in_array(MASE_DEVICE_MOBILE, $_devices)) $devices_str[] = '<a href="'.$url.'&_device='.MASE_DEVICE_MOBILE.'">'.__('Smartphone', MASE_TEXT_DOMAIN).'</a>';
                if(in_array(MASE_DEVICE_TABLET, $_devices)) $devices_str[] = '<a href="'.$url.'&_device='.MASE_DEVICE_TABLET.'">'.__('Tablet', MASE_TEXT_DOMAIN).'</a>';
                echo implode(", ", $devices_str);
                break;
            case 'size':
                $size = get_post_meta($post_id, '_media_size', true);
                $type = get_post_meta($post_id, '_media_type', true);
                $url = get_admin_url(null, '/edit.php?post_type='.MASE_PREFIX.$type.'_ads');
                echo '<a href="'.$url.'&_media_size='.$size.'">'.$size.'</a>';
                break;
            case 'img':
                $url = get_post_meta($post_id, '_media_url', true);
                if($url) {
                    echo '<div class="mase-bs"><a href="#" class="mase_html_tooltip" data-html="'.base64_encode('<img src="'.$url.'"></img>').'"><span class="glyphicon glyphicon-picture"></span></a></div>';
                }
                break;
            case 'html':
                $width = get_post_meta($post_id, '_media_width', true);
                $height = get_post_meta($post_id, '_media_height', true);
                $url = get_admin_url(null, 'admin-ajax.php')."?action=mase_ad_preview&id=".$post_id;
                echo '<div class="mase-bs"><a href="#" class="mase_html_tooltip" data-html="'.base64_encode('<iframe width="'.$width.'px" height="'.$height.'px" scrolling="no" frameborder="0" src="'.$url.'"></iframe>').'"><span class="glyphicon glyphicon-picture"></span></a></div>';
                break;
            case 'pop':
                $url = get_post_meta($post_id, '_target_url', true);
                echo '<div class="mase-bs"><a href="'.$url.'" target="_blank"><span class="glyphicon glyphicon-picture"></span></a></div>';
                break;
            case 'disabled':
                $disabled = get_post_meta($post_id, '_disabled', true);
                if($disabled) {
                    echo '<div class="mase-bs"><span class="glyphicon glyphicon-exclamation-sign" title="'.__('Ad is Disabled', MASE_TEXT_DOMAIN).'"></span></div>';
                }
                break;
            default :
                break;
        }
        return $column;
    }

    public static function wp_action_restrict_manage_posts() {
        global $typenow;

        // an array of all the taxonomyies you want to display. Use the taxonomy name or slug
        $taxonomies = array(MASE_PREFIX.'ad_tags');

        // must set this to the post type you want the filter(s) displayed on
        if( $typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' || $typenow == MASE_PREFIX.'popup_ads' ){
            foreach ($taxonomies as $tax_slug) {
                $tax_obj = get_taxonomy($tax_slug);
                $tax_name = $tax_obj->labels->name;
                $terms = get_terms($tax_slug);
                if(count($terms) > 0) {
                    echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                    echo "<option value=''>".__('Show All', MASE_TEXT_DOMAIN)." $tax_name</option>";
                    foreach ($terms as $term) {
                        echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
                    }
                    echo "</select>";
                }
            }
        }

        // Size
        if( $typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' ){
            $ad_sizes = MASE_Ads_Generic::getAllBannerSizes();
            if(count($ad_sizes) > 0) {
                echo "<select name='_media_size' id='_media_size' class='postform'>";
                echo "<option value=''>".__('Show All Ad-Sizes', MASE_TEXT_DOMAIN)."</option>";
                foreach ($ad_sizes as $size) {
                    echo '<option value='. $size, $_GET['_media_size'] == $size ? ' selected="selected"' : '','>' . $size .'</option>';
                }
                echo "</select>";
            }
        }

        // Country
        if( $typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' || $typenow == MASE_PREFIX.'popup_ads'){
                echo "<select name='_country' id='_country' class='postform'>";
                echo "<option value=''>".__('Show All Countries', MASE_TEXT_DOMAIN)."</option>";
                foreach (MASE::$countries as $cc => $country) {
                    echo '<option value='. $cc, $_GET['_country'] == $cc ? ' selected="selected"' : '','>' . $country .'</option>';
                }
                echo "</select>";

        }

        // Devices
        if( $typenow == MASE_PREFIX.'banner_ads' || $typenow == MASE_PREFIX.'html_ads' || $typenow == MASE_PREFIX.'popup_ads' ){
            echo "<select name='_device' id='_device' class='postform'>";
            echo "<option value=''>".__('Show All Devices', MASE_TEXT_DOMAIN)."</option>";
            ?>
            <option <?php if(MASE_DEVICE_DESKTOP == $_GET['_device']) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_DESKTOP; ?>"><?php _e('Desktop', MASE_TEXT_DOMAIN); ?></option>
            <option <?php if(MASE_DEVICE_TABLET == $_GET['_device']) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_TABLET; ?>"><?php _e('Tablet', MASE_TEXT_DOMAIN); ?></option>
            <option <?php  if(MASE_DEVICE_MOBILE == $_GET['_device']) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_MOBILE; ?>"><?php _e('Smartphone', MASE_TEXT_DOMAIN); ?></option>
            <?php

            echo "</select>";

        }
    }

    public static function wp_filter_manage_sortable_columns($sortable_columns) {

        return $sortable_columns;
    }


    public static function remove_row_actions( $actions, $post ) {
        global $current_screen;

        if( $current_screen->post_type != MASE_PREFIX.'banner_ads' && $current_screen->post_type != MASE_PREFIX.'popup_ads' && $current_screen->post_type != MASE_PREFIX.'html_ads' ) return $actions;
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );

        if( (!MASE_Pro::isSubscriptionActive() || !MASE_Pro::isSyncEnabled()) && get_post_meta($post->ID, '_sync', true)) {
            $actions['edit'] = '<b class="color: red !important;" title="'.__('moreAds SE Feature Global Ads is not enabled, therefore editing is not possible.', MASE_PREFIX).'">'.__('Edit', MASE_PREFIX).'</b>';
        }

        $actions[MASE_PREFIX.'clone'] = '<a href="'.get_edit_post_link($post->ID, '').'&mase_clone=1&mase_clone_id='.$post->ID.'" title="'.__('Clone this ad', MASE_PREFIX).'">'.__('Clone', MASE_PREFIX).'</a>';

        return $actions;
    }

}