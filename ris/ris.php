<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requisition and Issue Slip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="psa-header d-flex align-items-center border-bottom px-4 py-3">
          <img src="../assets/psa.png" alt="PSA Logo" class="psa-logo mr-3">
        
          <div>
              <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
              <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
        </div>        
        </div>

    <!-- BODY WRAPPER -->
    <div class="d-flex" style="height: 90vh;">
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
    <button class="btn btn-block sidebar-btn">
        <i class="bi bi-file-earmark-bar-graph icon-spacing"></i> Report of Supplies and Materials Issued
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
            <h2 class="text-center mb-4">REQUISITION AND ISSUE SLIP</h2>
            
            <!-- Card Wrapper for the Form -->
            <div class="card shadow-sm p-4">
                <form id="risForm">
                <div class="form-section">
                    <div>
                        <label>Division:</label>
                        <input type="text" name="division" class="form-control" required>

                        <label>Office:</label>
                        <input type="text" name="office" value="PSA-Quirino" class="form-control" required>

                        <label>Responsibility Center Code:</label>
                        <input type="text" name="rcc" class="form-control" required>

                        <label for="fc">Fund Cluster:</label>
                            <select name="fc" id="fc" class="form-select form-control" required>
                                <option value="" disabled selected>Fund Cluster</option>
                                <option value="Locally Funded">Locally Funded</option>
                                <option value="Regular">Regular</option>
                            </select>
                    </div>
                    <div>
                        <label>RIS No.:</label>
                        <input type="text" name="ris_no" class="form-control" required>

                        <label>Purpose:</label>
                        <textarea name="purpose" class="form-control" rows="3" required></textarea>

                        <label for="receiver">Received by:</label>
                            <select name="receiver" id="receiver" class="form-select form-control" required>
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
                    </div>
                </div>
                <br/>
                <table class="table table-bordered styled-table" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Stock No</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Requested Quantity</th>
                            <th>Issued Quantity</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="stock_no[]" class="form-control stock_no"></td>
                            <td><input type="text" name="item[]" class="form-control items" readonly></td>
                            <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
                            <td>
                                <select name="unit[]" class="form-select form-control" required>
                                    <option value="">--Select--</option>
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
                            <td><input type="number" name="qty[]" class="form-control" required></td>
                            <td><input type="number" name="i_qty[]" class="form-control" required></td>
                            <td><input type="text" name="remarks[]" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-secondary mr-2" onclick="addRow()">Add Row</button>
    <button type="button" class="btn btn-primary submit-btn" onclick="confirmSubmit()">Submit</button>
</div>

            </form>
            <button id="helpBtn" class="btn btn-primary rounded-circle" title="Need help?">
                <i class="bi bi-question-lg"></i>
            </button>
        </div>
    </div>
</div>

<script>
    function addRow() {
        const table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" name="stock_no[]" class="form-control stock_no"></td>
            <td><input type="text" name="item[]" class="form-control items" readonly></td>
            <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
            <td>
                <select name="unit[]" class="form-select form-control" required>
                    <option value="">--Select--</option>
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
            <td><input type="number" name="qty[]" class="form-control" required></td>
            <td><input type="number" name="i_qty[]" class="form-control" required></td>
            <td><input type="text" name="remarks[]" class="form-control"></td>
        `;
    }


    const sidebarButtons = document.querySelectorAll('.sidebar-btn');

    sidebarButtons.forEach(button => {
        button.addEventListener('click', function() {
        sidebarButtons.forEach(btn => btn.classList.remove('active')); // Remove active from all
        this.classList.add('active'); // Add active to clicked one
        });
    });

    function confirmSubmit() {
        Swal.fire({
            title: 'Submit RIS?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!'
        }).then(result => {
            if (result.isConfirmed) {
                submitFormAJAX();
            }
        });
    }

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
                Swal.fire('Success!', data.message, 'success');
                form.reset();
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(() => {
            Swal.fire('Oops!', 'Something went wrong.', 'error');
        });
    }

    $(document).on('input', '.stock_no', function () {
    var row = $(this).closest('tr');
    var stockCode = $(this).val();

    $.get("../get_description.php", { stock_code: stockCode }, function (response) {
        row.find(".items").val(response.item);
        row.find(".descd").val(response.description);
    });
});

// function confirmSubmit() {
//     // Check if all required fields are filled
//     const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
//     let isValid = true;

//     requiredFields.forEach(field => {
//         if (!field.value) {
//             isValid = false;
//         }
//     });

//     if (!isValid) {
//         Swal.fire({
//             title: 'Error!',
//             text: 'Please fill out all required fields.',
//             icon: 'error',
//             confirmButtonText: 'Ok'
//         });
//         return; // Prevent form submission if fields are empty
//     }

//     Swal.fire({
//         title: 'Submit RIS?',
//         icon: 'question',
//         showCancelButton: true,
//         confirmButtonText: 'Yes, submit it!'
//     }).then(result => {
//         if (result.isConfirmed) {
//             submitFormAJAX();
//         }
//     });
// }



    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = '../index.php';
            }
        });
    }

    document.getElementById('helpBtn').addEventListener('click', () => {
    Swal.fire({
        title: 'Need Help?',
        html: `
            <p>View the full code guide <a href="../codes.html" target="_blank" style="color: blue; text-decoration: underline;">here</a>.</p>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!'
    });
});
</script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color:rgb(11, 26, 48);
        color: #333;
  }

    .psa-header {
        background-color: rgb(21, 83, 150);
        color: white;
        font-family: 'Times New Roman', Times, serif;
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
  h2 {
    font-family: 'Cal Sans', sans-serif;
    color: white;
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
        margin-left: 25px;
        padding: 30px;
    }

    .form-section {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-section div {
        flex: 1;
        min-width: 300px;
    }

    table th, table td {
        text-align: center;
    }
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
.card {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.card .form-section {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.card .form-section div {
    flex: 1;
    min-width: 300px;
}

</style>
</body>
</html>
