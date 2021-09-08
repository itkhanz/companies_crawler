<?php


// $companies['url'] = 'https://www.nexxt-change.org/SiteGlobals/Forms/Verkaufsangebot_Suche/Verkaufsangebotssuche_Formular.html';
// $companies = array();
// $companies['nexxt-change.org'] = array(
//     "domainSource" => 'https://www.nexxt-change.org',

//     "crawl_url" => 'https://www.nexxt-change.org/SiteGlobals/Forms/Verkaufsangebot_Suche/Verkaufsangebotssuche_Formular.html',

//     "title_crawl_str" => '//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header/h4/a',

//     "date_crawl_str" => '//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header//div//p[@class="inserat-select"]/span',

//     "brief_crawl_str" => '//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]/p',

//     "linkSource_crawl_str" => '//ol[@class="inserate-liste"]//li//article//div[@class="inserate-item-content"]//div[@class="inserate-item-content-wrapper"]//header/h4/a',

//     "date_crawl_str" => "next date",

//     "date_crawl_str" => "next date",

//     "date_crawl_str" => "next date",

//     "date_crawl_str" => "next date",
// );

$sources = array();
$sources['nextt-change'] = 'https://www.nexxt-change.org/SiteGlobals/Forms/Verkaufsangebot_Suche/Verkaufsangebotssuche_Formular.html';
$sources['dub'] = 'https://www.dub.de/unternehmensboerse/unternehmen-kaufen//?tx_enterprisermarket_searchoffer%5Baction%5D=index&tx_enterprisermarket_searchoffer%5Bcontroller%5D=SearchOffer&tx_enterprisermarket_searchoffer%5Bload%5D=1&cHash=44e27fa7f0bd6e9a3ee786a59a640ddc';
foreach ($sources as $source_domain => $source_url) {
    echo $source_domain . '<br>';
}



// $companies = array();
// $companies['nexxt'] = array(
//     "url" => 'https://www.nexxt-change.org/SiteGlobals/Forms/Verkaufsangebot_Suche/Verkaufsangebotssuche_Formular.html',
//     "title_crawl_str" => "next title",
//     "brief_crawl_str" => "next brief",
//     "date_crawl_str" => "next date",

// );

// $companies['kernn'] = array(
//     "title_crawl_str" => "kernn  title",
//     "brief_crawl_str" => "kernn  brief",
//     "date_crawl_str" => "kernn date",

// );

// echo '<pre>';
// print_r($companies);
// echo '</pre>';

// // echo $companies['nexxt']['title_crawl_str'] . '<br>';
// // echo $companies['kernn']['title_crawl_str'] . '<br>';

// foreach ($companies as $source => $value) {
//     echo $source . '<br>';
//     if (is_array($value)) {
//         foreach ($value as $crawl_title => $crawl_val) {
//             echo $crawl_val . '<br>';
//         }
//     } else {
//         echo $value . '<br>';
//     }
// }







// $student_two["Maths"] = 95;
// $student_two["Physics"] = 90;
// $student_two["Chemistry"] = 96;
// $student_two["English"] = 93;
// $student_two["Computer"] = 98;

// /* Looping through an array using foreach */
// echo "Looping using foreach: <br>";
// foreach ($student_two as $subject => $marks) {
//     echo "Student one got " . $marks . " in " . $subject . "<br>";
// }
