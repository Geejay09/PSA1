<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "dbpsa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stock_no = '';
$balance_qty = '';
$updateMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_no = $_POST['stock_no'];
    $balance_qty = $_POST['balance_qty'];

    // Check if stock_no exists
    $check = $conn->prepare("SELECT * FROM tbl_sc WHERE stock_no = ?");
    $check->bind_param("s", $stock_no);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update all matching entries
        $update = $conn->prepare("UPDATE tbl_sc SET balance_qty = ? WHERE stock_no = ?");
        $update->bind_param("is", $balance_qty, $stock_no);
        $update->execute();
        $updateMessage = "Balance updated successfully for stock card number: $stock_no";
    } else {
        $updateMessage = "Stock card number not found in tbl_sc.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Stock Card Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Update Beginning Balance for Stock Card</h3>
    
    <?php if ($updateMessage): ?>
        <div class="alert alert-info"><?= $updateMessage ?></div>
    <?php endif; ?>

    <form method="post" class="mb-5">
        <div class="mb-3">
            <label for="stock_no" class="form-label">Stock Card Number</label>
            <input type="text" class="form-control" name="stock_no" id="stock_no" required>
        </div>
        <div class="mb-3">
            <label for="balance_qty" class="form-label">Beginning Balance</label>
            <input type="number" class="form-control" name="balance_qty" id="balance_qty" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Balance</button>
    </form>

    <?php if (!empty($stock_no)): ?>
        <h4>Entries for Stock Card Number: <code><?= htmlspecialchars($stock_no) ?></code></h4>
        <?php
        $stmt = $conn->prepare("SELECT * FROM tbl_sc WHERE stock_no = ?");
        $stmt->bind_param("s", $stock_no);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Stock No</th>
                    <th>Date</th>
                    <th>Ref</th>
                    <th>Receipt Qty</th>
                    <th>Issue Qty</th>
                    <th>Office</th>
                    <th>Balance Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['item']) ?></td>
                        <td><?= htmlspecialchars($row['dscrtn']) ?></td>
                        <td><?= htmlspecialchars($row['unit']) ?></td>
                        <td><?= htmlspecialchars($row['stock_no']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['ref']) ?></td>
                        <td><?= htmlspecialchars($row['receipt_qty']) ?></td>
                        <td><?= htmlspecialchars($row['issue_qty']) ?></td>
                        <td><?= htmlspecialchars($row['office']) ?></td>
                        <td><?= htmlspecialchars($row['balance_qty']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
