<?php

require '../vendor/autoload.php';
use Absmoca\Leboncoin;
use Absmoca\Annonce;

$params = array(
	"query" => "Oeuvre d'art",
	"title_only" => true,
	"category" => Leboncoin::searchCategory("Décoration")->id,
	"sortby" => array("price" => "desc")
);

Leboncoin::nombreResultats(10);
$annonces = Leboncoin::getAnnonces($params);

echo '<pre>' . print_r($annonces, true) . '</pre>';

?>