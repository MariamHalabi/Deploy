 <?php
 class Produit {
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
 }