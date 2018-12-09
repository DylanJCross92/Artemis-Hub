<?php

function redirect($url)
{
	header("Location: ".$url);
}

function cents_to_dollars($cents)
{
	setlocale(LC_MONETARY, 'en_US');
	return money_format('%!n', $cents/100);	
}

function ajax_success($redirect_url = false)
{
	return json_encode(array('success' => true, 'redirect_url' => $redirect_url));	
}

function ajax_error($array)
{
	return json_encode(array('field_errors' => $array));		
}

function time_elapsed($ptime)
{

     $estimate_time = $ptime - time();

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array(
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' remaining';
        }
    }

}

function change($value) {
	
	if($value > 0)
	{
		return "lost";	
	}
	else if($value < 0)
	{
		return "gained";		
	}
	else
	{
		return "neutral";		
	}
		
}

function decto_percent($decimal) 
{
	return (floor($decimal*100)/100) * 100;
}
?>
