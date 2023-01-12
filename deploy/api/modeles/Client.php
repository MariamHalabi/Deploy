<?php

class Client implements \JsonSerializable{
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

    /**
     * Specify data which should be serialized to JSON
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @return mixed Returns data which can be serialized by json_encode(), which is a value of any type other than a resource .
     */
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        unset($vars['password']);
        return $vars;
    }
}