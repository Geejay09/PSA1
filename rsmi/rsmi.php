<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Handle Excel export
if (isset($_POST['export_excel'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="RSMI_Report_'.date('Y-m-d').'.xls"');
    
    $today = date('Y-m-d');
    $query = "
        SELECT ris_no, rcc, stock_no, item, des, unit, qty
        FROM tbl_ris
        WHERE DATE(created_at) = CURDATE()
        ORDER BY ris_no, stock_no
    ";
    $result = $conn->query($query);
    
    // Group rows by ris_no
    $grouped = [];
    while ($row = $result->fetch_assoc()) {
        $grouped[$row['ris_no']][] = $row;
    }
    
    echo "<table border='1'>";
    echo "<tr><th colspan='7'>REPORT OF SUPPLIES AND MATERIALS ISSUED</th></tr>";
    echo "<tr><th>RIS No</th><th>RCC</th><th>Stock No.</th><th>Item</th><th>Unit</th><th>Quantity</th><th>Unit Cost</th></tr>";
    
    foreach ($grouped as $ris_no => $items) {
        foreach ($items as $row) {
            echo "<tr>";
            echo "<td>".$ris_no."</td>";
            echo "<td>".$row['rcc']."</td>";
            echo "<td>".$row['stock_no']."</td>";
            echo "<td>".$row['item']."(item)".$row['des']."(des)</td>";
            echo "<td>".$row['unit']."</td>";
            echo "<td>".$row['qty']."</td>";
            echo "<td></td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    exit();
}

$today = date('Y-m-d');

// Fetch only today's entries with the required columns (including description)
$query = "
    SELECT ris_no, rcc, stock_no, item, des, unit, qty
    FROM tbl_ris
    WHERE DATE(created_at) = CURDATE()
    ORDER BY ris_no, stock_no
";
$result = $conn->query($query);

// Group rows by ris_no and calculate grand total
$grouped = [];
$grand_total_qty = 0;
while ($row = $result->fetch_assoc()) {
    $grouped[$row['ris_no']][] = $row;
    $grand_total_qty += $row['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSMI Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 30px;
        }
        .fixed-table {
            table-layout: fixed;
            width: 100%;
        }
        .fixed-table td, .fixed-table th {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            word-wrap: break-word;
        }
        .fixed-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px;
            font-weight: bold;
            margin-top: 30px;
            border: 1px solid #dee2e6;
        }
        .grand-total {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f8f9fa;
            padding: 10px 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-weight: bold;
        }
        .form-header {
            margin-bottom: 30px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .form-detail-column {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .form-detail-item {
            white-space: nowrap;
        }
        .serial-no-input {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .serial-no-input input {
            border: none;
            border-bottom: 1px solid #000;
            border-radius: 0;
            padding: 0 5px;
            text-align: center;
            width: 150px;
        }
        /* Fixed column widths */
        .col-rcc { width: 10%; }
        .col-stock { width: 15%; }
        .col-item { width: 35%; text-align: left; }
        .col-unit { width: 10%; }
        .col-qty { width: 10%; }
        .col-cost { width: 10%; }
        .col-amount { width: 10%; }
    </style>
</head>
<body class="p-4">
    <form method="POST">
        <div class="form-header">
            <h5 class="form-title">REPORT OF SUPPLIES AND MATERIALS ISSUED</h5>
            
            <div class="form-details">
                <div class="form-detail-column">
                    <div class="form-detail-item">
                        <strong>Entity Name:</strong> Philippine Statistics Authority
                    </div>
                    <div class="form-detail-item">
                        <strong>Fund Cluster:</strong> Regular Fund
                    </div>
                </div>
                
                <div class="form-detail-column">
                    <div class="form-detail-item serial-no-input">
                        <strong>Serial No:</strong> <input type="text" name="serial_no" required>
                    </div>
                    <div class="form-detail-item">
                        <strong>Date:</strong> <?= date("F j, Y") ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($grouped)): ?>
            <div class="alert alert-info">No RIS entries found for today (<?= $today ?>).</div>
        <?php else: ?>
            <?php foreach ($grouped as $ris_no => $items): ?>
                <div class="section-title">RIS No: <?= $ris_no ?></div>
                <div class="table-container">
                    <table class="fixed-table table-bordered">
                        <thead>
                            <tr>
                                <th class="col-rcc">RCC</th>
                                <th class="col-stock">Stock No.</th>
                                <th class="col-item">Item</th>
                                <th class="col-unit">Unit</th>
                                <th class="col-qty">Quantity</th>
                                <th class="col-cost">Unit Cost</th>
                                <th class="col-amount">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $row): ?>
                        <tr>
                            <td><?= $row['rcc'] ?></td>
                            <td><?= $row['stock_no'] ?></td>
                            <td class="col-item">
                                <?= htmlspecialchars($row['item']) ?>
                                <?php if (!empty($row['des'])): ?>
                                    <?= htmlspecialchars($row['des']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['unit'] ?></td>
                            <td><?= $row['qty'] ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            
            <!-- Grand Total Display -->
            <div class="grand-total">
                Grand Total Quantity: <?= $grand_total_qty ?>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="export_excel" class="btn btn-success">Export to Excel</button>
            </div>
        <?php endif; ?>
    </form>
</body>
</html>

<?php $conn->close(); ?>