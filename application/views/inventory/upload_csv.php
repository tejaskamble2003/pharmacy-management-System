<?php
if ($_FILES["file"]["error"] == UPLOAD_ERR_OK && $_FILES["file"]["type"] == "text/csv") {
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");

    // Your database connection code goes here

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $medicine_name = $data[0];
        $generic_name = $data[1];
        $medicine_presentation = $data[2];
        $supplier_name = $data[3];

        // Insert data into the database (replace placeholders with actual connection and query)
        // $sql = "INSERT INTO csv_data (name, email, phone) VALUES ('$name', '$email', '$phone')";
        $sql = "SELECT medicine_name, generic_name, medicine_presentation, supplier_name FROM insert_purchase_info";
        $conn->query($sql);
    }

    fclose($handle);
    echo "CSV data uploaded successfully.";
} else {
    echo "Error uploading CSV file.";
}
?>
