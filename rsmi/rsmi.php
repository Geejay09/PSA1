<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

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
    
    // Group rows by ris_no and calculate stock summary
    $grouped = [];
    $stock_summary = [];
    $grand_total_qty = 0;
    while ($row = $result->fetch_assoc()) {
        $grouped[$row['ris_no']][] = $row;
        
        // Add to stock summary
        if (!isset($stock_summary[$row['stock_no']])) {
            $stock_summary[$row['stock_no']] = 0;
        }
        $stock_summary[$row['stock_no']] += $row['qty'];
        $grand_total_qty += $row['qty'];
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
    
    // Add summary table to Excel export
    echo "<br><br><table border='1'>";
    echo "<tr><th colspan='2'>Recapitulation</th></tr>";
    echo "<tr><th>Stock No.</th><th>Quantity</th></tr>";
    
    foreach ($stock_summary as $stock_no => $total_qty) {
        echo "<tr>";
        echo "<td>".$stock_no."</td>";
        echo "<td>".$total_qty."</td>";
        echo "</tr>";
    }
    
    // Add total row to Excel export
    echo "<tr style='font-weight:bold;'>";
    echo "<td>Total</td>";
    echo "<td>".$grand_total_qty."</td>";
    echo "</tr>";
    
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

// Group rows by ris_no and calculate grand total AND stock summary
$grouped = [];
$grand_total_qty = 0;
$stock_summary = []; // New array for stock number summary

while ($row = $result->fetch_assoc()) {
    $grouped[$row['ris_no']][] = $row;
    $grand_total_qty += $row['qty'];
    
    // Add to stock summary
    if (!isset($stock_summary[$row['stock_no']])) {
        $stock_summary[$row['stock_no']] = 0;
    }
    $stock_summary[$row['stock_no']] += $row['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSMI Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
    <!-- Header -->
    <div class="psa-header">
        <img src="../assets/psa.png" alt="PSA Logo" class="psa-logo">
        <div>
            <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
            <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
        </div>
    </div>

    <div class="content-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <button class="btn btn-block sidebar-btn" onclick="location.href='../home.php'">
                <i class="bi bi-speedometer2 icon-spacing"></i> Dashboard
            </button>

            <h5>Data Entry</h5>
            <button class="btn btn-block sidebar-btn" onclick="location.href='../ris/ris.php'">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Requisition and Issuance Slip
            </button>
            <button class="btn btn-block sidebar-btn" onclick="location.href='../iar/iar.php'">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Inspection and Acceptance Report
            </button>

            <h5>Generate Report</h5>
            <button class="btn btn-block sidebar-btn" onclick="location.href='../stck_crd.php'">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Stock Card
            </button>
            <button class="btn btn-block sidebar-btn">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Stock Ledger Card
            </button>
            <button class="btn btn-block sidebar-btn active" onclick="location.href='../rsmi/rsmi.php'">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Report of Supplies and Materials Issued
            </button>
            <button class="btn btn-block sidebar-btn">
                <i class="bi bi-file-earmark-text icon-spacing"></i> Report on the Physical Count of Inventories
            </button>

            <h5>Utilities</h5>
            <button class="btn btn-block sidebar-btn">
                <i class="bi bi-people icon-spacing"></i> Manage Employee List
            </button>

            <!-- Logout -->
            <form id="logoutForm" class="d-flex justify-content-center mt-5" method="post">
                <input type="hidden" name="logout" value="1">
                <button type="button" class="btn logout-btn rounded-pill px-4 sidebar-btn">
                    <i class="bi bi-box-arrow-right icon-spacing"></i> LOGOUT
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <form method="POST">
                <h4 class="form-title">REPORT OF SUPPLIES AND MATERIALS ISSUED</h4>
                
                <div class="form-details">
                    <div>
                        <div class="mb-2"><strong>Entity Name:</strong> Philippine Statistics Authority</div>
                        <div class="mb-2"><strong>Fund Cluster:</strong> Regular Fund</div>
                    </div>
                    
                    <div>
                        <div class="mb-2 serial-no-input">
                            <strong>Serial No:</strong> <input type="text" name="serial_no" required>
                        </div>
                        <div class="mb-2"><strong>Date:</strong> <?= date("F j, Y") ?></div>
                    </div>
                </div>

                <?php if (empty($grouped)): ?>
                    <div class="alert alert-info">No RIS entries found for today (<?= $today ?>).</div>
                <?php else: ?>
                    <?php foreach ($grouped as $ris_no => $items): ?>
                        <div class="section-title">RIS No: <?= $ris_no ?></div>
                        <div class="table-container">
                            <table class="table table-bordered fixed-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Responsibility Center Code</th>
                                        <th>Stock No.</th>
                                        <th>Item</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($items as $row): ?>
                                <tr>
                                    <td><?= $row['rcc'] ?></td>
                                    <td><?= $row['stock_no'] ?></td>
                                    <td><?= htmlspecialchars($row['item']) ?> <?= !empty($row['des']) ? htmlspecialchars($row['des']).'' : '' ?></td>
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
                    
                    <div class="grand-total">
                        Grand Total Quantity: <?= $grand_total_qty ?>
                    </div>

                    <!-- Summary Table -->
                    <div class="summary-table">
                        <h5>Recapitulation</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Stock No.</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stock_summary as $stock_no => $total_qty): ?>
                                <tr>
                                    <td><?= $stock_no ?></td>
                                    <td><?= $total_qty ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <!-- Add total row -->
                                <tr style="font-weight:bold; background-color:#f1f5f9;">
                                    <td>Total</td>
                                    <td><?= $grand_total_qty ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="export_excel" class="btn btn-success">
                            <i class="bi bi-file-excel"></i> Export to Excel
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        // EXACT SCRIPT FROM REFERENCE
        document.querySelector('.logout-btn').addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure you want to logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });

        const sidebarButtons = document.querySelectorAll('.sidebar-btn');

        sidebarButtons.forEach(button => {
            button.addEventListener('click', function() {
                sidebarButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <?php
    if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Logged out!',
                text: 'You have been logged out successfully.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }
    ?>
</body>

<style>
        /* EXACT SIDEBAR STYLES FROM REFERENCE */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(11, 26, 48);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .content-wrapper {
        display: flex;
        min-height: 100vh;
        }

        .psa-header {
            background-color: rgb(4, 33, 65);
            color: white;
            font-family: 'Times New Roman', Times, serif;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            border-bottom: 1px solid white;
            padding: 10px 20px;
            height: 100px;
        }

        .psa-small, .psa-main {
            font-family: 'Times New Roman', Times, serif;
        }

        .psa-logo {
            height: 60px;
            width: auto;
            margin-right: 20px;
        }

        .psa-small {
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            color: #dbeafe;
        }

        .psa-main {
            font-size: 2.1rem;
            font-weight: 700;
            color: #fff;
        }

        .sidebar {
        width: 280px;
        background: linear-gradient(to bottom, #1f2a40, #141c2b);
        color: #ffffff;
        padding: 20px;
        height: calc(100vh - 100px); /* Subtract header height */
        position: fixed;
        top: 100px; /* Match header height */
        left: 0;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        border: 1px solid rgb(255, 255, 255);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        overflow-y: auto;
        }

        .sidebar h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #a5c9ff;
            border-bottom: 1px solid #32425c;
            padding-bottom: 5px;
            margin-bottom: 15px;
            margin-bottom: 15px;
        }

        .sidebar .btn {
            background-color: #2b3a55;
            color: #dbeafe;
            border: none;
            text-align: left;
            padding: 10px 15px;
            font-weight: 500;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(180, 180, 180, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            
        }

        .sidebar .btn:hover {
            background-color: #3e4d6c;
            color: #ffffff;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(207, 204, 204, 0.1);
        }

        .logout-btn {
            background-color: transparent;
            border: 1px solid #ff4d4f;
            color: #ff4d4f;
            transition: all 0.3s ease-in-out;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #ff4d4f;
            color: white;
        }

        .icon-spacing {
            margin-right: 10px;
            font-size: 1.2rem;
            vertical-align: middle;
        }

        .sidebar-btn.active {
            border: 2px solid #ffffff;
            box-shadow: 0 0 10px #ffffff, 0 0 20px #ffffff, 0 0 30px #ffffff;
            background-color: #3e4d6c;
            color: #ffffff;
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 5px #ffffff, 0 0 10px #ffffff, 0 0 15px #ffffff;
            }
            to {
                box-shadow: 0 0 20px #ffffff, 0 0 30px #ffffff, 0 0 40px #ffffff;
            }
        }

        /* Main Content Styles */
        .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 30px;
        background-color: #f8f9fa;
        min-height: calc(100vh - 100px); /* Subtract header height */
        margin-top: 100px; /* Match header height */
        overflow-y: auto;
        }

        .form-title {
            color: #1a237e;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.8rem;
        }

        .form-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .serial-no-input input {
            border: none;
            border-bottom: 1px solid #333;
            padding: 0 5px;
            width: 200px;
            text-align: center;
        }

        .table-container {
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 15px;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        /* Fixed table layout with consistent column widths */
        .fixed-table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }
        
        .fixed-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .fixed-table thead th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Fixed column widths */
        .fixed-table th:nth-child(1),
        .fixed-table td:nth-child(1) {
            width: 10%; /* RCC */
        }
        
        .fixed-table th:nth-child(2),
        .fixed-table td:nth-child(2) {
            width: 15%; /* Stock No */
        }
        
        .fixed-table th:nth-child(3),
        .fixed-table td:nth-child(3) {
            width: 35%; /* Item */
            word-wrap: break-word;
        }
        
        .fixed-table th:nth-child(4),
        .fixed-table td:nth-child(4) {
            width: 10%; /* Unit */
        }
        
        .fixed-table th:nth-child(5),
        .fixed-table td:nth-child(5) {
            width: 10%; /* Quantity */
        }
        
        .fixed-table th:nth-child(6),
        .fixed-table td:nth-child(6) {
            width: 10%; /* Unit Cost */
        }
        
        .fixed-table th:nth-child(7),
        .fixed-table td:nth-child(7) {
            width: 10%; /* Amount */
        }

        .section-title {
            background-color: #f1f5f9;
            padding: 8px 15px;
            border-radius: 4px;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }

        .grand-total {
            background-color: #e3f2fd;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
        }

        /* Summary Table Styles */
        .summary-table {
            margin-top: 30px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .summary-table h5 {
            color: #1a237e;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        .summary-table table {
            width: 50%;
            margin: 0 auto;
            table-layout: fixed;
        }

        .summary-table th {
            background-color: #f1f5f9 !important;
        }

        .summary-table th:nth-child(1) {
            width: 60%;
        }

        .summary-table th:nth-child(2) {
            width: 40%;
        }

        .summary-table tr:last-child {
            background-color: #f1f5f9;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
            }
            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                border-radius: 0;
            }
            .main-content {
                margin-left: 0;
                margin-top: 0;
            }
            
            .summary-table table {
                width: 100%;
            }
        }
    </style>
</html>

<?php $conn->close(); ?>