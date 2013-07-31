<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */

header("Content-type: text/csv");  
header("Cache-Control: no-store, no-cache");  
header('Content-Disposition: attachment; filename="ABPO-Report-'. date("Y-m-d").'.csv"');  
  
$outstream = fopen("php://output",'w');  
  
$data = ab_press_getAllExperiment();
$heders = ['Experiment ID', 'Name', 'Description', 'Start Date', 'End Date', 'Total Visitors', 'Goal', 'Goal Type', 'URL'];
$spacer = ['', '', '', '', '', '', '', '', ''];

$variationheders = ['Variation', 'Convertion Rate', 'Imporvement', 'Chance To Beat Original', 'Convertions', 'Visiors', '', '', ''];


fputcsv($outstream, $heders, ',', '"');  

foreach( $data as $row )  
{  
	$experiment = [];
	$experiment[] = ucwords($row->id);
	$experiment[] = ucwords($row->description);
	$experiment[] = ucwords($row->description);
	$experiment[] = date("m-d-Y", strtotime($row->start_date));
	$experiment[] = date("m-d-Y", strtotime($row->end_date));
	$experiment[] = ab_press_getTotalVisitors($row);
	$experiment[] = ucwords($row->goal);
	$experiment[] = ucwords($row->goal_type);
	$experiment[] = $row->url;


    fputcsv($outstream, $experiment, ',', '"');  
    $spacer = ['', '', '', '', '', '', '', '', ''];

    fputcsv($outstream,  $spacer , ',', '"');  
    fputcsv($outstream, $variationheders, ',', '"');  

    	$controlArr = [];
    	$controlArr[] = "Control";
    	$controlConvertion = ($row->original_convertions == 0) ? 0 : ab_press_getConvertionRate($row->original_convertions,$row->original_visits);
    	$controlArr[] = $controlConvertion."%";
    	$controlArr[] = "N/A";
    	$controlArr[] = "N/A";
    	$controlArr[] = number_format($row->original_convertions); 
    	$controlArr[] = number_format($row->original_visits);
    	$controlArr[] = "";
    	$controlArr[] = "";
    	$controlArr[] = "";

    fputcsv($outstream, $controlArr , ',', '"');  

    foreach ($row->variations as $variations) {
    	$varrArray = [];
    	$varrArray[] = ucwords($variations->name);
    	$variationConvertion = ($variations->convertions == 0) ? 0 : ab_press_getConvertionRate($variations->convertions,$variations->visits);
    	$varrArray[] = $variationConvertion."%";
    	$varrArray[] = ab_press_getImprovement($controlConvertion, $variationConvertion )."%";
    	$varrArray[] = ab_press_getSignificance($row, $variations )."%"; 
    	$varrArray[] = number_format($variations->convertions); 
    	$varrArray[] = number_format($variations->visits); 
    	$varrArray[] = ''; 
    	$varrArray[] = ''; 
    	$varrArray[] = ''; 

     	fputcsv($outstream, $varrArray , ',', '"');  
    }

    fputcsv($outstream,  $spacer , ',', '"');  
    fputcsv($outstream,  $spacer , ',', '"');  
	fputcsv($outstream,  $spacer , ',', '"'); 
}  
  
fclose($outstream);  
exit;


?>
