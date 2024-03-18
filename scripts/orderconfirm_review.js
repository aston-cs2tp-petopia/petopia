document.addEventListener('DOMContentLoaded', function() {
    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Variables
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    const productStates = {
        reviewable: new Set(),
        deletable: new Set(),
    };

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Initialise productStates
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    document.querySelectorAll('.review-btn, .delete-review-btn').forEach(button => {
        const productId = button.getAttribute('data-product-id');

        if (button.classList.contains('review-btn')) {
            productStates.reviewable.add(productId);
        } else {
            productStates.deletable.add(productId);
        }
        button.addEventListener('click', () => handleReviewClick(productId, button));
    });

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Handle Clicks
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function handleReviewClick(productId, button) {
        if (productStates.reviewable.has(productId)) {
            createReviewModal(productId, button);
        } else if (productStates.deletable.has(productId)) {
            deleteReview(productId, button);
        }
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Alert Message
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function showAlert(message, isSuccess) {
        const alertDiv = document.createElement('div');
        alertDiv.textContent = message;
        alertDiv.style.position = 'fixed';
        alertDiv.style.left = '50%';
        alertDiv.style.bottom = '20px';
        alertDiv.style.transform = 'translateX(-50%)';
        alertDiv.style.backgroundColor = isSuccess ? '#d4edda' : '#f8d7da';
        alertDiv.style.color = isSuccess ? '#155724' : '#721c24';
        alertDiv.style.padding = '10px';
        alertDiv.style.borderRadius = '5px';
        alertDiv.style.zIndex = '1000';
        document.body.appendChild(alertDiv);
    
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Review Modals
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function createReviewModal(productId, button) {
        const modalExists = document.getElementById('submit-review-container');
        if (modalExists) modalExists.remove();

        // Modal HTML structure
        const modalHTML = `
            <div id="submit-review-container" class="submit-review-container">
                <div class="submit-review-content">
                    <span class="submit-review-close">&times;</span>
                    <h2>Submit a Review</h2>
                    <div class="star-rating">
                        ${[1, 2, 3, 4, 5].map(value => `<i class='bx bx-star' data-value="${value}"></i>`).join('')}
                    </div>
                    <textarea id="review-text" placeholder="Write your review here..."></textarea>
                    <button id="submit-review-btn">Submit Review</button>
                </div>
            </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        setupModalInteractions(productId, button);
    }

    //Modal Interactivity
    function setupModalInteractions(productId, button) {
        const modal = document.getElementById('submit-review-container');
        const closeBtn = modal.querySelector('.submit-review-close');
        const stars = modal.querySelectorAll('.star-rating .bx-star');
        const submitBtn = modal.querySelector('#submit-review-btn');

        closeBtn.addEventListener('click', () => modal.remove());
        window.addEventListener('click', event => {
            if (event.target === modal) modal.remove();
        });

        stars.forEach(star => {
            star.addEventListener('click', () => updateStars(star.dataset.value, stars));
        });

        submitBtn.addEventListener('click', () => {
            const rating = Array.from(stars).reduce((acc, star) => star.classList.contains('bxs-star') ? star.dataset.value : acc, 0);
            const reviewText = modal.querySelector('#review-text').value;
            submitReview(productId, rating, reviewText, button);
        });
    }

    //Update stars when clicked
    function updateStars(selectedValue, stars) {
        stars.forEach(star => {
            star.classList.toggle('bxs-star', star.dataset.value <= selectedValue);
            star.classList.toggle('bx-star', star.dataset.value > selectedValue);
        });
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Submit + Delete Reviews
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function submitReview(productId, rating, reviewText, button) {
        fetch('../php/submit_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Customer_ID: document.body.getAttribute('data-customer-id'), // Assuming this is set correctly
                Product_ID: productId,
                rating: rating,
                Rev_Text: reviewText,
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message, data.status === 'success');
            if (data.status === 'success') {
                document.getElementById('submit-review-container').remove();
                productStates.reviewable.delete(productId);
                productStates.deletable.add(productId);
                updateButtonState(productId, button);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while submitting the review.', false);
        });
    }
    
    function deleteReview(productId, button) {
        fetch('../php/delete_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `productId=${productId}&customerId=${document.body.getAttribute('data-customer-id')}`,
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message, data.status === 'success');
            if (data.status === 'success') {
                productStates.deletable.delete(productId);
                productStates.reviewable.add(productId);
                updateButtonState(productId, button);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the review.', false);
        });
    }

    /* ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~*\
        @Update States
    \*~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ */
    function updateButtonState(productId, button) {
        if (productStates.reviewable.has(productId)) {
            button.className = 'review-btn';
            button.textContent = 'Review';
        } else if (productStates.deletable.has(productId)) {
            button.className = 'delete-review-btn';
            button.textContent = 'Delete Review';
        }
    }
});
