<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 23/04/2018
 * Time: 12:14
 * Source Code: https://github.com/google/google-api-php-client
 * Source Example YouTue: https://developers.google.com/youtube/v3/code_samples/php
 * API Console: https://developers.google.com/apis-explorer/
 * Source Example google analytics: https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/accounts/list
 * Source Example google analytics: https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/profiles/list
 */



/**
 * it's required to change the $redirect_uri and $redirect_uris
 */
require_once '../vendor/autoload.php';
$client_id='';
$client_secret='';
$redirect_uris='["http://localhost/rs/google/main/"]';
$redirect_uri = 'http://localhost/rs/google/main/';
$file_oauth_name = 'client_secrets.json';


// Content of the json file that contains the user oauth's information
$config = "{
  \"web\": {
    \"client_id\": \"$client_id\",
    \"client_secret\": \"$client_secret\",
    \"redirect_uris\": $redirect_uris,
    \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",
    \"token_uri\": \"https://accounts.google.com/o/oauth2/token\"
  }
}";

// generate the authorisation json file for creating google object
$file_client = fopen("$file_oauth_name", "w");
fwrite($file_client, $config);
fclose($file_client);

// Configuration
$client = new Google_Client();
$client->setAuthConfig($file_oauth_name);
$client->setAccessType("offline");        // offline access
$client->setPrompt("consent");            // get refresh token all the time
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
$client->addScope(Google_Service_YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT);
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->setRedirectUri($redirect_uri);

// Generate a URL to request access from Google's OAuth 2.0 server:
$auth_url = $client->createAuthUrl();


if(isset($_GET['code'])){
    $client->authenticate($_GET['code']);
    $access_token = $client->getAccessToken();
    $refreshToken = $client->getRefreshToken();
    $_SESSION['access_token'] = $access_token;
    echo '<h3>Access Token: </h3>';
    print_r($access_token);


    /**
     * We save the refresh token in our database as the user token for YouTube
     * Refresh token is not expired
     * It is for generating an access token to call API
     * Access token is expired in 60 minutes
     */
    echo '<h3>Refresh Token: </h3>';
    print_r($refreshToken);


    $youtube = new Google_Service_YouTube($client);
    $listChannel = $youtube->channels->listChannels('snippet', array('mine' => true));

    $client_id = $client->getClientId() ;

    /**
     * Part of YouTube
     * We save the channel name ($Channel_name) as the account name in the database
     */
    echo '<h3>YouTube Channel Name</h3>';
    foreach ($listChannel as $channel){
        $Channel_name = $channel['snippet']['title'];
        echo $Channel_name;
    }


    /**
     * Part of Google Analytics
     */
    $analytics = new Google_Service_Analytics($client);
    // Get all google analytics account owned by the authorised google account
    $listAccount = $analytics->management_accounts->listManagementAccounts();
    echo '<h3>Google Analytics</h3>';
    foreach ($listAccount as $account) {
        $id_account = $account->getId();
        $name_account = $account->getName();
        echo '<h5>'.$id_account.' - '.$name_account.'</h5>';


        // Get all google analytics views owned by current the google analytics account
        $profiles = $analytics->management_profiles
            ->listManagementProfiles("$id_account", '~all');
        /**
         * We save id of view ($id_view) in our database.
         * On digital ID without "@".
         */
        echo '<table border="1"><tr><th>View id</th><th>View name</th><th>View site</th></tr>';
        foreach ($profiles->getItems() as $profile) {
            $id_view = $profile->getId();
            $name_view = $profile->getName();
            $site_view = $profile->getWebsiteUrl();
            echo '<tr>';
            echo '<td>' . $id_view . '</td>';
            echo '<td>' . $name_view . '</td>';
            echo '<td>' . $site_view . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
else{
// Redirect the user to $auth_url:
echo '<a href="'.$auth_url.'" target="_blank">
        <button type="button">Login
        </button></a>';
}

/**
 * Delete oauth file if it exists
 */
if(file_exists($file_oauth_name))
    unlink($file_oauth_name);