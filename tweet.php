<?php
require_once('TwitterAPIExchange.php');
require_once('functions.php');
require_once('bridgeService.php');

$bridge_status = getBridgeStatus();
$last_bridge_status = getLastBridgeStatus();

// If the current status is not the same as the last one
// Update the bridge status and send a tweet
if($bridge_status != $last_bridge_status) {
    $tweet_result = false;
    $updateBridgeStatus_result = false;

    // Update database
    $updateBridgeStatus_result = updateBridgeStatus($bridge_status);

    if(!$updateBridgeStatus_result) {
        // Error updating
        // echo("<pre>Error updating bridge status!</pre>");
        write_log('Error updating bridge status', 'log.txt');
    } else {
        write_log('Success updating bridge status', 'log.txt');
    }

    if($bridge_status) {
        $tweets = array(
            'Göta älvbron har öppnat! - http://götaälvbron.se',
            'Göta älvbron har nu öppnat! - http://götaälvbron.se',
            'Göta älvbron är nu öppen! - http://götaälvbron.se',
            'Göta älvbron är öppen! - http://götaälvbron.se',
            'Göta älvbron öppnas! - http://götaälvbron.se',
            'Göta älvbron öppnas nu! - http://götaälvbron.se'
        );
    } else {
        $tweets = array(
            'Göta älvbron har stängt! - http://götaälvbron.se',
            'Göta älvbron har nu stängt! - http://götaälvbron.se',
            'Göta älvbron är nu stängd! - http://götaälvbron.se',
            'Göta älvbron är stängd! - http://götaälvbron.se',
            'Göta älvbron stängs! - http://götaälvbron.se',
            'Göta älvbron stängs nu! - http://götaälvbron.se'
        );
    }

    $res = array_rand($tweets, 1);
    $tweet = $tweets[$res];

    // Send tweet
    $parameters = array(
        'status' => $tweet, 
        'lat' => '57.714653', 
        'long' => '11.966836',
        'place_id' => '53e060d6652640f4',
        'display_coordinates' => true
    );

    $tweet_result = sendTweet($parameters);
    write_log(json_encode($tweet_result), 'tweet_log.txt');

    if(!$tweet_result) {
        // Error posting tweet
        // echo("<pre>Error posting tweet</pre>");
        // echo $tweet_result;
        write_log('Error posting tweet: '.$tweet, 'log.txt');
    } else {
        // Success posting tweet
        // echo("<pre>Success posting tweet</pre>");
        // echo $tweet_result;

        write_log('Success posting tweet: '.$tweet, 'log.txt');
    }
} else {
    // write_log('No change', 'log.txt');
}

?>