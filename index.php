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
		
        try {
			$loader = new Twig_Loader_Filesystem('templates');
			$twig = new Twig_Environment($loader);
			$template = $twig->loadTemplate('auth.tmpl');
			echo $template->render(array());
		} 
		catch (Exception $auth) {
			die ('ERROR: ' . $auth->getMessage());
		}
    }
);

// get closed page '/api'
$app->get(
    '/api',
    function () use ($app) {
		if (!isset($_SESSION['l']) && !isset($_SESSION['p'])) {
			$app->redirect('/');
		}

		$lp = R::getAll("SELECT login, password FROM users");
		foreach ($lp as $k=>$v) {
			if ($_SESSION['l'] == $v["login"] && $_SESSION['p'] == $v["password"]) {	
				try {
					$loader = new Twig_Loader_Filesystem('templates');
					$twig = new Twig_Environment($loader);
					$template = $twig->loadTemplate('ClosePage.tmpl');
					echo $template->render(array(
						'login' => $_SESSION['l']
					));
				} 
				catch (Exception $cpage) {
					die ('ERROR: ' . $cpage->getMessage());
				}
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