<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 13/04/2018
 * Time: 14:09
 */

session_start();
require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// parameters of the application
$app_consumer_key = 'aVvwDDs9SpMdOlaXyQzmBPHcU';
$app_consumer_key_secret = 'qjFhahHfT1Td7bz9mZkDfY5cNYdELpTJ7NMol6JFpdsa0sCpKi';
$app_access_token = '2837985082-bOUOJl3G9RidyBvCu4xunOzgjSFGE4CsqBs2ZDO';
$app_access_token_secret = 'sLCDORfXGAmHu10k54sX394QpNwTtlhsnPYsmpJPTVMxW';



$get_oauth_token = null;
$get_oauth_verifier = null;
$oauth_token_secret = null;

// retrieve the oauth token and oauth verifier
if(isset($_GET['oauth_token'])){
    $get_oauth_token = $_GET['oauth_token'];
}

if(isset($_GET['oauth_verifier'])){
    $get_oauth_verifier = $_GET['oauth_verifier'];
}

if(isset($_SESSION['oauth_token_secret'])){
    $oauth_token_secret = $_SESSION['oauth_token_secret'];
}



// Start making API requests.
$connection = new TwitterOAuth("$app_consumer_key",
    "$app_consumer_key_secret",
    "$get_oauth_token",
    "$oauth_token_secret");
$content = $connection->get("account/verify_credentials");

//echo 'oauth_token: '.$get_oauth_token.'<br />';
//echo 'oauth_verifier: '.$get_oauth_verifier.'<br />';
//echo 'oauth_token_secret: '.$oauth_token_secret.'<br />';

$access_token = $connection->oauth("oauth/access_token",
    array('oauth_verifier' => $get_oauth_verifier));


/**
 * We save user token and its code secret
 * We save the user screen name ($user_screen_name) as the account name in database
 */
$user_oauth_token = $access_token['oauth_token'];
$user_oauth_token_secret = $access_token['oauth_token_secret'];
$user_id = $access_token['user_id'];
$user_screen_name = $access_token['screen_name'];
echo 'access token: '.$user_oauth_token.'<br />';
echo 'token secret: '.$user_oauth_token_secret.'<br />';
echo 'user id: '.$user_id.'<br />';
echo 'user screen name secret: '.$user_screen_name.'<br />';

session_destroy();