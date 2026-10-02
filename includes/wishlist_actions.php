<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'in_wishlist' => false
];

if (!isLoggedIn()) {
    $response['message'] = 'Please login to use the wishlist.';
    echo json_encode($response);
    exit;
}

$userId = (int)getCurrentUserId();
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'toggle':

        $productId = intval($_POST['product_id'] ?? 0);

        if ($productId <= 0) {
            $response['message'] = 'Invalid product.';
            break;
        }

        // Check if already in wishlist
        $sql = "SELECT wishlist_id FROM wishlist WHERE user_id = $userId AND product_id = $productId";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            error_log("Wishlist check error: " . mysqli_error($conn));
            $response['message'] = 'An error occurred. Please try again.';
            break;
        }

        $existing = mysqli_fetch_assoc($result);

        if ($existing) {

            // Remove from wishlist
            $wishlistId = (int)$existing['wishlist_id'];

            $deleteSql = "DELETE FROM wishlist
                          WHERE wishlist_id = $wishlistId
                          AND user_id = $userId";

            if (mysqli_query($conn, $deleteSql)) {
                $response['message'] = 'Removed from wishlist!';
                $response['in_wishlist'] = false;
                $response['success'] = true;
            } else {
                error_log("Wishlist delete error: " . mysqli_error($conn));
                $response['message'] = 'Failed to remove from wishlist.';
            }

        } else {

            // Add to wishlist
            $insertSql = "INSERT INTO wishlist (user_id, product_id)
                          VALUES ($userId, $productId)";

            if (mysqli_query($conn, $insertSql)) {
                $response['message'] = 'Added to wishlist!';
                $response['in_wishlist'] = true;
                $response['success'] = true;
            } else {
                error_log("Wishlist insert error: " . mysqli_error($conn));
                $response['message'] = 'Failed to add to wishlist.';
            }
        }

        break;

    default:
        $response['message'] = 'Invalid action.';
        break;
}


// Update wishlist count
$countSql = "SELECT COUNT(*) AS count FROM wishlist WHERE user_id = $userId";

$countResult = mysqli_query($conn, $countSql);

if ($countResult) {
    $wishlistCount = mysqli_fetch_assoc($countResult);
    $response['wishlist_count'] = (int)($wishlistCount['count'] ?? 0);
} else {
    $response['wishlist_count'] = 0;
}

echo json_encode($response);
?>