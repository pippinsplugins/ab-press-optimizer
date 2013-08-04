<?php 
	require_once('../../../../wp-load.php'); 

	if(isset($_POST['nonce']))
	{
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'abpress-click-event'  ) ) {
			echo 'error';
		} else {

			foreach ($ab_press_data as $experiment) {

				if( $_POST['experiment'] == $experiment->id)
				{
					$id = $experiment->id;
					$varId = $_POST['variation'];
					if(!isset($_COOKIE['_ab_press_exp_' .$id  .'_conv']))
					{
						if($varId == "c")
						{
							ab_press_updateConvertion($id, "control", $experiment->original_convertions);
						}
						else
						{
							$variationCount = 0;
							foreach ($experiment->variations as $variation) {
								if($variation->id == $varId)
								{
									$variationCount = $variation->convertions ; 
									break; 
								} 
							}

							ab_press_updateConvertion($varId, "variation", $variationCount );
						}
						setcookie('_ab_press_exp_' . $experiment->id .'_conv', 1, time()+60*60*24*1, '/');

						echo 'success';
					}

				}
				
			}

		} // end else
	}//End if
