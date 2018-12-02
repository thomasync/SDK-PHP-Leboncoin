<?php

require dirname(__DIR__) . '/vendor/autoload.php';
use Absmoca\Leboncoin;

$params = array(
	"sortby" => array("price" => "asc")
);

$annonces = Leboncoin::getAnnoncesUser("dc8d86cd-0b34-40b6-8783-b1e3a173a0d7", $params, 0);

echo '<pre>' . print_r($annonces, true) . '</pre>';

?>