<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class HomeController extends Controller {

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(LaravelFacebookSdk $fb) {
		$loginUrl = $fb->getLoginUrl(['email', 'user_posts', 'user_likes', 'user_photos']);
		return view('welcome', ['loginURL' => $loginUrl]);
	}

}
