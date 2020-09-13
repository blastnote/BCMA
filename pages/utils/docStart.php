<?php

session_start();
if (!isset($_SESSION['userid'])) {
	header("Location: ../pages/login.php");
	exit();
}