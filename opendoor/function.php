<?php
/**
 * form of authorization
 * выводит форму для ввода логина и пароля
 * @param $display - строка если существует ошибка при авторизации , "1" если нет ошибки
 */
function open_form($display = 0){
    $form = '<form action="" method="POST" class="od-form-open" id="od-form-open">';
    $form .= '<br><input type="hidden" name="od-hidInput" value="openDoorTest" >';
    $form .= '<label> Login or Email <input type="text" name="od-form-open-name" ></label>';
    $form .= '<br><label> Password or Key <input type="password" name="od-form-open-pass" ><br><br>';
    $form .= '<input type="submit" value="Open Go" name="od-form-open-sub" class="od-button"><br><br>';
    if($display){
        $form .= '<p style="color: #c00; font-weight: bold">' . $display . '</p>';
    }
    $form .= '</form>';
    echo $form;
}

/**
 * creating the table in database 'opendoor'
 * проверяет если таблицы в базе данных нет то созздается таблица плагина:
 * номер календаря, название календаря , часы и минуты ;
 * и заполняется существующими календарями заданными в плагине Pinpoint Booking System из таблицы dopbsp_calendars;
 * время по умолчанию заполняется "0"
 */
function create_bd_opendoor(){
    global $wpdb, $tableOpenDoor;
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableOpenDoor'") != $tableOpenDoor) {
        $sql = "CREATE TABLE " . $tableOpenDoor . " (
            id int  PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_calendar int DEFAULT '0' NOT NULL,
            name_calendar VARCHAR(255) NOT NULL,
            hours int DEFAULT '0' NOT NULL,
            minutes int DEFAULT '0' NOT NULL,
            date_created TIMESTAMP,
            UNIQUE KEY id (id)
	    );";
        dbDelta($sql);
        $tableCalendars = $wpdb->prefix . "dopbsp_calendars";
        $query = "SELECT id , name FROM $tableCalendars";
        $results = $wpdb->get_results( $query );
        foreach ($results as $key=>$value) {
            $query = "INSERT INTO $tableOpenDoor(id_calendar,name_calendar ) VALUES({$value->id}, '{$value->name}')";
            $wpdb->get_results($query);
        }
    }
}

/**
 * the outputting of data into admin panel by existing calendars
 * обновляет таблицу плагина opendoor при посещении страницы плагина в админке
 */

add_action('admin_table', 'fun_admin_table');
function fun_admin_table()
{
    global $wpdb, $tableOpenDoor;
    $openDoor = get_opendoor();
    $idOpenDoor = array();
    foreach ($openDoor as $key => $value) {
        //echo "$value->id_calendar<br>";
        $idOpenDoor[] = $value->id_calendar;
    }
    $tableCalendars = $wpdb->prefix . "dopbsp_calendars";
    $query = "SELECT id , name FROM $tableCalendars";
    $results = $wpdb->get_results($query);
    $idCalendar = array();
    foreach ($results as $key => $value) {
        $idCalendar[] = $value->id;
        if (!in_array($value->id, $idOpenDoor)) {
            $query = "INSERT INTO $tableOpenDoor(id_calendar,name_calendar ) VALUES({$value->id}, '{$value->name}')";
            $wpdb->get_results($query);
        }
    }
    foreach ($idOpenDoor as $key=>$value) {
        if (!in_array($value, $idCalendar)) {
            echo $value . '<br>';
            $query = "DELETE FROM $tableOpenDoor WHERE id_calendar=$value";
            $wpdb->get_results($query);
        }
    }
}


/**
 * the choosing of time from'dopbsp_reservations' by 'id' of the paid orders from 'postmeta'
 * Выбераются все заказы конкретного пользователя и проверяет ближайший заказ по времени
 * @param $idUser - id пользователя который пытается/пробует авторизироваться
 * @return bool
 */
function get_order_reservation($idUser){
    global $wpdb;
    $tableReservations = $wpdb->prefix . "dopbsp_reservations";
    $query = "
    SELECT calendar_id, check_in , start_hour , transaction_id 
    FROM $tableReservations
    WHERE transaction_id IN(
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE post_id IN (
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = '_customer_user'
            AND meta_value = $idUser ) 
        AND meta_key = '_date_completed' 
        AND meta_value != '' ) GROUP BY transaction_id ORDER BY check_in , start_hour";
    $results = $wpdb->get_results($query);

    foreach ($results as $key => $value) {
        // accurate time
        $timeEvent = $value->check_in . ' ' . $value->start_hour;
        $exactTime = strtotime($timeEvent);

        //time before the beginning of event,hours, minutes
        $dt_time = get_time_before_start($value->calendar_id);
        $dt = $dt_time[0] * 3600 + $dt_time[1] * 60;
        $spanTime = $exactTime - $dt;

        //echo 'exactTime = ' .$exactTime . ' = ' . date( 'Hhi l d F', $exactTime ) . '<br>';
        //echo 'spanTime = ' .$spanTime . ' = ' . date( 'Hhi l d F', $spanTime ) . '<br>';
        //echo "<h1>$exactTime ; $dt_time[0] : $dt_time[1]</h1>";
        if ($spanTime > current_time('timestamp')) {
            return 0;
            //echo 'Time before event = ' .$dt_timeHour . ' hours, ' . $dt_timeMin . ' min.<br>';
            //echo 'Time = ' .current_time('timestamp').' = ' . current_time('mysql') . '<br>';
            //echo 'Time before event = ' .$dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<h3>Too much time before event</h3>';

        } elseif ($exactTime > current_time('timestamp') && $spanTime < current_time('timestamp')) {
            //echo '<h3>Кнопка появись!</h3>';
            return 1;

            //echo 'Time = ' .current_time('timestamp').' = ' . current_time('mysql') . '<br>';

            echo 'Time before event = ' . $dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<br><a href="" class="button">OPEN</a><br><br><hr>';
            //break;
        } else {
            continue;
            //return 0;
            echo 'Time before event = ' . $dt_time[0] . ' hours, ' . $dt_time[1] . ' min.<br>';
            echo 'Exact time = ' . $timeEvent . '<br>';
            echo '<h3>Event ended</h3>';
        }
        echo '<hr>';
    }
}
/*
function get_order_reservation($idUser){
    global $wpdb;
    $tableReservations = $wpdb->prefix . "dopbsp_reservations";
    $query = "
    SELECT calendar_id, check_in , start_hour , transaction_id 
    FROM $tableReservations
    WHERE transaction_id IN(
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE post_id IN (
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = '_customer_user'
            AND meta_value = {$idUser} ) 
        AND meta_key = '_date_completed' 
        AND meta_value != '' ) GROUP BY transaction_id ORDER BY check_in , start_hour";
    $results = $wpdb->get_results($query);
    return $results;
}
*/
/**



/**
 * the output of data from the table in database'opendoor'
 * @return - mixed таблицу плагина
 */
function get_opendoor(){
    global $wpdb;
    $tableOpenDoor = $wpdb->prefix . "opendoor";
    $query = "SELECT * FROM $tableOpenDoor";
    $results = $wpdb->get_results( $query );
    return $results;
}

/**
 * test function
 */
function set_opendor(){
    if(isset($_POST['op'])){
        echo '<h1>URA</h1>';
    }
}
/**
 * updating time before the beginning of event in the table of database'opendoor'
 * @param $getOpendoor - существующая таблица плагина
 */
function updata_time_before_start($getOpendoor){
    global $wpdb, $tableOpenDoor;
        foreach($getOpendoor as $key=>$value){
            //echo $value->id_calendar .'<br>';
            $query = 'UPDATE ' . $tableOpenDoor . '
            SET hours=' . $_POST["od-hours-$value->id_calendar"] . ' , minutes=' . $_POST["od-minutes-$value->id_calendar"] .'
            WHERE id_calendar=' .$value->id_calendar ;
            //echo $query . '<br>';
            $wpdb->get_results($query);
        }

        //echo '<pre>';
        //var_dump($_POST);
        //echo '</pre>';
}


/**
 * the outputting of time before the beginning of event from the table'opendoor' in database
 * @param $calendarId - id календаря
 * @return массив в котором указано время в часах и мунутах для переданного календаря
 */
function get_time_before_start($calendarId){
    global $wpdb, $tableOpenDoor;

    //time before the beginning of event hours,minutes
    $dt_time = array();
    $query = "SELECT hours, minutes FROM $tableOpenDoor WHERE id_calendar =" . $calendarId;
    foreach($wpdb->get_results($query) as $key_1=>$value_1) {
        $dt_time[0] = $value_1->hours;
        $dt_time[1] = $value_1->minutes;
    }
    return $dt_time;
}

/**
 * after opening the redirect to another page
 */
/*
add_action( 'init', 'open_door_redirect');

function open_door_redirect(){

    if(isset($_POST['od-form-open-sub'])) {

wp_redirect( 'http://openhouse.jabusch.net/' );
exit;
    }
}
*/
//add_action( 'template_redirect', 'open_door_redirect');
function open_door_redirect(){
    if(is_front_page()){
        echo '<h1>ok!</h1>';
        //wp_redirect( 'http://openhouse.jabusch.net/' );
        wp_redirect( get_site_url() );
        exit;
    }
}


/**
 * checking log in and password of the user in the form of authorization
 * Проверяет данные полученые при отправлении формы.
 * @return int|string если проверка прошла то возвращает функцию "get_order_reservation" с "id" залогиневшегося пользователя ,
 *              если проверка не прошла то возвращает строку с ошибкой
 *
 */
function open_door_open(){
    if(isset($_POST['od-form-open-sub'])) {
        if (isset($_POST['od-form-open-name']) && isset($_POST['od-form-open-pass'])) {

            /**
             * checking while authorization of the outputting  of the existing email
             */
            $userId = NULL;
            if (is_email($_POST['od-form-open-name'])) {
                $user = get_user_by('email', $_POST['od-form-open-name']);
                $userId = $user->id;
            }
            /**
             * the checking of the output of the existing log in in authorization
             */
            else {
                $user = get_user_by('login', $_POST['od-form-open-name']);
                $userId = $user->id;
            }
            if ($userId) {
                $user = get_userdata($userId);
                if ($user) {
                    /**
                     * checking of password
                     */
                    $password = $_POST['od-form-open-pass'];
                    $hash = $user->data->user_pass;
                    if (wp_check_password($password, $hash)) {

                        if(get_order_reservation($userId)) {
                            //var_dump(get_order_reservation($userId));
                            return get_order_reservation($userId);
                        }else {
                            //var_dump('Not time or no events');
                            return 'Not time or no events';
                        }
                    } else {
                        //var_dump('Invalid username or password');
                        return 'Invalid login or password';
                    }
                }
            }
        }
    }
    return 0;
}