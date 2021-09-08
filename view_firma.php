<?php

$conn = mysqli_connect("localhost", "root", "optiplex.520", "companies_db") or die("Connection failed");


if (isset($_GET["id"])) {
    $page_id = $_GET["id"];
}

$sql = "SELECT * FROM companies WHERE company_id = {$page_id}";
$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");
if (mysqli_num_rows($result) > 0) {
    while ($company = mysqli_fetch_assoc($result)) {
        $company_id = $company["company_id"];
        $company_title = $company["company_title"];
        $company_brief = $company["company_brief"];
        $company_detail = $company["company_detail"];
        $company_location = $company["company_location"];
        $company_branches = $company["company_branches"];
        $company_employees = $company["company_employees"];
        $company_annual_sales = $company["company_annual_sales"];
        $company_price = $company["company_price"];
        $company_entry_date = $company["company_entry_date"];
        $company_source = $company["company_source"];
        $company_domain = $company["company_domain"];
    }
} else {
    echo "<h2>No Record Found.</h2>";
}
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>

<body>
    <!-- <h1>Firma details</h1> -->
    <div class="container" id="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card mb-5">
                    <div class="card-header">
                        <?php echo 'Date of Entry: ' .  $company_entry_date ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $company_title ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $company_branches ?></h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><?php echo 'Employees: ' .  $company_employees ?></li>
                            <li class="list-group-item"><?php echo 'Last annual turnover: ' .  $company_annual_sales ?></li>
                            <li class="list-group-item"><?php echo 'Asking Price: ' .  $company_price ?></li>
                        </ul>
                        <h6>Detailed description:</h6>
                        <p class="card-text"><?php echo $company_detail ?></p>
                        <hr>
                        <div class="mt-1">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal-<?php echo $key + 1 ?>">View details</button>
                            <a href="<?php echo $company_source ?>" target="_blank" class="btn btn-primary btn-sm">View Original</a>
                        </div>
                    </div>


                    <div class="card-footer text-muted">
                        <?php echo 'Locations: ' .  str_replace(">", "|", $company_location) ?>
                    </div>
                </div>


            </div>
        </div>
    </div>


</body>

</html>