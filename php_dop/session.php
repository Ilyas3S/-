<?
if (isset($_COOKIE[session_name()])) {
	session_start();
	if (isset($_GET['exit']) and $_GET['exit'] == 1) {
		$_SESSION = [];
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		session_destroy();
		
		$_GET['exit'] = 0;
	}
}
?>