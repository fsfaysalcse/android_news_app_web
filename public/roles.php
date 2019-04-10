<?php
// login checking
function is_login()
{
	if (! isset($_SESSION)) {
		session_start();
	}

	if (isset($_SESSION['user'])) {
		return true;
	}

	return false;
}

// role is super admin
function is_superadmin()
{
	if (! is_login()) {
		return false;
	}

	if (isset($_SESSION['_user_role']) &&
		$_SESSION['_user_role'] == '100'
	) {
		return true;
	}

	return false;
}

// role is admin
function is_admin()
{
	if (! is_login()) {
		return false;
	}

	if (isset($_SESSION['_user_role']) &&
		$_SESSION['_user_role'] == '101'
	) {
		return true;
	}

	return false;
}

// role is moderator
function is_moderator()
{
	if (! is_login()) {
		return false;
	}

	if (isset($_SESSION['_user_role']) &&
		$_SESSION['_user_role'] == '102'
	) {
		return true;
	}

	return false;
}
