<?php
class HttpRequestsHandler {
	public static function registerRequest($email, $password) {
		$request = new HttpRequest ( 'https://api.bandcloud.net/auth/register', HttpRequest::METH_POST );
		$response = http_get("http://www.example.com/", array("timeout"=>1), $info);
		$request->addPostFields ( array (
				'email' => $email,
				'password' => $password 
		) );
		
		try {
			$response = json_decode ( $request->send ()->getBody () );
			echo $response;
			
			return $response;
		} catch ( HttpException $ex ) {
			return ERROR;
		}
	}
	public static function loginRequest() {
	}
	public static function logoutRequest() {
	}
	public static function changePassRequest() {
	}
	public static function apiKeyValidationRequest() {
	}
}