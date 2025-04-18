document.addEventListener("DOMContentLoaded", function() {
    let cart = JSON.parse(localStorage.getItem('cart')) || []; 

    const addToCartButton = document.getElementById("add-to-cart-btn");

    if (addToCartButton) {
        addToCartButton.addEventListener('click', function() {
           
            const productName = document.getElementById('product-name').textContent; 
            const productPrice = parseFloat(document.getElementById('product-price').textContent.replace('$', '')); 
            const productDesc = document.getElementById('product-description').textContent; 
            const productId = document.getElementById('product-id').value;
            const existingProduct = cart.find(item => item.id === productId);

            if (existingProduct) {
                existingProduct.quantity += 1;  
            } else {

                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    desc: productDesc,
                    quantity: 1
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            alert(`${productName} has been added to your cart!`);
        });
    }
    const viewCartBtn = document.getElementById('viewcart-btn');
    if (viewCartBtn) {
        const cartRoute = viewCartBtn.getAttribute('data-cart-url');  

        viewCartBtn.addEventListener('click', () => {
            window.location.href = cartRoute;
        });
    }

    const cartContainer = document.getElementById('cart-container');
    const totalDisplay = document.getElementById('cart-total');

    if (cartContainer && totalDisplay) {
        function renderCart() {
            cartContainer.innerHTML = ''; 

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
            totalDisplay.textContent = `Total: $${total.toFixed(2)}`;
        }

        renderCart();  
        cartContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-from-cart')) {
                const productId = e.target.getAttribute('data-product-id');
                cart = cart.filter(item => item.id !== productId); 
                localStorage.setItem('cart', JSON.stringify(cart));  
                renderCart();  
            }
        });
    }
});
