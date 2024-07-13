import './bootstrap';
// Asumiramo da ste već podesili Echo kako treba.
// resources/js/app.js (ili odgovarajući JS fajl)


document.addEventListener("DOMContentLoaded", function() {
    const cartFixed = document.getElementById("cartFixed");
    const cartBadgeNav = document.getElementById("cartBadgeNav");
    const cartBadgeCart = document.getElementById("cartBadgeCart");

    if (!cartFixed) {
        console.error("Cart element not found");
        return;
    }

    function initializeAddToCartButtons() {
        const buttons = document.querySelectorAll(".add-to-cart");

        buttons.forEach((button) => {
            button.removeEventListener("click", handleAddToCartClick);
            button.addEventListener("click", handleAddToCartClick);
        });

        console.log("Dugmad za dodavanje u korpu: ", buttons);
    }

    function handleAddToCartClick(event) {
        const itemElement = this.closest(".card");
        if (!itemElement) return;

        const item = {
            id: itemElement.dataset.id,
            name: itemElement.dataset.name,
            price: parseFloat(itemElement.dataset.price),
            image: itemElement.querySelector("img").src,
        };

        console.log("Kliknuto na dugme za dodavanje u korpu", item);

        const flyItem = document.createElement("img");
        flyItem.src = item.image;
        flyItem.className = "fly-item";
        flyItem.style.position = "fixed";
        flyItem.style.top = `${itemElement.getBoundingClientRect().top}px`;
        flyItem.style.left = `${itemElement.getBoundingClientRect().left}px`;
        flyItem.style.width = "100px"; // Increased image size for better visibility
        flyItem.style.transition = "1.5s ease-in-out"; // Smoother and longer transition

        document.body.appendChild(flyItem);

        setTimeout(() => {
            flyItem.style.top = `${cartFixed.getBoundingClientRect().top}px`;
            flyItem.style.left = `${cartFixed.getBoundingClientRect().left}px`;
            flyItem.style.opacity = "0";
        }, 50);

        setTimeout(() => {
            flyItem.remove();
            console.log("Animacija završena");
        }, 1550);

        Livewire.dispatch('itemAdded', item);
    }

    initializeAddToCartButtons();

    const observer = new MutationObserver(() => {
        console.log("DOM promena detektovana, ponovo inicijalizujem dugmad");
        initializeAddToCartButtons();
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });

    Livewire.on('itemAdded', (item) => {
        const currentCount = parseInt(cartBadgeNav.textContent) || 0;
        const newCount = currentCount + 1;
        cartBadgeNav.textContent = newCount;
        cartBadgeCart.textContent = newCount;
    });
});
document.addEventListener("DOMContentLoaded", function() {
    const cartFixed = document.getElementById("cartFixed");
    const cartToggle = document.getElementById("cartToggle");
    const cartToggleClose = document.getElementById("cartToggleClose");

    if (!cartFixed || !cartToggle || !cartToggleClose) {
        console.error("One or more elements not found");
        return;
    }

    function toggleCart() {
        cartFixed.classList.toggle("open");
    }

    cartToggle.addEventListener("click", toggleCart);
    cartToggleClose.addEventListener("click", toggleCart);
});
