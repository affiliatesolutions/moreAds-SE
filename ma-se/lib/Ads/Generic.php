<?php
defined( 'ABSPATH' ) or die();
class MASE_Ads_Generic {

    public static function init() {
        add_action('save_post', array('MASE_Ads_Generic', 'wp_action_save_post'), 1, 2);
        add_action('before_delete_post', array('MASE_Ads_Generic', 'wp_action_delete_post'), 1, 2);
    }


    public static function GetAd($post_id) {
        $args = array();
        $args['ids'] = $post_id;
        $resp = self::GetAds($args);
        if(!empty($resp)) return array_shift($resp);
        return false;
    }

    public static function GetAdByProId($pro_id) {
        $args = array();
        $args['pro_id'] = $pro_id;
        $resp = self::GetAds($args);
        if(!empty($resp)) return array_shift($resp);
        return false;
    }

    public static function SelectZoneAd($ads, $zone_ads) {
        $hour = date('G');
        $weekday = date('N');

        $ad_selection = array();

        foreach($ads as $ad_id => $ad) {
            if(MASE::$ZONE_HOURS_OF_DAY) {
                if(isset($zone_ads[$ad_id]['hours']) && !empty($zone_ads[$ad_id]['hours']) && !in_array($hour, explode(",", $zone_ads[$ad_id]['hours']))) continue;
            }

            if(MASE::$ZONE_DAYS_OF_WEEK) {
                if(isset($zone_ads[$ad_id]['days']) && !empty($zone_ads[$ad_id]['days']) && !in_array($weekday, explode(",", $zone_ads[$ad_id]['days']))) continue;
            }

            if(MASE::$ZONE_WEIGHT) {
                if(isset($zone_ads[$ad_id]['weight']) && !empty($zone_ads[$ad_id]['weight'])) {
                    for($i=0;$i<$zone_ads[$ad_id]['weight'];$i++) $ad_selection[] = $ad_id;
                } else {
                    $ad_selection[] = $ad_id;
                }
            } else {
                $ad_selection[] = $ad_id;
            }
        }

        $ad_id = $ad_selection[array_rand($ad_selection, 1)];

        return $ads[$ad_id];
    }

    public static function GetAds($args) {
        global $wpdb;
        $sql_where = array();
        $sql_where_params = array();

        $sql_having = array();
        $sql_having_params = array();

        if(isset($args['ids'])) {
            if(!is_array($args['ids'])) {
                $tmp = $args['ids'];
                $args['ids'] = array();
                $args['ids'][] = $tmp;
            }
            $sql_where[]= 'AND p.ID in ('.implode(',', array_map('intval', $args['ids'])).')';
        }

        if(isset($args['size'])) {
            $sql_having[] = "AND media_size = '%s'";
            $sql_having_params[] = (string) esc_sql($args['size']);
        }

        if(isset($args['pro_id'])) {
            $sql_having[] = "AND pro_id = '%s'";
            $sql_having_params[] = (string) esc_sql($args['pro_id']);
        }

        if(isset($args['disabled']) && $args['disabled'] == 0) {
            $sql_having[] = "AND disabled IS NULL";
        }

        if(isset($args['country'])) {
            $sql_having[] = "AND geoip LIKE '%%%s%%'";
            $sql_having_params[] = (string) esc_sql(strtoupper($args['country']));
        }

        if(isset($args['device_id'])) {
            $sql_having[] = "AND device_ids LIKE '%%%s%%'";
            $sql_having_params[] = (string) esc_sql(intval($args['device_id']));
        }

        if(isset($args['connection_id'])) {
            $sql_having[] = "AND (connection_ids LIKE '%%%s%%' OR connection_ids IS NULL)";
            $sql_having_params[] = (string) esc_sql(intval($args['connection_id']));
        }

        if(isset($args['post_types'])) {
            $sql_tmp = '';
            if(!is_array($args['post_types'])) $args['post_types'][] = $args['post_types'];

            $sql_tmp.='AND (';
            $first = true;
            foreach($args['post_types'] as $post_type) {
                if(!$first) $sql_tmp .= ' OR ';
                if($first) $first = false;
                $sql_tmp .= "p.post_type = '%s'";
                $sql_where_params[] = (string) esc_sql($post_type);
            }
            $sql_tmp.=')';
            $sql_where[] = $sql_tmp;
        } else {
            $sql_where[] = " AND (p.post_type = 'mase_banner_ads' OR p.post_type = 'mase_html_ads' OR p.post_type = 'mase_popup_ads') ";
        }

        $where_str = '';
        foreach($sql_where as $where) {
            $where_str.= ' '.$where.' ';
        }
        $having_str = '';
        foreach($sql_having as $where) {
            $having_str.= ' '.$where.' ';
        }

        $SQL = "SELECT
                    p.ID as id,
                    p.post_title as name,
                    MAX(CASE WHEN m.meta_key = '_media_type' then m.meta_value ELSE NULL END) as media_type,
                    MAX(CASE WHEN m.meta_key = '_media_size' then m.meta_value ELSE NULL END) as media_size,
                    MAX(CASE WHEN m.meta_key = '_media_width' then m.meta_value ELSE NULL END) as media_width,
                    MAX(CASE WHEN m.meta_key = '_media_height' then m.meta_value ELSE NULL END) as media_height,
                    MAX(CASE WHEN m.meta_key = '_media_id' then m.meta_value ELSE NULL END) as media_id,
                    MAX(CASE WHEN m.meta_key = '_media_url' then m.meta_value ELSE NULL END) as media_url,
                    MAX(CASE WHEN m.meta_key = '_target_url' then m.meta_value ELSE NULL END) as target_url,
                    MAX(CASE WHEN m.meta_key = '_devices' then m.meta_value ELSE NULL END) as device_ids,
                    MAX(CASE WHEN m.meta_key = '_connection_ids' then m.meta_value ELSE NULL END) as connection_ids,
                    MAX(CASE WHEN m.meta_key = '_geoip' then m.meta_value ELSE NULL END) as geoip,
                    MAX(CASE WHEN m.meta_key = '_sync' then m.meta_value ELSE NULL END) as sync,
                    MAX(CASE WHEN m.meta_key = '_iframe_mode' then m.meta_value ELSE NULL END) as iframe_mode,
                    MAX(CASE WHEN m.meta_key = '_disabled' then m.meta_value ELSE NULL END) as disabled,
                    MAX(CASE WHEN m.meta_key = '_id' then m.meta_value ELSE NULL END) as pro_id,
                    MAX(CASE WHEN m.meta_key = '_show_real_link' then m.meta_value ELSE NULL END) as show_real_link,
                    MAX(CASE WHEN m.meta_key = '_chksum' then m.meta_value ELSE NULL END) as chksum
                FROM $wpdb->posts as p
                INNER JOIN $wpdb->postmeta as m ON m.post_id = p.ID
                WHERE p.post_status = 'publish' ".$where_str."
                GROUP BY p.ID
                HAVING p.ID > 0".$having_str;

        $sql_params = array();
        foreach($sql_where_params as $p) $sql_params[] = $p;
        foreach($sql_having_params as $p) $sql_params[] = $p;

        $res = $wpdb->get_results($wpdb->prepare($SQL, $sql_params), OBJECT);
        $result = array();
        foreach($res as $r) {

            $result[(int)$r->id]=array(
                'id' => (int)$r->id,
                'chksum' => $r->chksum,
                'pro_id' => (string)$r->pro_id,
                'sync' => (int) $r->sync,
                'media_type' => (string) $r->media_type,
                'device_ids' => explode(',', $r->device_ids),
                'connection_ids' => explode(',', $r->connection_ids),
                'countries' => explode(',', $r->geoip),
                'name' => (string) $r->name,
                'media_id' => (int) $r->media_id,
                'media_size' => (string) $r->media_size,
                'media_width' => (int) $r->media_width,
                'media_height' => (int) $r->media_height,
                'media_url' => (string) $r->media_url,
                'iframe_mode' => (int) $r->iframe_mode,
                'disabled' => (int) $r->disabled,
                'show_real_link' => (bool) $r->show_real_link,
                'target_url' => (string) $r->target_url,
            );
        }

        return $result;
    }

    public static function getAllBannerSizes() {
        global $wpdb;
        $SQL = "SELECT m.meta_value FROM $wpdb->posts as p
                INNER JOIN $wpdb->postmeta as m ON m.post_id = p.ID AND m.meta_key = '_media_size'
                WHERE (p.post_type = 'mase_banner_ads' OR p.post_type = 'mase_html_ads') AND p.post_status = 'publish'
                GROUP BY m.meta_value";
        $res = $wpdb->get_results($SQL, OBJECT);
        $result = array();
        foreach($res as $r) {
            $result[]=$r->meta_value;
        }

        return $result;
    }

    public static function wp_action_delete_post($post_id) {
        $post = get_post($post_id);
        if(!in_array($post->post_type, array(MASE_PREFIX.'html_ads', MASE_PREFIX.'banner_ads', MASE_PREFIX.'popup_ads'))) return $post_id;
        $sync = get_post_meta($post_id, '_sync', true);
        if(!$sync) return $post_id;
        if(!MASE_Pro::isSubscriptionActive() || !MASE_Pro::isSyncEnabled()) return $post_id; // TODO do we still want to allow delete ??
        MASE_Pro::deleteAd($post_id);
        return $post_id;
    }

    public static function wp_action_save_post($post_id, $post) {
        if ( !wp_verify_nonce( $_POST['ad_nonce'], 'ad_save' )) return $post->ID;
        if ( !current_user_can( 'edit_post', $post->ID )) return $post->ID;

        $data = array();
        switch($post->post_type) {
            case MASE_PREFIX.'html_ads':
                $data['_media_type'] = 'html';
                $data['_media_width'] = intval($_POST['_media_size_width']);
                $data['_media_height'] = intval($_POST['_media_size_height']);
                $data['_iframe_mode'] = intval($_POST['_iframe_mode']);
                $data['_disabled'] = intval($_POST['_disabled']);
                $data['_media_size'] = intval($data['_media_width']).'x'.intval($data['_media_height']);
                break;
            case MASE_PREFIX.'banner_ads':
                $data['_media_type'] = 'banner';
                $data['_target_url'] = $_POST['_target_url'];
                $data['_disabled'] = intval($_POST['_disabled']);

                $data['_media_id'] = $_POST['_media_id'];
                $media = wp_get_attachment_image_src($data['_media_id'], 'full', false);

                wp_update_post(
                    array(
                        'ID' => $data['_media_id'],
                        'post_parent' => $post_id
                    )
                );

                $width = $media[1];
                $height = $media[2];
                $data['_media_width'] = $width;
                $data['_media_height'] = $height;
                $data['_media_url'] = $media[0];
                $data['_media_size'] = intval($width).'x'.intval($height);
                $data['_show_real_link'] = intval($_POST['_show_real_link']);
                break;
            case MASE_PREFIX.'popup_ads':
                $data['_disabled'] = intval($_POST['_disabled']);
                $data['_media_type'] = 'popup';
                $data['_target_url'] = $_POST['_target_url'];

                break;
            default:
                return $post->ID;
                break;
        }

        $data['_geoip'] = $_POST['_geoip'];
        $data['_devices'] = $_POST['_devices'];


        $_id = get_post_meta($post_id, '_id', TRUE);
        $d = parse_url(get_site_url());
        $data['_id'] = !empty($_id) ? $_id : strtolower($d['host'].'_'.$post_id);

        if(MASE_Pro::isSubscriptionActive() && MASE_Pro::isSyncEnabled() && isset($_POST['_sync'])) {
            $data['_sync'] = true;
        }

        if(MASE_Pro::isSubscriptionActive() && MASE_Pro::isVMTAPIActive() && isset($_POST['_connection_ids'])) {
            $data['_connection_ids'] = $_POST['_connection_ids'];
        }

        self::_store_post_meta_data($post, $data);

        if($data['_sync']) MASE_Pro::syncAd($post_id);

        return $post->ID;
    }


    private static function _store_post_meta_data($post, $data) {
        foreach ($data as $key => $value) {
            if( $post->post_type == 'revision' ) return true;
            $value = implode(',', (array)$value);
            if(get_post_meta($post->ID, $key, FALSE)) {
                update_post_meta($post->ID, $key, $value);
            } else {
                add_post_meta($post->ID, $key, $value);
            }
            if(!$value) delete_post_meta($post->ID, $key);
        }
    }

    public static function handleAdDelete($data) {
        if(!isset($data['ad_id'])) return;

        $post_info = self::GetAdByProId($data['ad_id']);
        if($post_info) {
            remove_action('before_delete_post', array('MASE_Ads_Generic', 'wp_action_delete_post'), 1); // Remove so noo loop occurs
            if($post_info['id']) wp_delete_post($post_info['id'], true);
            add_action('before_delete_post', array('MASE_Ads_Generic', 'wp_action_delete_post'), 1, 2);
        }
        die();
    }

    public static function handleAdSync($data) {
        if(!isset($data['ad_id'])) return;
        if(!isset($data['media_type'])) return;
        if(!isset($data['post_title'])) return;

        $post_info = self::GetAdByProId($data['ad_id']);
        $post_id = $post_info['id'];

        $post = array(
            'post_title' => $data['post_title'],
            'post_content' => isset($data['post_content']) ? $data['post_content'] : '',
            'post_status' => $data['post_status'],
            'post_type' => $data['post_type']
        );

        if($post_id) $post['ID'] = (int) $post_id;
        remove_filter('content_save_pre','wp_filter_post_kses');
        remove_filter('content_filtered_save_pre','wp_filter_post_kses');
        $insert_post = wp_insert_post($post);
        add_filter('content_save_pre','wp_filter_post_kses');
        add_filter('content_filtered_save_pre','wp_filter_post_kses');
        if($post_id) {
            $real_post_id = $post_id;
        } else {
            $real_post_id = $insert_post;
        }
        wp_set_post_terms($real_post_id, $data['post_tags'], MASE_PREFIX.'ad_tags');

        self::_store_post_meta_data(get_post($real_post_id), array(
            '_media_height' => isset($data['media_height']) ? $data['media_height'] : false,
            '_media_width' => isset($data['media_width']) ? $data['media_width'] : false,
            '_media_type' => isset($data['media_type']) ? $data['media_type'] : false,
            '_media_size' => isset($data['media_size']) ? $data['media_size'] : false,
            '_iframe_mode' => isset($data['iframe_mode']) ? $data['iframe_mode'] : false,
            '_disabled' => isset($data['disabled']) ? $data['disabled'] : false,
            '_target_url' => isset($data['target_url']) ? $data['target_url'] : false,
            '_device_ids' => isset($data['device_ids']) ? $data['device_ids'] : false,
            '_devices' => isset($data['device_ids']) ? $data['device_ids'] : false,
            '_connection_ids' => isset($ad_info['connection_ids']) ? $ad_info['connection_ids'] : false,
            '_geoip' => isset($data['countries']) ? $data['countries'] : false,
            '_id' => isset($data['ad_id']) ? $data['ad_id'] : false,
            '_sync' => true
        ));

        if($data['media_payload']) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );

            if($post_media_id = get_post_meta($real_post_id, '_media_id', true)) { // Update
                $file = get_attached_file($post_media_id);

                $file_data = $file ? base64_encode(file_get_contents($file)) : 'err';
                if($file_data != $data['media_payload']) {
                    if(false !== wp_delete_attachment($post_media_id, true)) {
                        self::setup_gfx($real_post_id, $data['media_url'], $data['media_payload']);
                    }
                }

            } else { // Create
                self::setup_gfx($real_post_id, $data['media_url'], $data['media_payload']);
            }
        }

        MASE_Pro::UpdateAdCheckSum($real_post_id);
    }

    public static function handleAdClone($post_id) {

        $ad_info = self::GetAd($post_id);
        $post = get_post($post_id);

        $file = get_attached_file($ad_info['media_id']);

        $new_post = array(
            'post_title' => __('Clone of ', MASE_PREFIX).' '.$post_id.' '.$post->post_title,
            'post_content' => isset($post->post_content) ? $post->post_content : '',
            'post_status' => $post->post_status,
            'post_type' => $post->post_type
        );


        remove_filter('content_save_pre','wp_filter_post_kses');
        remove_filter('content_filtered_save_pre','wp_filter_post_kses');
        $new_post_id = wp_insert_post($new_post);
        add_filter('content_save_pre','wp_filter_post_kses');
        add_filter('content_filtered_save_pre','wp_filter_post_kses');

        if(!$new_post_id) return false;
        wp_set_post_terms($new_post_id, wp_get_post_terms($post_id, MASE_PREFIX.'ad_tags', array('fields' => 'names')), MASE_PREFIX.'ad_tags');

        self::_store_post_meta_data(get_post($new_post_id), array(
            '_media_height' => isset($ad_info['media_height']) ? $ad_info['media_height'] : false,
            '_media_width' => isset($ad_info['media_width']) ? $ad_info['media_width'] : false,
            '_media_type' => isset($ad_info['media_type']) ? $ad_info['media_type'] : false,
            '_media_size' => isset($ad_info['media_size']) ? $ad_info['media_size'] : false,
            '_target_url' => isset($ad_info['target_url']) ? $ad_info['target_url'] : false,
            '_device_ids' => isset($ad_info['device_ids']) ? $ad_info['device_ids'] : false,
            '_iframe_mode' => isset($ad_info['iframe_mode']) ? $ad_info['iframe_mode'] : false,
            '_disabled' => isset($ad_info['disabled']) ? $ad_info['disabled'] : false,
            '_devices' => isset($ad_info['device_ids']) ? $ad_info['device_ids'] : false,
            '_connection_ids' => isset($ad_info['connection_ids']) ? $ad_info['connection_ids'] : false,
            '_geoip' => isset($ad_info['countries']) ? $ad_info['countries'] : false,
            '_id' => false,
            '_sync' => false
        ));


        if(isset($ad_info['media_id']) && !empty($ad_info['media_id'])) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );

            $file = get_attached_file($ad_info['media_id']);
            $file_data_b64 = base64_encode(file_get_contents($file));
            if($file_data_b64) self::setup_gfx($new_post_id, $ad_info['media_url'], $file_data_b64);
        }

        return $new_post_id;
    }

    protected static function setup_gfx($post_id, $media_url, $base_64_media_payload) {
        $upload_dir_info = wp_upload_dir();
        $filename = pathinfo($media_url, PATHINFO_BASENAME);

        if(file_exists($upload_dir_info['path'].'/'.$filename)) $filename = substr(sha1($base_64_media_payload.time()), 0, 8).'-'.$filename;
        $file_path = $upload_dir_info['path'].'/'. $filename;
        $file_url = $upload_dir_info['url'].'/'. $filename;
        file_put_contents($file_path, base64_decode($base_64_media_payload));
        $file_type = wp_check_filetype( $file_path , null );

        $attachment = array(
            'guid'           => $file_path,
            'post_mime_type' => $file_type['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename),
            'post_content'   => '',
            'post_status'    => 'inherit',
            'post_parent'    => $post_id
        );

        $attach_id = wp_insert_attachment( $attachment, $file_path, $post_id );
        apply_filters('wp_handle_upload', array('file' => $file_path, 'url' => $file_url, 'type' => $file_type['type']), 'upload');

        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        update_post_meta($post_id, '_media_url', $file_url);
        update_post_meta($post_id, '_media_id', $attach_id);
    }
}