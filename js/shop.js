
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = document.getElementById('cart-count');
    cartCount.textContent = cart.length;
}

document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.add-to-cart');
        if (buttons.length > 0) {
            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = e.target.getAttribute('data-prod-id');
                    const productName = e.target.getAttribute('data-prod-name');
                    const productPrice = parseFloat(e.target.getAttribute('data-prod-price'));
    
                    if (!productId || !productName || isNaN(productPrice)) {
                        alert('Product information is missing!');
                        return;
                    }
    
                    const product = {
                        id: productId,
                        name: productName,
                        price: productPrice
                    };
    
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
                    cart.push(product);

                    localStorage.setItem('cart', JSON.stringify(cart));
    
                    updateCartCount();
    
                    alert(`${productName} has been added to the cart.`);
        });
    });
}

    updateCartCount();
});
