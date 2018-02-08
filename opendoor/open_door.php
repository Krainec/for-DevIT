<?php

require_once('function.php');
global $wpdb;
$blockVisible = 'none';
//$table_cn_photo = $wpdb->prefix . "cn_photo";
//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/**
 * checking the authorization of the user
 */



//echo shell_exec("curl -X PUT --header 'Content-Type: application / json' --header 'Accept: application / json' -d '(opentest)' 'https://api.nuki.io/account/user'");
echo '<hr>';



//$ch = curl_init('https://api.nuki.io/smartlock/35356674/action');
$target_url = "https://api.nuki.io/smartlock/546789/action";
$ch = curl_init($target_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"action": 1,"option":0}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response_data = curl_exec($ch);
if (curl_errno($ch) > 0) {
    echo 'Ошибка curl: ' . curl_error($ch);
} else {
    echo $response_data;
}




$id_user = wp_get_current_user();
if($id_user->user_login ) {
    echo '<div class="opdo">';

    $login_user = $id_user->user_login;
    echo "<h3>Hello $login_user</h3>";
    //echo '<button class="btn-primary btn">Open</button><br>';

    //the output of the form to open the door
    //open_button();
    if(isset($_POST['hidInput']))
    {
        echo $_POST['hidInput'];
    }

    echo '<br><hr>';


    /**
     * choosing the time 'dopbsp_reservations' by 'id' of the paid orders from'postmeta'
     */
    $tableReservations = $wpdb->prefix . "dopbsp_reservations";
    $query = "
    SELECT calendar_id, check_in , start_hour , transaction_id 
    FROM $tableReservations
    WHERE transaction_id IN(
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE post_id IN (
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = '_customer_user'
            AND meta_value = {$id_user->ID} ) 
        AND meta_key = '_date_completed' 
        AND meta_value != '' ) GROUP BY transaction_id ORDER BY check_in , start_hour";
    $results= $wpdb->get_results( $query );

    //echo '<pre>';
    //var_dump($results);
    //echo '</pre>';
    global $tableOpenDoor;
    foreach ($results as $key=>$value){
        // exact time
        $timeEvent = $value->check_in . ' ' . $value->start_hour;
        $exactTime = strtotime($timeEvent);

        //time before event hours,minutes
        $dt_time = time_before_start($value->calendar_id);
        $dt = $dt_time[0]*3600 + $dt_time[1]*60;
        $spanTime = $exactTime - $dt;

        //echo 'exactTime = ' .$exactTime . ' = ' . date( 'Hhi l d F', $exactTime ) . '<br>';
        //echo 'spanTime = ' .$spanTime . ' = ' . date( 'Hhi l d F', $spanTime ) . '<br>';

        if($spanTime > current_time('timestamp')){
            //echo 'Time before event = ' .$dt_timeHour . ' hours, ' . $dt_timeMin . ' min.<br>';
            //echo 'Time = ' .current_time('timestamp').' = ' . current_time('mysql') . '<br>';
            //echo 'Time before event = ' .$dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<h3>Too much time before event</h3>';
        }
        elseif($exactTime > current_time('timestamp') && $spanTime < current_time('timestamp')){
            //echo '<h3>Кнопка появись!</h3>';
            //echo 'Time = ' .current_time('timestamp').' = ' . current_time('mysql') . '<br>';
            echo 'Time before event = ' .$dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<br><a href="" class="button">OPEN</a><br><br><hr>';
        //break;
        }
        else{

            echo 'Time before event = ' .$dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<h3>Event ended</h3>';
        }
        echo '<hr>';
    }
    echo '</div>';

}