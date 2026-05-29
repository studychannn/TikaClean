<?php
require_once __DIR__ . '/../app/auth.php';
admin_logout();
header('Location: login.php');
exit;
