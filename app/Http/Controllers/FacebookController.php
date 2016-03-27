<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookController extends Controller {
	public function fbcallback(LaravelFacebookSdk $fb) {
		// Obtain an access token.
		try {
			$token = $fb->getAccessTokenFromRedirect();
		} catch (\Facebook\Exceptions\FacebookSDKException $e) {
			dd($e->getMessage());
		}

		// Access token will be null if the user denied the request
		// or if someone just hit this URL outside of the OAuth flow.
		if (!$token) {
			// Get the redirect helper
			$helper = $fb->getRedirectLoginHelper();

			if (!$helper->getError()) {
				abort(403, 'Unauthorized action.');
			}

			// User denied the request
			dd(
				$helper->getError(),
				$helper->getErrorCode(),
				$helper->getErrorReason(),
				$helper->getErrorDescription()
			);
		}

		if (!$token->isLongLived()) {
			// OAuth 2.0 client handler
			$oauth_client = $fb->getOAuth2Client();

			// Extend the access token.
			try {
				$token = $oauth_client->getLongLivedAccessToken($token);
			} catch (\Facebook\Exceptions\FacebookSDKException $e) {
				dd($e->getMessage());
			}
		}

		$fb->setDefaultAccessToken($token);
		$this->getLogin($token, $fb);

		return redirect('/')->with('success', 'Successfully logged in with Facebook');
	}

	protected function getLogin($token, $fb) {
		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get('/me?fields=id,name,email', $token);

		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$fb_user = $response->getGraphUser();
		$user = User::where('fb_id', $fb_user['id'])->first();

		if (isset($user)) {
			$user->fb_token = $token;
			$user->name = $fb_user['name'];
			//todo
			//Cập nhật danh sách bạn bè
			//
			Auth::login($user);

		} else {
			// Create new user
			$new_user = $this->createNewUser($fb_user);
			//todo
			//cập nhật danh sách bạn bè
			//
			Auth::login($new_user);
		}

	}

	protected function createNewUser($fb_user) {
		$new_user = new User();
		$new_user->name = $fb_user['name'];
		$new_user->fb_id = $fb_user['id'];
		$new_user->email = $fb_user['email'];
		$new_user->fb_token = $token;
		$new_user->save();

	}

	public function logout() {
		$user = Auth::user();
		Auth::logout($user);
		return redirect('/')->with('success', 'Successfully logout');
	}
}
