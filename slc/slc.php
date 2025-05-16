<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

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
    <title>Stock Ledger Card - <?= htmlspecialchars($stock_no) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
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
                <button class="btn sidebar-btn mb-1" onclick="location.href='slc/slc.php'">
                    <i class="bi bi-card-checklist me-2 active"></i> Stock Ledger Card
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rsmi/rsmi.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Report on Supplies and Materials Issued
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rpci/rpci.php'">
                    <i class="bi bi-clipboard-data me-2"></i> Report on Physical Count of Inventories
                </button>
            </div>

            <!-- Utilities Section -->
            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Utilities</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='employees/employees.php'">
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
                <h2 class="text-center mb-4 page-title">STOCK LEDGER CARD</h2>
                
                <!-- Search Form -->
                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="stock_no" class="form-control" placeholder="Enter stock number..." value="<?= htmlspecialchars($stock_no) ?>" required>
                        <button class="btn btn-accent" type="submit">
                            <i class="bi bi-search me-2"></i> Search
                        </button>
                    </div>
                </form>

                <?php if (!empty($stock_no)): ?>
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Stock Ledger Card - Stock Number: <?= htmlspecialchars($stock_no) ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data)): ?>
                                <div class="table-responsive">
                                    <table id="stockCardTable" class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Stock No.</th>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Date</th>
                                            <th>Reference</th>
                                            <th>Receipt Qty</th>
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
                                            <td><?= (int)$row['receipt_qty'] ?></td>
                                            <td><?= (int)$row['issue_qty'] ?></td>
                                            <td><?= (int)$row['balance_qty'] ?></td>
                                            <td><?= htmlspecialchars($row['office']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="btn btn-accent" onclick="exportStockCardToExcel('<?= $stock_no ?>')">
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

    // start of export
    async function exportStockCardToExcel(stock_no) {
    const response = await fetch('../fetch_stock_card.php?stock_no=' + stock_no);
    const stockData = await response.json();

    const rows = stockData.rows;
    const itemInfo = stockData.item_info;

    const workbook = new ExcelJS.Workbook();
    const sheet = workbook.addWorksheet('Stock Card');

    //border thin
    function borderThin(sheet, range) {
    const [startCell, endCell] = range.split(':');
    const start = sheet.getCell(startCell);
    const end = sheet.getCell(endCell);
    const startRow = start.row;
    const endRow = end.row;
    const startCol = start.col || ExcelJS.utils.decodeCol(start.address);
    const endCol = end.col || ExcelJS.utils.decodeCol(end.address);

    for (let row = startRow; row <= endRow; row++) {
        for (let col = startCol; col <= endCol; col++) {
            const cell = sheet.getCell(row, col);
            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' }
            };
        }
    }
}


    sheet.mergeCells('K1:L1');
    sheet.getCell('K1').value = 'Appendix 57';
    sheet.getCell('K1').font = { italic: true, size: 20, name: 'Times New Roman' };
    sheet.getCell('K1').alignment = { horizontal: 'left' };

    sheet.mergeCells('A3:L3');
    sheet.getCell('A3').value = 'SUPPLIES LEDGER CARD';
    sheet.getCell('A3').font = { bold: true, italic: false, size: 18, name: 'Times New Roman' };
    sheet.getCell('A3').alignment = { horizontal: 'center' };

    sheet.mergeCells('A4:L4');

    //table header
    sheet.mergeCells('A6:E6');
    sheet.getCell('A6').value = 'Entity Name: Philippine Statistics Authority';
    sheet.getCell('A6').font = { bold: true, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('A6').alignment = { horizontal: 'left' };

    sheet.mergeCells('J6:L6');
    sheet.getCell('J6').value = 'Fund Cluster:______________';
    sheet.getCell('J6').font = { bold: true, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('J6').alignment = { horizontal: 'left' };

    //table sub header
    sheet.mergeCells('A8:H8')
    sheet.getCell('A8').value = `Item: ${itemInfo?.item || ''}`;
    sheet.getCell('A8').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('A8').alignment = { horizontal: 'left' };
    
    sheet.mergeCells('I8:L8')
    sheet.getCell('I8').value = `Item Code: ${stock_no}`;
    sheet.getCell('I8').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('I8').alignment = { horizontal: 'left' };

    sheet.mergeCells('A9:H9')
    sheet.getCell('A9').value = `Description: ${itemInfo?.descode || ''}`;
    sheet.getCell('A9').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('A9').alignment = { horizontal: 'left' };

    sheet.mergeCells('I9:L9')
    sheet.getCell('I9').value = 'Re-order Point:';
    sheet.getCell('I9').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('I9').alignment = { horizontal: 'left' };

    sheet.mergeCells('A10:H10')
    sheet.getCell('A10').value = `Unit of Measurement: ${itemInfo?.unit || ''}`;
    sheet.getCell('A10').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('A10').alignment = { horizontal: 'left' };

    sheet.mergeCells('I10:L10')

    //table column header

    sheet.mergeCells('A11:A13')
    sheet.getCell('A11').value = 'Date';
    sheet.getCell('A11').font = { bold: true, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('A11').alignment = { vertical: 'middle', horizontal: 'center' };
    sheet.getColumn('A').width = 14;
    sheet.getRow(11).height = 18;
    sheet.getRow(12).height = 18;
    sheet.getRow(13).height = 18;

    sheet.mergeCells('B11:B13')
    sheet.getCell('B11').value = 'Reference';
    sheet.getCell('B11').font = { bold: true, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('B11').alignment = { vertical: 'middle', horizontal: 'center' };
    sheet.getColumn('B').width = 20;

    sheet.mergeCells('C11:E11')
    sheet.getCell('C11').value = 'Receipt';
    sheet.getCell('C11').font = { bold: true, italic: true, size: 12, name: 'Times New Roman' };
    sheet.getCell('C11').alignment = { vertical: 'middle', horizontal: 'center' };
    sheet.getColumn('C').width = 10;
    sheet.getColumn('D').width = 11
    sheet.getColumn('E').width = 12;
    sheet.getRow(11).height = 18;

    sheet.mergeCells('C12:C13')
    sheet.getCell('C12').value = 'Qty.';
    sheet.getCell('C12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('C12').alignment = { vertical: 'middle', horizontal: 'center' };

    sheet.mergeCells('D12:D13')
    sheet.getCell('D12').value = 'Unit \nCost';
    sheet.getCell('D12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('D12').alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };

    sheet.mergeCells('E12:E13')
    sheet.getCell('E12').value = 'Total Cost';
    sheet.getCell('E12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('E12').alignment = { vertical: 'middle', horizontal: 'center' };

    sheet.mergeCells('F11:H11')
    sheet.getCell('F11').value = 'Issue';
    sheet.getCell('F11').font = { bold: true, italic: true, size: 12, name: 'Times New Roman' };
    sheet.getCell('F11').alignment = { vertical: 'middle', horizontal: 'center' };
    sheet.getColumn('F').width = 10;
    sheet.getColumn('G').width = 11
    sheet.getColumn('H').width = 12;
    sheet.getRow(11).height = 18;

    sheet.mergeCells('F12:F13')
    sheet.getCell('F12').value = 'Qty.';
    sheet.getCell('F12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('F12').alignment = { vertical: 'middle', horizontal: 'center' };

    sheet.mergeCells('G12:G13')
    sheet.getCell('G12').value = 'Unit \nCost.';
    sheet.getCell('G12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('G12').alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };

    sheet.mergeCells('H12:H13')
    sheet.getCell('H12').value = 'Total Cost';
    sheet.getCell('H12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('H12').alignment = { vertical: 'middle', horizontal: 'center' };

    sheet.mergeCells('I11:K11')
    sheet.getCell('I11').value = 'Balance';
    sheet.getCell('I11').font = { bold: true, italic: true, size: 12, name: 'Times New Roman' };
    sheet.getCell('I11').alignment = { vertical: 'middle', horizontal: 'center' };
    sheet.getColumn('I').width = 10;
    sheet.getColumn('J').width = 11
    sheet.getColumn('K').width = 12;
    sheet.getRow(11).height = 18;

    sheet.mergeCells('I12:I13')
    sheet.getCell('I12').value = 'Qty';
    sheet.getCell('I12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('I12').alignment = { vertical: 'middle', horizontal: 'center' };

    sheet.mergeCells('J12:J13')
    sheet.getCell('J12').value = 'Unit \nCost';
    sheet.getCell('J12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('J12').alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };

    sheet.mergeCells('K12:K13')
    sheet.getCell('K12').value = 'Total Cost';
    sheet.getCell('K12').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('K12').alignment = { vertical: 'middle', horizontal: 'center' };
    
    sheet.mergeCells('L11:L13')
    sheet.getCell('L11').value = 'No. Of \nDays to \nConsume';
    sheet.getCell('L11').font = { bold: false, italic: false, size: 12, name: 'Times New Roman' };
    sheet.getCell('L11').alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
    sheet.getColumn('L').width = 10;
    sheet.getRow(11).height = 18;

     // table data

     //static table data
    sheet.getCell('A14').value = '1/1/2025'
    sheet.getCell('A14').font = {size: 12, name: 'Times New Roman'}
    sheet.getCell('A14').alignment = {horizontal: 'left'}

    sheet.getCell('B14').value = 'Beginning Balance'
    sheet.getCell('B14').font = {size: 12, name: 'Times New Roman'}
    sheet.getCell('B14').alignment = {horizontal: 'center'}

    let currentRow = 15;
    rows.forEach((entry) => {
    sheet.getCell(`A${currentRow}`).value = entry.date || '';
    sheet.getCell(`B${currentRow}`).value = entry.ref || '';
    for (let col of 'ABCDEFGHIJKL') {
        sheet.getCell(`${col}${currentRow}`).border = {
            top: { style: 'thin' },
            left: { style: 'thin' },
            bottom: { style: 'thin' },
            right: { style: 'thin' }
        };
    }
    currentRow++;
    });
    
    // Apply border to metadata rows
borderThin(sheet, 'A8:H8');
borderThin(sheet, 'I8:L8');
borderThin(sheet, 'A9:H9');
borderThin(sheet, 'I9:L9');
borderThin(sheet, 'A10:H10');
borderThin(sheet, 'I10:L10');

// Apply border to table headers (3 rows)
borderThin(sheet, 'A11:A13');
borderThin(sheet, 'B11:B13');
borderThin(sheet, 'C11:E11');
borderThin(sheet, 'C12:C13');
borderThin(sheet, 'D12:D13');
borderThin(sheet, 'E12:E13');
borderThin(sheet, 'F11:H11');
borderThin(sheet, 'F12:F13');
borderThin(sheet, 'G12:G13');
borderThin(sheet, 'H12:H13');
borderThin(sheet, 'I11:K11');
borderThin(sheet, 'I12:I13');
borderThin(sheet, 'J12:J13');
borderThin(sheet, 'K12:K13');
borderThin(sheet, 'L11:L13');

// Apply border to the first data row
borderThin(sheet, 'A14:L14');




    // Export the file using FileSaver.js
    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    });
    saveAs(blob, `Stock_Card_${stock_no}.xlsx`);
}

//end of excel export

    document.getElementById('helpBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <p>View the full code guide <a href="codes.php" target="_blank" style="color: var(--accent-color); text-decoration: underline;">here</a>.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: 'var(--accent-color)'
        });
    });

   // Confirm logout
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
                window.location.href = 'index.php';
            }
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
        
        /* Card Header */
        .card-header {
            background: var(--primary-dark);
            color: white;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
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
            
            .content-card {
                padding: 1.5rem;
            }
        }
    </style>
</html>