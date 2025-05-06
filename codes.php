<?php
$conn = new mysqli("localhost", "root", "", "dbpsa");
$result = $conn->query("SELECT * FROM tbl_items WHERE deleted = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
</head>
<body>

<h1>Stock Item List</h1>

<table>
    <thead>
        <tr>
            <th>Stock Code</th>
            <th>Item</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr id="row-<?= $row['id'] ?>">
            <td><?= htmlspecialchars($row['stock_code']) ?></td>
            <td><?= htmlspecialchars($row['item']) ?></td>
            <td><?= htmlspecialchars($row['descode']) ?></td>
            <td>
                <a href="edit_item.php?id=<?= $row['id'] ?>"><i class="fa fa-pen"></i></a>
                <i class="fa fa-trash" onclick="softDelete(<?= $row['id'] ?>)"></i>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="add_item.php">
    <button class="add">
        <i class="fa fa-plus-circle"></i> Add Item
    </button>
</a>
<script>
function softDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will mark the item as deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('delete_item.php?id=' + id)
                .then(() => {
                    document.getElementById('row-' + id).remove();
                    Swal.fire('Deleted!', 'The item was marked as deleted.', 'success');
                });
        }
    });
}
</script>

</body>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 40px;
            background-color: #f4f7fa;
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
        td .fa {
            cursor: pointer;
            margin: 0 5px;
        }
        td .fa-pen {
            color: #2980b9;
        }
        td .fa-trash {
            color: #e74c3c;
        }
        button.add {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2ecc71;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        button.add {
        background-color: #93f9d0;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 16px;
        color: #222;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: background-color 0.3s ease;
        }

        button.add i {
        margin-right: 8px;
        font-size: 16px;
        }

        button.add:hover {
        background-color: #7ee0bb;
        }

    </style>
</html>
