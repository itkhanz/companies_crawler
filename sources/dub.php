<?php
$domainSource = 'https://www.dub.de';
$xpath = crawl($source_url);
$count = 0;
while ($count < $crawl_count) {

    // $titles = $xpath->evaluate('//ol[@class="row"]//li//article//h3/a');
    $titles = $xpath->evaluate('//div[@class="searchResponsiveResults"]//div[@class="gi three-quarters information"]/h2/a');
    $linkSource = $xpath->evaluate('//div[@class="searchResponsiveResults"]//div[@class="gi three-quarters information"]/h2/a');
    $entryDates = 'published date not available';
    $briefs = $xpath->evaluate('//div[@class="searchResponsiveResults"]//div[@class="gi one-whole description"]/p');
    // $branches = $xpath->evaluate('//*[@id="c44"]/div/div/form/div[6]/div[1]/div[2]/div[1]/p[1]');

    foreach ($titles as $key => $title) {
        if ($count == $crawl_count) {
            break;
        }

        $data[$key]['title'] = $title->textContent . PHP_EOL;

        $data[$key]['source_domain'] = $domainSource;
        $data[$key]['source_link'] = $linkSource[$key]->getAttribute("href") . PHP_EOL;
        $data[$key]['source_url'] = $data[$key]['source_domain'] . $data[$key]['source_link'];

        // echo '<h5>Title: </h5>';
        // echo "<a href='{$data[$key]['source_url']}'> {$data[$key]['title']}</a>";
        // echo '<br>';

        // echo '<h6>Dated: </h6>';
        $data[$key]['entry_dates'] = $entryDates;
        // echo '<br>';

        // echo '<h6>Brief Description: </h6>';
        $data[$key]['brief_description'] = $briefs[$key]->textContent . PHP_EOL;
        // echo '<br>';


        // echo '<h6>Branches: </h6>';
        // echo  $data[$key]['branches'] = str_replace('Branche:', '', $branches[$key]->textContent);
        // echo '<br>';



        $xpathDetails = crawl($data[$key]['source_url']);
        $branches = $xpathDetails->evaluate('//div[@id="singleOfferIndex"]/div[3]/div[1]//div[10]//div//ul/li/a/node()');
        $branches_arr = iterator_to_array($branches);
        $branches_formatted = '<ul>';
        foreach ($branches_arr as $detail_key => $value) {
            $branches_formatted .= '<li>';
            $branches_formatted .= $value->textContent;
            $branches_formatted .= '</li>';
        }
        $branches_formatted .= '</ul>';
        // echo '<h6>Branches: </h6>';
        $data[$key]['branches'] =  $branches_formatted . PHP_EOL;
        // echo '<br>';
        // die;

        //*[@id="singleOfferIndex"]/div[3]/div[1]/div[4]/div[2]/p
        $employees = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[4]/div[2]/p');

        // echo '<h6>No. of Employees: </h6>';
        $data[$key]['employees'] = trim(iterator_to_array($employees)[0]->textContent) . PHP_EOL;
        // echo '<br>';

        $annual_sales = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[2]/div[2]/p');
        // echo '<h6>Annual Sales: </h6>';
        $data[$key]['last_annual_sales'] = trim(iterator_to_array($annual_sales)[0]->textContent) . PHP_EOL;
        // echo '<br>';

        $price = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[6]/div[2]/p');
        // echo '<h6>Price: </h6>';
        $data[$key]['price'] = trim(iterator_to_array($price)[0]->textContent) . PHP_EOL;
        // echo '<br>';

        $location_country_DOM = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[1]/div[2]');
        $location_place_DOM = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[1]/div[4]');

        // $array = iterator_to_array($location_country_DOM);
        // echo '<pre>';
        // // print_r($array);
        // print_r($array[0]->textContent);
        // echo '</pre>';
        // echo trim($array[0]->textContent) . "<br>";
        // die;

        // echo '<pre>';
        // print_r($location);
        // echo '</pre>';
        // die;

        $location_country_array = iterator_to_array($location_country_DOM);
        $location_place_array = iterator_to_array($location_place_DOM);

        $location_country = trim($location_country_array[0]->textContent);
        $location_place = trim($location_place_array[0]->textContent);


        $location = $location_place . ' ' . $location_country;

        // echo '<h6>Location: </h6>';
        $data[$key]['location'] = $location . PHP_EOL;
        // echo '<br>';


        // echo '<h6>Details: </h6>';
        $details = $xpathDetails->evaluate('//*[@id="singleOfferIndex"]/div[3]/div[1]/div[9]/div/p/node()');
        $details_arr = iterator_to_array($details);
        // echo '<pre>';
        // print_r(iterator_to_array($details));
        // echo '</pre>';

        $details_formatted = '';
        foreach ($details_arr as $detail_key => $value) {
            $details_formatted .= '<p>';
            $details_formatted .= $value->textContent;
            $details_formatted .= '</p>';
        }
        // print_r($details_formatted);
        $data[$key]['detailed_description'] = $details_formatted . PHP_EOL;
        // echo '<br>';



        $statement = $pdo->prepare("INSERT INTO companies (company_title, company_brief, company_detail, company_location, company_branches, company_employees, company_annual_sales, company_price, company_source, company_domain)
                    VALUES (:company_title, :company_brief, :company_detail, :company_location, :company_branches, :company_employees, :company_annual_sales, :company_price, :company_source, :company_domain) ");
        $statement->bindValue(':company_title', trim($data[$key]['title']));
        $statement->bindValue(':company_brief', trim($data[$key]['brief_description']));
        $statement->bindValue(':company_detail', trim($data[$key]['detailed_description']));
        $statement->bindValue(':company_location', trim($data[$key]['location']));
        $statement->bindValue(':company_branches', trim($data[$key]['branches']));
        $statement->bindValue(':company_employees', trim($data[$key]['employees']));
        $statement->bindValue(':company_annual_sales', trim($data[$key]['last_annual_sales']));
        $statement->bindValue(':company_price', trim($data[$key]['price']));
        // $statement->bindValue(':company_entry_date', trim($data[$key]['entry_dates']));
        $statement->bindValue(':company_source', trim($data[$key]['source_url']));
        $statement->bindValue(':company_domain', trim($data[$key]['source_domain']));
        $statement->execute();

        $count++;
        // echo '<hr>';
    }

    // if ($count % 10 == 0) {
    //     $page_index_url = $xpath->evaluate('//ul[@class="navIndex clearfix"]//li[@class="forward"]/a');
    //     $page_index_url_href = $domainSource . $page_index_url[0]->getAttribute("href");
    //     $xpath = crawl($page_index_url_href);
    // }
}

// die;
