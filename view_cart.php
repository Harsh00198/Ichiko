<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT products.*, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll();
}
?>

<h2>Your Cart</h2>
<?php if (!empty($cartItems)): ?>
    <?php foreach ($cartItems as $item): ?>
        <div>
            <img src="<?php echo $item['image']; ?>" width="100">
            <h3><?php echo $item['name']; ?></h3>
            <p>Quantity: <?php echo $item['quantity']; ?></p>
            <p>Price: $<?php echo $item['price']; ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>
