<?php
require_once '/var/www/lib/notifications_factory.php';
$notifications = new NotificationsFactory();
$notifications->writeFile();
?>
