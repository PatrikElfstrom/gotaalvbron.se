<?php

require_once('config.php');

function databaseConnection() {
    global $host;
    global $username;
    global $password;
    global $database;

    return mysqli_connect($host, $username, $password, $database);
}

function getLastBridge() {
    $db = databaseConnection();

    $results = mysqli_query($db, 'SELECT * FROM bridge_status ORDER BY timestamp DESC LIMIT 1');

    if($results) {

        $last_bridge = mysqli_fetch_assoc($results);
        return $last_bridge;

    } else {
        return false;
    }
}

function getLastBridgeStatus() {
    $db = databaseConnection();

    $last_bridge = getLastBridge();

    if($last_bridge) {
        $bridge_status = $last_bridge['status'];

        if($bridge_status == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function updateBridgeStatus($bridge_status) {
    $db = databaseConnection();

    if($bridge_status) {
        $status = 1;
    } else {
        $status = 0;
    }

    $results = mysqli_query($db, "INSERT INTO bridge_status SET status = $status");

    if($results) {
        return true;
    }

    return false;
}

function sendTweet($parameters) {
    global $oauth_access_token;
    global $oauth_access_token_secret;
    global $consumer_key;
    global $consumer_secret;
    
    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
        'oauth_access_token' => $oauth_access_token,
        'oauth_access_token_secret' => $oauth_access_token_secret,
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret
    );

    /** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
    $url = 'https://api.twitter.com/1.1/statuses/update.json';

    $twitter = new TwitterAPIExchange($settings);
    $result = $twitter->buildOauth($url, 'POST')->setPostfields($parameters)->performRequest();

    if($result) {
        return $result;
    } else {
        return false;
    }
}

function write_log($data, $file) {
    if($data && $file) {
        date_default_timezone_set('Europe/Stockholm');
        $path = dirname(__FILE__);
        file_put_contents($path.'/log/'.$file, date('r').' - '.$data."\n", FILE_APPEND);
    }
}