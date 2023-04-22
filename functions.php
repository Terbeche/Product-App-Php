<?php

require_once 'database.php';

function getAllProducts()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $products;
}

function addProduct($name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf)
{
    global $db;
    $photo_dir = "uploads/images/";
    $pdf_dir = "uploads/pdfs/";
    
    // Upload image
    $photo_name = basename($_FILES["photo"]["name"]);
    $photo_target = $photo_dir . $photo_name;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_target);
    
    // Upload PDF
    $pdf_name = basename($_FILES["pdf"]["name"]);
    $pdf_target = $pdf_dir . $pdf_name;
    move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdf_target);
    
    $stmt = $db->prepare("INSERT INTO products (name, marque, prix, couleur, quantite, date, description, photo, pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $marque, $prix, $couleur, $quantite, $date, $description, $photo_target, $pdf_target]);
    
    return "Product added successfully!";
}
function getProduct($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    return $product;
}

function updateProduct($id, $name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf)

{
    global $db;
     $stmt = $db->prepare("UPDATE products SET name=?, marque=?, prix=?, couleur=?, quantite=?, date=?, description=?, photo=?, pdf=? WHERE id=?");
     $stmt->execute([$name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf, $id]);

    return "Product updated successfully!";
}

function deleteProduct($id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    return "Product deleted successfully!";
}

function createProductsTable()
{
    global $db;
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        marque VARCHAR(255) NOT NULL,
        prix DECIMAL(10,2) NOT NULL,
        couleur VARCHAR(255) NOT NULL,
        quantite INT(11) NOT NULL,
        date DATE NOT NULL,
        description TEXT NOT NULL,
        photo LONGBLOB NOT NULL,
        pdf LONGBLOB NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $db->exec($sql);
}
