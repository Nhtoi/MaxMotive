document.addEventListener("DOMContentLoaded", function() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];  // Load cart from localStorage

    // === Add Product to Cart from the Product Details Page ===
    const addToCartButton = document.getElementById("add-to-cart-btn");

    if (addToCartButton) {
        addToCartButton.addEventListener('click', function() {
            // Dynamically fetching product details (these values could be fetched from a backend API)
            const productName = document.getElementById('product-name').textContent; // Use actual product name from the page
            const productPrice = parseFloat(document.getElementById('product-price').textContent.replace('$', '')); // Parse price as a number
            const productDesc = document.getElementById('product-description').textContent; // Product description
            const productId = document.getElementById('product-id').value; // A unique ID, typically from the database

            // Check if the product is already in the cart
            const existingProduct = cart.find(item => item.id === productId);

            if (existingProduct) {
                existingProduct.quantity += 1;  // Increase quantity if already in cart
            } else {
                // Otherwise, add the new product to the cart
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    desc: productDesc,
                    quantity: 1
                });
            }

            // Save updated cart to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Optionally alert the user
            alert(`${productName} has been added to your cart!`);
        });
    }

    // === View Cart Button ===
    const viewCartBtn = document.getElementById('viewcart-btn');
    if (viewCartBtn) {
        // Get the cart route URL from the data attribute
        const cartRoute = viewCartBtn.getAttribute('data-cart-url');  // Get URL from data attribute

        viewCartBtn.addEventListener('click', () => {
            window.location.href = cartRoute;  // Redirect to the cart page using the dynamic URL
        });
    }

    // === Cart Page - Render Cart Items ===
    const cartContainer = document.getElementById('cart-container');
    const totalDisplay = document.getElementById('cart-total');

    if (cartContainer && totalDisplay) {
        function renderCart() {
            cartContainer.innerHTML = '';  // Clear the cart container

            if (cart.length === 0) {
                cartContainer.innerHTML = `<p>Your cart is empty.</p>`;
                totalDisplay.textContent = `Total: $0.00`;
                return;
            }

            let total = 0;

            cart.forEach(item => {
                total += item.price * item.quantity;
                cartContainer.innerHTML += `
                    <div class="cart-item" data-id="${item.id}">
                        <span>${item.name}</span>
                        <span>$${item.price}</span>
                        <span>Qty: ${item.quantity}</span>
                        <button class="remove-from-cart" data-product-id="${item.id}">Remove</button>
                    </div>
                `;
            });

            // Update the total price display
            totalDisplay.textContent = `Total: $${total.toFixed(2)}`;
        }

        renderCart();  // Call renderCart to populate the cart on page load

        // === Remove Item from Cart ===
        cartContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-from-cart')) {
                const productId = e.target.getAttribute('data-product-id');
                cart = cart.filter(item => item.id !== productId);  // Remove the item from the cart
                localStorage.setItem('cart', JSON.stringify(cart));  // Save updated cart
                renderCart();  // Re-render the cart
            }
        });
    }
});
