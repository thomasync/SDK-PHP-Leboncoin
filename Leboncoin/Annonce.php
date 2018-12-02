<?php
namespace Absmoca;
class Annonce extends Leboncoin{

	public $id;				/** @var integer Id de l'annonce */
	public $name;			/** @var string Nom de l'annonce */
	public $date;			/** @var datetime Date de l'annonce */
	public $category_id;	/** @var integer Id de la catégorie de l'annonce */
	public $category_name;	/** @var string Nom de la catégorie de l'annonce */
	public $description;	/** @var string Description de l'annonce */
	public $url;			/** @var string Url de l'annonce */
	public $price;			/** @var integer Prix de l'annonce */
	public $images;			/** @var array Images de l'annonce */
	public $atributs;		/** @var array Options supplémentaires de l'annonce */
	public $location;		/** @var object(region_id,region_name,department_id,department_name,city_label,city,zipcode,lat,lng,source,provider,is_shape) Localisation de l'annonce */
	public $owner;			/** @var object(store_id,user_id,type,name,no_salesmen) Infos sur le propriétaire de l'annonce */
	public $phone;			/** @var boolean Indique si un numéro de téléphone est disponible */


	/**
	 * Constructeur, créer l'object Annonce
	 * @param (integer|object)  $a     Peut-être récupéré via getAnnonces ou donné via un ID d'annonce
	 * @param boolean $parse Vrai : Object donné / Faux : Integer donné
	 */
	public function __construct($a, $parse = false){
		if(!$parse){
			$a = PARENT::getAnnonce($a);
		}
		$this->id = (int) $a->list_id;
		$this->name = $a->subject;
		$this->date = new \DateTime($a->index_date);
		$this->category_id = (int) $a->category_id;
		$this->category_name = $a->category_name;
		$this->description = "";//$a->body;
		$this->url = $a->url;
		$this->price = (int) (isset($a->price[0]))?$a->price[0]:null;
		$this->images = (isset($a->images->urls)&&count($a->images->urls)>0)?$a->images->urls:null;
		$this->atributs = (isset($a->attributes))?$a->attributes:null;
		$this->location = $a->location;
		$this->owner = $a->owner;
		$this->phone = (boolean) $a->has_phone;
	}

	/**
	 * Récuperer l'image de l'annonce en grande taille
	 * @param  integer $index Spécifie le numéro de l'image
	 * @return string         Lien de l'image
	 */
	public function bigImage($index = 0){
		return str_replace('ad-image', 'ad-large', $this->images[$index]);
	}

}


?>