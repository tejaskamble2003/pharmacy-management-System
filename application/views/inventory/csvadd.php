<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacigt";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$popupMessage = "CSV data inserted successfully.";
if (isset($_POST["submit"])) {
  // Check if file was uploaded without errors
  if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first line (header) of the CSV file
    fgetcsv($handle);

    // Loop through the CSV file and insert data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
      $medicine_name = $data[0];
      $generic_name = $data[1];
      $medicine_presentation = $data[2];
      $supplier_name = $data[3];

      $sql = "INSERT INTO insert_purchase_info (medicine_name, generic_name, medicine_presentation, supplier_name) VALUES ('$medicine_name', '$generic_name', '$medicine_presentation', '$supplier_name')";
      $conn->query($sql);
    }

    fclose($handle);
    $popupMessage = "CSV data inserted successfully.";
  } else {
    $popupMessage = "Error uploading CSV file.";
  }
}


// Download CSV file
if (isset($_POST["download"])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="csv_data.csv"');

  $output = fopen("php://output", "w");

  $sql = "SELECT medicine_name, generic_name, medicine_presentation, supplier_name FROM insert_purchase_info";
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
  }

  fclose($output);
  exit();
}

$conn->close();
?>

<script>
  // JavaScript function to show popup message
  function showPopup(message) {
    alert(message);
  }
</script>

<section id="breadcrumb">
  <div class="container">
    <ol class="breadcrumb">
      <li><a href="#">Inventory</a></li>
    </ol>
  </div>
</section>

<section id="main">
  <div class="container">

    <div class="row">
      <div class="col-md-3">
        <div class="list-group">
          <a href="index.html" class="list-group-item active main-color-bg">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Inventory</a>
          <a href="<?php echo base_url(); ?>ShowForm/medicine_purchase_info/main" class="list-group-item">
            <span class="	fa fa-capsules" aria-hidden="true"></span> Insert Medicine Info.</a>
          <a href="<?php echo base_url(); ?>ShowForm/medicine_purchase_statement/main" class="list-group-item">
            <span class="fa fa-plus-circle" aria-hidden="true"></span> Purchase Statement</a>
          <a href="<?php echo base_url(); ?>ShowForm/supplier_payment/main" class="list-group-item">
            <span class="fa fa-pills" aria-hidden="true"></span> Supplier Payment</a>
        </div>
      </div>


      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading main-color-bg">
            <h3 class="panel-title">Update Medicine Purchase Information</h3>
          </div>
          <div class="panel-heading">
            <h3 class="panel-title">Select CSV File</h3>
          </div>
          <div class="panel-body">
            <div class="box-body">
              <div class="row">
                <form method="post" enctype="multipart/form-data">
                  <div class="col-sm-3" style="">
                    <label for="date">Upload File</label>
                    <input type="file" name="file" accept=".csv">
                  </div>

                  <div class="col-sm-4" style="margin-top: 17px;">
                    <button type="submit" name="submit" class="pull-left btn btn-primary" onclick="showPopup('<?php echo $popupMessage; ?>')">Upload</button>
                    <!-- /.Panel End -->
                  </div><!-- /.Panel End -->
                </form>
              </div>
              <!-- /.Panel 2nd -->
              <div class="col-md-12">

              </div>


            </div>

          </div> <!-- /.row -->
          <div class="panel-heading">
            <h3 class="panel-title">Download CSV File</h3>
          </div>
          <div class="panel-body">
            <div class="box-body">
              <div class="row">
                <form method="post" enctype="multipart/form-data">

                  <div class="col-sm-4" style="margin-top: 17px;">
                    <button type="submit" name="download" class="pull-left btn btn-primary">Download</button>
                    <!-- /.Panel End -->
                  </div>
                </form><!-- /.Panel End -->
              </div>
            </div>

          </div> <!-- /.Container -->
        </div>
      </div>
    </div>

  </div>
</section>