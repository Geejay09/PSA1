<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition and Issue Slip</title>
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
</head>
<body class="bg-dark-blue">

<div class="container-fluid p-0">
    <!-- Header -->
    <header class="psa-header d-flex align-items-center px-4 py-3">
        <img src="../assets/psa.png" alt="PSA Logo" class="psa-logo me-3">
        <div>
            <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
            <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
        </div>
    </header>

    <!-- Body -->
    <div class="d-flex" style="min-height: calc(100vh - 80px);">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column">
            <!-- Dashboard Button -->
            <button class="btn sidebar-btn mb-3" onclick="location.href='../home.php'">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </button>

            <!-- Data Entry Section -->
            <div class="sidebar-section mb-4">
                <h5>Data Entry</h5>
                <button class="btn sidebar-btn active" onclick="location.href='../ris/ris.php'">
                    <i class="bi bi-file-earmark-text me-2"></i> Requisition and Issuance Slip
                </button>
                <button class="btn sidebar-btn" onclick="location.href='../iar/iar.php'">
                    <i class="bi bi-clipboard-check me-2"></i> Inspection and Acceptance Report
                </button>
            </div>

            <!-- Generate Report Section -->
            <div class="sidebar-section mb-4">
                <h5>Generate Report</h5>
                <button class="btn sidebar-btn" onclick="location.href='../stck_crd.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Card
                </button>
                <button class="btn sidebar-btn">
                    <i class="bi bi-journal-text me-2"></i> Stock Ledger Card
                </button>
                <button class="btn sidebar-btn" onclick="location.href='../rsmi/rsmi.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Requisition and Issuance Slip
                </button>
                <button class="btn sidebar-btn">
                    <i class="bi bi-clipboard-data me-2"></i> Physical Count Report
                </button>
            </div>

            <!-- Utilities Section -->
            <div class="sidebar-section mb-4">
                <h5>Utilities</h5>
                <button class="btn sidebar-btn">
                    <i class="bi bi-people me-2"></i> Manage Employee List
                </button>
            </div>

            <!-- Spacer to push logout to bottom -->
            <div class="mt-auto"></div>

            <!-- Logout -->
            <form id="logoutForm" method="post">
                <input type="hidden" name="logout" value="1">
                <button type="button" class="btn logout-btn w-100 mt-3" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                </button>
            </form>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="content-card">
                <h2 class="text-center mb-4 text-light-blue">REQUISITION AND ISSUE SLIP</h2>
                
                <!-- Form Section -->
                <form id="risForm">
                    <div class="row g-3 mb-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="division" class="form-control bg-dark-input" id="division" required>
                                <label for="division">Division</label>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <input type="text" name="office" class="form-control bg-dark-input" id="office" value="PSA-Quirino" required>
                                <label for="office">Office</label>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <input type="text" name="rcc" class="form-control bg-dark-input" id="rcc" required>
                                <label for="rcc">Responsibility Center Code</label>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="ris_no" class="form-control bg-dark-input" id="ris_no" required>
                                <label for="ris_no">RIS No.</label>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <select name="fc" id="fc" class="form-select bg-dark-input" required>
                                    <option value="" disabled selected>Select Fund Cluster</option>
                                    <option value="Locally Funded">Locally Funded</option>
                                    <option value="Regular">Regular</option>
                                </select>
                                <label for="fc">Fund Cluster</label>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <select name="receiver" id="receiver" class="form-select bg-dark-input" required>
                                    <option value="" disabled selected>Select receiver</option>
                                    <option value="Cherry Grace D. Agustin">Cherry Grace D. Agustin</option>
                                    <option value="Karen T. Fernandez">Karen T. Fernandez</option>
                                    <option value="Marison S. Lomboy">Marison S. Lomboy</option>
                                    <option value="Lace Christelle D. Ladia">Lace Christelle D. Ladia</option>
                                    <option value="Liz T. Duque">Liz T. Duque</option>
                                    <option value="Alexander G. Austria">Alexander G. Austria</option>
                                    <option value="Jennifer B. Gamet">Jennifer B. Gamet</option>
                                    <option value="Archie C. Ferrer">Archie C. Ferrer</option>
                                    <option value="Santa Beverly P. Dorol">Santa Beverly P. Dorol</option>
                                    <option value="Elgin Adrian R. Ugot">Elgin Adrian R. Ugot</option>
                                    <option value="Joel N. Pilar">Joel N. Pilar</option>
                                    <option value="Bless M. Urbano">Bless M. Urbano</option>
                                    <option value="Sunshine D. Bumakel">Sunshine D. Bumakel</option>
                                    <option value="Psyche R. Villanueva">Psyche R. Villanueva</option>
                                </select>
                                <label for="receiver">Received by</label>
                            </div>
                        </div>
                        
                        <!-- Full Width -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="purpose" class="form-control bg-dark-input" id="purpose" style="height: 100px" required></textarea>
                                <label for="purpose">Purpose</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle" id="itemsTable">
                            <thead>
                                <tr class="bg-blue-dark">
                                    <th>Stock No</th>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Requested Qty</th>
                                    <th>Issued Qty</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="stock_no[]" class="form-control bg-dark-input stock_no"></td>
                                    <td><input type="text" name="item[]" class="form-control bg-dark-input items" readonly></td>
                                    <td><input type="text" name="dscrtn[]" class="form-control bg-dark-input descd" readonly></td>
                                    <td>
                                        <select name="unit[]" class="form-select bg-dark-input" required>
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
                                    <td><input type="number" name="qty[]" class="form-control bg-dark-input" required></td>
                                    <td><input type="number" name="i_qty[]" class="form-control bg-dark-input" required></td>
                                    <td><input type="text" name="remarks[]" class="form-control bg-dark-input"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Form Buttons -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-light me-3" onclick="addRow()">
                            <i class="bi bi-plus-circle me-2"></i>Add Row
                        </button>
                        <button type="button" class="btn btn-primary" onclick="confirmSubmit()">
                            <i class="bi bi-send-check me-2"></i>Submit
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Floating Help Button -->
<button id="helpBtn" class="btn btn-primary rounded-circle floating-help-btn" title="Need help?">
    <i class="bi bi-question-lg"></i>
</button>

<script>
    // Add active class to clicked sidebar button
    document.querySelectorAll('.sidebar-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Add new row to the table
    function addRow() {
        const table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" name="stock_no[]" class="form-control bg-dark-input stock_no"></td>
            <td><input type="text" name="item[]" class="form-control bg-dark-input items" readonly></td>
            <td><input type="text" name="dscrtn[]" class="form-control bg-dark-input descd" readonly></td>
            <td>
                <select name="unit[]" class="form-select bg-dark-input" required>
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
            <td><input type="number" name="qty[]" class="form-control bg-dark-input" required></td>
            <td><input type="number" name="i_qty[]" class="form-control bg-dark-input" required></td>
            <td><input type="text" name="remarks[]" class="form-control bg-dark-input"></td>
        `;
    }

    // Auto-fill item and description when stock number is entered
    $(document).on('input', '.stock_no', function() {
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
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        Swal.fire({
            title: 'Submit RIS?',
            text: 'Are you sure you want to submit this Requisition and Issue Slip?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) {
                submitFormAJAX();
            }
        });
    }

    // Submit form via AJAX
    function submitFormAJAX() {
        const form = document.getElementById('risForm');
        const formData = new FormData(form);

        fetch('submit_ris.php', {
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
                    confirmButtonColor: '#0d6efd'
                });
                form.reset();
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#0d6efd'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                title: 'Oops!',
                text: 'Something went wrong.',
                icon: 'error',
                confirmButtonColor: '#0d6efd'
            });
        });
    }

    // Confirm logout
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
                window.location.href = '../index.php';
            }
        });
    }

    // Help button
    document.getElementById('helpBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <p>View the full code guide <a href="../codes.html" target="_blank" style="color: #0d6efd; text-decoration: underline;">here</a>.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: '#0d6efd'
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
        width: 491px;
        background: linear-gradient(180deg, #0a192f 0%, #172a45 100%);
        padding: 1.5rem;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-section {
        margin-bottom: 1.5rem;
    }

    .sidebar h5 {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--light-blue);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(100, 255, 218, 0.2);
    }

    .sidebar-btn {
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border-radius: 6px;
        color: var(--text-light);
        background-color: rgba(23, 42, 69, 0.7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .sidebar-btn:hover {
        background-color: rgba(100, 255, 218, 0.1);
        color: var(--white);
        transform: translateX(5px);
    }

    .sidebar-btn.active {
        background-color: rgba(100, 255, 218, 0.2);
        color: var(--light-blue);
        border-left: 3px solid var(--light-blue);
        font-weight: 600;
    }

    .sidebar-btn i {
        width: 20px;
        text-align: center;
    }

    /* Logout Button */
    .logout-btn {
        background-color: transparent;
        color: #ff6b6b;
        border: 1px solid #ff6b6b;
        padding: 0.75rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: rgba(255, 107, 107, 0.1);
        color: #ff6b6b;
    }

    /* Main Content */
    .main-content {
        background-color: rgba(10, 25, 47, 0.7);
        overflow-y: auto;
    }

    .content-card {
        background-color: rgb(255, 255, 255);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(119, 99, 99, 0.17);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Table Styles */
    .table-dark {
        --bs-table-bg: var(--medium-blue);
        --bs-table-striped-bg: #1a2a4a;
        --bs-table-hover-bg: #1e365d;
        --bs-table-border-color: #2d3a5a;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        background-color: var(--blue-dark);
    }

    /* Form Styles */
    .form-floating label {
        color: var(--text-lighter);
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select~label {
        color: var(--light-blue);
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

    /* Invalid input styling */
    .is-invalid {
        border-color: #ff6b6b !important;
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
    }
</style>
</body>
</html>