<?php
session_start();
session_destroy();
header('Location: index.html?message=Logged out successfully');
exit;
?>