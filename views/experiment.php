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
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/banner-772x250.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>

	<h2 class="nav-tab-wrapper">
		<a href="" class="nav-tab nav-tab-active">Experiments</a>
		<a href="" class="nav-tab">Getting Started</a>
		<a href="" class="nav-tab">Settings</a>
	</h2>

	<div class="ab-press-nav">
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=abpo-new" class="button-primary">Add New</a>
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=" class="button">Export to CSV</a>
	</div>
	
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
		        <th>RegId</th>
		        <th>Name</th>       
		        <th>Email</th>
		    </tr>
		</thead>
		<tfoot>
		    <tr>
		    <th>RegId</th>
		    <th>Name</th>
		    <th>Email</th>
		    </tr>
		</tfoot>
		<tbody>
		   <tr>
		     <td><a href="">fsdgadsf</a></td>
		     <td>fsdgadsf</td>
		     <td>fsdgadsf</td>
		   </tr>
		    <tr>
		     <td><a href="">fsdgadsf</a></td>
		     <td>fsdgadsf</td>
		     <td>fsdgadsf</td>
		   </tr>
		</tbody>
	</table>

	<!-- TODO: Provide markup for your options page here. -->

</div>