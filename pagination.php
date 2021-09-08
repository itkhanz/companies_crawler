<?php

$conn = mysqli_connect("localhost", "root", "optiplex.520", "companies_db") or die("Connection failed");

$limit_per_page = 5;

$page = "";
if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
} else {
    $page = 1;
}

$offset = ((int)$page - 1) * $limit_per_page;
if (isset($_POST["search"])) {
    $param = $_POST["search"];
}

$str = '';
if (isset($param) && $param != '') {
    $str .= " WHERE company_title LIKE  '%" . $param . "%'";
}
$sql = "SELECT * FROM companies " . $str . " LIMIT {$offset},{$limit_per_page}";
// die;
$result = mysqli_query($conn, $sql) or die("Query Unsuccessful.");
$output = "";
if (mysqli_num_rows($result) > 0) {
    while ($company = mysqli_fetch_assoc($result)) {
        $formattedLocation = str_replace('>', '|', $company["company_location"]);
        $output .= "<div class='card mb-5'>
        <div class='card-header'>{$company["company_entry_date"]}</div>
        <div class='card-body'>
            <h5 class='card-title'>
            {$company["company_title"]}
            <a href='{$company["company_source"]}' target='_blank'><span class='fas fa-external-link-alt'></span></a>
            </h5>
            <h6 class='card-subtitle mb-2 text-muted'>{$company["company_branches"]}</h6>
            <p class='card-text'>{$company["company_brief"]}</p>
            <ul class='list-group list-group-flush'>
                <li class='list-group-item'>Anzahl Mitarbeiter: {$company["company_employees"]}</li>
                <li class='list-group-item'>Letzter Jahresumsatz in TEUR: {$company["company_annual_sales"]}</li>
                <li class='list-group-item'>Preisvorstellung: {$company["company_price"]}</li>
            </ul>
            <p class='card-text'>Source: {$company["company_domain"]}</p>
            <div class='mt-1'>
                <button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#exampleModal-{$company["company_id"]}'>View details</button>
                <a href='{$company["company_source"]}' target='_blank' class='btn btn-primary btn-sm'>View Original</a>

                <a href='view_firma.php?id={$company["company_id"]}&title={$company["company_title"]}' target='_blank' class='btn btn-secondary btn-sm'>Open</a>

            </div>
        </div>
        <div class='card-footer text-muted'>{$formattedLocation}</div>
    </div>";

        $output .= "<div class='modal fade' id='exampleModal-{$company["company_id"]}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
            <div class='modal-content'>
            <div class='modal-body'>
                <h5>{$company["company_title"]}</h5>
                <h6>Detailed description:</h6>
                <p>{$company["company_detail"]}</p>
                <hr>
                <h6>Branches:</h6>
                <p>{$company["company_branches"]}</p>
             </div>
             <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                <a href='{$company['company_source']}' target='_blank' class='btn btn-primary'>View Original</a>
            </div>
            </div>
        </div>
    </div>";
    }


    $str = "";
    if ($param != '') {
        $str .= " WHERE company_title LIKE  '%" . $param . "%'";
    }
    $sql_total = "SELECT * FROM companies " . $str;
    $records = mysqli_query($conn, $sql_total) or die("Query Unsuccessful.");
    $total_record = mysqli_num_rows($records);
    $total_pages = ceil($total_record / $limit_per_page);

    $output .= '<div id="pagination">';

    if ($page > 1) {
        $prev_page = $page - 1;
        $output .= "<a class='reverse' id='{$prev_page}' href=''><<</a>";
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            $class_name = "active";
        } else {
            $class_name = "";
        }
        $output .= "<a class='{$class_name}' id='{$i}' href=''>{$i}</a>";
    }

    $next_page = $page + 1;
    if ($next_page <= $total_pages) {
        $output .= "<a class='forward' disabled='true' id='{$next_page}' href=''>>></a>";
    }

    $output .= '</div>';

    echo $output;
} else {
    echo "<h2>No Record Found.</h2>";
}
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>

<body>
    <div class="container" id="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            </div>
        </div>
    </div>
</body>

</html> -->