<?php

//Ouverture d'une session
session_start();

/** Début de la configuration de la BDD **/

/*$host_db = 'mysql:host=localhost;dbname=switch'; 
$login = 'root'; 
$password = ''; */

$host_db = 'mysql:host=localhost;dbname=idywdwxt_switch'; 
$login = 'idywdwxt_idy'; 
$password = 'Rokaya-1986'; 
$options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
                );              
$pdo = new PDO($host_db, $login, $password, $options);
/** Fin de la configuration de la BDD **/


// Crétation d'une variable destinée à afficher des messages utilisateur

$msg = "";


//declaration de constante

	// URL racine du projet
	define('URL', 'http://switch/'); // lien absolu racine du projet
	//define('URL', 'https://idy-watt.fr/switch/'); // lien absolu racine du projet


	//chemin racine du serveur
	define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);

	//chemin racine du dossier d'enregistrement des images
	define('SITE_ROOT', '/switch/');
