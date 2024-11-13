<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image']; // Replace with actual file handling

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image]);
    header('Location: products.php');
}
?>

<form action="add_product.php" method="POST">
    Name: <input type="text" name="name"><br>
    Description: <textarea name="description"></textarea><br>
    Price: <input type="number" name="price" step="0.01"><br>
    Image URL: <input type="text" name="image"><br>
    <input type="submit" value="Add Product">
</form>
