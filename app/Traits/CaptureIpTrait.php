<?php

namespace App\Traits;

trait CaptureIpTrait {

    public $ipAddress = NULL;

    public function getClientIp() {

        if(getenv('HTTP_CF_CONNECTING_IP'))
        {
            $ipAddress = getenv('HTTP_CF_CONNECTING_IP');
        }
        else if(getenv('HTTP_X_FORWARDED_FOR'))
        {
            // Header can contain multiple IP-s of proxies that are passed through.
            // Only the IP added by the last proxy (last IP in the list) can be trusted.
            $proxy_list = explode (",", getenv('SERVER_X_ADDR'));
            $ipAddress = $proxy_list[0];

            // Validate just in case
            if (filter_var ($ipAddress, FILTER_VALIDATE_IP)) {
                return $ipAddress;
            } else {
                // Validation failed - beat the guy who configured the proxy or
                // the guy who created the trusted proxy list?
                // TODO: some error handling to notify about the need of punishment
            }
        }
        else if(getenv('HTTP_X_FORWARDED'))
        {
            $ipAddress = getenv('HTTP_X_FORWARDED');
        }
        else if(getenv('HTTP_FORWARDED_FOR'))
        {
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        }
        else if(getenv('HTTP_FORWARDED'))
        {
            $ipAddress = getenv('HTTP_FORWARDED');
        }
        else if(getenv('REMOTE_ADDR'))
        {
            $ipAddress = getenv('REMOTE_ADDR');
        }
        else
        {
            $ipAddress = config('settings.nullIpAddress');
        }

        return $ipAddress;
    }

}

