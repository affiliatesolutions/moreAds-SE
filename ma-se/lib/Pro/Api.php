<?php

class MASE_Pro_Api {
    private static $_api_url = 'http://mase-api.affiliate-solutions.xyz';

    private static $_api_vmt_url = 'http://mase-vmt-api.affiliate-solutions.xyz';
    private static $_username = false;
    private static $_token = false;

    public static function syncAd($args) {
        $args['action'] = 'addAd';
        return json_decode(self::do_http_query($args));
    }

    public static function deleteAd($args) {
        $args['action'] = 'deleteAd';
        return json_decode(self::do_http_query($args));
    }

    public static function init($username, $token) {
        self::$_username = $username;
        self::$_token = $token;
    }

    public static function getLicenseStatus() {
        return json_decode(self::do_http_query(array(
            'action' => 'GetLicenseStatus'
        )));
    }

    public static function getDomains() {
        return json_decode(self::do_http_query(array(
            'action' => 'Select2_GetDomainsByUser',
        )), true);
    }

    public static function getStats($query) {
        return json_decode(self::do_http_query(array_merge($query, array(
            'action' => 'GetStats2',
        )), false, 60), true);
    }

    public static function getCountries() {
        return json_decode(self::do_http_query(array(
            'action' => 'Select2_GetCountries',
        )), true);
    }

    public static function register($domain) {
        return json_decode(self::do_http_query(array(
            'action' => 'registerDomain',
            'domain' => $domain
        )));
    }

    public static function unregister($domain) {
        return json_decode(self::do_http_query(array(
            'action' => 'unregisterDomain',
            'domain' => $domain
        )));
    }

    public static function getConnection($ip) {
        return json_decode(self::do_http_query(array(
            'ip' => $ip
        ), self::$_api_vmt_url.'/'), true);
    }

    public static function getLicenseByDigiStoreLicense($license) {
        return json_decode(self::do_http_query(array(
            'action' => 'GetLicenseByDigiStore24License',
            'license' => $license
        )), true);
    }

    private static function do_http_query($args, $api_url=false, $timeout=15) {
        if(!$api_url) {
            $api_url = self::$_api_url.'/'.$args['action'].'/';
        }

        if(self::$_username && self::$_token) {
            $args['username'] = self::$_username;
            $args['token'] = self::$_token;
        }

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($args),
                'timeout' => (int)$timeout
            )
        );
        $context  = stream_context_create($opts);

        return @file_get_contents($api_url, false, $context);
    }

}