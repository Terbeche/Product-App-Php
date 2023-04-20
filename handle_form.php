<?php
require_once 'database.php';

$name = $_POST['name'];
$marque = $_POST['marque'];
$prix = $_POST['prix'];
$couleur = $_POST['couleur'];
$quantite = $_POST['quantite'];
$date = $_POST['date'];
$description = $_POST['description'];
$photo = $_POST['photo'];
$pdf = $_POST['pdf'];

// do something with the data, for example:
echo "Nom Produit: " . $name . "<br>";
echo "Marque: " . $marque . "<br>";
echo "Prix: " . $prix . "<br>";
echo "Couleur: " . $couleur . "<br>";
echo "Quantité: " . $quantite . "<br>";
echo "Date de péremption: " . $date . "<br>";
echo "Texte descriptif: " . $description . "<br>";
echo "Photo: " . $photo . "<br>";
echo "PDF enregistré: " . $pdf . "<br>";



$db = new Database('localhost', 'root', '', 'produits');

$db->createProduitsTable();
$db->addProduct($name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf);
