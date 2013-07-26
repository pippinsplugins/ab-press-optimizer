<?php 
session_start();

function storeExperiment($experiment)
{
	global $wpdb;

	$experiment = json_decode(json_encode($experiment), FALSE);
	createMessage("There was an issue saving your experiment please try again");

	if(!wp_verify_nonce( $experiment->_wpnonce, 'abpo-new-experiment' )) return false;	

	$row = $wpdb->insert( ABPressOptimizer::get_table_name('experiment') , array(
		'name' => $wpdb->escape($experiment->name),
		'description' => $wpdb->escape($experiment->description),
		'status' => 'running',
		'start_date' => date("Y-m-d", strtotime($experiment->startDate)) ,
		'end_date' => date("Y-m-d", strtotime($experiment->endDate)),
		'goal' => $wpdb->escape($experiment->goal),
		'goal_type' => $experiment->goalTrigger,
		'url' => $experiment->url,
		'date_created' => date('Y-m-d H:i:s')
	));

	if(!$row) return false;	

	$id = $wpdb->insert_id;

	for ($i=0; $i < count($experiment->type); $i++) { 
		$row = $wpdb->insert( ABPressOptimizer::get_table_name('variations') , array(
			'experiment_id' => $id,
			'type' => $experiment->type[$i],
			'value' => $experiment->variation[$i],
			'class' => $experiment->class[$i],
			'date_created' => date('Y-m-d H:i:s')
		));
	}

	createMessage("Your experiment has beeb created succesfully!");

	return true;
}

function udateExperiment($id, $experiment)
{
	//create experimetn logic
	createMessage("There was an issue updating your experiment please try again");
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

function createMessage($message)
{
	$_SESSION['message'] = $message;
}

function deleteMessage()
{
	$_SESSION['message'] = null;
}