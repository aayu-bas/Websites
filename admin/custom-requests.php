<?php

$adminTitle = 'Custom Requests';

require_once __DIR__ . '/../config/config.php';

$action = $_GET['action'] ?? 'list';

// Handle status update

if (isset($_GET['update_status']) && isset($_GET['id'])) {

    $requestId = intval($_GET['id']);
    $newStatus = $_GET['status'] ?? 'pending';

    // Allow only valid statuses
    if (in_array($newStatus, ['pending', 'approved', 'rejected', 'completed'])) {

        $newStatus = mysqli_real_escape_string($conn, $newStatus);

        $updateSql = "UPDATE custom_crochet_requests SET status = '$newStatus' WHERE request_id = $requestId";

        mysqli_query($conn, $updateSql);

        setFlashMessage('success','Status updated to ' . ucfirst($newStatus) . '!'
        );
    }
    redirect(ADMIN_URL . '/custom-requests.php');
}

// Handle set price and remarks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_price'])) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        setFlashMessage('error', 'Invalid request.');

    } else {

        $requestId = intval($_POST['request_id'] ?? 0);
        $finalPrice = floatval($_POST['final_price'] ?? 0);
        $remarks = sanitize($_POST['admin_remarks'] ?? '');
        $status = $_POST['status'] ?? 'pending';

        if ($finalPrice > 0) {

            // Escape text values
            $remarks = mysqli_real_escape_string($conn, $remarks);
            $status = mysqli_real_escape_string($conn, $status);

            $updateSql = "UPDATE custom_crochet_requests SET     final_price = $finalPrice,     admin_remarks = '$remarks',     status = '$status' WHERE request_id = $requestId";

            if (mysqli_query($conn, $updateSql)) {

                setFlashMessage('success','Price set and status updated!');

            } else {

                setFlashMessage('error','Failed to update request.');

                error_log(mysqli_error($conn));
            }

        } else {

            setFlashMessage('error','Please enter a valid price.');
        }
    }

    redirect(ADMIN_URL . '/custom-requests.php');
}
// View single request


if ($action === 'view' && isset($_GET['id'])) {

    $requestId = intval($_GET['id']);

    $requestSql = "SELECT ccr.*, u.first_name, u.last_name, u.email, u.phone
        FROM custom_crochet_requests ccr
        JOIN users u
            ON ccr.user_id = u.user_id
        WHERE ccr.request_id = $requestId
    ";

    $requestResult = mysqli_query($conn, $requestSql);

    $request = null;

    if ($requestResult) {
        $request = mysqli_fetch_assoc($requestResult);
    }

    if (!$request) {

        setFlashMessage('error','Request not found.');

        redirect(ADMIN_URL . '/custom-requests.php');
    }


} else {
    // List all requests
    $statusFilter = $_GET['status'] ?? '';

    $where = "WHERE 1=1";

    if ($statusFilter &&
        in_array(
            $statusFilter,
            ['pending', 'approved', 'rejected', 'completed']
        )
    ) {

        $statusFilter = mysqli_real_escape_string($conn,$statusFilter);

        $where .= " AND ccr.status = '$statusFilter'";
    }

    // Get all requests
    $requestsSql = "SELECT ccr.*, u.first_name, u.last_name
        FROM custom_crochet_requests ccr
        JOIN users u
            ON ccr.user_id = u.user_id
        $where
        ORDER BY ccr.created_at DESC
    ";

    $requestsResult = mysqli_query( $conn, $requestsSql);

    $requests = [];

    if ($requestsResult) {

        while ($row = mysqli_fetch_assoc($requestsResult)) {
            $requests[] = $row;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'view' && $request): ?>
<!-- View Single Request -->
<div class="content-header">
    <h2><i class="fas fa-magic"></i> Custom Request #<?php echo $request['request_id']; ?></h2>
    <a href="custom-requests.php" class="btn btn-small btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="custom-request-detail">
    <div class="request-detail-grid">
        <div>
            <div class="request-detail-item">
                <label>Customer</label>
                <p><?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?></p>
                <p style="font-size: 0.9rem; color: var(--color-gray);"><?php echo htmlspecialchars($request['email']); ?></p>
            </div>

            <div class="request-detail-item">
                <label>Product Type</label>
                <p><?php echo ucfirst($request['product_type']); ?></p>
            </div>

            <div class="request-detail-item">
                <label>Color</label>
                <p><span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background: <?php echo $request['color']; ?>; vertical-align: middle; margin-right: 8px; border: 1px solid #ddd;"></span> <?php echo htmlspecialchars($request['color']); ?></p>
            </div>

            <div class="request-detail-item">
                <label>Size</label>
                <p><?php echo htmlspecialchars($request['size']); ?></p>
            </div>

            <div class="request-detail-item">
                <label>Quantity</label>
                <p><?php echo $request['quantity']; ?></p>
            </div>
        </div>

        <div>
            <div class="request-detail-item">
                <label>Budget</label>
                <p><?php echo formatPrice($request['budget']); ?></p>
            </div>

            <div class="request-detail-item">
                <label>Status</label>
                <p><span class="status-badge <?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span></p>
            </div>

            <div class="request-detail-item">
                <label>Submitted On</label>
                <p><?php echo date('F d, Y 	 h:i A', strtotime($request['created_at'])); ?></p>
            </div>

            <?php if ($request['final_price']): ?>
            <div class="request-detail-item">
                <label>Final Price</label>
                <p style="font-size: 1.3rem; font-weight: 700; color: var(--color-green);"><?php echo formatPrice($request['final_price']); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($request['admin_remarks']): ?>
            <div class="request-detail-item">
                <label>Admin Remarks</label>
                <p><?php echo nl2br(htmlspecialchars($request['admin_remarks'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($request['reference_image']): ?>
    <div class="request-detail-item">
        <label>Reference Image</label>
        <div class="request-reference-image">
            <img src="<?php echo ASSETS_URL; ?>/images/custom/<?php echo $request['reference_image']; ?>" alt="Reference">
        </div>
    </div>
    <?php endif; ?>

    <?php if ($request['notes']): ?>
    <div class="request-detail-item">
        <label>Customer Notes</label>
        <p style="background: var(--color-cream); padding: 15px; border-radius: var(--radius-md);"><?php echo nl2br(htmlspecialchars($request['notes'])); ?></p>
    </div>
    <?php endif; ?>

    <!-- Admin Actions -->
    <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--color-beige);">
        <h3 style="margin-bottom: 20px;">Admin Actions</h3>

        <div style="display: flex; gap: 10px; margin-bottom: 25px;">
            <a href="custom-requests.php?update_status=1&id=<?php echo $request['request_id']; ?>&status=approved" 
               class="btn btn-primary" onclick="return confirm('Approve this request?')">
                <i class="fas fa-check"></i> Approve
            </a>
            <a href="custom-requests.php?update_status=1&id=<?php echo $request['request_id']; ?>&status=rejected" 
               class="btn btn-outline" style="border-color: #e74c3c; color: #e74c3c;" onclick="return confirm('Reject this request?')">
                <i class="fas fa-times"></i> Reject
            </a>
            <a href="custom-requests.php?update_status=1&id=<?php echo $request['request_id']; ?>&status=completed" 
               class="btn btn-secondary" onclick="return confirm('Mark as completed?')">
                <i class="fas fa-check-double"></i> Complete
            </a>
        </div>

        <form method="POST" action="" class="admin-form" style="max-width: 500px;">
            <?php echo csrfField(); ?>
            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
            <input type="hidden" name="set_price" value="1">

            <div class="form-row">
                <div class="form-group">
                    <label>Set Final Price (₹)</label>
                    <input type="number" name="final_price" step="0.01" min="0" 
                           value="<?php echo $request['final_price'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Update Status</label>
                    <select name="status">
                        <option value="pending" <?php echo $request['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo $request['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $request['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="completed" <?php echo $request['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <textarea name="admin_remarks" rows="3" placeholder="Add your remarks or notes for the customer..."><?php echo htmlspecialchars($request['admin_remarks'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<?php else: ?>
<!-- List Requests -->
<div class="content-header">
    <h2><i class="fas fa-magic"></i> Custom Crochet Requests</h2>
    <div style="display: flex; gap: 10px;">
        <a href="custom-requests.php" class="btn btn-small <?php echo !$statusFilter ? 'btn-primary' : 'btn-outline'; ?>">All</a>
        <a href="custom-requests.php?status=pending" class="btn btn-small <?php echo $statusFilter === 'pending' ? 'btn-primary' : 'btn-outline'; ?>">Pending</a>
        <a href="custom-requests.php?status=approved" class="btn btn-small <?php echo $statusFilter === 'approved' ? 'btn-primary' : 'btn-outline'; ?>">Approved</a>
        <a href="custom-requests.php?status=completed" class="btn btn-small <?php echo $statusFilter === 'completed' ? 'btn-primary' : 'btn-outline'; ?>">Completed</a>
    </div>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Color</th>
                <th>Size</th>
                <th>Budget</th>
                <th>Final Price</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
            <tr>
                <td>#<?php echo $req['request_id']; ?></td>
                <td><?php echo htmlspecialchars($req['first_name'] . ' ' . $req['last_name']); ?></td>
                <td><?php echo ucfirst($req['product_type']); ?></td>
                <td>
                    <span style="display: inline-block; width: 15px; height: 15px; border-radius: 50%; background: <?php echo $req['color']; ?>; vertical-align: middle; margin-right: 5px; border: 1px solid #ddd;"></span>
                    <?php echo htmlspecialchars($req['color']); ?>
                </td>
                <td><?php echo htmlspecialchars($req['size']); ?></td>
                <td><?php echo formatPrice($req['budget']); ?></td>
                <td><?php echo $req['final_price'] ? formatPrice($req['final_price']) : '-'; ?></td>
                <td><span class="status-badge <?php echo $req['status']; ?>"><?php echo ucfirst($req['status']); ?></span></td>
                <td><?php echo date('M d, Y', strtotime($req['created_at'])); ?></td>
                <td>
                    <div class="table-actions">
                        <a href="custom-requests.php?action=view&id=<?php echo $req['request_id']; ?>" class="view-btn" title="View & Manage">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
