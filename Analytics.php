<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

$analytics = initializeAnalytics();
$response = getReport($analytics);
$results = listResults($response);
renderTable($results);

/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */

function initializeAnalytics()
{
    // Use the developers console and download your service account
    // credentials in JSON format. Place them in this directory or
    // change the key file location if necessary.
    $KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("Analytics Reporting");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}

/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */

function getReport($analytics)
{
    $VIEW_ID = $_GET["view_id"];
    $START_DATE = $_GET["start_date"];
    $END_DATE = $_GET["end_date"];

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate($_GET["start_date"], FILTER_SANITIZE_STRING);
    $dateRange->setEndDate($_GET["end_date"], FILTER_SANITIZE_STRING);
    

    // Create the Metrics object
    $pageviews = new Google_Service_AnalyticsReporting_Metric();
    $pageviews->setExpression("ga:pageviews");
    $pageviews->setAlias("page_views");
    
    $uniquePageviews = new Google_Service_AnalyticsReporting_Metric();
    $uniquePageviews->setExpression("ga:uniquePageviews");
    $uniquePageviews->setAlias("unique_page_views");

    $avgPageDuration = new Google_Service_AnalyticsReporting_Metric();
    $avgPageDuration->setExpression("ga:avgTimeOnPage");
    $avgPageDuration->setAlias("avg_time_on_page");

    $entrances = new Google_Service_AnalyticsReporting_Metric();
    $entrances->setExpression("ga:entrances");
    $entrances->setAlias("entrances");

    $bouncerate = new Google_Service_AnalyticsReporting_Metric();
    $bouncerate->setExpression("ga:bouncerate");
    $bouncerate->setAlias("bounce_rate");

    $exitrate = new Google_Service_AnalyticsReporting_Metric();
    $exitrate->setExpression("ga:exitrate");
    $exitrate->setAlias("exit_rate");

    $pagevalue = new Google_Service_AnalyticsReporting_Metric();
    $pagevalue->setExpression("ga:pagevalue");
    $pagevalue->setAlias("page_value");

    //Create the browser dimension.
    $path = new Google_Service_AnalyticsReporting_Dimension();
    $path->setName("ga:pagePath");

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($VIEW_ID);
    $request->setDateRanges($dateRange);
    $request->setDimensions(array($path));
    $request->setMetrics(array($pageviews, $uniquePageviews, $entrances, $avgPageDuration, $bouncerate, $exitrate, $pagevalue));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests( array( $request) );

    return $analytics->reports->batchGet( $body );
}

/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */

function listResults($reports) {
    $results = [];

    for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
      $report = $reports[ $reportIndex ];
      $header = $report->getColumnHeader();
      $dimensionHeaders = $header->getDimensions();
      $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
      $rows = $report->getData()->getRows();
  
      for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
        $row = $rows[$rowIndex];
        $dimensions = $row->getDimensions();
        $metrics = $row->getMetrics();
        for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
            $page = $dimensions[$i];
        }
  
        for ($j = 0; $j < count($metrics); $j++) {
          $values = $metrics[$j]->getValues();
          for ($k = 0; $k < count($values); $k++) {
            $entry = $metricHeaders[$k];
            $results[$rowIndex]['page'] = $page;
            $results[$rowIndex][$entry->getName()] = $values[$k];
          }
        }
      }
    }

    return $results;
}

function renderTable($results)
{
    $index = 0;
    foreach($results as $result)
    {
        echo '<tr>'.'<td>'.$result['page'].'</td>';
        echo '<td>'.$result['page_views'].'</td>';
        echo '<td>'.$result['unique_page_views'].'</td>';
        echo '<td>'.$result['entrances'].'</td>';
        echo '<td>'.$result['avg_time_on_page'].'</td>';
        echo '<td>'.$result['bounce_rate'].'</td>';
        echo '<td>'.$result['exit_rate'].'</td>';
        echo '<td>'.$result['page_value'].'</td>'.'</tr>';   
    }
    $index++;
}

function insertDB($results, $conn)
{
    $table = '';

    foreach($results as $result)
    {
        $page = $result['page'];
        $pageviews = $result['page_views'];
        $uniquepageviews = $result['unique_page_views'];
        $entrances = $result['entrances'];
        $avgPageDuration = $result['avg_time_on_page'];
        $bouncerate = $result['bounce_rate'];
        $exitrate = $result['exit_rate'];
        $pagevalue = $result['page_value'];

        $sql = "INSERT INTO $table (page, pageviews, unique_page_views, entrances, avg_page_duration, bounce_rate, exit_rate, page_value) VALUES ($page, $pageviews, $uniquepageviews, $entrances, $avgPageDuration, $bouncerate, $exitrate, $pagevalue)";
        $save = mysqli_query($conn, $sql);
    }
}