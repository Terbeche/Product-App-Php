<?php
class Database
{
  private $host;
  private $user;
  private $password;
  private $name;
  private $connection;

  public function __construct($host, $user, $password, $name)
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->name = $name;

    $this->connect();
  }

  public function connect()
  {
    $dsn = "mysql:host={$this->host};dbname={$this->name}";
    $this->connection = new PDO($dsn, $this->user, $this->password);
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function createProductsTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS products (
id INT(11) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
marque VARCHAR(255) NOT NULL,
prix DECIMAL(10, 2),
couleur VARCHAR(255) NOT NULL,
quantite DECIMAL(10, 2),
date DATE,
description VARCHAR(255) NOT NULL,
photo LONGBLOB,
pdf LONGBLOB,
PRIMARY KEY (id)
)";

    $this->connection->exec($sql);
  }

  public function addProduct($name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf)
  {
    $sql = "INSERT INTO products (name, marque, prix, couleur, quantite, date, description, photo, pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$name, $marque, $prix, $couleur, $quantite, $date, $description, $photo, $pdf]);
  }
}
