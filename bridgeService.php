<?php

function getBridgeStatus() {
	$api_format = 'json';
	$api_url = 'http://data.goteborg.se/BridgeService/v1.0/GetGABOpenedStatus/'.$api_key.'?format='.$api_format;

    $bridge_status_json = get($api_url);
    
    if($bridge_status_json) {
        $bridge_status = json_decode($bridge_status_json);
    }
    
    if(isset($bridge_status->{"Value"})) {
        if($bridge_status->{"Value"} === true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get($url = false) {
    if($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    } else {
        return false;
    }
}

?>