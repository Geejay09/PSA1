<?php
$conn = new mysqli("localhost", "root", "", "dbpsa");

if (!isset($_GET['id'])) {
    header("Location: code.php");
    exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tbl_items WHERE id=$id");

if ($result->num_rows === 0) {
    echo "Item not found!";
    exit();
}

$row = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>

<h2>Edit Item</h2>

<form method="POST">
    <label for="stock_code">Stock Code:</label>
    <input type="text" id="stock_code" name="stock_code" value="<?= htmlspecialchars($row['stock_code']) ?>" required>

    <label for="item">Item:</label>
    <input type="text" id="item" name="item" value="<?= htmlspecialchars($row['item']) ?>" required>

    <label for="descode">Description:</label>
    <input type="text" id="descode" name="descode" value="<?= htmlspecialchars($row['descode']) ?>" required>

    <label for="descode">Unit   :</label>
    <input type="text" id="unit" name="unit" value="<?= htmlspecialchars($row['unit']) ?>" required>

    <div class="btn-container">
        <button type="submit">Update Item</button>
        <a href="codes.php" class="cancel-btn">Cancel</a>
    </div>
</form>

</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stock_code = $_POST['stock_code'];
    $item = $_POST['item'];
    $descode = $_POST['descode'];
    $unit = $_POST['unit'];

    $conn->query("UPDATE tbl_items SET stock_code='$stock_code', item='$item', descode='$descode', unit='$unit' WHERE id=$id");
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Item has been updated successfully.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = 'codes.php';
        });
    </script>";
}
?>

<style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 40px;
            background-color: #f7f9fc;
        }

        h2 {
            text-align: center;
            color: #041C32;
            margin-bottom: 30px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: 500;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn-container {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        button {
            background-color: #74f9c4;
            border: none;
            color: #000;
            padding: 10px 22px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #5ee6b7;
        }

        .cancel-btn {
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background 0.3s;
        }

        .cancel-btn:hover {
            background-color: #bbb;
        }
    </style>
</html>
