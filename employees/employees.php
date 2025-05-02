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
    <title>Manage Employees</title>
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
                <i class="bi bi-calendar3 me-2" aria-hidden="true"></i><?php echo date('F j, Y'); ?>
            </span>
            <span class="header-time">
                <i class="bi bi-clock me-2" aria-hidden="true"></i><span id="currentTime"></span>
            </span>
        </div>
    </header>

    <!-- Body -->
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-3" aria-label="Main navigation">
            <!-- User Profile -->
            <div class="d-flex align-items-center mb-4 p-3 rounded user-profile">
                <div class="me-3">
                    <i class="bi bi-person-circle fs-3" style="color: var(--accent-color);" aria-hidden="true"></i>
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

            <!-- Navigation Buttons -->
            <button class="btn sidebar-btn mb-2" onclick="location.href='../home.php'">
                <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i> Dashboard
            </button>

            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Data Entry</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../ris/ris.php'">
                    <i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i> Requisition Issuance Slip
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../iar/iar.php'">
                    <i class="bi bi-clipboard-check me-2" aria-hidden="true"></i> Issuance and Acceptance Report
                </button>
            </div>

            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Reports</div>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../stck_crd.php'">
                    <i class="bi bi-card-checklist me-2" aria-hidden="true"></i> Stock Card
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../slc/slc.php'">
                    <i class="bi bi-card-checklist me-2" aria-hidden="true"></i> Stock Ledger Card
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rsmi/rsmi.php'">
                    <i class="bi bi-file-earmark-spreadsheet me-2" aria-hidden="true"></i> Report on Supplies and Materials Issued
                </button>
                <button class="btn sidebar-btn mb-1" onclick="location.href='../rpci/rpci.php'">
                    <i class="bi bi-clipboard-data me-2" aria-hidden="true"></i> Report on Physical Count of Inventories
                </button>
            </div>

            <div class="mb-3">
                <div class="sidebar-title px-2 py-1 mb-2 small fw-bold">Utilities</div>
                <button class="btn sidebar-btn mb-1 active">
                    <i class="bi bi-people me-2" aria-hidden="true"></i> Employee List
                </button>
            </div>

            <div class="mt-auto"></div>

            <button id="logoutBtn" class="btn btn-outline-accent mt-3">
                <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i> Logout
            </button>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="content-card">
                <h1 class="text-center mb-4 page-title">Manage Employees</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="input-group w-50">
                                <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, position or access level" aria-label="Search employees">
                            </div>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="openAddModal()">
                                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Add Employee
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="employeeTable" aria-describedby="employeeTableDescription">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Access Level</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="employeeForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="employeeModalLabel">Add/Edit Employee</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="employeeId">
                    <div class="mb-2">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-2 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" style="position:absolute; right:10px; top:38px;" aria-label="Toggle password visibility">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <div class="mb-2">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" id="position" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="accessLevel" class="form-label">Access Level</label>
                        <select id="accessLevel" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="staff" selected>Staff</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute:'2-digit' });
    }
    
    setInterval(updateTime, 1000);
    updateTime();

    // Add active class to clicked sidebar button
    document.querySelectorAll('.sidebar-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Toggle password visibility
    $(document).on('click', '.toggle-password', function () {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        
        icon.toggleClass('bi-eye bi-eye-slash');
        $(this).attr('aria-label', type === 'password' ? 'Show password' : 'Hide password');
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
                window.location.href = '../index.php';
            }
        });
    });

    let employeeList = []; 

    function openAddModal() {
        $('#employeeForm')[0].reset();
        $('#employeeId').val('');
        $('#employeeModal .modal-title').text('Add Employee');
        $('#password').prop('disabled', false).attr('required', 'true');
    }

    function renderTable(data) {
        let rows = '';

        if (data.length === 0) {
            rows = `<tr><td colspan="7" class="text-center text-danger fw-bold">No Users Found!</td></tr>`;
        } else {
            data.slice().reverse().forEach((emp, index) => {
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${emp.first_name}</td>
                        <td>${emp.last_name}</td>
                        <td>${emp.email}</td>
                        <td>${emp.position}</td>
                        <td>${emp.access_level}</td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1" onclick="editEmployee('${emp.id}')" aria-label="Edit ${emp.first_name}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteEmployee('${emp.id}')" aria-label="Delete ${emp.first_name}">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>`;
            });
        }

        $('#employeeBody').html(rows);
    }

    // Form submit handler
    $('#employeeForm').on('submit', function (e) {
        e.preventDefault();
        
        const formData = {
            first_name: $('#firstName').val(),
            last_name: $('#lastName').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            position: $('#position').val(),
            access_level: $('#accessLevel').val()
        };

        const id = $('#employeeId').val();
        const url = id ? 'edit_employee.php' : 'add_employee.php';
        const method = 'POST';

        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            data: id ? {...formData, id: id} : formData,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: id ? 'Updated!' : 'Added!',
                        text: response.message
                    }).then(() => {
                        $('#employeeModal').modal('hide');
                        fetchEmployees(); 
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Something went wrong: ' + xhr.responseText, 'error');
            }
        });
    });

    function editEmployee(id) {
        $.ajax({
            url: 'get_employees.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(emp) {
                $('#employeeId').val(emp.id);
                $('#firstName').val(emp.first_name);
                $('#lastName').val(emp.last_name);
                $('#email').val(emp.email);
                $('#position').val(emp.position);
                $('#accessLevel').val(emp.access_level);
                $('#password').val('').prop('disabled', true).removeAttr('required');
                $('#employeeModal .modal-title').text('Edit Employee');
                $('#employeeModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not load employee data', 'error');
            }
        });
    }

    function deleteEmployee(id) {
        Swal.fire({
            title: 'Confirm Deletion',
            text: "Are you sure you want to remove this employee? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_employee.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success');
                            fetchEmployees(); 
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong: ' + xhr.responseText, 'error');
                    }
                });
            }
        });
    }

    function fetchEmployees() {
        $.ajax({
            url: 'get_employees.php', 
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    employeeList = response.data;
                    renderTable(employeeList);
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Something went wrong while fetching employee data.', 'error');
            }
        });
    }

    $('#searchInput').on('input', function () {
        const term = $(this).val().toLowerCase();
        const filtered = employeeList.filter(emp =>
            emp.first_name.toLowerCase().includes(term) ||
            emp.last_name.toLowerCase().includes(term) ||
            emp.email.toLowerCase().includes(term) ||
            emp.position.toLowerCase().includes(term) ||
            emp.access_level.toLowerCase().includes(term)
        );
        renderTable(filtered);
    });

    $(document).ready(function () {
        fetchEmployees();
    });
</script>

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
</body>
</html>