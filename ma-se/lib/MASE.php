<?php
defined( 'ABSPATH' ) or die();
class MASE {
    private static $cache_cc = false;
    private static $cache_is_mobile = false;
    private static $cache_is_tablet = false;
    public static $float_deps_loaded = false;
    public static $exitintent_deps_loaded = false;
    public static $WIDGET_IDS = array();
    public static $JS_ZONES = array();
    public static $WIDGET_TMP_DATA = array();

    public static $ZONE_HOURS_OF_DAY = true;
    public static $ZONE_DAYS_OF_WEEK = true;
    public static $ZONE_WEIGHT = true;

    public static $ZONE_MENU = true;

    public static $countries = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );

    public static function init() {
        self::$ZONE_WEIGHT = (bool) get_option(MASE_PREFIX.'ZONE_WEIGHT');
        self::$ZONE_DAYS_OF_WEEK = (bool) get_option(MASE_PREFIX.'ZONE_DAYS_OF_WEEK');
        self::$ZONE_HOURS_OF_DAY = (bool) get_option(MASE_PREFIX.'ZONE_HOURS_OF_DAY');
        self::$ZONE_MENU = (bool) get_option(MASE_PREFIX.'ZONE_MENU');

        MASE_Pro::init();
        MASE_Ads_Generic::init();
        MASE_Ads_Banner::init();
        MASE_Ads_HTML::init();
        MASE_Ads_Popup::init();
        MASE_Widgets::init();
        MASE_Menu::init();
        MASE_Ads_TagTaxonomy::init();
        MASE_Shortcode_Widgets::init();
        add_action('plugins_loaded', array('MASE', 'wp_action_plugins_loaded'));
        add_action('wp_footer', array('MASE', 'print_js_zones'));
        add_action('wp_head', array('MASE', 'wp_action_wp_head'), 0);

        add_action('template_redirect', array('MASE', 'buffer_start'), 0);
        add_action('shutdown', array('MASE', 'buffer_end'));

        if(is_admin()) MASE_Admin::init();

        add_action('wp_ajax_nopriv_mase_get_widgets', array('MASE', 'wp_ajax_nopriv_mase_get_widgets'));
        add_action('wp_ajax_mase_get_widgets', array('MASE', 'wp_ajax_nopriv_mase_get_widgets'));

        add_action('wp_ajax_nopriv_mase_menu_redirect', array('MASE', 'wp_ajax_nopriv_mase_menu_redirect'));
        add_action('wp_ajax_mase_menu_redirect', array('MASE', 'wp_ajax_nopriv_mase_menu_redirect'));
    }

    public static function buffer_start() {
        ob_start(array('MASE', 'buffer_content'));
    }

    public static function buffer_end() {
        ob_end_flush();
    }

    public static function buffer_content($content) {
        $content = str_replace('"MASE_WIDGET_IDS"', '"'.base64_encode(json_encode(self::$WIDGET_IDS)).'"', $content);
        return $content;
    }

    public static function wp_action_plugins_loaded() {
        load_plugin_textdomain(MASE_TEXT_DOMAIN, false, MASE_TEXTDOMAIN_PATH);
    }

    public static function get_user_country($ip=false) {
        if(!$ip) $ip = $_SERVER['REMOTE_ADDR'];
        $db = get_option(MASE_PREFIX.'geoip_db');
        if(!$db) return false;

        if(!file_exists($db) || !is_readable($db)) {
            delete_option(MASE_PREFIX.'geoip_db');
            if(!empty($db)) delete_option(MASE_PREFIX.'country-notice-dismissed'); // Delete if it was set
            return false;
        }

        $ctry = false;
        require_once(MASE_DIR.'/lib/GeoIP.php');

        if(!empty(self::$cache_cc)) $ctry = self::$cache_cc;

        if (function_exists('masegeoip_open') && empty(self::$cache_cc)) {
            $gi = masegeoip_open($db, MASEGEOIP_STANDARD);
            $ctry = strtoupper(masegeoip_country_code_by_addr($gi, $ip));
        }
        return !empty($ctry) ? $ctry : false;
    }

    public static function hasGeoIPDatabase() {
        //delete_option(MASE_PREFIX.'geoip_db');
        $geoip = get_option(MASE_PREFIX.'geoip_db');

        if(!file_exists($geoip) || !is_readable($geoip)) {
            delete_option(MASE_PREFIX.'geoip_db');
            if(!empty($geoip)) delete_option(MASE_PREFIX.'country-notice-dismissed'); // Delete if it was set
            return false;
        }

        if($geoip && self::get_user_country('193.99.144.80')) {
            return true;
        }

        if(is_admin()) {
            delete_option(MASE_PREFIX.'geoip_db');
            if(!empty($geoip)) {
                delete_option(MASE_PREFIX.'country-notice-dismissed');
            }
        }

        return false;
    }

    public static function get_user_device() {
        require_once(MASE_DIR.'/lib/MASE_MobileDetect.php');
        $md = false;
        if(empty(self::$cache_is_mobile)) {
            if(!$md) $md = new MASE_Mobile_Detect();
            self::$cache_is_mobile = $md->isMobile();
        }
        if(empty(self::$cache_is_tablet)) {
            if(!$md) $md = new MASE_Mobile_Detect();
            self::$cache_is_tablet = $md->isTablet();
        }

        if(self::$cache_is_tablet) {
            return MASE_DEVICE_TABLET;
        } elseif(self::$cache_is_mobile) {
            return MASE_DEVICE_MOBILE;
        } else {
            return MASE_DEVICE_DESKTOP;
        }
    }

    public static function print_js_zones() { ?>

            <?php foreach(self::$JS_ZONES as $zone) { ?>
            <?php if(isset($zone['src'])) { ?>
            <script type="text/javascript">
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = '<?php echo $zone['src']; ?>';
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
            </script>
            <?php } elseif(isset($zone['html'])) { ?>
            <?php echo $zone['html']; ?>
            <?php } else { ?>
            <script type="text/javascript">
            <?php echo $zone['js']; ?>
            </script>
            <?php } ?>
            <?php } ?>
        </script>
        <?php
    }

    public static function isWidgetJSDeliveryActive() {
        return true;
    }

    public static function wp_action_wp_head() {

        echo "<script type=\"text/javascript\">var adb = 1; IDS = \"MASE_WIDGET_IDS\"; var mase_ajaxurl = '".admin_url('admin-ajax.php')."';</script>";
        echo "<script type=\"text/javascript\" src=\"".MASE_URL.'static/js_delivr/show_ads.js'."\"></script>";
        if(MASE::isWidgetJSDeliveryActive()) {
        echo "<script type=\"text/javascript\">";
        echo file_get_contents(MASE_DIR.'/static/js_delivr/atomic.min.js')."\n";
        echo file_get_contents(MASE_DIR.'/static/js_delivr/deliver.js')."\n";
        echo "</script>";
        }
    }

    public static function GetZoneCount($zone_identifier) {
        $zone_ads = get_option($zone_identifier);
        if(empty($zone_ads) || !is_array($zone_ads)) return 0;

        return count(MASE_Ads_Generic::GetAds(array('ids' => array_keys($zone_ads))));
    }

    public static function wp_ajax_nopriv_mase_menu_redirect() {
        $redirect = get_site_url();
        $ad_block = isset($_GET['ab']) && $_GET['ab'] == '1' ? true : false;

        if(isset($_GET['id']) && isset($_GET['mid'])) {
            $id = (int) $_GET['id'];
            $mid = $_GET['mid'];

            $menue = wp_get_nav_menu_items($mid);
            if(!empty($menue) && is_array($menue)) {
                foreach($menue as $item) {
                    if($item->ID == $id) {
                        $redirect = $item->url;
                    }
                }
            }

            $res = get_post_meta($id, 'menu-item-mase-is-menu-zone', true);
            if(!empty($res)) {
                    $zone_identifier = MASE_PREFIX.'menu_zone_ads_'.$id;
                    $zone_ads = get_option($zone_identifier);
                    $device_id = MASE::get_user_device();

                    if(!empty($zone_ads)) {
                    $query_args = array();
                    $query_args['disabled'] = 0;

                    $query_args['device_id'] = $device_id;

                    $country = MASE::get_user_country();
                    if($country) $query_args['country'] = $country;
                    $query_args['ids'] = array_keys($zone_ads);

                    $connection_id = MASE_Pro::get_user_connection();
                    if($connection_id) $query_args['connection_id'] = $connection_id;

                    $ads = MASE_Ads_Generic::GetAds($query_args);

                    if(!empty($ads)) {
                        $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);

                        if($ad) {
                            MASE_Pro_Log::click($ad['pro_id'], $ad['media_type'], (int) MASE::get_user_device(), MASE::get_user_country(), MASE_Pro::get_user_connection(), $ad_block);
                            if($ad) {
                                $redirect = $ad['target_url'];
                            }
                        }
                    }
                }
            }
        }

        if(empty($redirect)) $redirect = get_site_url();

        if(MASE_Pro::isFPOPActive() && MASE_Pro::isSubscriptionActive()) {
            $deliver_url = get_admin_url(null, 'admin-ajax.php')."?action=mase_cst_redir&i=".urlencode($redirect);
            require_once MASE_DIR.'/lib/Ads/html/bypass_stage_1.php';
            //echo '<html><head><meta http-equiv="refresh" content="0;url='.$redirect.'"></head><body></body></html>';
        } else {
            header('Location: '.$redirect);
        }
        die();
    }

    public static function wp_ajax_nopriv_mase_get_widgets() {
        $resp=array();
        $re = "/(?P<widget_base>.*)-(?P<widget_id>\\d+)$/";
        $req = json_decode(file_get_contents("php://input"), true);
        $ad_block = isset($_GET['ab']) && $_GET['ab'] == '1' ? true : false;
        if(!empty($req)) {
            foreach($req as $widget_id) {
                preg_match($re, $widget_id, $widget_info);
                if(!empty($widget_info)) {
                    $widget_options = get_option('widget_'.$widget_info['widget_base']);
                    if($widget_options && isset($widget_options[(int) $widget_info['widget_id']]) && !empty($widget_options[(int) $widget_info['widget_id']])) {
                        $instance = $widget_options[(int) $widget_info['widget_id']];

                        $widget_class = false;
                        switch($widget_info['widget_base']) {
                            case 'mase_banner_widget':
                            $widget_class = 'MASE_Banner_Widget';
                            break;
                            case 'mase_float_widget':
                            $widget_class = 'MASE_Float_Widget';
                            break;
                            case 'mase_popup_widget':
                            $widget_class = 'MASE_Popup_Widget';
                            break;
                            case 'mase_textlink_widget':
                            $widget_class = 'MASE_TextLink_Widget';
                            break;
                            case 'mase_exitintent_widget':
                            $widget_class = 'MASE_ExitIntent_Widget';
                            break;
                        }

                        if($widget_class) {
                            $instance = wp_parse_args($instance);
                            ob_start();
                            the_widget($widget_class, $instance, array('xhr' => true, 'override_widget_id' => (int) $widget_info['widget_id'], 'ad_block' => $ad_block));
                            $deliver_options = array('m' => '', 'mo' => array());
                            if(!empty(self::$WIDGET_TMP_DATA)) {
                                $deliver_options['mo'] = array('w' => self::$WIDGET_TMP_DATA['w'], 'h' => self::$WIDGET_TMP_DATA['h'] );
                                if(self::$WIDGET_TMP_DATA['m']) $deliver_options['m'] = self::$WIDGET_TMP_DATA['m'];
                            }
                            $resp[$widget_id] = array('d' => base64_encode(ob_get_contents()), 'm' => $deliver_options['m'], 'mo' => $deliver_options['mo']);
                            if(!empty(self::$WIDGET_TMP_DATA['p'])) $resp[$widget_id]['p'] = self::$WIDGET_TMP_DATA['p'];
                            ob_end_clean();
                            self::$WIDGET_TMP_DATA = array();
                        }
                    }
                }
            }
        }

        echo json_encode($resp);
        die();
    }
}