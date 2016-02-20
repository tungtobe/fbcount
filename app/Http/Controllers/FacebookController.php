<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class FacebookController extends Controller
{
    public function getLogin(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
    		$loginUrl = $fb->getLoginUrl(['email']);
			// fix cross request
			
			foreach ($_SESSION as $k=>$v) {                    
			    if(strpos($k, "FBRLH_")!==FALSE) {
			        if(!setcookie($k, $v)) {
			            //what??
			        } else {
			            $_COOKIE[$k]=$v;
			        }
			    }
			}
    	return view('welcome', ['loginURL' => $loginUrl] );
    }

    public function fbcallback(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
			foreach ($_COOKIE as $k=>$v) {
			    if(strpos($k, "FBRLH_")!==FALSE) {
			        $_SESSION[$k]=$v;
			    }
			}
    	// Obtain an access token.
	    try {
	        $token = $fb->getAccessTokenFromRedirect();
	    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
	        dd($e->getMessage());
	    }
	    // dd($token);
	    

	    // // Access token will be null if the user denied the request
	    // // or if someone just hit this URL outside of the OAuth flow.
	    // if (! $token) {
	    //     // Get the redirect helper
	    //     $helper = $fb->getRedirectLoginHelper();

	    //     if (! $helper->getError()) {
	    //         abort(403, 'Unauthorized action.');
	    //     }

	    //     // User denied the request
	    //     dd(
	    //         $helper->getError(),
	    //         $helper->getErrorCode(),
	    //         $helper->getErrorReason(),
	    //         $helper->getErrorDescription()
	    //     );
	    // }

	    // if (! $token->isLongLived()) {
	    //     // OAuth 2.0 client handler
	    //     $oauth_client = $fb->getOAuth2Client();

	    //     // Extend the access token.
	    //     try {
	    //         $token = $oauth_client->getLongLivedAccessToken($token);
	    //     } catch (\Facebook\Exceptions\FacebookSDKException $e) {
	    //         dd($e->getMessage());
	    //     }
	    // }

	    // $fb->setDefaultAccessToken($token);

	    // // Save for later
	    // \Session::put('fb_user_access_token', (string) $token);

	    // // Get basic info on the user from Facebook.
	    // try {
	    //     $response = $fb->get('/me?fields=id,name,email');
	    // } catch (\Facebook\Exceptions\FacebookSDKException $e) {
	    //     dd($e->getMessage());
	    // }

	    // // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
	    // $facebook_user = $response->getGraphUser();

	    // // Create the user if it does not exist or update the existing entry.
	    // // This will only work if you've added the SyncableGraphNodeTrait to your User model.
	    // $user = App\User::createOrUpdateGraphNode($facebook_user);

	    // // Log the user into Laravel
	    // Auth::login($user);

	    return redirect('/')->with('message', 'Successfully logged in with Facebook');
    }
}


