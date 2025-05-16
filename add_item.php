<?php
// Include DB connection
$conn = new mysqli("localhost", "root", "", "dbpsa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST
$showSuccess = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_code = $_POST["stock_code"];
    $item = $_POST["item"];
    $descode = $_POST["descode"];
    $unit = $_POST['unit'];

    $sql = "INSERT INTO tbl_items (stock_code, item, descode, unit) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $stock_code, $item, $descode, $unit);

    if ($stmt->execute()) {
        $showSuccess = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>

<?php if ($showSuccess): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Added',
        text: 'The item was successfully added.',
        confirmButtonColor: '#3085d6',
    }).then(() => {
        window.location.href = 'codes.php';
    });
</script>
<?php endif; ?>

<div class="form-container">
    <h3 class="mb-4">Add New Stock Item</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Stock Code</label>
            <input type="text" class="form-control" name="stock_code" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" class="form-control" name="item" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" name="descode" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" class="form-control" name="unit" required>
        </div>
        <button type="submit" class="btn btn-add">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5V7.5H11.5a.5.5 0 0 1 0 1H8.5V11.5a.5.5 0 0 1-1 0V8.5H4.5a.5.5 0 0 1 0-1H7.5V4.5A.5.5 0 0 1 8 4z"/>
            </svg>
            Add Item
        </button>
        <a href="codes.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>

</body>

<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-add {
            background-color: #74f9c4;
            color: #000;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px 25px;
            transition: background 0.3s;
        }

        .btn-add:hover {
            background-color: #5ee6b7;
        }

        .btn-cancel {
            background-color: #e0e0e0;
            color: #000;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px 25px;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background-color: #d3d3d3;
        }
    </style>
</html>
