<?php
defined( 'ABSPATH' ) or die();
class MASE_Pro {
    private static $license = false;
    public static $license_status = false;
    private static $cache_connection = NULL;

    public static function init() {
        self::$license = get_option(MASE_PREFIX.'license');
        self::$license_status = get_option(MASE_PREFIX.'license_status');

        add_action('wp_ajax_nopriv_mase_pxcb', array('MASE_Pro', 'wp_ajax_mase_pxcb'));
        add_action('wp_ajax_mase_pxcb', array('MASE_Pro', 'wp_ajax_mase_pxcb'));
        add_action('wp_ajax_nopriv_mase_redirect', array('MASE_Pro', 'wp_ajax_mase_redirect'));
        add_action('wp_ajax_mase_redirect', array('MASE_Pro', 'wp_ajax_mase_redirect'));
        add_action('wp_ajax_nopriv_mase_cst_redir', array('MASE_Pro', 'wp_ajax_mase_cst_redir'));
        add_action('wp_ajax_mase_cst_redir', array('MASE_Pro', 'wp_ajax_mase_cst_redir'));

        add_action('wp_ajax_nopriv_mase_redirect_cst', array('MASE_Pro', 'wp_ajax_mase_redirect_cst'));
        add_action('wp_ajax_mase_redirect_cst', array('MASE_Pro', 'wp_ajax_mase_redirect_cst'));

        if(self::$license)  {
            MASE_Pro_Api::init(self::$license['username'], self::$license['token']);
            add_action('wp_ajax_nopriv_mase_ad_sync_request', array('MASE_Pro', 'wp_ajax_mase_ad_sync_request'));
            add_action('wp_ajax_nopriv_mase_ad_delete_request', array('MASE_Pro', 'wp_ajax_mase_ad_delete_request'));
            add_action('wp_ajax_nopriv_mase_poll_logs', array('MASE_Pro', 'wp_ajax_poll_logs'));
            add_action('wp_ajax_nopriv_mase_status', array('MASE_Pro', 'wp_ajax_status'));
            add_action('wp_ajax_nopriv_mase_syncstatus', array('MASE_Pro', 'wp_ajax_mase_syncstatus'));

            add_action('wp_ajax_mase_log_devices', array('MASE_Pro', 'wp_ajax_mase_log_devices'));
            add_action('wp_ajax_mase_log_connections', array('MASE_Pro', 'wp_ajax_mase_log_connections'));
            add_action('wp_ajax_mase_log_users', array('MASE_Pro', 'wp_ajax_mase_log_users'));
            add_action('wp_ajax_mase_log_countries', array('MASE_Pro', 'wp_ajax_mase_log_countries'));
            add_action('wp_ajax_mase_log_domains', array('MASE_Pro', 'wp_ajax_mase_log_domains'));
            add_action('wp_ajax_mase_stats_query', array('MASE_Pro', 'wp_ajax_mase_stats_query'));

            if(is_admin()) {
                if(time() - ( (int) get_option(MASE_PREFIX.'last_license_check')) > 60*10) {
                    $status = MASE_Pro_Api::getLicenseStatus();
                    if($status) update_option(MASE_PREFIX.'license_status', $status);
                    update_option(MASE_PREFIX.'last_license_check', time());
                }
            }
        }
    }

    public function getDeviceArray(){
        return
            array(
            array('id' => -1, 'text' => __('All Devices', MASE_TEXT_DOMAIN)),
            array('id' => MASE_DEVICE_DESKTOP, 'text' => __('Desktop', MASE_TEXT_DOMAIN)),
            array('id' => MASE_DEVICE_MOBILE, 'text' => __('Smartphone', MASE_TEXT_DOMAIN)),
            array('id' => MASE_DEVICE_TABLET, 'text' => __('Tablet', MASE_TEXT_DOMAIN)));
    }

    public function getUsersArray(){
        return
            array(
                array('id' => -1, 'text' => __('All Users', MASE_TEXT_DOMAIN)),
                array('id' => 1, 'text' => __('AdBlock Users', MASE_TEXT_DOMAIN)),
                array('id' => 0, 'text' => __('Normal Users', MASE_TEXT_DOMAIN)),
            );
    }

    public function getConnectionsArray(){
        return
            array(
                array('id' => -1, 'text' => __('All Connections', MASE_TEXT_DOMAIN)),
                array('id' => MASE_CONNECTION_3G, 'text' => __('Mobile', MASE_TEXT_DOMAIN)),
                array('id' => MASE_CONNECTION_WIFI, 'text' => __('WIFI', MASE_TEXT_DOMAIN)),
            );
    }

    public static function wp_ajax_mase_log_devices() {
        echo json_encode(self::getDeviceArray());
        die();
    }

    public static function wp_ajax_mase_log_users() {
        echo json_encode(self::getUsersArray());

        die();
    }

    public static function wp_ajax_mase_log_connections() {
        echo json_encode(self::getConnectionsArray());
        die();
    }

    public static function wp_ajax_mase_log_domains() {
        $domains = MASE_Pro_Api::getDomains();
        // Prepend All Domains
        $domains = array_merge(array('-1' => array('id' => '-1', 'domain' => __('All Domains', MASE_TEXT_DOMAIN))), $domains);


        if(isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
            foreach($domains as $id => $c) {
                if( (strpos($c['domain'], $_REQUEST['q']) === false) && (strpos($c['domain'], $_REQUEST['q']) === false) ) {
                    unset($domains[$id]);
                }
            }
        }
        echo json_encode($domains);
        die();
    }

    public static function wp_ajax_mase_stats_query() {
        $data = MASE_Pro_Api::getStats($_REQUEST);

        // title, ad type, preview btn, tags, countries, devices

        // Inject Our Data
        if(isset($data['data'])) {
            foreach($data['data'] as $k => &$d) {
                $ad = MASE_Ads_Generic::GetAdByProId($d[0]);
                if(!$ad) unset($data['data'][$k]);
                $d_org = $d;

                $d[0] = sha1($d[0]);

                // Update Tags
                $tags = json_decode($d[2], true);
                if(is_array($tags)) foreach($tags as &$tag) $tag = '<a class="mase_stats_update" data-value="'.$tag.'" href="#">'.$tag.'</a>';
                $d[2] = @implode(", ", $tags);

                // Update Title
                $d['1'] = '<a title="'.__('Preview', MASE_TEXT_DOMAIN).'" style="padding: 0 5px;" class="mase_stats_update" data-value="'.$d[1].'" href="#">'.$d[1].'</a>';
                // disabled
                if(($ad['disabled'])) {
                    $d['1'] .= '<span class="glyphicon glyphicon-exclamation-sign" title="'.__('Ad is Disabled', MASE_TEXT_DOMAIN).'"></span>';
                }

                $d[13] = "";
                // Update Title append Edit Ad Btn
                $d[13] .= '<a target="_blank" title="'.__('Edit', MASE_TEXT_DOMAIN).'" style="padding: 0 5px;" class="" href="'.get_admin_url(null, '/post.php?post='.$ad['id'].'&action=edit').'"><span class="glyphicon glyphicon-pencil"></span></a>';

                // Update Title append Graphic Preview
                if(!empty($ad['media_url'])) { // gfx
                    $d[13] .= '<a title="'.__('Preview', MASE_TEXT_DOMAIN).'" href="#" class="mase_html_tooltip" data-html="'.base64_encode('<img src="'.$ad['media_url'].'"></img>').'" ><span class="glyphicon glyphicon-picture"></span></a>';
                    $d[3] = '<a class="mase_stats_update" data-value="'.$d[3].'" href="#">'.$d[3].'</a>';
                } elseif($ad['media_type'] == 'html') {
                    $url = get_admin_url(null, 'admin-ajax.php')."?action=mase_ad_preview&id=".$ad['id'];
                    $d[13] .= '<a title="'.__('Preview', MASE_TEXT_DOMAIN).'" href="#" class="mase_html_tooltip" data-html="'.base64_encode('<iframe width="'.$ad['media_width'].'px" height="'.$ad['media_height'].'px" scrolling="no" frameborder="0" src="'.$url.'"></iframe>').'"><span class="glyphicon glyphicon-picture"></span></a>';
                    $d[3] = '<a class="mase_stats_update" data-value="'.$d[3].'" href="#">'.$d[3].'</a>';
                } elseif($ad['media_type'] == 'popup') {
                    $d[13] .= '<a title="'.__('Preview', MASE_TEXT_DOMAIN).'" href="'.$ad['target_url'].'" target="_blank" class=""><span class="glyphicon glyphicon-picture"></span></a>';
                    $d[3] = '';
                }


                // Update Ad Type
                $d[4] = '<a class="mase_stats_update" data-value="'.$d[4].'" href="#">'.$d[4].'</a>';

                // Make Domain clickable
                $d[5] =  sprintf('<a target="_blank" href="%s">%s</a>', $d[5], $d[5]);

                //get Devicename from id
                foreach(self::getDeviceArray() as $info){
                    if($info["id"]==$d[6]){
                        $d[6]=$info["text"];
                    }
                }
                //get Usertype from id
                foreach(self::getUsersArray() as $info){
                    if($info["id"]==$d[7]){
                        $d[7]=$info["text"];
                    }
                }
                //get Connection from id
                foreach(self::getConnectionsArray() as $info){
                    if($info["id"]==$d[8]){
                        $d[8]=$info["text"];
                    }
                }
                if($d[8]=="0"){
                    $d[8]="-";
                }

            }
            unset($d);
        }

        echo json_encode($data);
        die();
    }


    public static function wp_ajax_mase_log_countries() {
        $countries = MASE_Pro_Api::getCountries();
        // Prepend All Countries
        $countries = array_merge(array('-1' => array('id' => '-1',
            'cc' => __('All Countries', MASE_TEXT_DOMAIN),
            'name' => __('All Countries', MASE_TEXT_DOMAIN))), $countries);


        if(isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
            foreach($countries as $id => $c) {
                if((stripos($c['cc'], $_REQUEST['q']) === false)) {
                    unset($countries[$id]);
                }
            }
        }

        echo json_encode($countries);
        die();
    }

    public static function deleteAd($post_id) {
        $args = array();
        $args['ad_id'] = get_post_meta($post_id, '_id', true);
        $args['domain'] = self::get_wp_domain();
        MASE_Pro_Api::deleteAd($args);
    }

    public static function CalculateAdCheckSum($post_id) {
        $ad = MASE_Ads_Generic::GetAd($post_id);
        $media_id = $ad['media_id'];
        $post = get_post($post_id);
        unset($ad['chksum']);
        unset($ad['id']);
        unset($ad['media_id']);
        $ad['post_status'] = $post->post_status;
        $ad['post_title'] = $post->post_title;
        $ad['post_type'] = $post->post_type;
        $ad['post_tags'] = wp_get_post_terms($post_id, MASE_PREFIX.'ad_tags', array('fields' => 'names'));

        if($ad['media_type'] == 'banner') {
            $file = get_attached_file($media_id);
            $file_data = false;
            if($file) $file_data = base64_encode(file_get_contents($file));
            if($file_data) $ad['media_payload'] = $file_data;
        } elseif($ad['media_type'] == 'html') {
            $ad['post_content'] = $post->post_content;
        }

        return sha1(json_encode($ad));
    }

    public static function UpdateAdCheckSum($post_id) {
        $checksum = MASE_Pro::CalculateAdCheckSum($post_id);
        return update_post_meta($post_id, '_chksum', $checksum);
    }

    public static function syncAd($post_id) {
        $ad = MASE_Ads_Generic::GetAd($post_id);

        $chksum = $ad['chksum'];
        $media_id = $ad['media_id'];

        $post = get_post($post_id);

        unset($ad['chksum']);
        unset($ad['id']);
        unset($ad['media_id']);

        $ad['post_status'] = $post->post_status;
        $ad['post_title'] = $post->post_title;
        $ad['post_type'] = $post->post_type;
        $ad['post_tags'] = wp_get_post_terms($post_id, MASE_PREFIX.'ad_tags', array('fields' => 'names'));

        if($ad['media_type'] == 'banner') {
            $file = get_attached_file($media_id);
            $file_data = false;
            if($file) $file_data = base64_encode(file_get_contents($file));
            if($file_data) $ad['media_payload'] = $file_data;
        } elseif($ad['media_type'] == 'html') {
            $ad['post_content'] = $post->post_content;
        }

        $new_chksum = sha1(json_encode($ad));
        if($chksum == $new_chksum) return;

        // Format API Query
        switch($ad['media_type']) {
            case 'banner':
                $ad_type = 1;
                break;
            case 'popup':
                $ad_type = 2;
                break;
            case 'html':
                $ad_type = 3;
                break;
            default:
                $ad_type = 0;
        }

        $args = array();
        $args['ad_id'] = $ad['pro_id'];
        $args['ad_type_id'] = $ad_type;
        $args['domain'] = self::get_wp_domain();
        $args['data'] = $ad;
        MASE_Pro_Api::syncAd($args);
        update_post_meta($post_id, '_chksum', $new_chksum);

    }

    public static function wp_ajax_mase_ad_sync_request() {
        if(self::$license['token'] != $_REQUEST['auth_token']) die(0);
        MASE_Ads_Generic::handleAdSync($_REQUEST);
        die();
    }


    public static function wp_ajax_mase_syncstatus() {
        if(self::$license['token'] != $_REQUEST['auth_token']) die(0);

        $data = get_plugin_data(MASE_PLUG_FILE);
        $res = array(
            'ads' => array(),
            'plugin_version' => $data['Version']
        );

        $qargs = array();
        $ads = MASE_Ads_Generic::GetAds($qargs);

        foreach($ads as $ad) {
            if(empty($ad['chksum']) || empty($ad['pro_id'])) continue;

            $res['ads'][$ad['pro_id']] = $ad['chksum'];
        }


        echo json_encode($res);
        die();
    }

    public static function get_user_connection() {
        if(!MASE_Pro::isVMTAPIActive() || !MASE_Pro::isSubscriptionActive()) return 0;

        if(self::$cache_connection !== NULL) {
            return self::$cache_connection;
        }

        $connection_data = MASE_Pro_Api::getConnection($_SERVER['REMOTE_ADDR']);
        if(isset($connection_data['result']) && !empty($connection_data['result'])) {
            switch($connection_data['result']) {
                case 'mobile':
                    self::$cache_connection = MASE_CONNECTION_3G;
                    return MASE_CONNECTION_3G;
                    break;
                case 'wired':
                    self::$cache_connection = MASE_CONNECTION_WIFI;
                    return MASE_CONNECTION_WIFI;
                    break;
                default:
                    self::$cache_connection = 0;
                    return 0;
            }
        } else {
            self::$cache_connection = 0;
            return 0;
        }
    }

    public static function wp_ajax_mase_pxcb() {
        $ad =MASE_Ads_Generic::GetAd((int)$_GET['id']);
        $ad_block = isset($_GET['ab']) && $_GET['ab'] == '1' ? true : false;
        $connection_id = isset($_GET['c']) ? (int) $_GET['c'] : 0;
        if($ad) MASE_Pro_Log::view($ad['pro_id'], $ad['media_type'], (int) MASE::get_user_device(), MASE::get_user_country(), $connection_id, $ad_block);
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header('Content-Type: image/gif');
        echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
        die();
    }

    public static function wp_ajax_mase_redirect() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if(isset($_GET['pid'])) $id = (int)$_GET['pid'];

        $ad =MASE_Ads_Generic::GetAd($id);
        $ad_block = isset($_GET['ab']) && $_GET['ab'] == '1' ? true : false;
        $connection_id = isset($_GET['c']) ? (int) $_GET['c'] : 0;
        MASE_Pro_Log::click($ad['pro_id'], $ad['media_type'], (int) MASE::get_user_device(), MASE::get_user_country(), $connection_id, $ad_block);
        if($ad) {
            if(MASE_Pro::isFPOPActive() && $ad_block && MASE_Pro::isSubscriptionActive()) {
                //echo '<html><head><meta http-equiv="refresh" content="0;url='.$ad['target_url'].'"></head><body></body></html>';
                $deliver_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_cst_redir&i=".urlencode($ad['target_url']);
                require_once MASE_DIR.'/lib/Ads/html/bypass_stage_1.php';
            } else {
                header('Location: '.$ad['target_url']);
            }
        } else {
            header('Location: '.MASE_Pro::get_wp_domain());
        }
        die();
    }

    public function wp_ajax_mase_cst_redir() {
        require_once MASE_DIR.'/lib/Ads/html/bypass_stage_2.php';
        die();
    }

    public static function wp_ajax_mase_redirect_cst() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if(isset($_GET['pid'])) $id = (int)$_GET['pid'];

        $ad =MASE_Ads_Generic::GetAd($id);
        $ad_block = isset($_GET['ab']) && $_GET['ab'] == '1' ? true : false;
        if($ad) {
            if(MASE_Pro::isFPOPActive() && $ad_block && MASE_Pro::isSubscriptionActive()) {
                $deliver_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_cst_redir&i=".urlencode($ad['target_url']);
                require_once MASE_DIR.'/lib/Ads/html/bypass_stage_1.php';
                //echo '<html><head><meta http-equiv="refresh" content="0;url='.$ad['target_url'].'"></head><body></body></html>';
            } else {
                header('Location: '.$ad['target_url']);
            }
        } else {
            header('Location: '.MASE_Pro::get_wp_domain());
        }
        die();
    }

    public static function wp_ajax_mase_ad_delete_request() {
        if(self::$license['token'] != $_REQUEST['auth_token']) die(0);
        MASE_Ads_Generic::handleAdDelete($_REQUEST);
        die();
    }

    public static function wp_ajax_poll_logs() {
        if(self::$license['token'] != $_REQUEST['auth_token']) die(0);
        MASE_Pro_Log::printLogs();
        MASE_Pro_Log::clearLogs();
        die();
    }

    public static function wp_ajax_status() {
        if(self::$license['token'] != $_REQUEST['auth_token']) die(0);

        $time = time();
        echo json_encode(array(
            'tid' => sha1($time.self::$license['token'].$time),
            'time' => time(),
            'is_pro' => self::isPro(),
            'is_subscription_active' => self::isSubscriptionActive(),
            'is_sync_active' => self::isSyncEnabled(),
            'is_statistics_active' => self::isStatisticsActive(),
            'is_vmt_api_active' => self::isVMTAPIActive()
        ));
        die();
    }

    public static function isPro() {
        return self::$license ? true : false;
    }

    public static function isSubscriptionActive() {
        return !empty(self::$license_status->subscription_start) && (strtotime(self::$license_status->subscription_end) > time() || empty(self::$license_status->subscription_end)) ? true : false;
    }

    public static function isStatisticsActive() {
        return ((bool) get_option(MASE_PREFIX.'enable_statistics') && MASE_Pro::isSubscriptionActive());
    }

    public static function isFPOPActive() {
        return ((bool) get_option(MASE_PREFIX.'enable_fpop') && MASE_Pro::isSubscriptionActive());
    }

    public static function isVMTAPIActive() {
        return ((bool) get_option(MASE_PREFIX.'enable_vmt_api') && MASE_Pro::isSubscriptionActive());
    }

    public static function getSubscription() {
        return self::$license_status;
    }

    public static function isSyncEnabled() {
        return ((bool) get_option(MASE_PREFIX.'enable_sync') && MASE_Pro::isSubscriptionActive());
    }

    public static function register() {
        if(!get_option(MASE_PREFIX.'registered') || get_option(MASE_PREFIX.'registered') != self::get_wp_domain()) {
            $response = MASE_Pro_Api::register(self::get_wp_domain());
            if(isset($response->status) && $response->status == 1) {
                update_option(MASE_PREFIX.'registered', self::get_wp_domain());
                return true;
            }
        }
        return false;
    }

    public static function unregister() {
        if(MASE_Pro::isPro()) MASE_Pro_Api::unregister(self::get_wp_domain());
    }

    public static function get_wp_domain() {
        return get_site_url();
    }

    public static function setupLicense($license) {
        $license_req = $license;
        $license = self::parseLicense($license);
        if(!$license) {
            $license = self::getDigiStoreLicense($license_req);
            if(!$license) return false;
        }
        update_option(MASE_PREFIX.'license', $license);
        self::$license = $license;
        MASE_Pro_Api::init(self::$license['username'], self::$license['token']);
        if(self::register() ) {
            $status = MASE_Pro_Api::getLicenseStatus();
            if($status) update_option(MASE_PREFIX.'license_status', $status);
            self::$license_status = get_option(MASE_PREFIX.'license_status');
            update_option(MASE_PREFIX.'last_license_check', time());
            return true;
        } else {
            delete_option(MASE_PREFIX.'license');
            self::$license = false;
        }
    }

    private static function parseLicense($license) {
        preg_match("#^([a-zA-Z0-9+\/\r\n=]{20,})#im", $license, $matches);
        if(isset($matches[0])) {
            $license = json_decode(self::xdec(base64_decode($matches[0]), 'MASE_OP_CODE'), true);
            if ($license) {
                return $license;
            }
        }
        return false;
    }

    private static function getDigiStoreLicense($license) {
        $license = trim($license);
        $res = MASE_Pro_Api::getLicenseByDigiStoreLicense($license);
        if(isset($res['status']) && $res['status'] && isset($res['user'])) {
            return $res['user'];
        }
        return false;
    }

    private static function xdec($string, $k) {
        for($i=0; $i<strlen($string); $i++) {
            for($j=0; $j<strlen($k); $j++) {
                $string[$i] = $k[$j]^$string[$i];
            }
        }
        return $string;
    }
}