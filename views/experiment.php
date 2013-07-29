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
		<a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=abpo-export&noheader=true" class="button" >Export to CSV</a>
	</div>



	<?php
		if(isset($_SESSION['message']))
		{
			$message = explode("|", $_SESSION['message']);
			if(count($message) > 1 )
				echo "<div id='message' class=' below-h2 error'><p>".$message[0]."</p></div>";
			else
				echo "<div id='message' class='updated below-h2'><p>".$message[0]."</p></div>";
			deleteMessage();
		}
		
	?>

	<?php 
		$pagenum = isset( $_GET['paging'] ) ? absint( $_GET['paging'] ) : 1;
     	$limit = 10;
     	$offset = ( $pagenum - 1 ) * $limit;
		$experiments = getAllExperiment($offset, $limit);

		$total = count(getAllExperiment());
		$num_of_pages = ceil( $total / $limit );

		$page_links = paginate_links( array(
		    'base' => add_query_arg( 'paging', '%#%' ),
		    'format' => '',
		    'prev_text' => __( '&laquo;', 'aag' ),
		    'next_text' => __( '&raquo;', 'aag' ),
		    'total' => $num_of_pages,
		    'current' => $pagenum
		) );
	?>

	<?php
		foreach ($experiments as $experiment) {
			if($experiment->status == "running")
			{
				$featuredExperiment = $experiment;
				break;
			}
		}
	?>
	
	<div class="ab-current-test">
		<h2>Current Summery Experiment: <a href="admin.php?page=abpo-details&eid=<?php echo $featuredExperiment->id; ?>"><?php echo ucwords($featuredExperiment->name); ?></a> </h2>

		<ul class="ab-press-dashboard">
			<li class="totalVisitore"><span>Total Visitors</span><?php echo number_format($totalVisitor = getTotalVisitors($featuredExperiment)); ?></li>
			<li class="convertions"><span>Convertions</span><?php echo number_format($totalConvertions = getTotalConvertions($featuredExperiment));  ?></li>
			<li class="converstionRate"><span>Convertion Rate</span>
				<?php echo ($totalConvertions == 0) ? "0%" : getConvertionRate($totalConvertions,$totalVisitor);?>%
			</li>
			<li class="variations"><span>Variations</span><?php echo count($featuredExperiment->variations); ?></li>
		</ul>
	</div>
	
	<h2>Experiments</h2>

	<table class="widefat">
		<thead>
		    <tr>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Convertions</th>
		        <th>Convertion Rate</th>
		        <th>Variations</th>
		        <th>Experiment Date</th>
		        <th>Status</th>
		    </tr>
		</thead>
		<tfoot>
		     <tr>
		        <th>Name</th>
		        <th>Visitors</th>       
		        <th>Convertions</th>
		        <th>Convertions Rate</th>
		        <th>Variations</th>
		        <th>Experiment Dates</th>
		        <th>Status</th>
		    </tr>
		</tfoot>
		<tbody>

		<?php foreach ($experiments as $experiment): ?>

		<tr>
			<th><a href="admin.php?page=abpo-details&eid=<?php echo $experiment->id; ?>"><?php echo ucwords($experiment->name); ?></a></th>
			<th><?php echo number_format($totalVariationVisitor = getTotalVisitors($experiment)); ?></th>
			<th><?php echo number_format($totalVariationConvertion = getTotalConvertions($experiment));  ?></th>
			<th><?php echo ($totalVariationConvertion == 0) ? "0" : getConvertionRate($totalVariationConvertion,$totalVariationVisitor);?>%</th>
			<th><?php echo count($experiment->variations); ?></th>
			<th><?php echo date("m-d-Y", strtotime($experiment->start_date)) ?> - <?php echo date("m-d-Y", strtotime($experiment->end_date)) ?></th>
			<th><?php echo ucwords($experiment->status); ?></th>
		</tr>

		<?php endforeach; ?>
		
		</tbody>
	</table>

	<?php 
		if ( $page_links ) {
    		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
	?>
</div>