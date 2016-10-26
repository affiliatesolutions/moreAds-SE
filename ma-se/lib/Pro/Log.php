<?php

class MASE_Pro_Log {

    public static function view($pro_ad_id, $ad_type, $device_id, $cc, $connection_id, $ad_block) {
        $args = array(
            'pro_id' => (string) $pro_ad_id,
            'ad_type' => (string) $ad_type,
            'device_id' => (int) $device_id,
            'con_id' => (int) $connection_id,
            'cc' => (string) $cc,
            'domain' => (string) MASE_Pro::get_wp_domain(),
            'remote_addr' => (string) $_SERVER['REMOTE_ADDR'],
            'action' => 'view',
            'ab' => (bool) $ad_block,
            'time' => time()
        );

        self::save_log_data($args);
    }

    public static function click($pro_ad_id, $ad_type, $device_id, $cc, $connection_id, $ad_block) {
        $args = array(
            'pro_id' => (string) $pro_ad_id,
            'ad_type' => (string) $ad_type,
            'device_id' => (int) $device_id,
            'con_id' => (int) $connection_id,
            'cc' => (string) $cc,
            'domain' => (string) MASE_Pro::get_wp_domain(),
            'remote_addr' => (string) $_SERVER['REMOTE_ADDR'],
            'action' => 'click',
            'ab' => (bool) $ad_block,
            'time' => time()
        );

        self::save_log_data($args);
    }

    public static function printLogs() {
        if(file_exists(self::get_log_file_path())) {
            $fh = fopen(self::get_log_file_path(), 'r');
            if(!$fh) return false;
            fpassthru($fh);

            fclose($fh);
            return true;
        }
        return false;

        global $wpdb;
        $SQL = "SELECT option_value FROM $wpdb->options WHERE option_name = 'mase_logging'";
        return $wpdb->get_var($SQL);
    }
    public static function clearLogs() {
        return file_put_contents(self::get_log_file_path(), '');
    }

    private static function save_log_data($data) {
        $file = self::get_log_file_path();
        $data_json = json_encode($data).',';
        if(!file_exists($file)) {
            @touch($file);
        }

        if(file_exists($file)) {
            file_put_contents($file, $data_json, FILE_APPEND);
            return true;
        }
        return false;
    }

    private static function get_log_file_path() {
        $file =get_option(MASE_PREFIX.'log_file_name');
        if(!$file) {
            $file = substr( md5(time()), 0, 15).'.log';
            update_option(MASE_PREFIX.'log_file_name', $file);
        }
        return MASE_DIR.'/'.'tmp/'.$file;
    }
}