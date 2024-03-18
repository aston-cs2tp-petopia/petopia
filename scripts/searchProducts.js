document.addEventListener("DOMContentLoaded", function() {

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Variables
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    const itemsContainer = document.querySelector('.results-container');
    const searchInput = document.getElementById('contact-name');
    const sortByPriceSelect = document.getElementById('sortByPrice');
    const sortByProductSelect = document.getElementById('sortByProduct');
    const showSelect = document.getElementById('show');
    let currentPage = 1;
    let itemsArray = [];
    let originalOrder = Array.from(itemsContainer.querySelectorAll('.item-template'));

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Filters + Sorting
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function applyFilterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortPriceValue = sortByPriceSelect.value;
        const sortProductValue = sortByProductSelect.value;

        //Filter
        let filteredItems = originalOrder.filter(item => {
            const itemName = item.querySelector('h4 a').textContent.toLowerCase();
            return itemName.includes(searchTerm);
        });

        //Sort by price
        if (sortPriceValue === 'lowToHigh' || sortPriceValue === 'highToLow') {
            filteredItems.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.item-info h5').textContent.replace(/[^\d.]/g, ''));
                const priceB = parseFloat(b.querySelector('.item-info h5').textContent.replace(/[^\d.]/g, ''));
                return sortPriceValue === 'lowToHigh' ? priceA - priceB : priceB - priceA;
            });
        }

        //Sort by product
        if (sortProductValue !== 'select') {
            filteredItems = filteredItems.filter(item => {
                const categoryNames = item.querySelector('.item-info h6').textContent.toLowerCase();
                return categoryNames.includes(sortProductValue);
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

        //Clear the page n show if no products have been found
        itemsContainer.innerHTML = itemsArray.length ? '' : '<div>No products found.</div>';

        //Add items to current page
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

        //Shows the prev/next buttons and hides them if there is only one page
        const displayStyle = totalPages > 1 ? '' : 'none';
        prevPageButton.style.display = nextPageButton.style.display = displayStyle;

        //Checks the state of next/prev buttons
        const configureButton = (button, isDisabled) => {
            button.disabled = isDisabled;
            button.style.backgroundColor = isDisabled ? 'rgb(22 55 65 / 50%)' : '';
        };

        //Changes configuration based on currentpage
        configureButton(prevPageButton, currentPage === 1);
        configureButton(nextPageButton, currentPage >= totalPages);
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Event Listeners
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    searchInput.addEventListener('input', applyFilterAndSort);
    sortByPriceSelect.addEventListener('change', applyFilterAndSort);
    sortByProductSelect.addEventListener('change', applyFilterAndSort);
    showSelect.addEventListener('change', applyFilterAndSort);

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
    applyFilterAndSort();

    const availableCategories = new Set();
    
    //Grab available categories
    originalOrder.forEach(item => {
        const categoryLine = item.querySelector('.item-info h6').textContent.toLowerCase().trim();
        const categories = categoryLine.match(/\b\w+\b/g); // Match words
        if (categories) {
            categories.forEach(category => availableCategories.add(category));
        }
    });

    //Add select option
    const selectOption = document.createElement('option');
    selectOption.value = 'select';
    selectOption.textContent = 'Select';
    sortByProductSelect.appendChild(selectOption);

    //Add cateogory to options
    availableCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category.charAt(0).toUpperCase() + category.slice(1); // Capitalize first letter
        sortByProductSelect.appendChild(option);
    });

    //Hide sort product if there is only 1 product
    document.querySelector('.hide-sortby-product').style.display = availableCategories.size > 1 ? '' : 'none';
});