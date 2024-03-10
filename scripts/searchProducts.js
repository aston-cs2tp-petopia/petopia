document.addEventListener("DOMContentLoaded", function() {

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Variables
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    const itemsContainer = document.querySelector('.results-container');
    const searchInput = document.getElementById('contact-name');
    const sortBySelect = document.getElementById('sortBy');
    const showSelect = document.getElementById('show');
    let currentPage = 1;
    let itemsArray = [];
    let originalOrder = Array.from(itemsContainer.querySelectorAll('.item-template'));

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Filters + Sorting
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function applyFilterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortValue = sortBySelect.value;

        //Filter
        let filteredItems = originalOrder.filter(item => {
            const itemName = item.querySelector('h4 a').textContent.toLowerCase();
            return itemName.includes(searchTerm);
        });

        //Sort
        if (sortValue === 'lowToHigh' || sortValue === 'highToLow') {
            filteredItems.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.item-info h5').textContent.replace(/[^\d.]/g, ''));
                const priceB = parseFloat(b.querySelector('.item-info h5').textContent.replace(/[^\d.]/g, ''));
                return sortValue === 'lowToHigh' ? priceA - priceB : priceB - priceA;
            });
        }

        itemsArray = filteredItems;
        currentPage = 1;
        updateItemsVisibility();
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Visability
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function updateItemsVisibility() {
        const itemsPerPage = parseInt(showSelect.value, 10);
        const totalPages = Math.ceil(itemsArray.length / itemsPerPage);

        //Clear the container and manage no items found scenario
        itemsContainer.innerHTML = itemsArray.length ? '' : '<div>No products found.</div>';

        //Append items for the current page
        itemsArray.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage).forEach(item => {
            itemsContainer.appendChild(item);
        });

        //Update paging controls
        updatePagingControls(totalPages);
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Paging Controls
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function updatePagingControls(totalPages) {
        const prevPageButton = document.getElementById('prev-page');
        const nextPageButton = document.getElementById('next-page');

        // Directly set display style based on totalPages; no need to show buttons for a single page
        const displayStyle = totalPages > 1 ? '' : 'none';
        prevPageButton.style.display = nextPageButton.style.display = displayStyle;

        // Function to configure button states
        const configureButton = (button, isDisabled) => {
            button.disabled = isDisabled;
            button.style.backgroundColor = isDisabled ? 'rgb(22 55 65 / 50%)' : ''; // Set background color based on disabled state
        };

        // Configure previous and next buttons based on currentPage and totalPages
        configureButton(prevPageButton, currentPage === 1);
        configureButton(nextPageButton, currentPage >= totalPages);
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Event Listeners
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    searchInput.addEventListener('input', applyFilterAndSort);
    sortBySelect.addEventListener('change', applyFilterAndSort);
    showSelect.addEventListener('change', applyFilterAndSort); // Update for consistency

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateItemsVisibility();
        }
    });

    document.getElementById('next-page').addEventListener('click', () => {
        const totalPages = Math.ceil(itemsArray.length / parseInt(showSelect.value, 10));
        if (currentPage < totalPages) {
            currentPage++;
            updateItemsVisibility();
        }
    });

    //Initial setup
    applyFilterAndSort(); //Also takes care of initial visibility update
});