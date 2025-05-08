<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../index.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report of Supplies and Materials Issued</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>          

    
</head>
<body>

<div class="container-fluid p-0">
    <!-- Header -->
    <header class="psa-header d-flex align-items-center px-4 py-3">
        <img src="../assets/psa.png" alt="PSA Logo" class="psa-logo me-3" style="height: 50px;">
        <div>
            <div class="text-uppercase small" style="color: rgba(255,255,255,0.6); letter-spacing: 1px; font-size: 0.7rem;">REPUBLIC OF THE PHILIPPINES</div>
            <div class="psa-main" style="font-size: 1.3rem;">PHILIPPINE STATISTICS AUTHORITY</div>
            <div class="psa-sub" style="font-size: 0.85rem;">Quirino Provincial Office</div>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="header-time">
                <i class="bi bi-calendar3 me-2"></i><?php echo date('F j, Y'); ?>
            </span>
            <span class="header-time">
                <i class="bi bi-clock me-2"></i><span id="currentTime"></span>
            </span>
        </div>
    </header>

    <!-- Body -->
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-3">
            <!-- User Profile -->
            <div class="d-flex align-items-center mb-4 p-3 rounded user-profile">
                <div class="me-3">
                    <i class="bi bi-person-circle fs-3" style="color: var(--accent-color);"></i>
                </div>
                <div>
                    <div class="fw-medium" style="color: white; font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>
                    </div>
                    <small style="color: rgba(255,255,255,0.6); font-family: 'Roboto', sans-serif;">
                        <?php echo htmlspecialchars($_SESSION['position'] ?? 'Position'); ?>
                    </small>
                </div>
            </div>

            <!-- Dashboard Button -->
            <button class="btn sidebar-btn mb-2" onclick="location.href='../home.php'">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </button>

            <!-- Data Entry Section -->
            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Data Entry</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../ris/ris.php'">
                    <i class="bi bi-file-earmark-text me-2"></i> Requisition Issuance Slip
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../iar/iar.php'">
                    <i class="bi bi-clipboard-check me-2"></i> Issuance and Acceptance Report
                </button>
            </div>

            <!-- Reports Section -->
            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Reports</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../stck_crd.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Card
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../slc/slc.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Ledger Card
                </button>
                <button class="btn sidebar-btn mb-1 active" onclick="location.href='../rsmi/rsmi.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Report on Supplies and Materials Issued
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rpci/rpci.php'">
                    <i class="bi bi-clipboard-data me-2"></i> Report on Physical Count of Inventoriess
                </button>
            </div>

            <!-- Utilities Section -->
            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Utilities</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../employees/employees.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Employee List
                </button>
            </div>

            <!-- Spacer -->
            <div class="mt-auto"></div>

            <!-- Logout -->
            <button id="logoutBtn" class="btn btn-outline-accent mt-3">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="content-card">
                <h2 class="text-center mb-4 page-title">REPORT OF SUPPLIES AND MATERIALS ISSUED</h2>
                
                <!-- Search Form -->
                <form method="POST">
                    <div class="form-details">
                        <div>
                            <div class="mb-2"><strong>Entity Name:</strong> Philippine Statistics Authority</div>
                            <div class="mb-2"><strong>Fund Cluster:</strong> Regular Fund</div>
                        </div>
                        
                        <div>
                            <div class="mb-2">
                                <strong>Serial No:</strong> 
                                <input type="text" name="serial_no" class="form-control" style="display: inline-block; width: auto;">
                            </div>
                            <div class="mb-2"><strong>Date:</strong> <?= date("F j, Y") ?></div>
                        </div>
                    </div>

                    <?php if (empty($grouped)): ?>
                        <div class="alert alert-info">No RIS entries found for today (<?= $today ?>).</div>
                    <?php else: ?>
                        <?php foreach ($grouped as $ris_no => $items): ?>
                            <div class="section-title">RIS No: <?= $ris_no ?></div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
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
                            <table class="table table-bordered" style="width: 50%; margin: 0 auto;">
                                <thead>
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

                        <!-- Export Button -->
                    <div class="text-center mt-4">
                        <button class="btn btn-accent" onclick="exportRISReport()">
                            <i class="bi bi-file-earmark-excel me-2"></i> Export to Excel
                        </button>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Floating Help Button -->
<button id="helpBtn" class="btn help-btn rounded-circle position-fixed" style="bottom: 20px; right: 20px;">
    <i class="bi bi-question-lg"></i>
</button>

<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute:'2-digit' });
    }
    
    setInterval(updateTime, 1000);
    updateTime();

    document.getElementById('logoutBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            confirmButtonColor: 'var(--accent-color)',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = '../index.php';
            }
        });
    });

    document.getElementById('helpBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <p>View the full code guide <a href="../codes.php" target="_blank" style="color: var(--accent-color); text-decoration: underline;">here</a>.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: 'var(--accent-color)'
        });
    });

    // Add active class to clicked sidebar button
    document.querySelectorAll('.sidebar-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    //excel export

    async function exportRISReport() {
    try {
        // Create workbook and worksheet
        const workbook = new ExcelJS.Workbook();
        const sheet = workbook.addWorksheet("RSMI");

        // Set up layout
        sheet.columns = [
            { width: 12 }, { width: 25 }, { width: 15 }, { width: 35 },
            { width: 10 }, { width: 10 }, { width: 15 }, { width: 15 }
        ];

        // Add title and headers
        sheet.mergeCells("A1:H1");
        sheet.getCell("A1").value = "REPORT OF SUPPLIES AND MATERIALS ISSUED";
        sheet.getCell("A1").alignment = { horizontal: "center" };
        sheet.getCell("A1").font = { bold: true, size: 13 };

        sheet.getCell("A2").value = "Entity Name: Philippine Statistics Authority";
        sheet.getCell("F2").value = "Serial No.:";
        sheet.getCell("A3").value = "Fund Cluster: Regular Fund";
        sheet.getCell("F3").value = "Date: " + new Date().toLocaleDateString();

        sheet.addRow([]);
        sheet.addRow([]);

        // Table headers
        const headers = ["RIS No.", "Responsibility Center Code", "Stock No.", "Item", "Unit", "Quantity", "Unit Cost", "Amount"];
        const headerRow = sheet.addRow(headers);
        headerRow.eachCell(cell => {
            cell.font = { bold: true };
            cell.alignment = { horizontal: "center" };
            cell.border = {
                top: { style: 'thin' }, bottom: { style: 'thin' },
                left: { style: 'thin' }, right: { style: 'thin' }
            };
        });

        // Get all data tables from the page
        const tables = document.querySelectorAll('.table-responsive table');
        const stockSummary = {};
        let totalQty = 0;

        // Process each table
        tables.forEach(table => {
            const risNo = table.closest('.table-responsive').previousElementSibling.textContent.replace('RIS No:', '').trim();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const stockNo = cells[1].textContent;
                const qty = parseInt(cells[4].textContent);
                
                // Add to Excel sheet
                sheet.addRow([
                    risNo,
                    cells[0].textContent, // RCC
                    stockNo,
                    cells[2].textContent, // Item + Description
                    cells[3].textContent, // Unit
                    qty,
                    "", // Unit Cost
                    ""  // Amount
                ]);

                // Update stock summary
                stockSummary[stockNo] = (stockSummary[stockNo] || 0) + qty;
                totalQty += qty;
            });
        });

        // Add recap section
        sheet.addRow([]);
        sheet.addRow([]);
        sheet.getCell(`A${sheet.lastRow.number + 1}`).value = "Recapitulation:";
        sheet.getCell(`A${sheet.lastRow.number}`).font = { bold: true };

        const recapHeader = sheet.addRow(["Stock No.", "Quantity"]);
        recapHeader.font = { bold: true };

        // Add recap data
        for (const [stockNo, qty] of Object.entries(stockSummary)) {
            sheet.addRow([stockNo, qty]);
        }

        // Add total row
        const totalRow = sheet.addRow(["Total", totalQty]);
        totalRow.font = { bold: true };

        // Add footer and signatures
        sheet.addRow([]);
        sheet.addRow([]);

        const footerStart = sheet.lastRow.number + 1;
        sheet.mergeCells(`A${footerStart}:H${footerStart}`);
        sheet.getCell(`A${footerStart}`).value = "I hereby certify to the correctness of the above information.";

        const signatureRow1 = sheet.lastRow.number + 2;
        sheet.getCell(`A${signatureRow1}`).value = "ALEXANDER G. AUSTRIA";
        sheet.getCell(`A${signatureRow1 + 1}`).value = "Signature over Printed Name of Supply and/or Property Custodian";

        sheet.getCell(`F${signatureRow1}`).value = "ARCHIE C. FERRER";
        sheet.getCell(`F${signatureRow1 + 1}`).value = "Signature over Printed Name of Designated Accounting Staff";
        sheet.getCell(`F${signatureRow1 + 2}`).value = "Date: " + new Date().toLocaleDateString();

        // Generate and download the file
        const buffer = await workbook.xlsx.writeBuffer();
        saveAs(new Blob([buffer]), `RSMI_Report_${new Date().toISOString().split('T')[0]}.xlsx`);
        
    } catch (error) {
        console.error("Export error:", error);
        Swal.fire({
            title: 'Export Failed',
            text: 'An error occurred while exporting to Excel: ' + error.message,
            icon: 'error'
        });
    }
}
</script>
</body>
<style>
        :root {
            --primary-dark: #0a192f;
            --primary-blue: #172a45;
            --accent-color: #64ffda;
            --light-bg: #f8f9fa;
            --text-light: #ccd6f6;
            --text-dark: #1a1a1a;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .psa-header {
            background: var(--primary-dark);
            height: 80px;
            position: relative;
            z-index: 100;
            box-shadow: 0 2px 15px rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(100, 255, 218, 0.2);
        }
        
        .psa-main {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white;
        }
        
        .psa-sub {
            font-family: 'Roboto', sans-serif;
            color: rgba(255,255,255,0.7);
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: var(--primary-blue);
            transition: all 0.3s ease;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            border-right: 1px solid rgba(100, 255, 218, 0.1);
            width: 250px;
        }
        
        .sidebar-title {
            background-color: rgba(100, 255, 218, 0.1);
            border-radius: 4px;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1px;
            color: var(--accent-color);
        }
        
        .sidebar-btn {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-radius: 4px;
            padding: 10px 15px;
            text-decoration: none;
            width: 100%;
            text-align: left;
            position: relative;
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            color: var(--text-light);
            margin-bottom: 5px;
            border: 1px solid transparent;
            background: none;
        }
        
        .sidebar-btn:hover {
            background-color: rgba(100, 255, 218, 0.1);
            transform: translateX(5px);
            color: var(--accent-color);
            border-color: rgba(100, 255, 218, 0.3);
        }
        
        .sidebar-btn:hover i {
            color: var(--accent-color);
        }
        
        .sidebar-btn.active {
            background-color: rgba(100, 255, 218, 0.1);
            font-weight: 500;
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
            box-shadow: 0 0 10px rgba(100, 255, 218, 0.2);
        }
        
        .sidebar-btn.active i {
            color: var(--accent-color);
        }
        
        .sidebar-btn i {
            transition: all 0.3s ease;
            color: rgba(255,255,255,0.7);
        }

        /* User Profile */
        .user-profile {
            background: rgba(100, 255, 218, 0.05);
            border: 1px solid rgba(100, 255, 218, 0.1);
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            background: rgba(100, 255, 218, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Main Content */
        .main-content {
            background: white;
            transition: all 0.3s ease;
            min-height: calc(100vh - 80px);
        }
        
        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none;
            padding: 2rem;
        }
        
        /* Form Elements */
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(100, 255, 218, 0.25);
        }
        
        /* Buttons */
        .btn-accent {
            background-color: var(--accent-color);
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            border: none;
        }
        
        .btn-accent:hover {
            background-color: rgba(100, 255, 218, 0.8);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(100, 255, 218, 0.3);
        }
        
        .btn-outline-accent {
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
        }
        
        .btn-outline-accent:hover {
            background-color: rgba(100, 255, 218, 0.1);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(100, 255, 218, 0.2);
        }
        
        /* Table Styles */
        .table th {
            background-color: var(--primary-dark);
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(100, 255, 218, 0.05);
        }
        
        /* Help Button */
        .help-btn {
            background: var(--primary-dark);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .help-btn:hover {
            background: var(--accent-color);
            color: var(--primary-dark);
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(100, 255, 218, 0.5);
        }
        
        /* Page Title */
        .page-title {
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        /* Section Styles */
        .section-title {
            background-color: rgba(100, 255, 218, 0.1);
            padding: 8px 15px;
            border-radius: 4px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
        }
        
        .grand-total {
            background-color: rgba(100, 255, 218, 0.1);
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
            color: var(--primary-dark);
            border: 1px solid var(--accent-color);
        }
        
        /* Form Details */
        .form-details {
            display: flex;
            justify-content: space-between;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        /* Summary Table */
        .summary-table {
            margin-top: 30px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .summary-table h5 {
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 8px;
        }

        .header-time {
            font-family: 'Roboto', sans-serif;
            color: rgba(255,255,255,0.8);
            background: rgba(100, 255, 218, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(100, 255, 218, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
                position: fixed;
                height: 100%;
                z-index: 1000;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .psa-header {
                flex-direction: column;
                align-items: flex-start;
                height: auto;
                padding: 15px;
            }
            
            .form-details {
                flex-direction: column;
            }
            
            .summary-table table {
                width: 100% !important;
            }
        }
    </style>
</html>

<?php $conn->close(); ?>