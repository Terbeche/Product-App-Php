  <?php
  require_once 'functions.php';
  
  function delete_product($id)
  {
     deleteProduct($id);
      echo "Product deleted successfully!";
  }

  $editForm = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action  = $_POST['action'];

    if ($action  === 'add') {

      $name = $_POST['name'];
      $marque = $_POST['marque'];
      $prix = $_POST['prix'];
      $couleur = $_POST['couleur'];
      $quantite = $_POST['quantite'];
      $date = $_POST['date'];
      $description = $_POST['description'];
    
  
      $photo_name = $_FILES['photo']['name'];
      $photo_tmp_name = $_FILES['photo']['tmp_name'];
      $photo_error = $_FILES['photo']['error'];
      $photo_size = $_FILES['photo']['size'];
    
      $pdf_name = $_FILES['pdf']['name'];
      $pdf_tmp_name = $_FILES['pdf']['tmp_name'];
      $pdf_error = $_FILES['pdf']['error'];
      $pdf_size = $_FILES['pdf']['size'];
    
      // Check for photo upload errors
      if ($photo_error !== UPLOAD_ERR_OK) {
      // Handle photo upload error
        die("Error uploading photo: $photo_error");
       }
    
      // Check for pdf upload errors
      if ($pdf_error !== UPLOAD_ERR_OK) {
       // Handle pdf upload error
         die("Error uploading PDF: $pdf_error");
       }

      // Move uploaded photo to permanent location
       $photo_target_path = "uploads/" . basename($photo_name);
       if (!move_uploaded_file($photo_tmp_name, $photo_target_path)) {
       // Handle photo move error
         die("Error moving photo to $photo_target_path");
       }

       // Move uploaded pdf to permanent location
        $pdf_target_path = "uploads/" . basename($pdf_name);
        if (!move_uploaded_file($pdf_tmp_name, $pdf_target_path)) {
         // Handle pdf move error
         die("Error moving PDF to $pdf_target_path");
       }

      createProductsTable();
      
      addProduct($name, $marque, $prix, $couleur, $quantite, $date, $description, $photo_target_path, $pdf_target_path);
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }
  else if ($action  === 'delete') {
    $id = $_POST['id'];
    delete_product($id);
  }else if ($action === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $marque = $_POST['marque'];
    $prix = $_POST['prix'];
    $couleur = $_POST['couleur'];
    $quantite = $_POST['quantite'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $photo = $_FILES['photo']['name'] ?? null;
    $pdf = $_FILES['pdf']['name'] ?? null;


    // check if photo was uploaded
if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
  $photo_path = 'uploads/' . basename($_FILES['photo']['name']);
  move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
} else {
  // get original photo path from database
  $stmt = $db->prepare("SELECT photo FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  $photo_path = $row['photo'];
}

// check if pdf was uploaded
if (isset($_FILES['pdf']) && $_FILES['pdf']['name']) {
  $pdf_path = 'uploads/' . basename($_FILES['pdf']['name']);
  move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_path);
} else {
  // get original pdf path from database
  $stmt = $db->prepare("SELECT pdf FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  $pdf_path = $row['pdf'];
}
   $editForm = "
      <h2>Edit Product</h2>
      <form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' enctype='multipart/form-data'>
        <input type='hidden' name='id' value='$id'>
        <label for='name'>Nom Produit:</label><br>
        <input type='text' id='name' name='name' value='$name'><br>
        <label for='marque'>Marque:</label><br>
        <input type='text' id='marque' name='marque' value='$marque'><br>
        <label for='prix'>Prix:</label><br>
        <input type='number' id='prix' name='prix' value='$prix'><br>
        <label for='couleur'>Couleur:</label><br>
        <input type='text' id='couleur' name='couleur' value='$couleur'><br>
        <label for='quantite'>Quantité:</label><br>
        <input type='number' id='quantite' name='quantite' value='$quantite'><br>
        <label for='date'>Date de péremption:</label><br>
        <input type='date' id='date' name='date' value='$date'><br>
        <label for='description'>Texte descriptif:</label><br>
        <textarea id='description' name='description'>$description</textarea><br>
        <label for='photo'>Photo:</label><br>
        <input type='file' id='photo' name='photo'><br>
        <label for='pdf'>PDF:</label><br>
        <input type='file' id='pdf' name='pdf'><br>
        <input type='submit' name='action' value='Save Changes'>
      </form>";
  
  }else if ($action === 'Save Changes') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $marque = $_POST['marque'];
    $prix = $_POST['prix'];
    $couleur = $_POST['couleur'];
    $quantite = $_POST['quantite'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $photo = $_FILES['photo']['name'] ?? null;
    $pdf = $_FILES['pdf']['name'] ?? null;
  
    updateProduct($id, $name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;


  }
}
  $allproducts = getAllProducts();

  ?>

  <!DOCTYPE html>
  <html>
  <head>
      <meta charset="UTF-8">
      <title>My Page</title>
      <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
      <?php if ($editForm) {
        echo $editForm;
      }else {
       ?>

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
      <input type="hidden" name="action" value="add">
      <input type="submit" id="submit" name="submit" value="Soumettre">
      </form>
      <?php } ?>
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
        <th>Action</th>
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
  <td><a href="<?php echo $photo_path ?>" download="<?php echo basename($photo_path) ?>">Download Photo</a></td>
  <td><a href="<?php echo $pdf_path ?>" download="<?php echo basename($pdf_path) ?>">Download PDF</a></td>
  
      
        <td>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
          <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
          <input type="hidden" name="marque" value="<?php echo $product['marque']; ?>">
          <input type="hidden" name="prix" value="<?php echo $product['prix']; ?>">
          <input type="hidden" name="couleur" value="<?php echo $product['couleur']; ?>">
          <input type="hidden" name="quantite" value="<?php echo $product['quantite']; ?>">
          <input type="hidden" name="date" value="<?php echo $product['date']; ?>">
          <input type="hidden" name="description" value="<?php echo $product['description']; ?>">
          <input type="hidden" name="photo" value="<?php echo $product['photo']; ?>">
          <input type="hidden" name="pdf" value="<?php echo $product['pdf']; ?>">
          <button type="submit">Edit</button>
        </form>


          <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <button type="submit">Delete</button>
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </body>

  </html>