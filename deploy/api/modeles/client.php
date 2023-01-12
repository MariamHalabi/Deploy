<?php
class Client {
    public int $id;
    public string $login;
    public string $password;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $phone;
    public string $address;
    public string $city;
    public string $cp;
    public string $country;
    public string $civility;

        public function getId()
    {
        return $this->id;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    public function setLogin($login)
    {
        $this->login = $login;
    }
    public function setCivility($civility)
    {
        $this->civility = $civility;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }
    public function setCity($city)
    {
        $this->city = $city;
    }
    public function setCP($codecity)
    {
        $this->codecity = $codecity;
    }
    public function setCountry($country)
    {
        $this->country = $country;
    }
}