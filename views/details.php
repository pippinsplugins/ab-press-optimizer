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
?>
<div class="wrap">

	<?php 
		$experiment = getExperiment($_GET['eid']);
		if(!$experiment)
		{
			createMessage("The experiment you selected does not exist!|ERROR");
			header( 'Location: admin.php?page=abpo-experiment' ) ;
			exit();
		}
	?>

	<div class="ab-press-header">
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/ab-logo.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>
	<h2><?php echo ucwords($experiment->name); ?></h2>
	
	<div class="ab-press-nav">
		<a href="admin.php?page=abpo-new" class="button-primary" >Add New</a>
		<a href="admin.php?page=abpo-edit&eid=<?php echo $experiment->id; ?>" class="button" >Edit</a>
		<a href="" class="delete-button " >Delete</a>
	</div>

	<div class="ab-current-test">
		<h2>Experiment Summery</h2>
		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span><?php echo number_format($totalVisitor = getTotalVisitors($experiment)); ?></li>
			<li class="convertions"><span>Convertions</span><?php echo number_format($totalConvertions = getTotalConvertions($experiment));  ?></li>
			<li class="converstionRate"><span>Convertion Rate</span>
				<?php echo ($totalConvertions == 0) ? "0" : getConvertionRate($totalConvertions,$totalVisitor);?>%
			</li>
			<li class="variations"><span>Variations</span><?php echo count($experiment->variations); ?></li>
		</ul>
	</div>
	

	<div class="ab-columns-2">
		<div class="ab-column-content">
			<h3>Description</h3>
			<p><?php echo $experiment->description; ?></p>
			<h3>Goal</h3>
			<p><?php echo $experiment->goal; ?></p>
		</div>
	</div>	

	<div class="ab-columns-2">
		<div class="ab-column-content">
			<h3>Staus</h3>
			<p><?php echo $experiment->status; ?></p>		
			<h3>Experimanet Date</h3>
			<p><?php echo date("m-d-Y", strtotime($experiment->start_date)) ?> - <?php echo date("m-d-Y", strtotime($experiment->end_date)) ?></p>
		</div>
	</div>	

	<h2>Experiments</h2>

	<p>Test ? is beationg out the origanial by +x%</p>

	<table class="widefat">
		<thead>
		    <tr>
		        <th>Variation</th>
		        <th>Convertion Rate</th>       
		        <th>Percentage of Improvement</th>
		        <th>Change To Beat Original</th>       
		        <th>Convertion</th>
		        <th>Visitors</th>
		    </tr>
		</thead>
		<tfoot>
		     <tr>
		        <th>Variation</th>
		        <th>Convertion Rate</th>       
		        <th>Percentage of Improvement</th>
		        <th>Change To Beat Original</th>       
		        <th>Convertion</th>
		        <th>Visitors</th>
		    </tr>
		</tfoot>
		<tbody>

		<tr>
			<th>Control</th> 
			<th>
				<?php echo $controlConvertion = ($experiment->original_convertions == 0) ? 0 : getConvertionRate($experiment->original_convertions,$experiment->original_visits); ?>%
				( &plusmn;<?php echo $controlInterval = getConfidenceInterval($experiment->original_convertions,$experiment->original_visits); ?>)
			</th>
			<th> -- </th>
			<th> -- </th>
			<th><?php echo $experiment->original_convertions; ?></th>
			<th><?php echo $experiment->original_visits; ?></th>
		</tr>

		<?php foreach ($experiment->variations as $variations): ?>

		<tr>
			<th><?php echo ucwords($variations->name); ?></th>
			<th>
				<?php echo $variationConvertion = ($variations->convertions == 0) ? 0 : getConvertionRate($variations->convertions,$variations->visits); ?>% 
				( &plusmn;<?php echo $variationInterval = getConfidenceInterval($variations->convertions,$variations->visits); ?>)</th>
			<th><?php echo getImprovement($controlConvertion, $variationConvertion ) ?>%</th>
			<th><?php echo getSignificance($experiment->original_convertions/$experiment->original_visits, $controlInterval, $variations->convertions/$variations->visits, $variationInterval ); ?>%</th>
			<th><?php echo $variations->convertions; ?></th>
			<th><?php echo $variations->visits; ?></th>
		</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>

	
	<?php echo $a = getConfidenceInterval(48 ,552); ?> <br>
	<?php echo $b = getConfidenceInterval(727 ,5502); ?> <br>
	<?php echo getSignificance(13.21, $b  ,8.7, $a  ); ?>
</div>