# SDK PHP LEBONCOIN

> SDK du plus célèbre site entre particuliers en France

 - Recherche d'annonces via plusieurs paramètres
	 - Mot clé
	 - Titre seulement
	 - Catégorie
	 - Localisations
	 - Le tri par prix ou temps (ascendant, descendant)
	 - Seulement les professionnels ou particuliers
	 - Indiquer la page de recherche
- Rechercher toutes les annonces d'un utilisateur
- (A venir) Rechercher toutes les annonces par rapport a des coordonnées GPS
- Connexion de l'utilisateur
	- Récuperer toutes les informations du compte
	- Rechercher toutes ses annonces
	- Gérer le compte

## Documentation

**Pour commencer**

    require '../vendor/autoload.php';
	use Absmoca\Leboncoin;
	use Absmoca\Annonce;

**Récuperer les annonces**

    $params = array(
		"query" => "Oeuvre d'art"
	);
	$annonces = Leboncoin::getAnnonces($params);

**Options**

    $params = array(
		"query" => "Oeuvre d'art",
		"title_only" => true,
		"category" => Leboncoin::searchCategory("Décoration")->id,
		"location" => array(
			Leboncoin::searchLocation("Montpellier", true),
			Leboncoin::searchLocation("Paris", true)
		),
		"sortby" => array("price" => "desc"),
		"particuliers" => false
	);

**Limiter le nombre de résultats**

    Leboncoin::nombreResultats(10);

**Annonces d'un utilisateur**

    $params = array(
		"sortby" => array("price" => "asc")
	);
	$annonces = Leboncoin::getAnnoncesUser("a2db2eb9-6330-4ad7-9442-a1SJ09c9f236", $params);

**Récupérer une annonce**

    use Absmoca\Annonce;
    $annonce = new Annonce(1512169842);

**Connexion au compte**

    use Absmoca\User;
    $account = new User();
    $account->connexion('user@gmail.com', 'pasSworD1289');

**Déconnexion du compte**

    $account->logout();

Cauquil Thomas | https://thomascauquil.fr

> Written with [StackEdit](https://stackedit.io/).