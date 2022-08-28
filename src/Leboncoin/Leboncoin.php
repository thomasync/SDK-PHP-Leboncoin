<?php

namespace Absmoca;

/**
 * API LEBONCOIN PHP
 *
 * @author     Cauquil Thomas <contact@thomascauquil.fr> @absmoca
 * @version    3.0
 *
 */
class Leboncoin
{
    /**
     * @var string    URL DE L'API LEBONCOIN
     */
    protected $urlBase = "https://api.leboncoin.fr/";

    /**
     * @var string    CLÉ DE L'API
     */
    protected $apiKey = "ba0c2dad52b3ec";

    /**
     * @var integer NOMBRE DE RÉSULTATS MAX PAR RECHERCHE
     */
    protected $resultLimit = 100;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * @var User
     */
    protected $user;

    /**
     * @return string
     */
    public function getUrlBase()
    {
        return $this->urlBase;
    }

    /**
     * @param string $urlBase
     */
    public function setUrlBase($urlBase)
    {
        $this->urlBase = $urlBase;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return int
     */
    public function getResultLimit()
    {
        return $this->resultLimit;
    }

    /**
     * Permet de changer le nombre de résultats obtenus par requete
     *
     * @param integer $resultLimit
     */
    public function setResultLimit($resultLimit)
    {
        $this->resultLimit = $resultLimit;
    }


    /**
     * Permet la recherche d'annonces via parametres
     *
     * @param array $params (query, title_only, category, location, sortby, (particuliers|professionnels))
     * ['query'] string: Nom de la recherche
     * ['title_only'] boolean: Recherche uniquement "query" dans le titre
     * ['category'] integer: ID de la catégorie de l'objet via searchCategory
     * ['location'] array|array of object: Localisation(s) des annonces via searchLocation
     * ['sortby'] array(price|time => asc, desc) : Tri les résultats
     * ['particuliers'] boolean(false) : Masque les annonces de particuliers,
     * ['professionnels'] boolean(false) : Masque les annonces de professionnels
     * @param integer $page Numéro de la page
     * @return Object|bool
     * @throws \Exception
     */
    public function getAnnonces($params, $page = 0)
    {
        $result = $this->callApi("finder/search", $this->filterMapGetAnnonces($params, $page));
        $annonces = array("total" => $result->total, "annonces" => array());
        if (!isset($result->ads) || count($result->ads) == 0) {
            return false;
        }
        foreach ($result->ads as $k => $a) {
            try {
                $annonces['annonces'][] = new Annonce($a);
            } catch(\Exception $ex) {}
        }

        return (Object) $annonces;
    }

    /**
     * Permet de rechercher les annonces d'un utilisateur spécifique
     *
     * @param string $userId
     * @param boolean|array $params Parametres donnés à la recherche (voir getAnnonces)
     * @param integer $page Numéro de le page
     * @return bool|Object
     * @throws \Exception
     */
    public function getAnnoncesUser($userId, $params = null, $page = 0)
    {
        if ($params) {
            $params['user_id'] = $userId;
        } else {
            $params = array('user_id' => $userId);
        }

        return $this->getAnnonces($params, $page);
    }

    /**
     * @param  array $params Formate les paramètres en json
     * @return string
     */
    private function filterMapGetAnnonces($params, $page)
    {
        // Limit
        $post = array("limit" => $this->resultLimit, "limit_alu" => 3, "filters" => array());

        // Query
        if (isset($params['query'])) {
            $post['filters']['keywords']['text'] = htmlspecialchars($params['query']);
        }

        // Title only
        if (isset($params['title_only']) && $params['title_only'] == true) {
            $post['filters']['keywords']['type'] = "subject";
        }

        // Context
        if (isset($params['context'])) {
            $post['context'] = 'default';
        }

        // Store
        if (isset($params['store_id'])) {
            $post['filters']['owner']['store_id'] = $params['store_id'];
        }

        // User
        if (isset($params['user_id'])) {
            $post['filters']['owner']['user_id'] = $params['user_id'];
        }

        // Category
        if (isset($params['category'])) {
            $post['filters']['category'] = (Object) array('id' => (string)$params['category']);
        } else {
            $post['filters']['category'] = (Object) array();
        }

        // Location
        $l = [];
        if (isset($params['location'])) {
            if (isset($params['location']['regions'])) {
                foreach ($params['location']['regions'] as $region) {
                    $l["regions"][] = (string)$region;
                }

            }
            if (isset($params['location']['zipcodes'])) {
                $l['city_zipcodes'] = [];
                foreach ($params['location']['zipcodes'] as $zipcode) {
                    $l['city_zipcodes'][] = array("zipcode" => (string)$zipcode);
                }
            }
        } else {
            $l = array("locations" => []);
        }

        $post['filters']['location'] = $l;

        // Sort
        if (isset($params['sortby'])) {
            $post['sort_by'] = key($params['sortby']);
            $post['sort_order'] = $params['sortby'][$post['sort_by']];
        }

        // private
        if (isset($params['particuliers']) && $params['particuliers'] == false) {
            $post['owner_type'] = "pro";
        } elseif (isset($params['professionnels']) && $params['professionnels'] == false) {
            $post['owner_type'] = "private";
        }

        //$post['ranges'] = array();

        // Offset
        $post['offset'] = ($post['limit'] * $page);

        return json_encode($post);
    }

    /**
     * Rechercher une annonce via son ID depuis la classe Annonce
     *
     * @param integer $id ID de l'annonce
     * @return Annonce|bool
     * @throws \Exception
     */
    public function getAnnonce($id)
    {
        if(!is_numeric($id)) return false;
        $response = $this->callApi("finder/classified/".$id);
        if(!$response) return false;
        return new Annonce($response);
    }

    /**
     * Construit l'appel de l'API
     *
     * @param  string $base Repertoire de l'API
     * @param  string $post Données de la recherche
     * @return Object|bool
     */
    protected function callApi($base, $post = false)
    {
        $a = json_decode($this->curl($this->urlBase.$base, $post));

        return (json_last_error() == JSON_ERROR_NONE) ? $a : false;
    }

    /**
     * Construit l'appel de l'API avec un access authentifié
     *
     * @param string $base Repertoire de l'API
     * @param string $access Token d'utilisateur
     * @param bool|string $post Données de la recherche
     * @return Object|bool
     */
    protected function callApiLogged($base, $access, $post = false)
    {
        $a = json_decode($this->curl($this->urlBase.$base, $post, $access));

        return (json_last_error() == JSON_ERROR_NONE) ? $a : false;
    }

    /**
     * Récupère les catégories des annonces dans le fichier categories.json
     *
     * @return array Liste des catégories
     */
    public function categories()
    {
        $c = json_decode(file_get_contents(__DIR__.'/Resources/categories.json'));
        $cats = array();
        foreach ($c as $v) {
            $cats[$v->id] = $v->name;
            if (isset($v->subcategories) && isset($v->subcategories[0])) {
                foreach ($v->subcategories as $vv) {
                    $cats[$vv->id] = $vv->name;
                }
            }
        }
        ksort($cats);

        return $cats;
    }

    /**
     * Récupère l'id de la catégorie par rapport à son nom
     *
     * @param  string $n Nom de la categorie
     * @return object
     */
    public function searchCategory($n)
    {
        $res = array("perc" => 0, "id" => null, "name" => null);
        foreach ($this->categories() as $id => $name) {
            similar_text($this->simpleText($name), $this->simpleText($n), $p);
            if ($p > $res['perc']) {
                $res = array("perc" => $p, "id" => $id, "name" => $name);
            }
        }

        return (object)$res;
    }


    /**
     * Recherche un lieu par son nom
     *
     * @param string $n Lieu à rechercher
     * @param boolean $precis Si vrai récupère seulement le premier résultat de façon précise (pas la région)
     * @return array|bool
     */
    public function searchLocation($n, $precis = false)
    {
        $post = '{"text":"'.$n.'","context":{}}';
        $r = $this->callApi("api/parrot/v1/cityzipcode/complete", $post);
        if (!is_array($r) || count($r) == 0) {
            return false;
        }
        if (!$precis) {
            return $r;
        } else {
            if (count($r) > 1 && preg_match('#(toute la ville|toutes les communes)#i', $r[0]->label)) {
                return $r[1];
            } else {
                return $r[0];
            }
        }
    }

    /**
     * Récupère les endroits des annonces dans le fichier locations.json
     *
     * @return array of object(id, name)
     */
    public function locations()
    {
        $c = json_decode(file_get_contents(__DIR__.'/Resources/locations.json'));

        return $c;
    }


    /**
     * Supprime tous les caractères spéciaux
     *
     * @param  string $str Chaine à utiliser
     * @return string
     */
    private function simpleText($str): string
    {
        return trim(preg_replace('#[^a-z0-9]#', '', mb_strtolower(urldecode($str), 'UTF-8')));
    }

    /**
     * Récuperer des données via une requete HTTP
     *
     * @param string $url Lien où l'on vas récuperer les données
     * @param string|bool $post Si des données POST seront à envoyer
     * @param string|bool $access Pour donner un token d'access
     * @param string|bool $cookie Si des données COOKIE seront à envoyer
     * @param bool $cache Spécifie le controle du cache
     * @return string
     */
    private function curl($url, $post = false, $access = false, $cookie = false, $cache = false): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($post != false) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($cookie != false) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        $headers = array();
        if (!$cache) {
            $headers[] = "Cache-Control: no-cache";
        }

        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER['REMOTE_ADDR'] ?? null;
        if (isset($ip)) {
            $headers[] = "REMOTE_ADDR: ".$ip;
            $headers[] = "HTTP_X_FORWARDED_FOR: ".$ip;
        }

        $headers[] = 'api_key: '.$this->apiKey;

        if ($access) {
            $headers[] = 'Authorization: Bearer '.$access;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Leboncoin/3.16.1 (iPhone; iOS 10.0; Scale/2.00)');
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 1
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        switch ($status) {
            case 400:
                throw new \RuntimeException('API a répondu 400 : bad request');
        }

        curl_close($ch);

        return $output;
    }

    /**
     * Connexion de l'utilisateur
     *
     * @param string $username Adresse email
     * @param string $password Mot de passe
     * @return bool Retourne le résultat de la connexion, si vrai appelle getInfosAccount
     */
    public function login($username, $password): bool
    {
        $call = $this->callApi(
            "api/oauth/v1/token",
            "client_id=frontweb&grant_type=password&password={$password}&username={$username}"
        );
        if (isset($call->access_token) && isset($call->refresh_token)) {
            $this->token = $call->access_token;
            $this->refreshToken = $call->refresh_token;
            $this->getInfosAccount();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Récupère les infos de l'utilisateur
     */
    protected function getInfosAccount()
    {
        $call = $this->callApiLogged('api/accounts/v1/accounts/me/personaldata', $this->token);

        $user = new User();

        $user->setUserId($call->userId);
        $user->setStoreId($call->storeId);
        $user->setEmail($call->personalData->email);
        $user->setGender($call->personalData->gender);
        $user->setFirstname($call->personalData->firstname);
        $user->setLastname($call->personalData->lastname);
        $user->setAddress(
            (isset($call->personalData->addresses->billing)) ? $call->personalData->addresses->billing : null
        );
        $user->setPhone(
            (isset($call->personalData->phones->main->number)) ? $call->personalData->phones->main->number : null
        );
        $user->setPseudo($call->personalData->pseudo);
        try {
            $user->setBirthDate(
                (isset($call->personalData->birthDate->day)) ? new \DateTime(
                    $call->personalData->birthDate->day.'-'.$call->personalData->birthDate->month.'-'.$call->personalData->birthDate->year
                ) : null
            );
        } catch(\Exception $ex) {}
        $this->user = $user;
    }

    /**
     * Récupérer les annonces de l'utilisateur connecté
     *
     * @param bool|array $params Parametres donnés à la recherche (voir getAnnonces)
     * @param integer $page Numéro de le page
     * @return bool|Object
     * @throws \Exception
     */
    public function getMyAnnonces($params = false, $page = 0): array
    {
        if (!$this->user) {
            throw new \RuntimeException('Not logged in');
        }

        return $this->getAnnoncesUser($this->user->getUserId(), $params, $page);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
