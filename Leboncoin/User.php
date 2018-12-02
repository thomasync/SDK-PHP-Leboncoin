<?php
namespace Absmoca;

class User extends Leboncoin{

	private $token;
	private $refresh_token;

	public $userId; 	/** @var string Id de l'utilisateur */
	public $storeId;	/** @var integer Id du store de l'utilisateur */
	public $email;		/** @var string Email de l'utilisateur */
	public $gender;		/** @var boolean Sexe de l'utilisateur */
	public $firstname;	/** @var string Prénom de l'utilisateur */
	public $lastname;	/** @var string Nom de l'utilisateur */
	public $address;	/** @var array(label,regionCode,dptCode,address,zipcode,city) Adresse de l'utilisateur */
	public $phone;		/** @var string Numéro de l'utilisateur */
	public $pseudo;		/** @var string Pseudo de l'utilisateur */
	public $birthDate;	/** @var datetime Date de naissance de l'utilisateur */


	/**
	 * Constructeur, accepte la connexion via token
	 * @param boolean|string $token         
	 * @param boolean\string $refresh_token 
	 */
	public function __construct($token = false, $refresh_token = false){
		if($token && $refresh_token){
			$this->token = $token;
			$this->refresh_token = $refresh_token;
			$this->getInfosAccount();
		}
	}

	/**
	 * Connexion de l'utilisateur
	 * @param  string $username Adresse email
	 * @param  string $password Mot de passe
	 * @return boolean          Retourne le résultat de la connexion, si vrai appelle getInfosAccount
	 */
	public function connexion($username, $password){
		$call = SELF::callApi("api/oauth/v1/token", "client_id=frontweb&grant_type=password&password={$password}&username={$username}");
		if(isset($call->access_token) && isset($call->refresh_token)){
			$this->token = $call->access_token;
			$this->refresh_token = $call->refresh_token;
			$this->getInfosAccount();
			return true;
		}else return false;
	}


	/**
	 * Déconnecte l'utilisateur
	 */
	public function logout(){
		if($this->userId){
			SELF::callApiLogged('api/oauth/v1/logout', $this->token);
		}
	}

	/**
	 * Récupère les infos de l'utilisateur
	 */
	public function getInfosAccount(){
		$call = SELF::callApiLogged('api/accounts/v1/accounts/me/personaldata', $this->token);
		$this->userId = $call->userId;
		$this->storeId = $call->storeId;
		$this->email = $call->personalData->email;
		$this->gender = $call->personalData->gender;
		$this->firstname = $call->personalData->firstname;
		$this->lastname = $call->personalData->lastname;
		$this->address = (isset($call->personalData->addresses->billing))?$call->personalData->addresses->billing:null;
		$this->phone = (isset($call->personalData->phones->main->number))?$call->personalData->phones->main->number:null;
		$this->pseudo = $call->personalData->pseudo;
		$this->birthDate = (isset($call->personalData->birthDate->day))?new \DateTime($call->personalData->birthDate->day.'-'.$call->personalData->birthDate->month.'-'.$call->personalData->birthDate->year):null;
	}

	/**
	 * Récupérer les annonces de l'utilisateur connecté
	 * @param  boolean|array $params Parametres donnés à la recherche (voir getAnnonces)
	 * @param  integer $page   Numéro de le page
	 * @return Object          Retourne le résultat de getAnnoncesUser
	 */
	public function getMyAnnonces($params = false, $page = 0){
		if($this->userId){
			return SELF::getAnnoncesUser($this->userId, $params, $page);
		}else return false;
		
	}

}


?>