<?php
session_start();
error_reporting(E_ALL);
require_once'../app/config/config.php';
ini_set('display_errors', 1);

date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу

//создаем объект PDO
try {
	$pdo = new PDO("mysql:host={$db_host};dbname={$db_name}",
				$db_user,
				$db_user_pass);
} catch (Exception $e) {
	//формальный отлов
	die("<h1>Error!!!</h1>");
}

// 1) аутентификация: получить текущего пользователя
$user = new \app\auth\CUser($pdo); 
// 2) роутинг: определить, какую страницу запрашивает пользователь
$controller = \app\router\CRouter::getController();
// 3) сформировать запрашиваемую страницу и отправить в браузер пользователю
$controller->render($pdo);

$pdo = null; //чистим переменную, освобождаем память