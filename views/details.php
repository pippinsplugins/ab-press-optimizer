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
		$experiment = ab_press_getExperiment($_GET['eid']);
	?>
	
	<?php
		if(isset($_SESSION['message']))
		{
			$message = explode("|", $_SESSION['message']);
			if(count($message) > 1 )
				echo "<div id='message' class=' below-h2 error'><p>".$message[0]."</p></div>";
			else
				echo "<div id='message' class='updated below-h2'><p>".$message[0]."</p></div>";
			ab_press_deleteMessage();
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
		<a href="admin.php?page=abpo-delete&eid=<?php echo $_GET['eid']; ?>" class="delete-button " >Delete</a>
	</div>

	<div class="ab-current-test">
		<h2>Experiment Summary</h2>
		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span><?php echo number_format($totalVisitor = ab_press_getTotalVisitors($experiment)); ?></li>
			<li class="convertions"><span>Total Conversions</span><?php echo number_format($totalConvertions = ab_press_getTotalConvertions($experiment));  ?></li>
			<li class="converstionRate"><span>Total Conversions Rate</span>
				<?php echo ($totalConvertions == 0) ? "0" : ab_press_getConvertionRate($totalConvertions,$totalVisitor);?>%
			</li>
			<li class="variations"><span>Variations</span><?php echo count($experiment->variations); ?></li>
		</ul>
	</div>
	

	<div class="ab-columns-2">
		<div class="ab-column-content">
			<h3>Description</h3>
			<p><?php echo ($experiment->description != "") ? $experiment->description : "N/A"; ?></p>
			<h3>Goal</h3>
			<p><?php echo $experiment->goal; ?></p>
		</div>
	</div>	

	<div class="ab-columns-2">
		<div class="ab-column-content">
			<h3>Staus</h3>
			<p><?php echo ucwords($experiment->status); ?></p>		
			<h3>Experimanet Date</h3>
			<p><?php echo date("m-d-Y", strtotime($experiment->start_date)) ?> - <?php echo date("m-d-Y", strtotime($experiment->end_date)) ?></p>
		</div>
	</div>	

	<h2>Experiments</h2>
	<?php if($totalConvertions > 0) : ?>
		<p class="ab-press-winner"><?php echo  ab_press_experimentWinner($experiment);?></p>
	<?php endif; ?>


	<table class="widefat">
		<thead>
		    <tr>
		        <th width="250">Variation</th>
		        <th colspan="2">Conversions Rate</th>       
		        <th>Improvement</th>
		        <th>Change To Beat Original</th>       
		        <th>Conversions</th>
		        <th>Visitors</th>
		    </tr>
		</thead>
		<tfoot>
		     <tr>
		        <th>Variation</th>
		        <th colspan="2">Conversions Rate</th>       
		        <th>Improvement</th>
		        <th>Change To Beat Original</th>       
		        <th>Conversions</th>
		        <th>Visitors</th>
		    </tr>
		</tfoot>
		<tbody>

		<tr>
			<th>Control</th> 
			<th>
				<?php echo $controlConvertion = ($experiment->original_convertions == 0) ? 0 : ab_press_getConvertionRate($experiment->original_convertions,$experiment->original_visits); ?>%
				<?php if($controlConvertion != 0): ?>
					( &plusmn;<?php echo $controlInterval = ab_press_getConfidenceInterval($experiment->original_convertions,$experiment->original_visits); ?>)
				<?php endif; ?>
			</th>
			<th >
				<div id="experiment<?php echo $experiment->id;?>" class="ab-boxplot"></div>
				<?php if($controlConvertion != 0): ?>

					<!--<script type="text/javascript">
						jQuery(document).ready(function() {

							jQuery("#experiment<?php echo $experiment->id;?>").sparkline(
							[<?php echo ab_press_getPlotControlData($experiment); ?>], {
						    type: 'box',
						    width: "200",
						    showOutliers:false,
						    medianColor: "black",
						    boxFillColor: '#e5e5e5',
						    whiskerColor: '#cccccc',
						    minValue: 6,
	   						maxValue: 20,
	   						disableTooltips: true,
	   						raw:true
						  
						    });
						})
						
					</script>-->
				<?php endif; ?>
			</th>
			<th> -- </th>
			<th> -- </th>
			<th><?php echo number_format($experiment->original_convertions); ?></th>
			<th><?php echo number_format($experiment->original_visits); ?></th>
		</tr>

		<?php foreach ($experiment->variations as $variations): ?>

		<tr>
			<th><?php echo ucwords($variations->name); ?></th>
			<th>
				<?php echo $variationConvertion = ($variations->convertions == 0) ? 0 : ab_press_getConvertionRate($variations->convertions,$variations->visits); ?>% 
				<?php if($variationConvertion != 0): ?>
					( &plusmn;<?php echo $variationInterval = ab_press_getConfidenceInterval($variations->convertions,$variations->visits); ?>)
				<?php endif; ?>
			</th>
			<th >
				<div id="variation<?php echo $variations->id;?>" class="ab-boxplot"></div>
				
				<?php if($variationConvertion != 0): ?>
					<!--<script type="text/javascript">
						jQuery(document).ready(function() {

							jQuery("#variation<?php echo $variations->id;?>").sparkline(
							[<?php echo ab_press_getPlotVariationData($variations); ?>], {
						    type: 'box',
						    width: "200",
						    showOutliers:false,
						    medianColor: "black",
						    boxFillColor: '#e5e5e5',
						    whiskerColor: '#cccccc',
						    minValue: -1,
	    				    maxValue: 150,
	    				    disableTooltips: true,
	    				    raw:true
						    });
						})
						
					</script>-->
				<?php endif; ?>
			</th>
			<th>
				<?php echo ab_press_getImprovement($controlConvertion, $variationConvertion ) ?>%
			</th>
			<th><?php echo ab_press_getSignificance($experiment, $variations ); ?>%</th>
			<th><?php echo number_format($variations->convertions); ?></th>
			<th><?php echo number_format($variations->visits); ?></th>
		</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>


</div>