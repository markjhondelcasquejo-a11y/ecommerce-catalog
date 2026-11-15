<?php
// delete.php - Delete Product Handler (Optimized)
require_once 'db_connect.php';
session_start();

// Check if 'id' parameter is present in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Check if the product exists before deleting
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $delete_stmt->bind_param("i", $product_id);

        if ($delete_stmt->execute()) {
            header("Location: catalog.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $delete_stmt->close();
    } else {
        header("Location: catalog.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: catalog.php");
    exit();
}
?>