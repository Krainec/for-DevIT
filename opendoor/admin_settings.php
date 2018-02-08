<?php
echo '<div><h1>' . OP_NAME . '</h1></div>';

do_action('admin_table');

$getOpendoor = array();
if($_POST) {
    $getOpendoor = get_opendoor();
    updata_time_before_start($getOpendoor);
    //echo 'POST isset <br>';
    echo '<div class="updated">Settings updated</div>';
}
else{
    $getOpendoor = get_opendoor();
    //echo 'POST is empty<br>';
}

$a = array_search('od-hours-5', $_POST);
//echo "<h1>a = $a</h1>";
?>
<form action="?page=opendoor&op=update" method="POST" class="form-table" id="od-admin-table">
    <input type="hidden" name="action" value="update" />
    <table>
        <thead>
            <tr>
                <td>№</td>
                <td>id</td>
                <td>Name</td>
                <td>Hours</td>
                <td>Minutes</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        foreach($getOpendoor as $key=>$value){
            $str = '<tr>';
            $str .= '<td>'. ++$i;
            $str .= '</td><td>' . $value->id_calendar;
            $str .= '</td><td>' . $value->name_calendar;
            $hours = (isset($_POST["od-hours-$value->id_calendar"])) ? $_POST["od-hours-$value->id_calendar"] : $value->hours;
            $str .= '</td><td><input type="number" min="0" max="23" name="od-hours-' . $value->id_calendar .'" value="' . $hours . '">';
            $minutes = (isset($_POST["od-minutes-$value->id_calendar"])) ? $_POST["od-minutes-$value->id_calendar"] : $value->minutes;
            $str .= '</td><td><input type="number" min="0" max="59" name="od-minutes-' . $value->id_calendar .'" value="' . $minutes . '"></td>' ;
            $str .= '</tr>';
            echo $str;
        }
        ?>
        </tbody>
    </table>
    <input type="submit" value="Update" class="button-primary">
</form>