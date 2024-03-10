document.addEventListener("DOMContentLoaded", function() {
    let itemsArray = []; //This will hold the currently displayable items based on search and sort
    let originalOrder = []; //Stores the original HTML for reset purposes
    const itemsContainer = document.querySelector('.results-container');
    const searchInput = document.getElementById('contact-name');
    const sortBySelect = document.getElementById('sortBy');
    const showSelect = document.getElementById('show');
    let currentPage = 1;
    
    //Helper function to refresh the items array based on current DOM
    function refreshItemsArray() {
        originalOrder = Array.from(itemsContainer.querySelectorAll('.item-template'));
        itemsArray = [...originalOrder]; //Clone the original order
    }

    //Initial refresh
    refreshItemsArray();

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

        itemsArray = filteredItems; //Update the global itemsArray to reflect current search and sort
        currentPage = 1; //Reset to the first page
        updateItemsVisibility();
    }

    function updateItemsVisibility() {
        const itemsPerPage = parseInt(showSelect.value, 10);
        const totalPages = Math.ceil(itemsArray.length / itemsPerPage);
        //Clear the container
        itemsContainer.innerHTML = '';

        //Append only the items that should be visible on the current page
        itemsArray.forEach((item, index) => {
            const position = index + 1;
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = currentPage * itemsPerPage;
            if (position >= start && position <= end) {
                itemsContainer.appendChild(item);
            }
        });

        updateButtonStates(totalPages);
    }

    //Search and sort integration
    searchInput.addEventListener('input', applyFilterAndSort);
    sortBySelect.addEventListener('change', applyFilterAndSort);

    //Show selection change
    showSelect.addEventListener('change', updateItemsVisibility);

    //Pagination controls
    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateItemsVisibility();
        }
    });

    document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(itemsArray.length / parseInt(showSelect.value, 10));
        if (currentPage < totalPages) {
            currentPage++;
            updateItemsVisibility();
        }
    });

    //Update button states based on the current page and total pages
    function updateButtonStates(totalPages) {
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
    }

    //Initial call to set everything up
    updateItemsVisibility();
});