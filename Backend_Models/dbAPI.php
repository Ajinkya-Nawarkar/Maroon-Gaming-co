<?php
class dbAPI
{
  //Database connection information
  public $conn;


  public function __construct(){
    $hn = "pluto.cse.msstate.edu";
    $un = "cu81";
    $pw = "aDqhvAtAp4ny5JMr";
    $db = "cu81";
    $this->conn = new mysqli($hn, $un, $pw, $db);
    if ($this->conn->connect_error) die($this->conn->connect_error);
  }

  //Function accepts any query and returns the result.
  public function query($query){
    $result = mysqli_fetch_array($this->conn->query($query));
    return $result;
  }

  public function newUser($user){
    //Initialize a new user's cart with an array containing 50 indexes filled with 0's.
    //When a user adds an item to their cart the value at the index equal to the item's sku
    //will be changed to the amount of the item the user wants.
    $array = array();
    for($i = 0;$i < 50;$i++) {
      $array[] = $i;
      $array[$i] = 0;
    }
    $query  = "INSERT INTO users (username, password, firstname, lastname, cart) "
            . "VALUES('$user->username', '$user->password', '$user->firstname', '$user->lastname', '$array')";
    $this->conn->query($query);
    return true;
  }

  public function deleteUser($username){
    $query  = "DELETE FROM users WHERE username = '$username'";
    $this->conn->query($query);
    return true;
  }

  public function newAdmin($admin){
    $query  = "INSERT INTO admins (username, password) "
            . "VALUES('$admin->username', '$admin->password')";
    $this->conn->query($query);
    return true;
  }

  public function deleteAdmin($username){
    $query  = "DELETE FROM admins WHERE username = '$username'";
    $this->conn->query($query);
    return true;
  }
  public function getItem($sku){
    $result = mysqli_fetch_array($this->conn->query("SELECT * FROM items WHERE sku = '$sku'"));
    return $result;
  }

  public function addItemToDB($item){
    $query  = "INSERT INTO items (sku, name, platform, type, developer, description, priceUSD, quantity) "
            . "VALUES('$item->sku', '$item->name', '$item->platform', '$item->type', '$item->developer', '$item->description', '$item->priceUSD', '$item->quantity')";
    $this->conn->query($query);
    return true;
  }

  public function removeItemfromDB($sku){
    $query  = "DELETE FROM items WHERE sku = '$sku'";
    $this->conn->query($query);
    return true;
  }

  public function modifyItemQuantityInDB($sku, $amount){
    $query  = "UPDATE items SET quantity = '$amount' WHERE sku = '$sku'";
    $this->conn->query($query);
    return true;
  }

  public function editCartQuant($username, $sku, $amount){
    //Retrieve arrays from itemQuantity and itemList
    $cartQuery = mysqli_fetch_array($this->conn->query("SELECT cart FROM users WHERE username = '$username'"));
    $cartQuery[0][$sku] = $amount;
    //Add queries to update overall inventory

    //Send an updated query using the new cartQuery array
    $query  = "UPDATE users SET cart = '$cartQuery' WHERE username = '$username'";
    $this->conn->query($query);
    return true;
  }
  public function addToCart($username, $sku, $amount){
    //Retrieve arrays from itemQuantity and itemList
    $cartQuery = mysqli_fetch_array($this->conn->query("SELECT cart FROM users WHERE username = '$username'"));
    $cartQuery[0][$sku] = $amount;
    //Add queries to update overall inventory
    
    //Send an updated query using the new cartQuery array
    $query  = "UPDATE users SET cart = '$cartQuery' WHERE username = '$username'";
    $this->conn->query($query);
    return true;
  }
  public function removeFromCart($username, $sku){
    //Retrieve arrays from itemQuantity and itemList
    $cartQuery = mysqli_fetch_array($this->conn->query("SELECT cart FROM users WHERE username = '$username'"));
    $cartQuery[0][$sku] = 0;
    //Add queries to update overall inventory

    //Send an updated query using the new cartQuery arrays
    $query  = "UPDATE users SET cart = '$cartQuery' WHERE username = '$username'";
    $this->conn->query($query);
    return true;
  }
}
?>
