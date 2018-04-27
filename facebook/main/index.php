<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 12/04/2018
 * Time: 12:14
 * Source: https://github.com/facebook/php-graph-sdk
 */

if(!session_id()) {
    session_start();
}

require_once '../vendor/autoload.php';

/**
 * Application
 * Retrieve from
 * https://developers.facebook.com/apps/
 */
$app_id = '';
$app_secret = '';
$app_version = '';


/**
 * configuration for the application with the parameters
 */
$config =[
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => $app_version,
];

$fb = new \Facebook\Facebook($config );

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();


/**
 * Require the scope for retrieving the data of page
 * remember that manage_pages and read_insights is required
 */
try {
    $permissions = ['read_insights',
        'manage_pages',
        'email',
        'user_events',
        'public_profile',
        'user_birthday',
        'user_friends',
        'user_hometown',
        'user_location',
        'user_likes',
        'user_photos',
        'user_posts',
        'user_tagged_places',
        'user_videos',
        'user_events',
        'user_managed_groups'];
    $loginUrl = $helper->getLoginUrl('http://localhost/rs/facebook/main/fb-callback.php', $permissions);

    /**
     * Show the link for the authentication
     */
    echo '<a href="' . htmlspecialchars($loginUrl) . '" target="_blank"><button type="button">Log in with Facebook!</button></a>';

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}


if (! isset($accessToken)) {
    exit;
}


