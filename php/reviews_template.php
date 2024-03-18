<?php
$query = "SELECT r.Review_ID, r.Review_Date, r.rating, r.Rev_Text, c.Username 
          FROM reviews r
          JOIN customer c ON r.Customer_ID = c.Customer_ID
          WHERE r.Product_ID = :productID
          ORDER BY r.Review_Date DESC";
$stmt = $db->prepare($query);
$stmt->execute(['productID' => $tempPID]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="reviews-container">
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="review-header">
                    <p class="review-username"><?= htmlspecialchars($review['Username']); ?></p>
                    <p class="review-date"><?= htmlspecialchars($review['Review_Date']); ?></p>
                </div>
                <div class="review-rating">
                    <?php 
                    $fullStars = floor($review['rating']);
                    $halfStar = $review['rating'] - $fullStars >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;

                    for ($i = 0; $i < $fullStars; $i++): ?>
                        <i class='bx bxs-star'></i>
                    <?php endfor; ?>

                    <?php if ($halfStar): ?>
                        <i class='bx bxs-star-half'></i>
                    <?php endif; ?>

                    <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                        <i class='bx bx-star'></i>
                    <?php endfor; ?>
                </div>

                <div class="review-text">
                    <?= htmlspecialchars($review['Rev_Text']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>