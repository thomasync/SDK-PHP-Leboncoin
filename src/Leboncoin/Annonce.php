<?php

namespace Absmoca;

class Annonce
{
    /**
     * @var integer Id de l'annonce
     */
    protected $id;

    /**
     * @var string Nom de l'annonce
     */
    protected $name;

    /**
     * @var \Datetime Date de l'annonce
     */
    protected $date;

    /**
     * @var integer Id de la catégorie de l'annonce
     */
    protected $categoryId;

    /**
     * @var string Nom de la catégorie de l'annonce
     */
    protected $category_name;

    /**
     * @var string Description de l'annonce
     */
    protected $description;

    /**
     * @var string Url de l'annonce
     */
    protected $url;

    /**
     * @var integer|null Prix de l'annonce
     */
    protected $price;

    /**
     * @var array|null Images de l'annonce
     */
    protected $images;

    /**
     * @var array|null Options supplémentaires de l'annonce
     */
    protected $attributes;

    /**
     * @var array(region_id,region_name,department_id,department_name,city_label,city,zipcode,lat,lng,source,provider,is_shape) Localisation de l'annonce
     */
    protected $location;

    /**
     * @var array(store_id,user_id,type,name,no_salesmen) Infos sur le propriétaire de l'annonce
     */
    protected $owner;

    /**
     * @var boolean Indique si un numéro de téléphone est disponible
     */
    protected $phone;

    /**
     * Créer l'object Annonce à partir d'un objet issue de l'API
     *
     * @param object $date Peut-être récupéré via getAnnonces ou donné via un ID d'annonce
     * @throws \Exception
     */
    public function __construct($data = null) {
        if(is_object($data)
            && isset($data->list_id)
            && isset($data->subject)
            && isset($data->index_date)
            && isset($data->category_id)
            && isset($data->category_name)
            && isset($data->body)
            && isset($data->url)
            && isset($data->images)
            && isset($data->location)
            && isset($data->owner)
            && isset($data->has_phone)
        ){
            $this->setId((int)$data->list_id);
            $this->setName($data->subject);
            $this->setDate(new \DateTime($data->index_date));
            $this->setCategoryId((int)$data->category_id);
            $this->setCategoryName($data->category_name);
            $this->setDescription($data->body);
            $this->setUrl($data->url);
            $this->setPrice((int)(isset($data->price[0])) ? $data->price[0] : null);
            $this->setImages(
                (isset($data->images->urls) && count($data->images->urls) > 0) ? $data->images->urls : null
            );
            $this->setAttributes((isset($data->attributes)) ? $data->attributes : null);
            $this->setLocation((array)$data->location);
            $this->setOwner((array)$data->owner);
            $this->setPhone((boolean)$data->has_phone);
        }
    }

    /**
     * Récuperer l'image de l'annonce en grande taille
     * @param  integer $index Spécifie le numéro de l'image
     * @return string         Lien de l'image
     */
    public function bigImage($index = 0)
    {
        return str_replace('ad-image', 'ad-large', $this->images[$index]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function setId(int $id): void
    {
        if($id > 0){
            $this->id = $id;
        } else throw new \Exception("Id invalide");
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \Datetime
     */
    public function getDate(): \Datetime
    {
        return $this->date;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate(\Datetime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * @param string $category_name
     */
    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return array|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param array|null $images
     */
    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param array|null $attributes
     */
    public function setAttributes(?array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getLocation(): array
    {
        return (is_array($this->location)) ? $this->location : array();
    }

    /**
     * @param array $location
     */
    public function setLocation(array $location): void
    {
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getOwner(): array
    {
        return (is_array($this->owner)) ? $this->owner : array();
    }

    /**
     * @param array $owner
     */
    public function setOwner(array $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return bool
     */
    public function isPhone(): bool
    {
        return $this->phone;
    }

    /**
     * @param bool $phone
     */
    public function setPhone(bool $phone): void
    {
        $this->phone = $phone;
    }
}