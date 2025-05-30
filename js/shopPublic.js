document.addEventListener('DOMContentLoaded', function () {

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCart() {
        const cartItemsList = document.getElementById('cart-items');
        const totalPrice = document.getElementById('total-price');
        cartItemsList.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.name} - $${item.price} x ${item.quantity}`;
            cartItemsList.appendChild(li);
            total += item.price * item.quantity;
        });

        totalPrice.textContent = `Total: $${total.toFixed(2)}`;
    }

    const addToCartButtons = document.querySelectorAll('add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const prodID = this.getAttribute('data-id');
            const prodName = this.getAttribute('data-name');
            const prodPrice = parseFloat(this.getAttribute('data-price'));

            console.log(`Adding to cart: ${prodName} - $${prodPrice}`); 
            let found = false;
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].id === prodID) {
                    cart[i].quantity += 1;
                    found = true;
                    break;
                }
            }

            if (!found) {
                cart.push({
                    id: prodID,
                    name: prodName,
                    price: prodPrice,
                    quantity: 1
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));

            console.log('Cart after update:', cart);

            updateCart();
        });
    });

    updateCart();
});
