<?php
// your_script.php

// Configuration de la connexion à la base de données
// (remplacez les valeurs par celles de votre propre base de données)
$servername = "localhost";
$username = "root";
//$password = "";
$dbname = "medsell";

// Connexion à la base de données avec PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérez les données du panier
$cartData = json_decode($_POST['cart'], true);

// Insérez ici la logique pour enregistrer la commande et les détails de la commande dans la base de données
// Vous devrez adapter cette partie en fonction de la structure de votre base de données

try {
    // Commencez une transaction
    $conn->beginTransaction();

    $client_name = $_SESSION['client_name']; // Récupérez le nom du client à partir de la session
    $id_client = $_SESSION['id_client']; // Récupérez l'ID du client à partir de la session
    $id_user_order = $_SESSION['id_user']; // Récupérez l'ID de l'utilisateur à partir de la session
    $statut = "En attente"; // Définissez le statut initial de la commande

    // Insérez la commande dans la table 'orders'
    $sql = "INSERT INTO orders (amount, client_name, date_order, id_client, id_user_order, statut) VALUES (:amount, :client_name, CURRENT_TIMESTAMP, :id_client, :id_user_order, :statut)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':client_name', $client_name);
    $stmt->bindParam(':id_client', $id_client);
    $stmt->bindParam(':id_user_order', $id_user_order);
    $stmt->bindParam(':statut', $statut);
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Insérez les détails de la commande dans la table 'data_orders'
    $sql = "INSERT INTO data_orders (id_order, id_product, price, quantity) VALUES (:id_order, :productId, :price, :quantity)";
    $stmt = $conn->prepare($sql);

    foreach ($cartData as $item) {
        $product_id = $item['productId'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        $stmt->bindParam(':id_order', $order_id);
        $stmt->bindParam(':productId', $product_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);

        $stmt->execute();
    }

    // Validez la transaction
    $conn->commit();
} catch (PDOException $e) {
    // Annulez la transaction en cas d'erreur
    $conn->rollBack();
    die("Erreur lors de l'enregistrement de la commande : " . $e->getMessage());
}
