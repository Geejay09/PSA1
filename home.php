<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Philippine Statistics Authority</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column">
            <!-- Dashboard Button -->
            <button class="btn sidebar-btn mb-3">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </button>

            <!-- Data Entry Section -->
            <div class="sidebar-section mb-4">
                <h5>Data Entry</h5>
                <button class="btn sidebar-btn" onclick="location.href='ris/ris.php'">
                    <i class="bi bi-file-earmark-text me-2"></i> Requisition and Issuance Slip
                </button>
                <button class="btn sidebar-btn" onclick="location.href='iar/iar.php'">
                    <i class="bi bi-clipboard-check me-2"></i> Inspection and Acceptance Report
                </button>
            </div>

            <!-- Generate Report Section -->
            <div class="sidebar-section mb-4">
                <h5>Generate Report</h5>
                <button class="btn sidebar-btn" onclick="location.href='stck_crd.php'">
                    <i class="bi bi-card-checklist me-2"></i> Stock Card
                </button>
                <button class="btn sidebar-btn">
                    <i class="bi bi-journal-text me-2"></i> Stock Ledger Card
                </button>
                <button class="btn sidebar-btn" onclick="location.href='rsmi/rsmi.php'">
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
                <button type="button" class="btn logout-btn w-100 mt-3">
                    <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                </button>
            </form>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 d-flex justify-content-center align-items-center p-4">
            <div class="text-center">
                <img src="assets/dash.jpg" alt="Dashboard Preview" class="img-fluid rounded-3 shadow-lg" style="max-width: 100%;">
                <h2 class="mt-4 text-white">Welcome to PSA Inventory System</h2>
                <p class="text-light-blue">Manage your inventory efficiently with our comprehensive tools</p>
            </div>
        </main>
    </div>
</div>

<!-- Floating Help Button -->
<button id="helpBtn" class="btn btn-primary rounded-circle floating-help-btn" title="Need help?">
    <i class="bi bi-question-lg"></i>
</button>

<!-- SweetAlert Logic -->
<script>
    document.querySelector('.logout-btn').addEventListener('click', () => {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    });

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
            window.location.href = 'index.php';
        });
    </script>";
    exit();
}
?>

</body>

<style>
    :root {
        --dark-blue: #0a192f;
        --medium-blue: #172a45;
        --light-blue: #64ffda;
        --white: #ffffff;
        --text-light: #ccd6f6;
        --text-lighter: #a8b2d1;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background-color: var(--dark-blue);
        color: var(--text-light);
    }

    .bg-dark-blue {
        background-color: var(--dark-blue);
    }

    .text-light-blue {
        color: var(--text-lighter);
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
        width: 280px;
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
        border-radius: 12px;
        margin: 1.5rem;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
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
    }
</style>
</html>