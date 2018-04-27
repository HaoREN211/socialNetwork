<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 23/04/2018
 * Time: 11:03
 * Source: https://github.com/cosenary/Instagram-PHP-API
 */


require_once ('../vendor/autoload.php');
use MetzWeb\Instagram\Instagram;

$APP_KEY = '';
$APP_SECRET = '';
$APP_CALLBACK = 'http://localhost/rs/instagram/main/';

echo '<h3>Instagram</h3><br />';
$instagram = new Instagram(array(
    'apiKey'      => $APP_KEY,
    'apiSecret'   => $APP_SECRET,
    'apiCallback' => $APP_CALLBACK
));

if(isset($_GET['code'])){
    $code = $_GET['code'];
    $data = $instagram->getOAuthToken($code);
    echo 'Your username is: ' . $data->user->username;
}
else{
    echo "<a href='{$instagram->getLoginUrl()}' target='_blank'>
        <button type='button'>Login with Instagram
        </button></a>";
}

