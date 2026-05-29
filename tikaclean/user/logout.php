<?php
require_once __DIR__ . '/../app/auth.php';
user_logout();
header('Location: /tikaclean/');
exit;
