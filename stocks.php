<?php

//     $stock_dict =  "[{'regularMarketPrice': '208.7', 'regularMarketVolume': '22588870', 'fiftyTwoWeekHigh': '217.64', 'fiftyTwoWeekLow': '132.52', 'longName': 'Microsoft Corporation', 'sector': 'Technology', 'industry': 'Software Infrastructure', 'fullTimeEmployees': '163000', 'strongBuy': 14, 'buy': 13, 'hold': 6, 'sell': 0, 'strongSell': 1, 'trailingPE': '36.232635', 'forwardPE': '28.43324', 'pegRatio': 
// '2.15', 'recommendationMean': '1.7', 'targetMeanPrice': '226.93', 'PROFIT_MARGIN': 30.962, 'operatingMargins': 37.03, 'returnOnAssets': 11.261000000000001, 'returnOnEquity': 40.14, 'TotalAssets': '301311', 'TotalLiabilities': '183007', 'TAssets_V_TLiabilities': 1.646445217942483, 'revenue1': 33720000000, 'revenue2': 33060000000, 'revenue3': 36910000000, 'revenue4': 35020000000, 'revenue5': 38030000000, 'eps1': 1.37, 'eps2': 1.38, 'eps3': 1.51, 'eps4': 1.4, 'eps5': 1.46, 'EPS_EQUEL': 0, 'EPS_MORE': 5, 'EPS_LESS': 0, 'REVENUE_EQUEL': 0, 'REVENUE_MORE': 5, 'REVENUE_LESS': 0, 'revenue6': 35750000000.0, 'eps6': 1.54, 'return_on_equity_industry': '12.4', 'return_on_assets_industry': '5.61', 'operating_margin_industry': '12.04', 'profit_margin_industry': '6.01', 'sales_q_q_industry': '5.09', 'sales_5y_industry': '11.55', 'rsi': '52.28', 'eps_this_y_precent': '13.40', 'eps_next_y_precent': '13.59', 'eps_next_5y_precent': '15.00%', 'sales_past_5y_precent': '8.90', 'sales_q_q': '12.80', 'SMA20': '0.48', 'SMA50': '3.11', 'SMA200': '20.45', 'InsiderTrans': '-4.21', 'institutional_investors0': '5476', 'institutional_investors1': '5482', 'institutional_investors2': '5658', 'institutional_investors3': 
// '5504', 'institutional_investors4': '5496', 'institutional_investors5': '5558'}]";



if (isset($_GET["stock_dict"])) {

    $data = json_decode('[' . str_replace("'", '"', $_GET["stock_dict"]) . ']', true)[0];
    $keys = 'INSERT INTO stock_analysis (';
    $values = ' VALUES (';
    $numItems = count($data);
    $i = 0;
    foreach ($data as $key => $value) {
        if (++$i === $numItems) {
            $keys .= $key;
            $values .= '"' . $value . '"';
        } else {
            $keys .= $key . ',';
            $values .= '"' . $value . '"' . ',';
        }
    }
    $keys .= ')';
    $values .= ')';

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $db = "trading";

    $conn = new mysqli($hostname, $username, $password, $db) or die("Database connection error." . $conn->connect_error);
    mysqli_set_charset($conn, "utf8");
    $sql = $keys.$values;
    $result = $conn->query($sql);
    echo $result;

} else {
    echo 'Not Today';
}
