<?php
spl_autoload_extensions(".php");
spl_autoload_register();

try {
	$router = new Router();
	$router->process();
} catch (Exception $e){
	$e->getMessage();
}