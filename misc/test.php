<?php

include('GeoIP.inc.php');

$ip="8.8.8.8";
$gi = masegeoip_open("mmisp.dat",MASEGEOIP_ISP_EDITION);
$data =  masegeoip_org_by_addr($gi, $ip);
var_dump($data);
