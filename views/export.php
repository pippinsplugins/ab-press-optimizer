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
$heders = ['Name', 'Description', 'Start Date', 'End Date', 'Total Visitors', 'Goal', 'Goal Type', 'URL'];
fputcsv($outstream, $heders, ',', '"');  

foreach( $data as $row )  
{  
	unset($row->variations);
	unset($row->id);
	unset($row->status);
	unset($row->original);
	unset($row->original_visits);
	unset($row->original_convertions);
	unset($row->date_created);
	$row = (array) $row;
    fputcsv($outstream, $row, ',', '"');  
}  
  
fclose($outstream);  
exit;


?>
