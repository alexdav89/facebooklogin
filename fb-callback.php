<?php

require_once 'bootstrap.php';

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
  $logoutUrl = $helper->getLogoutUrl($accessToken, "http://login.php");
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
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


// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();



if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

}


// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
try {
  // Returns a FB user and picture
  $response = $fb->get('/me?fields=id,name,picture', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();

if(!array_key_exists("fb_access_token",$_SESSION))
{
	$q = new fbtest();
	$resultUser = $q->selectUser($user["id"]);
	
	//if user does not exist  than  inset 
	if(!count($resultUser)>0)
	{
		$q->insertUser($user["id"], $user["name"], $user["picture"]["url"]);
		
	}

	//insert access token
	$q->insertAccessToken($user["id"], $accessToken);
}

$_SESSION['fb_access_token'] = (string) $accessToken;



$username = $user["name"];
$photoUrl = $user["picture"]["url"];

?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Facebook App</title>
</head>

<body>

	<div>
		<label>Name:</label>
		<span><?php echo  $username; ?></span>
	</div>
	<div>
		<label>Photo:</label>
		<img src="<?php echo  $photoUrl; ?>" />
	</div>
	<div>
		<a href="<?php echo $logoutUrl; ?>">Logout</a>
	</div>
</body>

</html>
