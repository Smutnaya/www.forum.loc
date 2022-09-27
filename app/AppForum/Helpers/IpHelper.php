<?php

namespace App\AppForum\Helpers;

class IpHelper
{
    public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }
    public static function getLocation($ip)
    {
        $json     = file_get_contents("http://ipinfo.io/$ip/geo");
        $json     = json_decode($json, true);
        $location = [
            'country'  => $json['country'],
            'region'   => $json['region'],
            'city'     => $json['city']
        ];

        return ($location);
    }
}
