<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 12/04/2018
 * Time: 14:00
 * Source: https://github.com/facebook/php-graph-sdk
 */

if(!session_id()) {
    session_start();
}

require_once '../vendor/autoload.php';

/**
 * Application
 */
$app_id = '';
$app_secret = '';
$app_version = '';

$config =[
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => $app_version,
];

$fb = new Facebook\Facebook($config );

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    var_dump($helper->getError());
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
echo '<h3>Access Token</h3>';
/**
 * Short lived token
 */
var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId("$app_id"); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
    }

    /**
     * long-lived user token
     * It's the long-lived user token that we save in our database
     */
    echo '<h3>Long-lived Token</h3>';
    $longLivedToken = (string)$accessToken->getValue();
    echo '<br />'.$longLivedToken.'<br />';
//    var_dump($accessToken->getValue());
    echo '<br />';


    /**
     * Show all page that are managed by the user
     * We save the page tag name ($username) in our database as the account name
     */
    $pages = $fb->get('me/accounts',
        "$longLivedToken");
    echo '<br /><br />';
    echo '<h3>Facebook Pages</h3>';
    echo '<table border="1"><tr>
        <th>Page Id</th>
        <th>Page Tag Name</th>
        <th>Page Name</th>
        <th>Page Category</th>
        <th>Page Permission</th></tr>';
    $datas = json_decode($pages->getBody());
    $pages = $datas->data;
    foreach ($pages as $page){
        $category = (String)$page->category;
        $name = (String)$page->name;
        $id = (String)$page->id;
        $perms = (String)implode(',', $page->perms);
        $page_access_token = $page->access_token;
        $single_page = $fb->get("$id".'?fields=id,name,username',
            "$page_access_token");
        $response = json_decode($single_page->getBody());
        $username = $response->username;

        echo '<tr>
            <td>'.$id.'</td>
            <td>'.$username.'</td>
            <td>'.$name.'</td>
            <td>'.$category.'</td>
            <td>'.$perms.'</td></tr>';
    }
    echo '</table>';
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
