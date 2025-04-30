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
    <title>Philippine Statistics Authority</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container-fluid p-0">
    <!-- Header -->
    <div class="psa-header d-flex align-items-center border-bottom px-4 py-3">
        <img src="assets/psa.png" alt="PSA Logo" class="psa-logo mr-3">
        <div>
            <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
            <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
        </div>
    </div>

    <!-- Body -->
    <div class="d-flex" style="height: 90vh;">
        <!-- Sidebar -->
        <div class="sidebar">
    <button class="btn btn-block sidebar-btn">
        <i class="bi bi-speedometer2 icon-spacing"></i> Dashboard
    </button>

    <h5>Data Entry</h5>
    <button class="btn btn-block sidebar-btn" onclick="location.href='ris/ris.php'">
        <i class="bi bi-file-earmark-text icon-spacing"></i> Requisition and Issuance Slip
    </button>
    <button class="btn btn-block sidebar-btn" onclick="location.href='iar/iar.php'">
        <i class="bi bi-file-earmark-text icon-spacing"></i> Inspection and Acceptance Report
    </button>

    <h5>Generate Report</h5>
    <button class="btn btn-block sidebar-btn" onclick="location.href='stck_crd.php'">
        <i class="bi bi-file-earmark-text icon-spacing"></i> Stock Card
    </button>
    <button class="btn btn-block sidebar-btn">
        <i class="bi bi-file-earmark-text icon-spacing"></i> Stock Ledger Card
    </button>
    <button class="btn btn-block sidebar-btn" onclick="location.href='rsmi/rsmi.php'">
        <i class="bi bi-file-earmark-text icon-spacing"></i> Requisition and Issuance Slip
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


        <!-- Main Content with Image -->
        <div class="flex-grow-1 d-flex justify-content-center align-items-center">
            <div class="main-content text-center">
                <img src="assets/dash.jpg" alt="Dashboard Preview" class="img-fluid rounded shadow" style="max-width: 500%;">
            </div>
        </div>
    </div>
</div>

<!-- Floating Help Icon -->
<button id="helpBtn" class="btn btn-primary rounded-circle" title="Need help?">
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
            cancelButtonText: 'Cancel'
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
            <p>View the full code guide <a href="codes.html" target="_blank" style="color: blue; text-decoration: underline;">here</a>.</p>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!'
    });
});

    const sidebarButtons = document.querySelectorAll('.sidebar-btn');

    sidebarButtons.forEach(button => {
        button.addEventListener('click', function() {
        sidebarButtons.forEach(btn => btn.classList.remove('active')); // Remove active from all
        this.classList.add('active'); // Add active to clicked one
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
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color:rgb(11, 26, 48);
        color: #333;
    }

    .psa-header {
        background-color: rgb(4, 33, 65);
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Times New Roman', Times, serif;
    }

    .psa-small, .psa-main {
        font-family: 'Times New Roman', Times, serif;
    }

    .psa-logo {
        height: 60px;
        width: auto;
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
    background: linear-gradient(to bottom, #1f2a40, #141c2b); /* dark blue to dark gray */
    color: #ffffff;
    padding: 20px;
    height: 100%;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    border: 1px solid rgb(255, 255, 255); /* <<<< WHITE border */
    backdrop-filter: blur(10px); /* Optional: adds a frosted glass effect */
    border-radius: 10px; /* Optional: makes the sidebar corners slightly rounded */
}

.sidebar h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #a5c9ff;
    border-bottom: 1px solid #32425c;
    padding-bottom: 5px;
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
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(207, 204, 204, 0.1);
}


.logout-btn {
    background-color: transparent;
    border: 1px solid #ff4d4f;
    color: #ff4d4f;
    transition: all 0.3s ease-in-out;
}

.logout-btn:hover {
    background-color: #ff4d4f;
    color: white;
}

.icon-spacing {
    margin-right: 10px; /* Adjust space between icon and text */
    font-size: 1.2rem;  /* Slightly bigger icons (optional) */
    vertical-align: middle; /* Aligns icon properly with text */
}


    .main-content {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ffffff;
        border-radius: 8px;
        margin: 20px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    /* Floating Help Button */
    #helpBtn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        z-index: 1000;
    }

    .sidebar-btn.active {
    border: 2px solid #ffffff; /* white border */
    box-shadow: 0 0 10px #ffffff, 0 0 20px #ffffff, 0 0 30px #ffffff;
    background-color: #3e4d6c;
    color: #ffffff;
    animation: glow 1.5s infinite alternate;
}

    /* Soft glowing animation */
    @keyframes glow {
    from {
        box-shadow: 0 0 5px #ffffff, 0 0 10px #ffffff, 0 0 15px #ffffff;
    }
    to {
        box-shadow: 0 0 20px #ffffff, 0 0 30px #ffffff, 0 0 40px #ffffff;
    }
}


</style>
</html>
