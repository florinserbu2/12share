<?php class Fd_Auth_Token
{

	/**
	 * retrieve an ajax security token
	 *
	 * @param string $page
	 * @return array
	 */
	static public function getToken ()
	{
		$token = uniqid(rand(), true);
		$_SESSION['current']['token'] = $token;
		return $token;
	}

	/**
	 * 
	 * retrieve a next security token based on a valid token
	 * @param string $token
	 * @return mixed string boolean
	 */
	static public function getNextToken ($token)
	{
		if (self::verifyToken($token)) {
			$newtoken = self::getToken();
			return $newtoken;
		} else {
			return false;
		}
	}

	/**
	 * 
	 * verify if the token is valid
	 * @param string $token
	 * @return boolean
	 */
	static public function verifyToken ($token)
	{
		if (isset($_SESSION['current']['token'])) {
			if ($_SESSION['current']['token'] != '') {
				if ($token == $_SESSION['current']['token']) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}