<?php

require __DIR__.'/../vendor/autoload.php';

use Absmoca\Leboncoin;

$lbc = new Leboncoin();
$params = array(
	"query" => "Oeuvre d'art",
	"title_only" => true,
	"category" => Leboncoin::searchCategory("Décoration")->id,
	"sortby" => array("price" => "desc")
);

$lbc->setResultLimit(10);
$annonces = $lbc->getAnnonces($params);

echo print_r($annonces, true);