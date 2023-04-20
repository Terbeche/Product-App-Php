<?php
require_once 'database.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>My Page</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <?php

  // First, we check if the form has been submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If it has, we handle the form data
    $name = $_POST['name'];
    $marque = $_POST['marque'];
    $prix = $_POST['prix'];
    $couleur = $_POST['couleur'];
    $quantite = $_POST['quantite'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $photo = $_FILES['photo']['name'];
    $pdf = $_FILES['pdf']['name'];

    // Create a database connection
    $db = new Database('localhost', 'root', '', 'products');

    // Create the products table if it doesn't exist
    $db->createProductsTable();

    // Add the product to the database
    $db->addProduct($name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf);

    // After handling the form data, we display a message to the user
    echo "Product submitted successfully!";
  }


  function getAllProducts()
  {
      // Create a database connection
      $db = new PDO('mysql:host=localhost;dbname=products', 'root', '');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // Prepare the query
      $stmt = $db->prepare("SELECT * FROM products");
  
      // Execute the query
      $stmt->execute();
  
      // Fetch the results
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
      return $products;
  }
  
  $allproducts = getAllProducts();



  
  foreach ($allproducts as $product) {
    $id = $product['id'];
    $name = $product['name'];
    $marque = $product['marque'];
    $prix = $product['prix'];
    $couleur = $product['couleur'];
    $quantite = $product['quantite'];
    $date = $product['date'];
    $description = $product['description'];
    $photo = $product['photo'];
    $pdf = $product['pdf'];
    
    $edit_url = "edit_product.php?id=$id";
    $delete_url = "delete_product.php?id=$id";
    echo "<tr><td>$id</td><td>$name</td><td>$marque</td><td>$prix</td><td>$couleur</td><td>$quantite</td><td>$date</td><td>$description</td><td><a href='$edit_url'>Edit</a></td><td><a href='$delete_url'>Delete</a></td></tr>";
}


  ?>

  <!-- Here's the HTML form -->
  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
    <label for="name">Nom Produit:</label><br>
    <input type="text" id="name" name="name"><br>
    <label for="marque">Marque:</label><br>
    <input type="text" id="marque" name="marque"><br>
    <label for="prix">Prix:</label><br>
    <input type="number" id="prix" name="prix"><br>
    <label for="couleur">Couleur:</label><br>
    <input type="text" id="couleur" name="couleur"><br>
    <label for="quantite">Quantité:</label><br>
    <input type="number" id="quantite" name="quantite"><br>
    <label for="date">Date de péremption:</label><br>
    <input type="date" id="date" name="date"><br>
    <label for="description">Texte descriptif:</label><br>
    <textarea id="description" name="description"></textarea><br>
    <label for="photo">Photo:</label><br>
    <input type="file" id="photo" name="photo"><br>
    <label for="pdf">PDF enregistré:</label><br>
    <input type="file" id="pdf" name="pdf"><br>
    <input type="submit" id="submit" name="submit" value="Soumettre">
  </form>

  <table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nom Produit</th>
      <th>Marque</th>
      <th>Prix</th>
      <th>Couleur</th>
      <th>Quantité</th>
      <th>Date de péremption</th>
      <th>Texte descriptif</th>
      <th>Photo</th>
      <th>PDF enregistré</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($allproducts as $product) { ?>
    <tr>
      <td><?php echo $product['id']; ?></td>
      <td><?php echo $product['name']; ?></td>
      <td><?php echo $product['marque']; ?></td>
      <td><?php echo $product['prix']; ?></td>
      <td><?php echo $product['couleur']; ?></td>
      <td><?php echo $product['quantite']; ?></td>
      <td><?php echo $product['date']; ?></td>
      <td><?php echo $product['description']; ?></td>
      <td><?php echo $product['photo']; ?></td>
      <td><?php echo $product['pdf']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>

</body>

</html>