<?php
// Get wishlist count if user is logged in
$wishlistCount = 0;
if (isset($_SESSION['user_id'])) {
    $wishlistModel = new \App\Models\Wishlist();
    $wishlistCount = $wishlistModel->countWishlistItems($_SESSION['user_id']);
}
?>

<!-- Add this in your header navigation -->
<a href="/wishlist" class="nav-link">
    <i class="far fa-heart"></i>
    <span>Wishlist</span>
    <span class="wishlist-count <?= $wishlistCount > 0 ? '' : 'hidden' ?>"><?= $wishlistCount ?></span>
</a>
