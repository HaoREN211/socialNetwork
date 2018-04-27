<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 19/04/2018
 * Time: 11:18
 * Source: https://github.com/zoonman/linkedin-api-php-client
 */


namespace LinkedIn;

require_once '../vendor/autoload.php';
use LinkedIn\Client;
use LinkedIn\Scope;

$client_id = '';
$client_secret = '';
$url_redirect = 'http://localhost/rs/linkedin/main/';

// instantiate the Linkedin client
$client = new Client(
    "$client_id",
    "$client_secret"
);

// define scope
$scopes = [
    Scope::READ_BASIC_PROFILE,
    Scope::READ_EMAIL_ADDRESS,
    Scope::MANAGE_COMPANY,
];

// Set redirect page
$client->setRedirectUrl("$url_redirect");

// show the access token if the user has already login
// else ask the client to login
if(isset($_GET['code'])){
    /**
     * User Access Token
     * We save the user access token in our base
     */
    $accessToken = $client->getAccessToken($_GET['code']);
    echo 'Access Token: '.$accessToken.'<br />';


    /**
     * Show all the enterprise pages of which the user is administrator
     * In our database, we save company id as the 'tag_name'
     */
    $companies = $client->get('companies',
        ['format'=>'json', 'is-company-admin' => 'true']);
    $numbre_company = $companies['_total'];
    $companies = $companies['values'];
    echo '<table><tr><th>Company id</th><th>Company Name</th></tr>';
    foreach ($companies as $company){
        echo '<tr><td>'.$company['id'].'</td><td>'.$company['name'].'</td></tr>';
    }
    echo '</table>';
}
else{
    // get url on LinkedIn to start linking
    $loginUrl = $client->getLoginUrl($scopes);
    echo "<a href='$loginUrl'><button type='button'>Login with LinkedIn</button></a>";
}