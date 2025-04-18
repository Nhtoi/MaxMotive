document.addEventListener("DOMContentLoaded", function() {
    const addToCartButton = document.getElementById("add-to-cart-btn");

    if (addToCartButton) {
        addToCartButton.addEventListener('click', function() {
            const productId = document.getElementById('product-id').value;
            window.location.href = `/cart/${productId}/add`; 
        });
    }

    const viewCartBtn = document.getElementById('viewcart-btn');
    const cartRoute = window.cartRoute;

    if (viewCartBtn && cartRoute) {
        viewCartBtn.addEventListener('click', () => {
            window.location.href = cartRoute;
        });
    }

    const cartContainer = document.getElementById('cart-container');
    const totalDisplay = document.getElementById('cart-total');

    if (cartContainer) {
        cartContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-from-cart')) {
                const itemId = e.target.getAttribute('data-item-id');
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/cart/${itemId}/remove`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
