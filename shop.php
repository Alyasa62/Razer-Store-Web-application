<?php
session_start();

$products = [
    1 => [
        'name' => 'Razer Viper V2 Pro',
        'description' => 'Ultra-lightweight Wireless Esports Mouse',
        'price' => 149.99,
        'image' => 'assets/mouse.png'   
    ],
    2 => [
        'name' => 'Razer Huntsman V2',
        'description' => 'Optical Gaming Keyboard with near-zero input latency.',
        'price' => 189.99,
        'image' => 'assets/keyboard.png'
    ],
    3 => [
        'name' => 'Razer BlackShark V2',
        'description' => 'The definitive esports gaming headset. Unleashed.',
        'price' => 99.99,
        'image' => 'assets/headset.png'
    ]
];

if (isset($_POST["action"]) && $_POST["action"] == "add_to_cart") {
    $product_id = intval($_POST["product_id"]);
    $product_quantity = intval($_POST["product_quantity"]);

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($products[$product_id])) {
        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id]['quantity'] += $product_quantity;
        } else {
            $_SESSION["cart"][$product_id] = [
                'quantity' => $product_quantity,
                'name' => $products[$product_id]['name'],
                'description' => $products[$product_id]['description'],
                'price' => $products[$product_id]['price'],
                'image' => $products[$product_id]['image']
            ];
        }

        $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart!',
            'totalItems' => $total_items
        ]);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Product not found!'
        ]);
        exit();
    }
}

$total_items_on_load = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razer Store | For Gamers. By Gamers.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="icon" href="assets/razerLogo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #111;
            color: #eee;
        }
        .razer-green { color: #44d62c; }
        .bg-razer-green { background-color: #44d62c; }
        .border-razer-green { border-color: #44d62c; }
        .razer-header { background-color: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px); }
        .product-card { background-color: #222; border: 1px solid #333; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 30px rgba(0, 0, 0, 0.5), 0 0 40px rgba(68, 214, 44, 0.3); }
        .product-image-container { background-color: #1a1a1a; }
        .add-to-cart-btn { background-color: #44d62c; color: #111; font-weight: 900; text-transform: uppercase; transition: all 0.3s ease; letter-spacing: 1px; border: 2px solid #44d62c; }
        .add-to-cart-btn:hover { background-color: #fff; color: #44d62c; }
        .quantity-input { background-color: #333; border: 1px solid #555; color: #fff; }
        .quantity-input:focus { outline: none; border-color: #44d62c; box-shadow: 0 0 10px rgba(68, 214, 44, 0.5); }
        #toast-notification { position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%); background-color: #44d62c; color: #111; padding: 16px 24px; border-radius: 8px; font-weight: bold; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: bottom 0.5s ease-in-out; z-index: 1000; }
        #toast-notification.show { bottom: 30px; }
    </style>
</head>
<body class="bg-black">
    <header class="razer-header sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div><img src="https://placehold.co/150x40/000000/44D62C?text=RAZER" alt="Razer Logo"></div>
            <div class="hidden md:flex items-center space-x-8 text-lg">
                <a href="shop.php" class="razer-green font-bold">Store</a>
                <a href="Razer_PC.html" class="text-gray-300 hover:razer-green transition-colors">PC</a>
                <a href="console.html" class="text-gray-300 hover:razer-green transition-colors">Console</a>
                <a href="mobile.html" class="text-gray-300 hover:razer-green transition-colors">Mobile</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="cart.php" class="relative">
                    <i class="fas fa-shopping-cart text-2xl text-gray-300 hover:razer-green transition-colors"></i>
                    <span id="cart-count-bubble" class="absolute -top-2 -right-3 bg-razer-green text-black text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"><?= $total_items_on_load ?></span>
                </a>
                <a href="logout.php" title="Logout">
                    <i class="fas fa-sign-out-alt text-2xl text-gray-300 hover:text-red-500 transition-colors"></i>
                </a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <section>
            <h1 class="text-5xl font-black text-center mb-4">RAZER GAMING GEAR</h1>
            <p class="text-xl text-center razer-green mb-12">FOR GAMERS. BY GAMERS.™</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $id => $product): ?>
                <div class="product-card rounded-lg flex flex-col">
                    <div class="product-image-container p-8">
                        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="w-full h-auto">
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold mb-2"><?= $product['name'] ?></h3>
                        <p class="text-gray-400 mb-4 flex-grow"><?= $product['description'] ?></p>
                        <p class="text-3xl font-black razer-green mb-6">$<?= $product['price'] ?></p>
                        <form class="add-to-cart-form mt-auto">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <div class="flex items-center justify-between gap-4">
                                <input type="number" name="product_quantity" value="1" min="1" max="10" class="quantity-input w-20 text-center rounded-md p-2">
                                <button type="submit" class="add-to-cart-btn flex-grow py-3 rounded-md">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="border-t border-gray-800 mt-16 py-8">
        <div class="container mx-auto px-6 text-center text-gray-500">
            <p>&copy; <?= date("Y") ?> Razer Inc. All rights reserved.</p>
        </div>
    </footer>

    <div id="toast-notification">Item added to cart!</div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('.add-to-cart-form');
        const cartCountBubble = document.getElementById('cart-count-bubble');
        const toast = document.getElementById('toast-notification');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'add_to_cart');
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.textContent = 'Adding...';

                fetch('shop.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartCountBubble.textContent = data.totalItems;
                        cartCountBubble.classList.add('animate-ping');
                        setTimeout(() => cartCountBubble.classList.remove('animate-ping'), 700);
                        showToast(data.message);
                    } else {
                        showToast('Failed to add item.', true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred.', true);
                })
                .finally(() => {
                    button.disabled = false;
                    button.textContent = 'Add to Cart';
                });
            });
        });

        let toastTimer;
        function showToast(message, isError = false) {
            clearTimeout(toastTimer);
            toast.textContent = message;
            toast.style.backgroundColor = isError ? '#ef4444' : '#44d62c';
            toast.style.color = isError ? '#fff' : '#111';
            toast.classList.add('show');
            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    });
    </script>
</body>
</html>