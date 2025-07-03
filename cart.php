<?php
session_start();

error_reporting(E_ERROR | E_PARSE);

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="assets/razerLogo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111;
            color: #eee;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .cart-item {
            animation: fadeInUp 0.5s ease-out backwards;
        }
        .cart-item.removing {
            animation: fadeOutLeft 0.5s ease-in-out forwards;
        }
        @keyframes fadeOutLeft {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-50px); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); color: initial; }
            50% { transform: scale(1.1); color: #4f46e5; }
        }
        .price-update { animation: pulse 0.4s ease-out; }
        .checkout-button { transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .checkout-button:hover { transform: translateY(-3px); box-shadow: 0 7px 14px rgba(79, 70, 229, 0.2); }
        .checkout-button:active { transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .transition-all { transition: all 0.3s ease-in-out; }
        .quantity-input { width: 60px; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; }
        .cart-item:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-gray-900 text-gray-200">
    <header class="bg-gray-800 shadow-lg sticky top-0 z-10">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-gray-200">
                <i class="fas fa-shopping-bag text-indigo-400"></i> Razer Store
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="shop.php" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">Home</a>
                <a href="shop.php" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">Products</a>
                <a href="mailto:m.alyasa62@gmail.com" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">Contact</a>
                <a href="cart.php" class="text-indigo-400 font-semibold border-b-2 border-indigo-400">Cart</a>
            </div>
            <div class="flex items-center">
                <button id="cart-icon" class="relative">
                    <i class="fas fa-shopping-cart text-2xl text-gray-400 hover:text-indigo-400 transition-colors"></i>
                    <span id="cart-count-bubble" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
                    </span>
                </button>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div id="cart-container">
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="w-full lg:w-2/3">
                    <h1 class="text-3xl font-bold mb-6 border-b pb-4">Your Shopping Cart</h1>
                    <div id="cart-items-container" class="space-y-6">
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                <div class="cart-item flex items-center justify-between bg-gray-800 p-4 rounded-lg shadow-md transition-all duration-300">
                                    <div class="flex items-center gap-4 flex-grow">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-20 object-cover rounded-md">
                                        <div class="flex-grow">
                                            <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['name']) ?></h3>
                                            <p class="text-gray-400">$<?= number_format($item['price'], 2) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center border rounded-md overflow-hidden">
                                            <button class="quantity-change px-3 py-1 text-lg bg-gray-700 hover:bg-gray-600 transition-colors" data-action="decrease" data-id="<?= $product_id ?>">-</button>
                                            <input type="number" class="quantity-input text-center appearance-none bg-gray-800" value="<?= $item['quantity'] ?>" min="1" data-id="<?= $product_id ?>">
                                            <button class="quantity-change px-3 py-1 text-lg bg-gray-700 hover:bg-gray-600 transition-colors" data-action="increase" data-id="<?= $product_id ?>">+</button>
                                        </div>
                                        <p class="item-total font-semibold w-24 text-right text-lg">$<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                        <button class="remove-item text-gray-400 hover:text-red-500 transition-colors w-10 h-10 flex items-center justify-center" data-id="<?= $product_id ?>">
                                            <i class="fas fa-trash-alt text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div id="empty-cart-message" class="text-center py-16 bg-gray-800 rounded-lg shadow-md">
                                <i class="fas fa-cart-arrow-down text-5xl text-gray-400 mb-4"></i>
                                <h2 class="text-2xl font-semibold text-gray-400">Your cart is empty.</h2>
                                <p class="text-gray-400 mt-2">Looks like you haven't added anything to your cart yet.</p>
                                <a href="shop.php" class="mt-6 inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 checkout-button">Start Shopping</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="order-summary" class="w-full lg:w-1/3">
                    <div class="bg-gray-800 p-6 rounded-lg shadow-xl sticky top-28">
                        <h2 class="text-2xl font-bold mb-6 border-b pb-4">Order Summary</h2>
                        <div class="space-y-4 mb-6 text-lg">
                            <div class="flex justify-between text-gray-400">
                                <span>Subtotal</span>
                                <span id="subtotal">
                                    $<?php
                                    $subtotal = 0;
                                    if (isset($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $item) {
                                            $subtotal += $item['price'] * $item['quantity'];
                                        }
                                    }
                                    echo number_format($subtotal, 2);
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="flex justify-between border-t pt-4 font-bold text-xl">
                                <span>Total</span>
                                <span id="total">
                                    $<?php
                                    $total = 0;
                                    if (isset($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $item) {
                                            $total += $item['price'] * $item['quantity'];
                                        }
                                    }
                                    echo number_format($total, 2);
                                    ?>
                                </span>
                            </div>
                        </div>
                        <a href="checkout.php" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 checkout-button">
                                 Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 mt-16 py-8 border-t border-gray-700">
        <div class="container mx-auto px-6 text-center text-gray-400">
            <p>&copy; <?= date("Y") ?> MyStore Shopping Application. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartItemsContainer = document.getElementById('cart-items-container');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const cartCountBubble = document.getElementById('cart-count-bubble');

            function updateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.cart-item').forEach(item => {
                    const priceText = item.querySelector('.text-gray-400').textContent.replace('$', '');
                    const price = parseFloat(priceText);
                    const quantity = parseInt(item.querySelector('.quantity-input').value);
                    subtotal += price * quantity;
                });

                subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
                totalEl.textContent = `$${subtotal.toFixed(2)}`;

                let totalItems = 0;
                document.querySelectorAll('.quantity-input').forEach(input => {
                    totalItems += parseInt(input.value);
                });
                cartCountBubble.textContent = totalItems;
            }

            cartItemsContainer.addEventListener('click', (e) => {
                const target = e.target.closest('button');
                if (!target) return;

                if (target.classList.contains('quantity-change')) {
                    const id = parseInt(target.dataset.id);
                    const action = target.dataset.action;
                    const input = target.parentElement.querySelector('.quantity-input');
                    let quantity = parseInt(input.value);

                    if (action === 'increase') {
                        quantity++;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantity--;
                    }

                    input.value = quantity;
                    updateTotals();
                }

                if (target.classList.contains('remove-item')) {
                    const id = parseInt(target.dataset.id);
                    const itemEl = target.closest('.cart-item');
                    itemEl.classList.add('removing');

                    itemEl.addEventListener('animationend', () => {
                        itemEl.remove();
                        updateTotals();
                    });
                }
            });

            cartItemsContainer.addEventListener('change', (e) => {
                if (e.target.matches('.quantity-input')) {
                    const newQuantity = parseInt(e.target.value);
                    if (newQuantity < 1) {
                        e.target.value = 1;
                    }
                    updateTotals();
                }
            });

            updateTotals();
        });
    </script>
</body>
</html>
