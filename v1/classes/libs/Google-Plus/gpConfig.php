<?php
session_start();

//Include Google client library

include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = Constants::G_CLIENT_ID; //Google client ID
$clientSecret = Constants::G_CLIENT_SECRECT; //Google client secret
$redirectURL = Constants::HOME_URL . '/api/v1/google/callback'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to W3tweaks.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>