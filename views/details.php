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
		}
	?>

	<div class="ab-press-header">
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/ab-logo.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>
	<h2><?php echo ucwords($experiment->name); ?></h2>
	
	<div class="ab-press-nav">
		<a href="" class="button-primary" >Edit</a>
		<a href="" class="delete-button " >Delete</a>
	</div>

	<div class="ab-current-test">
		<h2>Experiment Summery</h2>
		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span><?php echo $totalVisitor = $experiment->total_visitors; ?></li>
			<li class="convertions"><span>Convertions</span><?php echo $totalConvertions = getTotalConvertions($experiment); ?></li>
			<li class="converstionRate"><span>Convertion Rate</span>
				<?php echo ($totalConvertions == 0) ? 0 : round($totalConvertions/$totalVisitor, 2) ?>%
			</li>
			<li class="variations"><span>Variations</span><?php echo count($experiment->variations); ?></li>
		</ul>
	</div>

	<?php print_r($experiment) ?>
</div>