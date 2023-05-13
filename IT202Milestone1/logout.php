<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h3>Logout Successful!</h3>
</body>
</html>

<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");