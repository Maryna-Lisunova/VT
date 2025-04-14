<?php 
declare (strict_types = 1); 
require_once __DIR__ . "/Routers/Router.php"; 
$url = $_GET['route'] ?? ''; 
Router::route($url);