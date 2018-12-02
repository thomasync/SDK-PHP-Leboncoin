<?php

require '../vendor/autoload.php';
use Absmoca\User;

$account = new User();
$account->connexion('user@gmail.com', 'pasSworD1289');

echo '<pre>' . var_export($account, true) . '</pre>';

echo '<pre>' . print_r($account->getMyAnnonces(), true) . '</pre>';

$account->logout();

?>