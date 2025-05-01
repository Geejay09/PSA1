<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection and Acceptance Report</title>
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
                    <i class="bi bi-file-earmark-text me-2"></i> Requisition Slip
                </button>
                <button class="btn sidebar-btn mb-1 active" onclick="location.href='../iar/iar.php'">
                    <i class="bi bi-clipboard-check me-2"></i> Inspection Report
                </button>
            </div>

            <!-- Reports Section -->
            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Reports</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../stck_crd.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Card
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../slc/slc.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Ledger
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rsmi/rsmi.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> RIS Report
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rpci/rpci.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Physical Count
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
                <h2 class="text-center mb-4 page-title">INSPECTION AND ACCEPTANCE REPORT</h2>
                
                <!-- Form Section -->
                <form id="iarForm">
                    <!-- Header Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" value="Philippine Statistics Authority" readonly>
                                <label>Entity Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="fund_cluster" class="form-control">
                                <label>Fund Cluster</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="supplier" class="form-control" required>
                                <label>Supplier</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" name="iar_no" class="form-control" required>
                                <label>IAR No.</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" name="date" class="form-control" required>
                                <label>Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="pr_no" class="form-control" required>
                                <label>PO No./Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="invoice_no" class="form-control">
                                <label>Invoice No.</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" name="requisitioning_office" class="form-control">
                                <label>Requisitioning Office/Dept.</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" name="responsibility_center" class="form-control">
                                <label>Responsibility Center Code</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Stock/Property No.</th>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control stock-code" name="stock_code[]"></td>
                                    <td><input type="text" name="item[]" class="form-control items" readonly></td>
                                    <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
                                    <td>
                                        <select name="unit[]" class="form-select" required>
                                            <option value="" disabled selected>--Select--</option>
                                            <option value="pc">pc</option>
                                            <option value="bottle">bottle</option>
                                            <option value="ream">ream</option>
                                            <option value="kg">kg</option>
                                            <option value="liter">liter</option>
                                            <option value="pack">pack</option>
                                            <option value="box">box</option>
                                            <option value="roll">roll</option>
                                            <option value="pair">pair</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity[]" class="form-control" min="0"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-accent" onclick="addRow()">
                            <i class="bi bi-plus-circle me-2"></i>Add Row
                        </button>
                    </div>
                    
                    <!-- Inspection Section -->
                    <h5 class="mb-3 section-title">INSPECTION</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" name="date_inspected" class="form-control" required>
                                <label>Date Inspected</label>
                            </div>
                            <p>Inspected, verified and found in order as to quantity and specifications.</p>
                            <div class="form-floating">
                                <input type="text" name="i_officer" class="form-control" placeholder="Enter name" required>
                                <label>Inspection Officer</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" name="final_date_received" class="form-control" required>
                                <label>Date Received</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="custodian" class="form-control" placeholder="Enter name" required>
                                <label>Custodian</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-accent" onclick="confirmSubmit()">
                            <i class="bi bi-send-check me-2"></i>Submit
                        </button>
                    </div>
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

    // Add new row to the table
    function addRow() {
        const table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" class="form-control stock-code" name="stock_code[]"></td>
            <td><input type="text" name="item[]" class="form-control items" readonly></td>
            <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
            <td>
                <select name="unit[]" class="form-select" required>
                    <option value="" disabled selected>--Select--</option>
                    <option value="pc">pc</option>
                    <option value="bottle">bottle</option>
                    <option value="ream">ream</option>
                    <option value="kg">kg</option>
                    <option value="liter">liter</option>
                    <option value="pack">pack</option>
                    <option value="box">box</option>
                    <option value="roll">roll</option>
                    <option value="pair">pair</option>
                </select>
            </td>
            <td><input type="number" name="quantity[]" class="form-control" min="0"></td>
        `;
    }

    // Auto-fill item and description when stock number is entered
    $(document).on('input', '.stock-code', function() {
        var row = $(this).closest('tr');
        var stockCode = $(this).val();

        $.get("../get_description.php", { stock_code: stockCode }, function(response) {
            row.find(".items").val(response.item);
            row.find(".descd").val(response.description);
        });
    });

    // Confirm before submitting form
    function confirmSubmit() {
        // Validate required fields
        const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                title: 'Error!',
                text: 'Please fill out all required fields.',
                icon: 'error',
                confirmButtonText: 'Ok',
                confirmButtonColor: 'var(--accent-color)'
            });
            return;
        }

        Swal.fire({
            title: 'Submit IAR?',
            text: 'Are you sure you want to submit this Inspection and Acceptance Report?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: 'var(--accent-color)',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) {
                submitFormAJAX();
            }
        });
    }

    // Submit form via AJAX
    function submitFormAJAX() {
        const form = document.getElementById('iarForm');
        const formData = new FormData(form);

        fetch('submit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: 'var(--accent-color)'
                });
                form.reset();
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: 'var(--accent-color)'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                title: 'Oops!',
                text: 'Something went wrong.',
                icon: 'error',
                confirmButtonColor: 'var(--accent-color)'
            });
        });
    }

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
                window.location.href = '../logout.php';
            }
        });
    });

    // Help button
    document.getElementById('helpBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <p>View the full code guide <a href="../codes.html" target="_blank" style="color: var(--accent-color); text-decoration: underline;">here</a>.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: 'var(--accent-color)'
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
        
        .form-floating>label {
            color: #6c757d;
        }
        
        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label,
        .form-floating>.form-select~label {
            color: var(--accent-color);
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
        
        /* Section Titles */
        .section-title {
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 50px;
            height: 2px;
            background: var(--accent-color);
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