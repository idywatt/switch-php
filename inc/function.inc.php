<?php 
//Fonction pour savoir si l'utilisateur est connécté

function user_is_connect() {
	if(!empty($_SESSION['membre'])) {
		return true; // l'utilisateur est connécté 
	}
	return false; // l'utilisateur n'est pas connécté
}

// Fonction pour savoir si l'utilisateur a le statut d'admin
function user_is_admin() {
	if(user_is_connect() && $_SESSION['membre']['statut']==2){
		//si l'utilisateur est connécté et que son statut est égale à 2 alors il est admin
		return true;
	} else {
		return false;
	}
}

// Fonction pour créer le panier
function creation_panier() {
	if (!isset($_SESSION['panier'])) {
		// Si l'indice
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_article'] =array();
		$_SESSION['panier']['titre'] =array();
		$_SESSION['panier']['prix'] =array();
		$_SESSION['panier']['quantite'] =array();
	}
}

// Fonction pour ajouter un article au panier
function ajout_panier($id_article, $quantite, $prix, $titre) {
	//Si un article existe déja dans la panier, on ne change que sa quantité sinon on le rajoute


	//On verifie si l'id-article est déja présent dans le sous-tableau $_SESSION['panier']['id_article']
	// array_search() cherche une information dans les valeurs d'un tableau array et nous renvois son indice. Ensuite grace à l'indice on modifiera la quantité

	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);

	if($position_article !== false) {
		//!==strictement different car on peut recuperer l'indice 0
		$_SESSION['panier']['quantite'][$position_article] +=$quantite;
	}else {
		$_SESSION['panier']['id_article'][] = $id_article;
		$_SESSION['panier']['quantite'][] = $quantite;
	    $_SESSION['panier']['prix'][] = $prix;
	    $_SESSION['panier']['titre'][] = $titre;
	}  
}

//Fonction pour retirer un article du panier
function retirer_article($id_article) {
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);

	if($position_article !==false) {
		//array_splice() permet d'enlever un element d'un array mais aussi de reodonner les indices du tableau pour ne pas avoir de trou
		//1e argument:le tableau concerné
		//2e argument:a quel indice on supprime
		//3e agument:combien de ligne on supprime
		array_splice($_SESSION['panier']['id_article'], $position_article, 1);
		array_splice($_SESSION['panier']['titre'], $position_article, 1);
		array_splice($_SESSION['panier']['prix'], $position_article, 1);
		array_splice($_SESSION['panier']['quantite'], $position_article, 1);
	}
}

//fonction pour calculer le montant total du panier

function total_panier(){
	$total = 0;
	for ($i = 0; $i < count($_SESSION['panier']['id_article']); $i++) {
	$total += $_SESSION['panier']['quantite'][$i]* $_SESSION['panier']['prix'][$i];
    }

    return round($total, 2);
}