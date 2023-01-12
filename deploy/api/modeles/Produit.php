 <?php
 class Produit implements jsonSerialize{
  public int $id;
public  string $name;
  public string $description;
  public int $price;
  public string $image;
  public string $summary;

      public function setName($name)
    {
        $this->name = $name;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Specify data which should be serialized to JSON
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @return mixed Returns data which can be serialized by json_encode(), which is a value of any type other than a resource .
     */
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
 }