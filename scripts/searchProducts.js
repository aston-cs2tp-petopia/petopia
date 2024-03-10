document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('contact-name'); // Ensure this ID matches your search input

    searchInput.addEventListener('input', function() { // Using 'input' event for immediate response on any input change
        const searchTerm = this.value.toLowerCase(); // Get current value and convert to lower case for case-insensitive comparison
        const items = document.querySelectorAll('.item-template'); // Select all item containers

        items.forEach(item => {
            const itemName = item.querySelector('h4 a').textContent.toLowerCase(); // Targeting the <a> tag inside <h4>
            if (itemName.includes(searchTerm)) {
                item.style.display = ''; // Item matches, show it
            } else {
                item.style.display = 'none'; // Item doesn't match, hide it
            }
        });
    });
});