<?php 


function storeExperiment($experiment, $files = null)
{
	global $wpdb;

	$experiment = json_decode(json_encode($experiment), FALSE);
	createMessage("There was an issue saving your experiment please try again");

	if(!wp_verify_nonce( $experiment->_wpnonce, 'abpo-new-experiment' )) return false;	
	$status = (date("Y-m-d", strtotime($experiment->startDate))  > date("Y-m-d") ) ? 'paused' : "running";

	$row = $wpdb->insert( ABPressOptimizer::get_table_name('experiment') , array(
		'name' => $wpdb->escape($experiment->name),
		'description' => $wpdb->escape($experiment->description),
		'status' => $status ,
		'start_date' => date("Y-m-d", strtotime($experiment->startDate)) ,
		'end_date' => date("Y-m-d", strtotime($experiment->endDate)),
		'goal' => $wpdb->escape($experiment->goal),
		'goal_type' => $experiment->goalTrigger,
		'url' => $experiment->url,
		'date_created' => date('Y-m-d H:i:s')
	));

	if(!$row) return false;	

	$id = $wpdb->insert_id;
	$currImage = 0;
	$currValue = 0;

	$files = $files['variationFile'];
	foreach ($files['name'] as $key => $value) {
	  if ($files['name'][$key]) {

	    $file[] = array(
	      'name'     => $files['name'][$key],
	      'type'     => $files['type'][$key],
	      'tmp_name' => $files['tmp_name'][$key],
	      'error'    => $files['error'][$key],
	      'size'     => $files['size'][$key]
	    );
	  }
	}

	for ($i=0; $i < count($experiment->type); $i++) { 
		if($experiment->type[$i] == "img"){
			$overide = array("test_form" => false);
			$path = wp_handle_upload($file[$currImage], $overide);
			++$currImage;
			$value = $path['url'];
		}
		else 
		{
			$value = $experiment->variation[$currValue];
			++$currValue;
		}

		$row = $wpdb->insert( ABPressOptimizer::get_table_name('variations') , array(
			'experiment_id' => $id,
			'type' => $experiment->type[$i],
			'value' => $value ,
			'class' => $experiment->class[$i],
			'date_created' => date('Y-m-d H:i:s')
		));
	}

	createMessage("Your experiment has beeb created succesfully!");

	return true;
}

function updateExperiment($experiment, $files = null)
{
	global $wpdb;
	$experiment = json_decode(json_encode($experiment), FALSE);
	createMessage("There was an issue updating your experiment please try again");

	if(!wp_verify_nonce( $experiment->_wpnonce, 'abpo-new-experiment' )) return false;	
	$status = (date("Y-m-d", strtotime($experiment->startDate))  > date("Y-m-d") ) ? 'paused' : "running";

	// $row = $wpdb->update( ABPressOptimizer::get_table_name('experiment'), array( 
	// 		'name' => $wpdb->escape($experiment->name),
	// 		'description' => $wpdb->escape($experiment->description),
	// 		'status' => $status ,
	// 		'start_date' => date("Y-m-d", strtotime($experiment->startDate)) ,
	// 		'end_date' => date("Y-m-d", strtotime($experiment->endDate)),
	// 		'goal' => $wpdb->escape($experiment->goal),
	// 		'goal_type' => $experiment->goalTrigger,
	// 		'url' => $experiment->url),
	// 		array( 'id' => $experiment->id )
	// );

	$id =  $experiment->id;
	$currImage = 0;
	$currValue = 0;

	$files = $files['variationFile'];
	$file = [];
	foreach ($files['name'] as $key => $value) {
	  if ($files['name'][$key]) {
	    $file[] = array(
	      'name'     => $files['name'][$key],
	      'type'     => $files['type'][$key],
	      'tmp_name' => $files['tmp_name'][$key],
	      'error'    => $files['error'][$key],
	      'size'     => $files['size'][$key]
	    );
	  }
	}

	print_r($experiment);
	
	echo "<br>";

	for ($i=0; $i < count($experiment->type); $i++) { 

		if($experiment->type[$i] == "img" ){
			if(!$experiment->vId[$i])
			{
				$overide = array("test_form" => false);
				$path = wp_handle_upload($file[$currImage], $overide);
				++$currImage;
				$value = $path['url'];
				print_r('New File');
			}	
		}
		else 
		{
			print_r($currValue . '<br>');
			$value = $experiment->variation[$currValue];
			++$currValue;
		}

		// $row = $wpdb->insert( ABPressOptimizer::get_table_name('variations') , array(
		// 	'experiment_id' => $id,
		// 	'type' => $experiment->type[$i],
		// 	'value' => $value ,
		// 	'class' => $experiment->class[$i],
		// 	'date_created' => date('Y-m-d H:i:s')
		// ));
	}
	exit();
	
	createMessage("Your experiment has beeb updated succesfully!");
	return true;
}

function getExperiment($id){
	global $wpdb;
	$table = ABPressOptimizer::get_table_name('experiment');
	$table2 = ABPressOptimizer::get_table_name('variations');
	$query = "SELECT * FROM $table Where id = $id";
	$query2 = "SELECT * FROM $table2";
	$result = $wpdb->get_row($query, OBJECT );
	$variations = $wpdb->get_results($query2, OBJECT );

	if(!$result) return false;

	$result->variations = []; 
	foreach ($variations as $variation) {
		if($result->id == $variation->experiment_id)
			$result->variations[] = $variation;
	}

	return $result;
}

function getAllExperiment($offset = null, $limit = null){
	global $wpdb;
	$table = ABPressOptimizer::get_table_name('experiment');
	$table2 = ABPressOptimizer::get_table_name('variations');
	if(is_null($offset))
		$query = "SELECT * FROM $table Order By date_created DESC";
	else
		$query = "SELECT * FROM $table Order By date_created DESC LIMIT $offset, $limit ";
	$query2 = "SELECT * FROM $table2";
	$results = $wpdb->get_results($query, OBJECT );
	$variations = $wpdb->get_results($query2, OBJECT );

	foreach ($results as $result) {
		$result->variations = []; 
		foreach ($variations as $variation) {
			if($result->id == $variation->experiment_id)
				$result->variations[] = $variation;
		}
	 }

	return $results;
}

function getTotalConvertions($experiment)
{
	$total = $experiment->original_convertions;

	foreach ($experiment->variations as $variation) {
		$total += $variation->convertions;
	}

	return $total;
}

function getTotalVisitors($experiment)
{
	$total = $experiment->original_visits;

	foreach ($experiment->variations as $variation) {
		$total += $variation->visits;
	}

	return $total;
}

function getConvertionRate( $convertions , $total)
{
	return round(($convertions/$total) * 100, 2);
}

//Is not working check total vs passing %
function getConfidenceInterval($convertions , $total)
{
	$rate = $convertions/$total;
	$rate = sqrt($rate * (1-$rate)/$total);
	return round(($rate )*100, 2);
}

function getImprovement($controrl, $test)
{
	if ($controrl == 0) { return 0; }
	$imporvement = round((($controrl - $test)/$controrl) *  -100, 2);
	if($imporvement > 0)
		$imporvement = "+".$imporvement;
	else
		$imporvement = $imporvement;

	return $imporvement;
}

function getSignificance($original_rate, $controrlInterval, $variation_rate, $variationInterval){
	$base = sqrt(pow($controrlInterval, 2) + pow($variationInterval, 2));

	if ($base == 0) { return 0; }
	$zscore = ($original_rate - $variation_rate)/$base;
	return round(cumnormdist($zscore )* 100, 2) ;
}

function cumnormdist($x)
{
  $b1 =  0.319381530;
  $b2 = -0.356563782;
  $b3 =  1.781477937;
  $b4 = -1.821255978;
  $b5 =  1.330274429;
  $p  =  0.2316419;
  $c  =  0.39894228;

  if($x >= 0.0) {
      $t = 1.0 / ( 1.0 + $p * $x );
      return (1.0 - $c * exp( -$x * $x / 2.0 ) * $t *
      ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
  }
  else {
      $t = 1.0 / ( 1.0 - $p * $x );
      return ( $c * exp( -$x * $x / 2.0 ) * $t *
      ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
    }
}

function createMessage($message)
{
	$_SESSION['message'] = $message;
}

function deleteMessage()
{
	$_SESSION['message'] = null;
}