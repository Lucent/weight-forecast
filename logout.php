<?php
include "server/session.php";

$_SESSION = array();
session_destroy();
header("Location: /");
?>
