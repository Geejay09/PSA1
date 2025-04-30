<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle stock number from form or URL
$stock_no = $_GET['stock_no'] ?? '';
$stock_no = trim($stock_no);

// Fetch entries
$data = [];
if (!empty($stock_no)) {
    $stmt = $conn->prepare("SELECT * FROM tbl_sc WHERE stock_no = ? ORDER BY date ASC");
    $stmt->bind_param("s", $stock_no);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Card - <?= htmlspecialchars($stock_no) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body class="bg-dark-blue">

<div class="container-fluid p-0">
    <!-- Header -->
    <header class="psa-header d-flex align-items-center px-4 py-3">
        <img src="assets/psa.png" alt="PSA Logo" class="psa-logo me-3">
        <div>
            <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
            <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
        </div>
    </header>

    <!-- Body -->
    <div class="d-flex" style="min-height: calc(100vh - 80px);">
        <!-- Sidebar - Matches first design -->
        <nav class="sidebar d-flex flex-column" style="width: 280px; background: linear-gradient(to bottom, #1f2a40, #141c2b); color: #ffffff; padding: 20px; border-right: 1px solid rgb(255, 255, 255);">
            <!-- Dashboard Button -->
            <button class="btn btn-block sidebar-btn mb-3" onclick="location.href='home.php'" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </button>

            <!-- Data Entry Section -->
            <h5 style="font-size: 1rem; font-weight: 600; color: #a5c9ff; border-bottom: 1px solid #32425c; padding-bottom: 5px; margin-bottom: 15px;">Data Entry</h5>
            <button class="btn btn-block sidebar-btn" onclick="location.href='ris/ris.php'" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-file-earmark-text me-2"></i> Requisition and Issuance Slip
            </button>
            <button class="btn btn-block sidebar-btn" onclick="location.href='iar/iar.php'" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-clipboard-check me-2"></i> Inspection and Acceptance Report
            </button>

            <!-- Generate Report Section -->
            <h5 style="font-size: 1rem; font-weight: 600; color: #a5c9ff; border-bottom: 1px solid #32425c; padding-bottom: 5px; margin-bottom: 15px;">Generate Report</h5>
            <button class="btn btn-block sidebar-btn active" onclick="location.href='stck_crd.php'" style="background-color: #3e4d6c; color: #ffffff; border: 2px solid #ffffff; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-card-checklist me-2"></i> Stock Card
            </button>
            <button class="btn btn-block sidebar-btn" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-journal-text me-2"></i> Stock Ledger Card
            </button>
            <button class="btn btn-block sidebar-btn" onclick="location.href='rsmi/rsmi.php'" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Requisition and Issuance Slip
            </button>
            <button class="btn btn-block sidebar-btn" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-clipboard-data me-2"></i> Physical Count Report
            </button>

            <!-- Utilities Section -->
            <h5 style="font-size: 1rem; font-weight: 600; color: #a5c9ff; border-bottom: 1px solid #32425c; padding-bottom: 5px; margin-bottom: 15px;">Utilities</h5>
            <button class="btn btn-block sidebar-btn" style="background-color: #2b3a55; color: #dbeafe; border: none; text-align: left; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
                <i class="bi bi-people me-2"></i> Manage Employee List
            </button>

            <!-- Spacer to push logout to bottom -->
            <div class="mt-auto"></div>

            <!-- Logout -->
            <form id="logoutForm" class="d-flex justify-content-center mt-5" method="post">
                <input type="hidden" name="logout" value="1">
                <button type="button" class="btn logout-btn w-100" style="background-color: transparent; border: 1px solid #ff4d4f; color: #ff4d4f; padding: 10px 15px; border-radius: 50px;" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                </button>
            </form>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="content-card p-4">
                <h2 class="text-center mb-4 text-light-blue">STOCK CARD</h2>
                
                <!-- Search Form -->
                <form method="GET" class="search-bar mb-4">
                    <div class="input-group">
                        <input type="text" name="stock_no" class="form-control bg-dark-input" placeholder="Enter stock number..." value="<?= htmlspecialchars($stock_no) ?>" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-2"></i> Search
                        </button>
                    </div>
                </form>

                <?php if (!empty($stock_no)): ?>
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-blue-dark text-white">
                            <h5 class="mb-0">Stock Card - Stock Number: <?= htmlspecialchars($stock_no) ?></h5>
                        </div>
                        <div class="card-body bg-dark-content">
                            <?php if (!empty($data)): ?>
                                <div class="table-responsive">
                                    <table id="stockCardTable" class="table table-dark table-hover align-middle">
                                        <thead class="bg-blue-dark">
                                            <tr>
                                                <th>Stock No.</th>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Date</th>
                                                <th>Reference</th>
                                                <th>Issue Qty</th>
                                                <th>Balance Qty</th>
                                                <th>Office</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['stock_no']) ?></td>
                                                    <td><?= htmlspecialchars($row['item']) ?></td>
                                                    <td><?= htmlspecialchars($row['dscrtn']) ?></td>
                                                    <td><?= htmlspecialchars($row['unit']) ?></td>
                                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                                    <td><?= htmlspecialchars($row['ref']) ?></td>
                                                    <td><?= (int)$row['issue_qty'] ?></td>
                                                    <td><?= (int)$row['balance_qty'] ?></td>
                                                    <td><?= htmlspecialchars($row['office']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="btn btn-success" onclick="exportStockCardToExcel('<?= $stock_no ?>')">
                                        <i class="bi bi-file-earmark-excel me-2"></i> Export to Excel
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">No entries found for stock number <strong><?= htmlspecialchars($stock_no) ?></strong>.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Floating Help Button -->
<button id="helpBtn" class="btn btn-primary rounded-circle floating-help-btn" title="Need help?">
    <i class="bi bi-question-lg"></i>
</button>

<script>
    async function exportStockCardToExcel(stock_no) {
        const response = await fetch('fetch_stock_card.php?stock_no=' + stock_no);
        const stockData = await response.json();

        const rows = stockData.rows;
        const itemInfo = stockData.item_info;

        const wb = XLSX.utils.book_new();
        const ws = {};

        // Helper style objects
        const borderAll = {
            top: { style: "thin" },
            bottom: { style: "thin" },
            left: { style: "thin" },
            right: { style: "thin" }
        };

        const boldCenter = {
            font: { bold: true },
            alignment: { horizontal: "center", vertical: "center" },
            border: borderAll
        };

        const normalCenter = {
            alignment: { horizontal: "center", vertical: "center" },
            border: borderAll
        };

        const normalLeft = {
            alignment: { horizontal: "left", vertical: "center" },
            border: borderAll
        };

        // Title
        ws['A1'] = { v: 'STOCK CARD', t: 's', s: { font: { bold: true, sz: 14 }, alignment: { horizontal: "center" } } };

        // Header Info
        ws['A2'] = { v: 'Entity Name: Philippine Statistics Authority', t: 's', s: normalLeft };
        ws['F2'] = { v: 'Fund Cluster:', t: 's', s: normalLeft };
        ws['A3'] = { v: 'Item: ' + (itemInfo?.item || ''), t: 's', s: normalLeft };
        ws['F3'] = { v: 'Stock No.: ' + stock_no, t: 's', s: normalLeft };
        ws['A4'] = { v: 'Description: ' + (itemInfo?.descode || ''), t: 's', s: normalLeft };
        ws['F4'] = { v: 'Re-order Point:', t: 's', s: normalLeft };
        ws['A5'] = { v: 'Unit of Measurement: ' + (itemInfo?.unit || ''), t: 's', s: normalLeft };

        // Table Headers
        ws['A7'] = { v: 'Date', t: 's', s: boldCenter };
        ws['B7'] = { v: 'Reference', t: 's', s: boldCenter };
        ws['C7'] = { v: 'Receipt', t: 's', s: boldCenter };
        ws['E7'] = { v: 'Issue', t: 's', s: boldCenter };
        ws['G7'] = { v: 'Balance', t: 's', s: boldCenter };
        ws['I7'] = { v: 'No. of Days to Consume', t: 's', s: boldCenter };

        // Sub-headers
        ws['C8'] = { v: 'Qty.', t: 's', s: boldCenter };
        ws['E8'] = { v: 'Qty.', t: 's', s: boldCenter };
        ws['F8'] = { v: 'Office', t: 's', s: boldCenter };
        ws['G8'] = { v: 'Qty.', t: 's', s: boldCenter };

        // Dynamic data rows
        // Insert static row for Balance Forwarded at row 9
        ws['A9'] = { v: '01/01/2025', t: "s", s: normalCenter };
        ws['B9'] = { v: 'Balance Forwarded', t: "s", s: normalLeft };
        ws['G9'] = { v: itemInfo?.initial_qty || 0, t: "n", s: normalCenter };
        ws['I9'] = { v: '', t: "s", s: normalCenter };  // Blank value for No. of Days to Consume

        // Now insert dynamic data starting from row 10
        for (let i = 0; i < rows.length; i++) {
            const entry = rows[i];
            const row = i + 10;

            ws[`A${row}`] = { v: entry.date || '', t: "s", s: normalCenter };
            ws[`B${row}`] = { v: entry.ref || '', t: "s", s: normalCenter };
            ws[`C${row}`] = { v: entry.receipt_qty || '', t: "n", s: normalCenter };
            ws[`E${row}`] = { v: entry.issue_qty || '', t: "n", s: normalCenter };
            ws[`F${row}`] = { v: entry.office || '', t: "s", s: normalCenter };
            ws[`G${row}`] = { v: entry.balance_qty || '', t: "n", s: normalCenter };
            ws[`I${row}`] = { v: '', t: "s", s: normalCenter };  // Blank value for No. of Days to Consume
        }

        const totalRows = rows.length + 9;  // One extra row for the static entry
        ws['!ref'] = `A1:I${totalRows}`;

        // Merge cells
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } }, // A1:I1
            { s: { r: 1, c: 0 }, e: { r: 1, c: 3 } }, // A2:D2
            { s: { r: 1, c: 5 }, e: { r: 1, c: 8 } }, // F2:I2
            { s: { r: 2, c: 0 }, e: { r: 2, c: 3 } }, // A3:D3
            { s: { r: 2, c: 5 }, e: { r: 2, c: 8 } }, // F3:I3
            { s: { r: 3, c: 0 }, e: { r: 3, c: 3 } }, // A4:D4
            { s: { r: 3, c: 5 }, e: { r: 3, c: 8 } }, // F4:I4
            { s: { r: 4, c: 0 }, e: { r: 4, c: 3 } }, // A5:D5

            { s: { r: 6, c: 2 }, e: { r: 6, c: 3 } }, // C7:D7 Receipt
            { s: { r: 6, c: 4 }, e: { r: 6, c: 5 } }, // E7:F7 Issue
            { s: { r: 6, c: 6 }, e: { r: 6, c: 7 } }, // G7:H7 Balance
            { s: { r: 6, c: 8 }, e: { r: 7, c: 8 } }, // I7:I8 No of Days to Consume

            { s: { r: 6, c: 0 }, e: { r: 7, c: 0 } }, // A7:A8 Date
            { s: { r: 6, c: 1 }, e: { r: 7, c: 1 } }, // B7:B8 Reference
        ];

        ws['!cols'] = [
            { wch: 15 },
            { wch: 20 },
            { wch: 10 },
            { wch: 5 },
            { wch: 10 },
            { wch: 20 },
            { wch: 10 },
            { wch: 5 },
            { wch: 20 },
        ];

        XLSX.utils.book_append_sheet(wb, ws, "Stock Card");
        XLSX.writeFile(wb, `Stock_Card_${stock_no}.xlsx`);
    }

    document.getElementById('helpBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <p>View the full code guide <a href="codes.html" target="_blank" style="color: #0d6efd; text-decoration: underline;">here</a>.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: '#0d6efd'
        });
    });

    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });
    }

    // Add active class to clicked sidebar button
    document.querySelectorAll('.sidebar-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>

<style>
    :root {
        --dark-blue: #0a192f;
        --medium-blue: #172a45;
        --blue-dark: #1a365d;
        --light-blue: #64ffda;
        --white: #ffffff;
        --text-light: #ccd6f6;
        --text-lighter: #a8b2d1;
        --dark-input: #1e2b48;
        --dark-content: #1a2a4a;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background-color: var(--dark-blue);
        color: var(--text-light);
    }

    .bg-dark-blue {
        background-color: var(--dark-blue);
    }

    .bg-blue-dark {
        background-color: var(--blue-dark);
    }

    .bg-dark-input {
        background-color: var(--dark-input);
        color: var(--text-light);
        border: 1px solid #2d3a5a;
    }

    .bg-dark-input:focus {
        background-color: var(--dark-input);
        color: var(--text-light);
        border-color: var(--light-blue);
        box-shadow: 0 0 0 0.25rem rgba(100, 255, 218, 0.25);
    }

    .bg-dark-content {
        background-color: var(--dark-content);
    }

    .text-light-blue {
        color: var(--light-blue);
    }

    /* Header Styles */
    .psa-header {
        background: linear-gradient(135deg, #0a192f 0%, #172a45 100%);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .psa-logo {
        height: 60px;
        width: auto;
        filter: brightness(0) invert(1);
    }

    .psa-small {
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        color: var(--text-lighter);
    }

    .psa-main {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--white);
    }

    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        background: linear-gradient(to bottom, #1f2a40, #141c2b);
        color: #ffffff;
        padding: 20px;
        border-right: 1px solid rgb(255, 255, 255);
    }

    .sidebar-btn {
        background-color: #2b3a55;
        color: #dbeafe;
        border: none;
        text-align: left;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .sidebar-btn:hover {
        background-color: #3e4d6c;
        color: #ffffff;
        transform: translateX(5px);
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

    .logout-btn {
        background-color: transparent;
        border: 1px solid #ff4d4f;
        color: #ff4d4f;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #ff4d4f;
        color: white;
    }

    /* Main Content */
    .main-content {
        background-color: var(--dark-blue);
        overflow-y: auto;
    }

    .content-card {
        background-color: rgba(23, 42, 69, 0.8);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Search Bar */
    .search-bar {
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    /* Table Styles */
    .table-dark {
        --bs-table-bg: var(--dark-content);
        --bs-table-striped-bg: #1e365d;
        --bs-table-hover-bg: #1f3a6b;
        --bs-table-border-color: #2d3a5a;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }

    .table td, .table th {
        vertical-align: middle;
        padding: 0.75rem;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(to right, #0a0b3d, #172a45);
        padding: 1rem 1.5rem;
    }

    /* Floating Help Button */
    .floating-help-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--light-blue);
        color: var(--dark-blue);
        font-size: 1.5rem;
        box-shadow: 0 4px 20px rgba(100, 255, 218, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .floating-help-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 24px rgba(100, 255, 218, 0.4);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .sidebar {
            width: 240px;
            padding: 1rem;
        }
        
        .psa-main {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .psa-header {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }
        
        .psa-logo {
            margin-bottom: 0.5rem;
            margin-right: 0;
        }
        
        .content-card {
            padding: 1.5rem;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
</body>
</html>