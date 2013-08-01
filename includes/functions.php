<?php 

/**
 * Store a new experiment and it's variations
 *
 * @return boolean
 */
function ab_press_storeExperiment($experiment, $files = null)
{
	global $wpdb;

	$experiment = json_decode(json_encode($experiment), FALSE);
	ab_press_createMessage("There was an issue saving your experiment please try again");

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
			'name' => $experiment->variationName[$i] ,
			'value' => $value ,
			'class' => $experiment->class[$i],
			'date_created' => date('Y-m-d H:i:s')
		));
	}

	ab_press_createMessage("Your experiment has beeb created succesfully!");

	return true;
}

/**
 * Update a new experiment and it's variations
 *
 * @return boolean
 */
function ab_press_updateExperiment($experiment, $files = null)
{
	global $wpdb;
	$experiment = json_decode(json_encode($experiment), FALSE);
	ab_press_createMessage("There was an issue updating your experiment please try again");

	if(!wp_verify_nonce( $experiment->_wpnonce, 'abpo-new-experiment' )) return false;	
	$status = (date("Y-m-d", strtotime($experiment->startDate))  > date("Y-m-d") ) ? 'paused' : "running";

	$row = $wpdb->update( ABPressOptimizer::get_table_name('experiment'), array( 
			'name' => $wpdb->escape($experiment->name),
			'description' => $wpdb->escape($experiment->description),
			'status' => $status ,
			'start_date' => date("Y-m-d", strtotime($experiment->startDate)) ,
			'end_date' => date("Y-m-d", strtotime($experiment->endDate)),
			'goal' => $wpdb->escape($experiment->goal),
			'goal_type' => $experiment->goalTrigger,
			'url' => $experiment->url),
			array( 'id' => $experiment->id )
	);

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

	for ($i=0; $i < count($experiment->type); $i++) { 
		if($experiment->delete[$i] == "true")
		{
			$wpdb->delete( ABPressOptimizer::get_table_name('variations'), array( 'id' => $experiment->vId[$i] ) );
		}
		elseif($experiment->type[$i] == "img" ){
			if(!$experiment->vId[$i])
			{
				$isNew = true;
				$overide = array("test_form" => false);
				$path = wp_handle_upload($file[$currImage], $overide);
				++$currImage;
				$value = $path['url'];
			}
			else
			{
				$isNew = false;
				$overide = array("test_form" => false);
				$path = wp_handle_upload($file[$currImage], $overide);
				++$currImage;
				$value = $path['url'];
			}
		}
		elseif(empty($experiment->vId[$i]))
		{
			$isNew = true;
			$value = $experiment->variation[$currValue];
			++$currValue;
		}
		else 
		{
			$isNew = false;
			$value = $experiment->variation[$currValue];
			++$currValue;
		}

		if($isNew)
		{
			$row = $wpdb->insert( ABPressOptimizer::get_table_name('variations') , array(
				'experiment_id' => $id,
				'type' => $experiment->type[$i],
				'value' => $value ,
				'class' => $experiment->class[$i],
				'date_created' => date('Y-m-d H:i:s')
			));
		}
		elseif(!$isNew)
		{
			$row = $wpdb->update( ABPressOptimizer::get_table_name('variations'), array( 
				'type' => $experiment->type[$i],
				'name' => $experiment->variationName[$i] ,
				'value' => $value ,
				'class' => $experiment->class[$i]),
				array( 'id' => $experiment->vId[$i] )
			);	
		}

	}
	
	ab_press_createMessage("Your experiment has been updated succesfully!");
	return true;
}

/**
 * Get an experiment by id
 *
 * @return boolean
 */
function ab_press_getExperiment($id){
	global $wpdb;
	$table = ABPressOptimizer::get_table_name('experiment');
	$table2 = ABPressOptimizer::get_table_name('variations');
	$query = "SELECT * FROM $table WHERE id = $id";
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

function ab_press_getAllExperiment($offset = null, $limit = null){
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

/**
 * Get All active experiments
 *
 * @return results
 */
function ab_press_getAllActiveExperiments($withVariations = false){
	global $wpdb;
	$table = ABPressOptimizer::get_table_name('experiment');
	$table2 = ABPressOptimizer::get_table_name('variations');
	$query = "SELECT * FROM $table WHERE status = 'running' Order By date_created DESC";
	$query2 = "SELECT * FROM $table2";
	$results = $wpdb->get_results($query, OBJECT );

	if($withVariations)
	{
		$variations = $wpdb->get_results($query2, OBJECT );
		foreach ($results as $result) {
			$result->variations = []; 
			foreach ($variations as $variation) {
				if($result->id == $variation->experiment_id)
					$result->variations[] = $variation;
			}
		}
	}

	return $results;
}

/**
 * Update status of an experiment
 */
function ab_press_updateExperimentStatus($id, $status){
	global $wpdb;

	$wpdb->update( ABPressOptimizer::get_table_name('experiment'), array( 
			'status' => $status),
			array( 'id' => $id )
	);
}

function ab_press_updateImpression($id, $type, $count){
	global $wpdb;
	$count = $count + 1;

	if($type == 'control')
	{
		$wpdb->update( ABPressOptimizer::get_table_name('experiment'), array( 
				'original_visits' => $count),
				array( 'id' => $id )
		);
	}
	else
	{
		$wpdb->update( ABPressOptimizer::get_table_name('variations'), array( 
				'visits' => $count),
				array( 'id' => $id )
		);
	}
}

/**
 * Get Total Convertions
 *
 * @return number
 */
function ab_press_getTotalConvertions($experiment)
{
	$total = $experiment->original_convertions;

	foreach ($experiment->variations as $variation) {
		$total += $variation->convertions;
	}

	return $total;
}

/**
 * Get Total Visitors
 *
 * @return number
 */
function ab_press_getTotalVisitors($experiment)
{
	$total = $experiment->original_visits;

	foreach ($experiment->variations as $variation) {
		$total += $variation->visits;
	}

	return $total;
}

/**
 * Get Convertion Rate
 *
 * @return number
 */
function ab_press_getConvertionRate( $convertions , $total, $isPercent = true)
{
	if($isPercent )
		return round(($convertions/$total) * 100, 2);
	else
		return $convertions/$total;
}

/**
 * Get Confinece Interval (Standard Error)
 *
 * @return number
 */
function ab_press_getConfidenceInterval($convertions , $total, $isPercent = true)
{
	$rate = ab_press_getConvertionRate($convertions, $total, false);
	$se = sqrt(($rate * (1-$rate))/$total) * 1.96;
	if($isPercent)
		return round($se * 100 , 2);
	else
		return $se;
}

/**
 * Get Variation Improvement
 *
 * @return number
 */
function ab_press_getImprovement($controrl, $test)
{
	if ($controrl == 0) { return 0; }
	$imporvement = round((($controrl - $test)/$controrl) *  -100, 2);
	if($imporvement > 0)
		$imporvement = "+".$imporvement;
	else
		$imporvement = $imporvement;

	return $imporvement;
}

/**
 * Get Plot Points for Control
 *
 * @return string
 */
function ab_press_getPlotControlData($experiment)
{
	$rate = ab_press_getConvertionRate($experiment->original_convertions, $experiment->original_visits, false);
	$variance = 1.282*( sqrt(($rate * (1-$rate))/$experiment->original_visits));
	$variance95 = 1.96*( sqrt(($rate * (1-$rate))/$experiment->original_visits));
	
	$upper = $rate + $variance;
	$lower= $rate- $variance;

	$upper95 = $rate + $variance95;
	$lower95 = $rate - $variance95;

	$plotPoints = [($lower *100 ) - 1, $lower *100 , $rate *100 , $upper  *100 , ($upper *100 ) + 1 ];

	return implode(", ", $plotPoints);
}

/**
 * Get Plot Points for Variations
 *
 * @return string
 */
function ab_press_getPlotVariationData($variation)
{
	$rate = ab_press_getConvertionRate($variation->convertions, $variation->visits, false);
	$variance = 1.282*( sqrt(($rate * (1-$rate))/$variation->visits));
	$variance95 = 1.96*( sqrt(($rate * (1-$rate))/$variation->visits));
	
	$upper = $rate + $variance;
	$lower= $rate- $variance;

	$upper95 = $rate + $variance95;
	$lower95 = $rate - $variance95;

	$plotPoints = [($lower *100 ) - 1, $lower *100 , $rate *100 , $upper  *100 , ($upper *100 ) + 1 ];

	return implode(", ", $plotPoints);
}


/**
 * Get Experiment Winner
 *
 * @return string
 */
function ab_press_experimentWinner($experiment){
	$winnerAmount = 0;

	foreach ($experiment->variations as $variation) {
		$significance =  ab_press_getSignificance($experiment, $variation );

		if( $significance  > $winnerAmount)
		{
			$winnerAmount = $significance;
			$winner = $variation;
		}
	}

	if($winnerAmount <= 0) return "";

	$original_rate = ab_press_getConvertionRate($experiment->original_convertions,$experiment->original_visits);
	$variation_rate = ab_press_getConvertionRate($winner->convertions,$winner->visits);
	$improvement = ab_press_getImprovement($original_rate, $variation_rate);

	return "Test <strong>". ucwords($winner->name) . "</strong> is beating out the control by <strong>$improvement%</strong>!";
}


/**
 * Get Statistical Significance
 *
 * @return number
 */
function ab_press_getSignificance($original, $variation){
	if($variation->visits == 0) return 0;

	$original_rate = ab_press_getConvertionRate($original->original_convertions, $original->original_visits, false);
	$variation_rate = ab_press_getConvertionRate($variation->convertions, $variation->visits, false);

	$original_se= ab_press_getConfidenceInterval($original->original_convertions, $original->original_visits, false);
	$variation_se = ab_press_getConfidenceInterval($variation->convertions, $variation->visits, false);

	$zscore = ab_press_normalcdf($original_rate, $original_se, $variation_rate );
	return round($zscore *100, 2);
}

/**
 * Normalize Data
 *
 * @return number
 */
function ab_press_normalcdf($mean, $sigma, $to) {
	$z = ($to-$mean)/sqrt(2*$sigma*$sigma);
	$t = 1/(1+0.3275911*abs($z));
	$a1 =  0.254829592;
	$a2 = -0.284496736;
	$a3 =  1.421413741;
	$a4 = -1.453152027;
	$a5 =  1.061405429;
	$erf = 1-((((($a5*$t + $a4)*$t) + $a3)*$t + $a2)*$t + $a1)*$t*exp(-$z*$z);
	$sign = 1;
	if($z < 0)
	{
		$sign = -1;
	}
	return (1/2)*(1+$sign*$erf);
}

/**
 * Create markup for experiment also used inside of code
 */
function ab_press_optimizer($id, $content)
{
	//Select Experiment
	$experiment = ab_press_getExperiment($id);
	$control = (object) array('id'=>$experiment->id, 'type'=>'control', 'value' => $content, 'class' => '');
	array_unshift($experiment->variations, $control);

	//Select Random Variation
	$randomVariation = rand(0 , count($experiment->variations)-1) ;
	$variation = $experiment->variations[$randomVariation];

	$tag = ab_press_getTag($content);
	$attributes = ab_press_getAttributes($content, $tag, $variation);

	if($variation->type == "control")
	{
		ab_press_updateImpression($id, 'control', $experiment->original_visits);
		return ab_press_createControl($content, $tag, $attributes);
	}
	else
	{
		ab_press_updateImpression($variation->id, 'variation', $variation->visits);
		return ab_press_createVariation($variation, $tag, $attributes);
	}
}

/**
 * Get Html Tag
 *
 * @return String
 */
function ab_press_getTag($content)
{
	$tagTypes = ['a', 'p', 'div', 'span', 'section', 'input', 'img'  ];
	$tag = '';

	foreach ($tagTypes as $tagType) {
		if(preg_match('%(^<'.$tagType.'[^>]*>.*?</'.$tagType.'>)%i', $content, $tempTag) || preg_match('#<'.$tagType.'[^>]*>#i', $content, $tempTag) || preg_match('#<'.$tagType.'[^>]*>#i', $content, $tempTag) )
		{
			$tag = $tagType;
		}
	}

	return $tag;
}

/**
 * Get Attributes from html
 *
 * @return String
 */
function ab_press_getAttributes($content, $tag, $variation)
{
	$attributes = "";

	if(!empty($tag) && preg_match_all('/(alt|type|title|src|href|class|id|value|name)=("[^"]*")/i', $content, $elemtAttributes))
	{
		$attr = [];
		for ($i=0; $i < count($elemtAttributes[1]); $i++) { 
			$tempAttr = str_replace('"',"", $elemtAttributes[2][$i]);
			$tempAttr = str_replace("'","", $tempAttr);
			$attr[strtolower($elemtAttributes[1][$i])] = $tempAttr;
		}


		if(isset($attr['class']))
			$attr['class'] = (string) $attr['class'] . ' ab-press-hock ' . $variation->class;
		else
			$attr['class'] = 'ab-press-hock';

		if($variation->type == "img")
			$attr['src'] = $variation->value; 

		foreach ($attr as $key => $value) {
			$attributes .= ( ' '. $key . '="' .$value .'" ');
		}
	}



	return $attributes;
}

/**
 * Get content from html
 *
 * @return String
 */
function ab_press_getContent($content, $tag){
	$tagContent = "";

	if($tag != "img" && $tag != "input")
	{
	    if(preg_match("/<".$tag."[^>]*>(.*?)<\/".$tag.">/is", $content, $matches))
	    {
	    	$tagContent = $matches[1];
	    }
	}

	return $tagContent;
}

/**
 * Create a control markup
 *
 * @return String
 */
function ab_press_createControl($content, $tag, $attributes)
{
	$tagContent = ab_press_getContent($content, $tag);

	if($tag == "img")
	{
		$result = "<img $attributes />";
	}
	elseif ($tag == "input") 
	{
		$result = "<input $attributes />";
	}
	else
	{
		$result = "<$tag $attributes>$tagContent</$tag>";
	}

	return $result;
}

/**
 * Create a variation markup
 *
 * @return String
 */
function ab_press_createVariation($variation, $tag, $attributes){
	
	if($variation->type == "html")
	{
		$html = $variation->value;
		$htmlTag = ab_press_getTag($html);
		$htmlAttributes = ab_press_getAttributes($html, $tag, $variation);
		$htmlContent = ab_press_getContent($html, $htmlTag);
		return "<$htmlTag $htmlAttributes>$htmlContent</$htmlTag>";
	}
	elseif($variation->type == "img")
	{
		return "<img $attributes />";
	}
	else
	{
		if ($tag == "input") 
			return "<input $attributes />";
		else
			return "<$tag $attributes>$variation->value</$tag>";
	}
}

/**
 * Create a flash message
 */
function ab_press_createMessage($message)
{
	$_SESSION['message'] = $message;
}

/**
 * Delete a flash message
 */
function ab_press_deleteMessage()
{
	$_SESSION['message'] = null;
}