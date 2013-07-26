<?php

header("Content-type: text/csv");  
header("Cache-Control: no-store, no-cache");  
header('Content-Disposition: attachment; filename="ABPO-Report-'. date("Y-m-d").'.csv"');  
  
$outstream = fopen("php://output",'w');  
  
$data = getAllExperiment();
$heders = ['Name', 'Description', 'Start Date', 'End Date', 'End Date', 'Total Visitors', 'Goal', 'Goal Type', 'URL'];
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
?>
<div class="wrap">

	<?php screen_icon('ab-press-optimizer'); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- TODO: Provide markup for your options page here. -->
asdfasdf
</div>