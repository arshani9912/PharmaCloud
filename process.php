<?php
session_start();
include('connect.php');

if (isset($_POST['create'])) {
    // Get form values and sanitize
    $brand_name = mysqli_real_escape_string($conn, $_POST['brand name']);       // Brand Name
    $generic_name = mysqli_real_escape_string($conn, $_POST['generic name']);     // Generic Name
    $medicine_category = mysqli_real_escape_string($conn, $_POST['medicine category']);         // Medicine Type
    $company_name = mysqli_real_escape_string($conn, $_POST['company name']); // Company Name
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry date']); //Expiry Date

    // Validate required fields
    if (empty($brand_name) || empty($generic_name) || empty($medicine_category) || empty($company_name) || empty($expiry_date)) {
        $_SESSION['create'] = "Please fill all fields!";
        header("Location: add_medicine.php");
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO medicines (brand name, generic name, medicine category, company name, expiry date) 
            VALUES ('$brand_name', '$generic_name', '$medicine_category', '$company_name','$expiry_date')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['create'] = "Medicine added successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['create'] = "Error: " . mysqli_error($conn);
        header("Location: add_medicine.php");
        exit();
    }
} else {
    header("Location: add_medicine.php");
    exit();
}
?>
