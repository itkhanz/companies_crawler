<?php
$domainSource = 'https://www.concess.de';
$xpath = crawl($source_url);
$count = 0;
while ($count < $crawl_count) {

    // $titles = $xpath->evaluate('//ol[@class="row"]//li//article//h3/a');
    $titles = $xpath->evaluate('//*[@id="c42"]/div/div/div/div[4]/div/div/div[1]/h2/a');
    $linkSource = $xpath->evaluate('//*[@id="c42"]/div/div/div/div[4]/div/div/div[1]/h2/a');
    $entryDates = 'published date not available';
    $briefs = $xpath->evaluate('//*[@id="c42"]/div/div/div/div[4]/div/div/div[1]/p');


    foreach ($titles as $key => $title) {
        if ($count == $crawl_count) {
            break;
        }

        echo '<h5>Title: </h5>';
        echo $data[$key]['title'] = $titles->textContent . PHP_EOL;
        echo '<br>';
        $count++;
        continue;


        $data[$key]['source_domain'] = $domainSource;
        $data[$key]['source_link'] = $linkSource[$key]->getAttribute("href") . PHP_EOL;
        $data[$key]['source_url'] = $data[$key]['source_domain'] . $data[$key]['source_link'];

        echo '<h6>Dated: </h6>';
        echo $data[$key]['entry_dates'] = $entryDates;
        echo '<br>';

        echo '<h6>Brief Description: </h6>';
        echo $data[$key]['brief_description'] = $briefs[$key]->textContent . PHP_EOL;
        echo '<br>';

        $annual_sales = $xpath->evaluate('//*[@id="c42"]/div/div/div/div[8]/div/div/div[2]/div[1]/div[2]/p/span[2]');
        echo '<h6>Annual Sales: </h6>';
        echo $data[$key]['last_annual_sales'] = trim(iterator_to_array($annual_sales)[0]->textContent) . PHP_EOL;
        echo '<br>';

        // echo $data[$key]['source_url'];
        $xpathDetails = crawl(trim($data[$key]['source_url']));

        $branches = $xpathDetails->evaluate('//*[@id="c48"]/div/div/div/div/div[2]/div[1]/ul/li[1]/span[2]');
        echo '<h6>Branches: </h6>';
        echo  $data[$key]['branches'] = iterator_to_array($branches)[0]->textContent;
        echo '<br>';


        $employees = $xpathDetails->evaluate('//*[@id="c48"]/div/div/div/div/div[2]/div[1]/ul/li[5]/span[2]');
        echo '<h6>No. of Employees: </h6>';
        echo $data[$key]['employees'] = iterator_to_array($employees)[0]->textContent . PHP_EOL;
        echo '<br>';

        $price = $xpathDetails->evaluate('//*[@id="c48"]/div/div/div/div/div[2]/div[1]/ul/li[3]/span[2]');
        echo '<h6>Price: </h6>';
        echo $data[$key]['price'] = iterator_to_array($price)[0]->textContent . PHP_EOL;
        echo '<br>';

        $location = $xpathDetails->evaluate('//*[@id="c48"]/div/div/div/div/div[2]/div[1]/ul/li[4]/span[2]');
        echo '<h6>Location: </h6>';
        echo $data[$key]['location'] = iterator_to_array($location)[0]->textContent . PHP_EOL;
        echo '<br>';


        echo '<h6>Details: </h6>';
        $details = $xpathDetails->evaluate('//*[@id="c48"]/div/div/div/div/div[1]/div[1]/node()');
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
        echo $data[$key]['detailed_description'] = $details_formatted . PHP_EOL;
        echo '<br>';
        // die;


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
        echo '<hr>';
    }

    // if ($count % 10 == 0) {
    //     $page_index_url = $xpath->evaluate('//ul[@class="navIndex clearfix"]//li[@class="forward"]/a');
    //     $page_index_url_href = $domainSource . $page_index_url[0]->getAttribute("href");
    //     $xpath = crawl($page_index_url_href);
    // }
}

die;
