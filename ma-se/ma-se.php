<?php
/*
Plugin Name:        moreAds SE
Plugin URI:         https://www.affiliate-solutions.xyz/produkte/moreads-se/
Description:        moreAds SE is a standalone ad server used as a WordPress plugin
Version:            1.4.6
Author:             Affiliate Solutions S.L.U
Author URI:         https://www.affiliate-solutions.xyz/produkte/moreads-se/
License:            GPLv2
Text Domain:        mase
Domain Path: /languages/
 */

defined( 'ABSPATH' ) or die();

define('MASE_WPDIR', ABSPATH);
define('MASE_DIR', plugin_dir_path(__FILE__));
define('MASE_URL', plugin_dir_url(__FILE__));
define('MASE_SLUG', plugin_basename(__FILE__));
define('MASE_TEXTDOMAIN_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/');

define('MASE_PLUG_FILE', __FILE__);
define('MASE_DEVICE_DESKTOP', 1);
define('MASE_DEVICE_TABLET', 2);
define('MASE_DEVICE_MOBILE', 3);
define('MASE_TEXT_DOMAIN', 'mase');
define('MASE_PREFIX', 'mase_');
define('MASE_CONNECTION_3G', 1);
define('MASE_CONNECTION_WIFI', 2);

require_once(MASE_DIR.'/lib/Ads/Generic.php');
require_once(MASE_DIR.'/lib/Ads/Banner.php');
require_once(MASE_DIR.'/lib/Ads/HTML.php');
require_once(MASE_DIR.'/lib/Ads/Popup.php');
require_once(MASE_DIR.'/lib/Ads/TagTaxonomy.php');
require_once(MASE_DIR.'/lib/Ads/CustomColumns.php');
require_once(MASE_DIR.'/lib/Ads/MetaBoxes.php');
require_once(MASE_DIR.'/lib/Zones/Banner.php');
require_once(MASE_DIR.'/lib/Zones/TextLink.php');
require_once(MASE_DIR.'/lib/Zones/Menu.php');
require_once(MASE_DIR.'/lib/Zones/Popup.php');
require_once(MASE_DIR.'/lib/Zones/Float.php');
require_once(MASE_DIR.'/lib/Zones/ExitIntent.php');
require_once(MASE_DIR.'/lib/Pro/Api.php');
require_once(MASE_DIR.'/lib/Pro/Log.php');
require_once(MASE_DIR.'/lib/MASE_Menu.php');
require_once(MASE_DIR.'/lib/MASE_Widgets.php');
require_once(MASE_DIR.'/lib/MASE_Pro.php');
require_once(MASE_DIR.'/lib/MASE_Shortcode_Widgets.php');
require_once(MASE_DIR.'/lib/MASE.php');
require_once(MASE_DIR.'/lib/MASE_Admin.php');
MASE::init();