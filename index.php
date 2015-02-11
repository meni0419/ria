<?php
session_start();
echo '<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <link rel="stylesheet" type="text/css" href="style.css">
        </head>
        <body>';    

/**
Задание 1.
В KPI-Drive логика программы такова, что она разделена всего на 2 страницы:

/ - главная страница, на ней же выводится register и recovery
/app/ - страница приложения

1) //Соответственно надо сделать в SLIM 2 адреса - главная страница и приложение
2) //на главной странице организовать форму логина и пароля
3) //сделать проверку введенных данных
4) если данные правильные - переходит на /app/
5) сделаешь таблицу БД, хешировать пароли  не надо*/

// require slim
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// require twig
require_once '/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// require redBeanPHP
require 'rb.php';
R::setup('mysql:host=localhost;dbname=ria','root','');
R::freeze(true);

// new rest
$app = new \Slim\Slim();

//get page '/' auth
$app->get(
    '/',
    function () use ($app) {
		if (isset($_SESSION['l']) && isset($_SESSION['p'])) {
			$app->redirect('/api');
		}
		
        $template = <<<EOT
        <form method="POST" action="/">
			<label>Логин</label><input name="l" size="25" type="text"><br>
			<label>Пароль</label><input name="p" size="25" type="password"><br>
		    <input name="remember" type="checkbox"><label>Запомнить</label><br>
			<input type="submit" value="Войти">
		</form>
EOT;
        echo $template;
    }
);

// get closed page '/api'
$app->get(
    '/api',
    function () use ($app) {
		if (!isset($_SESSION['l'])){
			$app->redirect('/');
		}
		
		$lp = R::getAll("SELECT login, password FROM users"); 
		foreach ($lp as $k=>$v) {
			if ($_SESSION['l'] == $v["login"] && $_SESSION['p'] == $v["password"]) {	
			echo '
				Close page
				<form method="POST" action="/exit">
				<input type="hidden" name="exit" value="exit">
				<input type="submit" value="Выйти">
				</form>';
			}
		}
	}
);

//post data for auth
$app->post(
    '/',
    function () use ($app) {
		$lp = R::getAll('SELECT login, password FROM users');
		foreach ($lp as $k=>$v) {
			if ($_POST['l'] == $v["login"] && $_POST['p'] == $v["password"]) {
				$_SESSION['l']=$_POST['l'];
				$_SESSION['p']=$_POST['p'];
				$app->redirect('/api');
			} 
		}
		$app->redirect('/');
	}
);

//post exit
$app->post(
    '/exit',
    function () use ($app) {
		if (isset($_POST['exit'])) {
			session_destroy();
			$app->redirect('/');
		}
	}
);

$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);


$app->delete(
    '/',
    function () {
		echo 'получилось';
    }
);

$app->run();