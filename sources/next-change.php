<?php
$domainSource = 'https://www.nexxt-change.org';
$xpath = crawl($source_url);
$count = 0;
while ($count < $crawl_count) {
    // $titles = $xpath->evaluate('//ol[@class="row"]//li//article//h3/a');
    $titles = $xpath->evaluate('//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header/h4/a');
    $entryDates = $xpath->evaluate('//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header//div//p[@class="inserat-select"]/span');
    $briefs = $xpath->evaluate('//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]/p');
    $linkSource = $xpath->evaluate('//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header/h4/a');



    foreach ($titles as $key => $title) {
        if ($count == $crawl_count) {
            break;
        }

        $data[$key]['title'] = $title->textContent . PHP_EOL;
        $data[$key]['source_domain'] = $domainSource;
        $data[$key]['source_link'] = $linkSource[$key]->getAttribute("href") . PHP_EOL;
        $data[$key]['source_url'] = $data[$key]['source_domain'] . $data[$key]['source_link'];
        $data[$key]['entry_dates'] = date("Y-m-d", strtotime($entryDates[$key]->textContent)) . PHP_EOL;
        $data[$key]['brief_description'] = $briefs[$key]->textContent . PHP_EOL;

        $xpathDetails = crawl($data[$key]['source_url']);

        $details = $xpathDetails->evaluate('//div[@id="inserat-detail"]/p/node()');
        $details_arr = iterator_to_array($details);
        $details_formatted = '';
        foreach ($details_arr as $detail_key => $value) {
            $details_formatted .= '<p>';
            $details_formatted .= $value->textContent;
            $details_formatted .= '</p>';
        }

        $data[$key]['detailed_description'] = $details_formatted . PHP_EOL;


        $branches = $xpathDetails->evaluate('//*[@id="inserat-detail"]/div[2]/dl/dd[2]/ul/node()');
        $branches_arr = iterator_to_array($branches);
        $branches_formatted = '<ul>';
        foreach ($branches_arr as $detail_key => $value) {
            if (trim($value->textContent) != '') {
                $branches_formatted .= '<li>';
                $branches_formatted .= $value->textContent;
                $branches_formatted .= '</li>';
            }
        }
        $branches_formatted .= '</ul>';
        // echo '<h6>Branches: </h6>';
        $data[$key]['branches'] =  $branches_formatted . PHP_EOL;
        // echo '<br>';
        // die;

        $more_info = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');
        // $location = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');
        // $branches = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');
        // $employees = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');
        // $annual_sales = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');
        // $price = $xpathDetails->evaluate('//div[@id="inserat-detail"]//div[@class="detail-item details-unternehmen"]//dl//dd');

        $data[$key]['location'] = $more_info[0]->textContent . PHP_EOL;
        $data[$key]['employees'] = $more_info[2]->textContent . PHP_EOL;
        $data[$key]['last_annual_sales'] = $more_info[3]->textContent . PHP_EOL;
        $data[$key]['price'] = $more_info[4]->textContent . PHP_EOL;

        $statement = $pdo->prepare("INSERT INTO companies (company_title, company_brief, company_detail, company_location, company_branches, company_employees, company_annual_sales, company_price, company_entry_date, company_source, company_domain)
                    VALUES (:company_title, :company_brief, :company_detail, :company_location, :company_branches, :company_employees, :company_annual_sales, :company_price, :company_entry_date, :company_source, :company_domain) ");
        $statement->bindValue(':company_title', trim($data[$key]['title']));
        $statement->bindValue(':company_brief', trim($data[$key]['brief_description']));
        $statement->bindValue(':company_detail', trim($data[$key]['detailed_description']));
        $statement->bindValue(':company_location', trim($data[$key]['location']));
        $statement->bindValue(':company_branches', trim($data[$key]['branches']));
        $statement->bindValue(':company_employees', trim($data[$key]['employees']));
        $statement->bindValue(':company_annual_sales', trim($data[$key]['last_annual_sales']));
        $statement->bindValue(':company_price', trim($data[$key]['price']));
        $statement->bindValue(':company_entry_date', trim($data[$key]['entry_dates']));
        $statement->bindValue(':company_source', trim($data[$key]['source_url']));
        $statement->bindValue(':company_domain', trim($data[$key]['source_domain']));
        $statement->execute();

        $count++;
    }

    if ($count % 10 == 0) {
        $page_index_url = $xpath->evaluate('//ul[@class="navIndex clearfix"]//li[@class="forward"]/a');
        $page_index_url_href = $domainSource . $page_index_url[0]->getAttribute("href");
        $xpath = crawl($page_index_url_href);
    }
}
