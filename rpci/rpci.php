<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "dbpsa");
$result = $conn->query("SELECT * FROM tbl_items WHERE deleted = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Count Report | PSA Inventory System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
    
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
                <h2 class="text-center mb-4 page-title">REPORT ON PHYSICAL COUNT OF INVENTORIES</h2>
                <!-- Empty content area for your implementation -->
                 <div class="form-floating">
                    <input type="date" name="date" class="form-control" required>
                    <label>Date</label>
                </div>
                <button onclick="generateReport()">Download Inventory Report</button>
            </div>
                 <h1>Stock Item List</h1>
                 <table>    
                    <thead>
                        <tr>
                            <th>Stock Code</th>
                            <th>Item</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr id="row-<?= $row['id'] ?>">
                            <td><?= htmlspecialchars($row['stock_code']) ?></td>
                            <td><?= htmlspecialchars($row['item']) ?></td>
                            <td><?= htmlspecialchars($row['descode']) ?></td>
                        </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
        </main>
    </div>
</div>
<!-- Scripts -->
<script>
    //excel export

      async function generateReport() {
    try {
        // Fetch items from the server
        const response = await fetch('fetch_items.php');
        const items = await response.json();

        const workbook = new ExcelJS.Workbook();
        let currentRow = 17;
        let itemIndex = 0;
        let pageNumber = 1;

        // Helper function to set cell borders
        function setCellBorder(cell) {
            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' }
            };
        }

        while (itemIndex < items.length) {
            const sheet = workbook.addWorksheet(`Inventory Report Page ${pageNumber}`);
            
            // Set column widths
            sheet.columns = [
                { header: 'Article', width: 25 },
                { header: 'Description', width: 30 },
                { header: 'Stock Number', width: 20 },
                { header: 'Unit of Measure', width: 15 },
                { header: 'Unit Value', width: 20 },
                { header: 'Balance Per Card (Quantity)', width: 17 },
                { header: 'On Hand Per Count (Quantity)', width: 17 },
                { header: 'Total Value', width: 25 },
                { header: 'Shortage/Overage Quantity', width: 10 },
                { header: 'Shortage/Overage Value', width: 15 },
                { header: 'Remarks', width: 15 }
            ];

            const column1 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

column1.forEach(col => {
  const cell = sheet.getCell(`${col}39`);
  cell.border = {
    top: { style: 'thin' },
    bottom: { style: 'thin' },
    left: { style: 'thin' },
    right: { style: 'thin' }
  };
});

            // Common header for all pages
            sheet.mergeCells('A1:K1');
            sheet.getCell('A1').value = 'Appendix 66';
            sheet.getCell('A1').font = { bold: true };
            sheet.getCell('A1').alignment = { horizontal: 'right' };

            sheet.mergeCells('A3:K3');
            sheet.getCell('A3').value = 'REPORT ON THE PHYSICAL COUNT OF INVENTORIES';
            sheet.getCell('A3').font = { bold: true };
            sheet.getCell('A3').alignment = { horizontal: 'center' };

            sheet.mergeCells('A4:K4');
            sheet.getCell('A4').value = 'Office Supplies Inventory';
            sheet.getCell('A4').font = { bold: true };
            sheet.getCell('A4').alignment = { horizontal: 'center' };

            sheet.mergeCells('A5:K5');
            sheet.getCell('A5').value = '(Type of Inventory Item)';
            sheet.getCell('A5').font = { bold: false };
            sheet.getCell('A5').alignment = { horizontal: 'center' };

            sheet.mergeCells('A6:K6');
            sheet.getCell('A6').value = 'As at (Date)';
            sheet.getCell('A6').font = { bold: true };
            sheet.getCell('A6').alignment = { horizontal: 'center' };

            // Sub-headers
            sheet.mergeCells('A8:B8');
            sheet.getCell('A8').value = 'Fund Cluster: Regular Fund';
            sheet.getCell('A8').font = { bold: true };
            sheet.getCell('A8').alignment = { horizontal: 'left' };

            sheet.mergeCells('A10:K10');
            sheet.getCell('A10').value = 'For which Alexander G. Austria, Administrative Officer I(Supply and Records Officer), Philippine Statistics Authority is accountable having assumed such';
            sheet.getCell('A10').font = { bold: true };
            sheet.getCell('A10').alignment = { horizontal: 'left' };

            sheet.mergeCells('A11:B11');
            sheet.getCell('A11').value = 'Accountability on August 22, 2018';
            sheet.getCell('A11').font = { bold: true };
            sheet.getCell('A11').alignment = { horizontal: 'left' };

            // Column headers
            sheet.mergeCells('A13:A15');
            sheet.getCell('A13').value = 'Article';
            sheet.getCell('A13').font = { bold: true };
            sheet.getCell('A13').alignment = { horizontal: 'center' };

            sheet.mergeCells('B13:B15');
            sheet.getCell('B13').value = 'Description';
            sheet.getCell('B13').font = { bold: true };
            sheet.getCell('B13').alignment = { horizontal: 'center' };

            sheet.mergeCells('C13:C15');
            sheet.getCell('C13').value = 'Stock Number';
            sheet.getCell('C13').font = { bold: true };
            sheet.getCell('C13').alignment = { horizontal: 'center' };

            sheet.mergeCells('D13:D15');
            sheet.getCell('D13').value = 'Unit of Measure';
            sheet.getCell('D13').font = { bold: true };
            sheet.getCell('D13').alignment = { horizontal: 'center' };

            sheet.mergeCells('E13:E15');
            sheet.getCell('E13').value = 'Unit Value';
            sheet.getCell('E13').font = { bold: true };
            sheet.getCell('E13').alignment = { horizontal: 'center' };

            sheet.mergeCells('F13:F14');
            sheet.getCell('F13').value = 'Balance per Card';
            sheet.getCell('F13').font = { bold: true };
            sheet.getCell('F13').alignment = { horizontal: 'center' };

            sheet.getCell('F15').value = '(Quantity)';
            sheet.getCell('F15').font = { bold: true };
            sheet.getCell('F15').alignment = { horizontal: 'center' };

            sheet.mergeCells('G13:G14');
            sheet.getCell('G13').value = 'On Hand per Count';
            sheet.getCell('G13').font = { bold: true };
            sheet.getCell('G13').alignment = { horizontal: 'center' };

            sheet.getCell('G15').value = '(Quantity)';
            sheet.getCell('G15').font = { bold: true };
            sheet.getCell('G15').alignment = { horizontal: 'center' };

            sheet.mergeCells('H13:H15');
            sheet.getCell('H13').value = 'Total Value';
            sheet.getCell('H13').font = { bold: true };
            sheet.getCell('H13').alignment = { horizontal: 'center' };

            sheet.mergeCells('I13:J14');
            sheet.getCell('I13').value = 'Shortage/Overage';
            sheet.getCell('I13').font = { bold: true };
            sheet.getCell('I13').alignment = { horizontal: 'center' };

            sheet.getCell('I15').value = 'Quantity';
            sheet.getCell('I15').font = { bold: true };
            sheet.getCell('I15').alignment = { horizontal: 'center' };

            sheet.getCell('J15').value = 'Value';
            sheet.getCell('J15').font = { bold: true };
            sheet.getCell('J15').alignment = { horizontal: 'center' };

            sheet.mergeCells('K13:K15');
            sheet.getCell('K13').value = 'Remarks';
            sheet.getCell('K13').font = { bold: true };
            sheet.getCell('K13').alignment = { horizontal: 'center' };

            // Set borders for header rows
            const columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
            for (let row = 13; row <= 15; row++) {
                columns.forEach(col => {
                    const cell = sheet.getCell(`${col}${row}`);
                    setCellBorder(cell);
                });
            }

            // Gray fill for row 16
            const grayFill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'FFDCE0E8' }
            };

            columns.forEach(col => {
                const cell = sheet.getCell(`${col}16`);
                cell.fill = grayFill;
            });

            sheet.getCell('B16').value = 'A. OFFICE SUPPLIES';
            sheet.getCell('B16').font = { bold: true };
            sheet.getCell('B16').alignment = { horizontal: 'center' };

            // Add items to the current page (rows 17-38)
            currentRow = 17;
            while (currentRow <= 38 && itemIndex < items.length) {
                const item = items[itemIndex];
                
                sheet.getCell(`A${currentRow}`).value = item.item; // Article
                sheet.getCell(`B${currentRow}`).value = item.descode; // Description
                sheet.getCell(`C${currentRow}`).value = item.stock_code; // Stock Number
                
                // Set borders for the current row
                columns.forEach(col => {
                    const cell = sheet.getCell(`${col}${currentRow}`);
                    setCellBorder(cell);
                });
                
                currentRow++;
                itemIndex++;
            }

            // Set borders for all data rows (16-38)
            for (let row = 16; row <= 38; row++) {
                columns.forEach(col => {
                    const cell = sheet.getCell(`${col}${row}`);
                    setCellBorder(cell);
                });
            }

            // Footer for each page
            sheet.getCell('A39').value = 'PAGE TOTAL';
            sheet.getCell('A39').font = { bold: true };
            sheet.getCell('A39').alignment = { horizontal: 'left' };

            // Signatures section (same for all pages)
            sheet.getCell('A41').value = 'PREPARED BY:';
            sheet.getCell('A41').font = { bold: true };
            sheet.getCell('A41').alignment = { horizontal: 'left' };

            sheet.mergeCells('A43:B43');
            sheet.getCell('A43').value = 'ALEXANDER G. AUSTRIA';
            sheet.getCell('A43').font = { bold: true };
            sheet.getCell('A43').alignment = { horizontal: 'center' };

            sheet.mergeCells('A44:B44');
            sheet.getCell('A44').value = 'Supply and Records Officer';
            sheet.getCell('A44').font = { bold: false };
            sheet.getCell('A44').alignment = { horizontal: 'center' };

            sheet.getCell('B41').value = 'CERTIFIED CORRECT BY:';
            sheet.getCell('B41').font = { bold: true };
            sheet.getCell('B41').alignment = { horizontal: 'right' };

            sheet.mergeCells('C43:E43');
            sheet.getCell('C43').value = 'LIZ T. DUQUE';
            sheet.getCell('C43').font = { bold: true };
            sheet.getCell('C43').alignment = { horizontal: 'center' };

            sheet.mergeCells('C44:E44');
            sheet.getCell('C44').value = 'Signature Over Printed Name of Inventory Committee Chair';
            sheet.getCell('C44').font = { bold: false };
            sheet.getCell('C44').alignment = { horizontal: 'center' };

            sheet.mergeCells('F43:H43');
            sheet.getCell('F43').value = 'ENGR. CHERRY GRACE D. AGUSTIN';
            sheet.getCell('F43').font = { bold: true };
            sheet.getCell('F43').alignment = { horizontal: 'center' };

            sheet.mergeCells('F44:H44');
            sheet.getCell('F44').value = 'Signature Over Printed Name of Head of Agency/Entity or Authorized Representative';
            sheet.getCell('F44').font = { bold: false };
            sheet.getCell('F44').alignment = { horizontal: 'center' };

            sheet.getCell('I41').value = 'NOTED BY:';
            sheet.getCell('I41').font = { bold: true };
            sheet.getCell('I41').alignment = { horizontal: 'right' };

            sheet.mergeCells('J43:K43');
            sheet.mergeCells('J44:K44');
            sheet.getCell('J44').value = 'Signature over Printed Name of COA Representative';
            sheet.getCell('J44').font = { bold: false };
            sheet.getCell('J44').alignment = { horizontal: 'center' };

            pageNumber++;
        }

        // Save the workbook
        const buffer = await workbook.xlsx.writeBuffer();
        saveAs(new Blob([buffer]), 'Inventory_Report.xlsx');
    } catch (error) {
        console.error('Error generating report:', error);
        Swal.fire({
            title: 'Error',
            text: 'An error occurred while generating the report.',
            icon: 'error'
        });
    }
}

    //end of excel export

    // Update current time
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        if(timeElement) {
            timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute:'2-digit' });
        }
    }
    
    setInterval(updateTime, 1000);
    updateTime();
    
    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Logout Confirmation',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--accent-color)',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../index.php';
            }
        });
    });
    // Active sidebar link highlighting
    document.querySelectorAll('.sidebar-btn').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
        
        link.addEventListener('click', function(e) {
            if (this.href === window.location.href) {
                e.preventDefault();
            }
            
            document.querySelectorAll('.sidebar-btn').forEach(el => {
                el.classList.remove('active');
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

        .header-time {
            font-family: 'Roboto', sans-serif;
            color: rgba(255,255,255,0.8);
            background: rgba(100, 255, 218, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(100, 255, 218, 0.2);
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #0a192f;
            color: white;
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