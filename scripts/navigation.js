document.addEventListener("DOMContentLoaded", function () {
    /* Elements Variables */
    const closeButton = document.querySelector(".close-menu-button");
    const menuButton = document.querySelector("#hamburger-button");
    const mobileMenu = document.querySelector(".mobile-nav");
    const mobileBackground = document.querySelector(".mobile-background");

    /* Prevents Spamming */
    let debounce = false;

    /* Click Event for Menu Button */
    menuButton.addEventListener("click", () => {
        if (!debounce) {
            debounce = true;
            toggleMenu(true); // Show menu
        }
        debounce = false;
    });

    /* Click Event for Close Menu Button */
    closeButton.addEventListener("click", () => {
        if (!debounce) {
            debounce = true;
            toggleMenu(false); // Hide menu
        }
        debounce = false;
    });

    function toggleMenu(show) {
        if (show) {
            // Show menu
            addClassWithDelay(mobileMenu, "show-menu");
            mobileBackground.style.transition = "0.5s";
            mobileBackground.style.display = "block";
            mobileBackground.style.opacity = 0;
            setTimeout(() => {
                mobileBackground.style.opacity = 1;
            }, 0);
        } else {
            // Hide menu
            removeClassWithDelay(mobileMenu, "show-menu");
            mobileBackground.style.opacity = 0;
            setTimeout(() => {
                mobileBackground.style.display = "none";
            }, 200);
        }
    }

    function addClassWithDelay(element, className) {
        setTimeout(() => {
            element.classList.add(className);
        }, 0);
    }

    function removeClassWithDelay(element, className) {
        setTimeout(() => {
            element.classList.remove(className);
        }, 0);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const menuItemsWithDropdown = document.querySelectorAll('.mobile-nav ul li');
    const mobileNav = document.querySelector('.mobile-nav');

    mobileNav.addEventListener('click', (event) => {
        const target = event.target;

        // Check if the clicked element is a menu item with a dropdown
        const menuItemWithDropdown = target.closest('.mobile-nav ul li');

        if (menuItemWithDropdown) {
            // Prevent the default link behavior
            event.preventDefault();

            // Close other dropdowns
            menuItemsWithDropdown.forEach(item => {
                if (item !== menuItemWithDropdown) {
                    item.classList.remove('show-dropdown');
                }
            });

            // Toggle the visibility of the dropdown menu
            menuItemWithDropdown.classList.toggle('show-dropdown');
        }
    });
});
