<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 13/04/2018
 * Time: 13:59
 * Source document: https://twitteroauth.com/
 * Source Code: https://github.com/abraham/twitteroauth
 * Source example: https://www.thepolyglotdeveloper.com/2014/11/understanding-request-signing-oauth-1-0a-providers/
 */

session_start();
// parameters of the application
$app_consumer_key = '';
$app_consumer_key_secret = '';
$app_access_token = '';
$app_access_token_secret = '';

// parameter of api to request user token
$api_uri = 'https://api.twitter.com/oauth/request_token';
$api_callback_uri = 'http://localhost/rs/twitter/main/callback.php';
$api_consumer_key = $app_consumer_key;
$api_oauth_nonce = '32 bits string';
$api_oauth_signature_method = 'HMAC-SHA1';
$api_oauth_timestamp = '1523625299';
$api_oauth_version = '1.0';


// Import the TwitterOAuth class.
require_once '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// Start making API requests.
$connection = new TwitterOAuth("$app_consumer_key",
    "$app_consumer_key_secret");

// Authorizing access to a users account through OAuth starts with getting a temporary request_token.
// This request_token is only good for a few minutes and will soon be forgotten about.
$request_token = $connection->oauth('oauth/request_token',
    array('oauth_callback' => $api_callback_uri));

$oauth_token = $request_token['oauth_token'];
$oauth_token_secret = $request_token['oauth_token_secret'];
$oauth_callback_confirmed = $request_token['oauth_callback_confirmed'];

$_SESSION['oauth_token_secret'] = $oauth_token_secret;

// Here we are building a URL the authorizing users must navigate to in their browser.
// It is to Twitter's authorize page where the list of permissions being granted is displayed along with allow/deny buttons.
$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

// $url contains the url of authentication
print '<a href="'.$url.'" target="_blank"><button type="button">Authentication</button></a>';

//$_SESSION['connexion'] = $connection;

?>