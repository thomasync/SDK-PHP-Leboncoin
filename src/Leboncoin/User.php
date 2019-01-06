<?php

namespace Absmoca;

class User
{

    private $token;
    private $refresh_token;

    /**
     * @var string Id de l'utilisateur
     */
    protected $userId;
    
    /**
     * @var integer Id du store de l'utilisateur
     */
    protected $storeId;
    
    /**
     * @var string Email de l'utilisateur
     */
    protected $email;
    
    /**
     * @var boolean Sexe de l'utilisateur
     */
    protected $gender;
    
    /**
     * @var string Prénom de l'utilisateur
     */
    protected $firstname;

    /**
     * @var string Nom de l'utilisateur
     */
    protected $lastname;

    /**
     * @var array(label,regionCode,dptCode,address,zipcode,city) Adresse de l'utilisateur
     */
    protected $address;

    /**
     * @var string Numéro de l'utilisateur
     */
    protected $phone;

    /**
     * @var string Pseudo de l'utilisateur
     */
    protected $pseudo;

    /**
     * @var \Datetime Date de naissance de l'utilisateur
     */
    protected $birthDate;


    /**
     * Constructeur, accepte la connexion via token
     * @param boolean|string $token
     * @param boolean|string $refresh_token
     */
    public function __construct($token = false, $refresh_token = false)
    {
        if ($token && $refresh_token) {
            $this->token = $token;
            $this->refresh_token = $refresh_token;
            $this->getInfosAccount();
        }
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isGender()
    {
        return $this->gender;
    }

    /**
     * @param bool $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return array
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param array $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return \Datetime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \Datetime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

}