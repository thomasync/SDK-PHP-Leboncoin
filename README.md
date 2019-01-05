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

```php
require __DIR__ . '/vendor/autoload.php';

use Absmoca\Leboncoin;
```

**Récuperer les annonces**

```php
$params = array(
    "query" => "Oeuvre d'art"
);

$lbc = new Leboncoin();
$annonces = $lbc->getAnnonces($params);
```

**Options**

```php
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
```

**Limiter le nombre de résultats**

```php
$lbc = new Leboncoin();
$lbc->setResultLimit(10);
```

**Annonces d'un utilisateur**

```php
$params = array(
    "sortby" => array("price" => "asc")
);

$lbc = new Leboncoin();
$annonces = $lbc->getAnnoncesUser("a2db2eb9-6330-4ad7-9442-a1SJ09c9f236", $params);
```

**Récupérer une annonce**

```php
$lbc = new Leboncoin();
$annonce = $lbc->getAnnonce(1512169842);
```

**Connexion au compte**

```php
$lbc = new Leboncoin();
$lbc->login('user@gmail.com', 'pasSworD1289');
```

Cauquil Thomas | https://thomascauquil.fr

> Written with [StackEdit](https://stackedit.io/).