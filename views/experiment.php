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
	
	<div class="ab-press-header">
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/ab-logo.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>

	<h2 class="nav-tab-wrapper">
		<a href="" class="nav-tab nav-tab-active">Experiments</a>
		<a href="admin.php?page=abpo-gettingStarted" class="nav-tab">Getting Started</a>
		<a href="admin.php?page=abpo-settings" class="nav-tab">Settings</a>
	</h2>

	<div class="ab-press-nav">
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=abpo-new" class="button-primary">Add New</a>
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=" class="button">Export to CSV</a>
	</div>

	<?php
		if(isset($_SESSION['message']))
		{
			echo "<div id='message' class='updated below-h2'><p>".$_SESSION['message']."</p></div>";
			deleteMessage();
		}
	?>
	
	<div class="ab-current-test">
		<h2>Current Experiment: <a href="">Example Experiment</a> </h2>
		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span>10</li>
			<li class="convertions"><span>Convertions</span>15</li>
			<li class="converstionRate"><span>Convertion Rate</span>8%</li>
			<li class="variations"><span>Variations</span>35</li>
		</ul>
	</div>
	
	<table class="widefat">
		<thead>
		    <tr>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Convertions</th>
		        <th>Variations</th>
		        <th>Status</th>
		    </tr>
		</thead>
		<tfoot>
		     <tr>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Convertions</th>
		        <th>Variations</th>
		        <th>Status</th>
		    </tr>
		</tfoot>
		<tbody>
		<?php 
			$experiments = getAllExperiment();

			foreach ($experiments as $experiment):
		?>

		<tr>
			<th><a href=""><?= ucwords($experiment->name); ?></a></th>
			<th><?= $experiment->total_visitors; ?></th>
			<th>0</th>
			<th><?= count($experiment->variations); ?></th>
			<th><?= ucwords($experiment->status); ?></th>
		</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>

	<!-- TODO: Provide markup for your options page here. -->

</div>