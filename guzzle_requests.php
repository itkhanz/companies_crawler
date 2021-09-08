<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=companies_db', 'root', 'optiplex.520');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $statement = $pdo->prepare('DELETE FROM companies');
// $statement->execute();

$search = $_GET['search'] ?? '';


// $statement->execute();
// $companies = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/45da8c226f.js" crossorigin="anonymous"></script>

</head>

<body>
    <?php
    # scraping books to scrape: https://books.toscrape.com/
    require 'vendor/autoload.php';
    # $url = 'https://books.toscrape.com/';
    $url = 'https://www.nexxt-change.org/SiteGlobals/Forms/Verkaufsangebot_Suche/Verkaufsangebotssuche_Formular.html';

    function crawl($url)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->get($url);
        $htmlString = (string) $response->getBody();
        //add this line to suppress any warnings
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);
        return $xpath;
    }

    $data = [];

    $crawl_count = 10;
    require_once 'sources/sources.php';

    // foreach ($sources as $source_domain => $source_url) {

    //     switch ($source_domain) {
    //         case "nextt-change":
    //             require_once 'sources/next-change.php';
    //             break;
    //         case "dub":
    //             require_once 'sources/dub.php';
    //             break;
    //         case "biz-trade":
    //             require_once 'sources/biz-trade.php';
    //             break;
    //         case "concess":
    //             require_once 'sources/concess.php';
    //             break;
    //         default:
    //             echo "Invalid source";
    //     }
    // }

    ?>
    <div id="testing"></div>
    <div class="container" id="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search" value="<?php echo $search ?>" class="form-control" placeholder="Search for companies">
                        <button class="btn btn-secondary" type="submit" id="button-search">Search</button>
                    </div>
                    </form>

                    <div id="companies-data">
                        <!-- <?php foreach ($companies as $key => $company) { ?>

                        


                    <?php }; ?> -->
                    </div>

            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            loadTable();

            function loadTable(page = 1, search_term = '') {
                //console.log(search_term);
                $.ajax({
                    url: "pagination.php",
                    type: "POST",
                    data: {
                        page_no: page,
                        search: search_term,
                    },
                    success: function(data) {
                        $("#companies-data").html(data);
                    }
                });
            }


            //Search Code
            $(document).on("click", "#pagination a", function(e) {
                e.preventDefault();
                var page_id = $(this).attr("id");
                var search_term = $('#search').val();
                loadTable(page_id, search_term);
            })

            //Pagination Code
            $(document).on("click", "#button-search", function(e) {
                e.preventDefault();
                var search_term = $('#search').val();
                loadTable(1, search_term);

            })

        });
    </script>
</body>

</html>