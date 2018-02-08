<?php

require_once('function.php');
global $wpdb, $tableOpenDoor;


$display = 0;
$currenturl = get_permalink();
//var_dump($_POST);
/*
echo '<pre>';
echo shell_exec(
  "curl -X PUT --header 'Content-Type: application/json' --header 'Accept: application/json' --header 'Api-key: rp9aKyC5SKT60sY6MYSv9g' -d '{ \
  \"email\": \"pshenychnyj%40ukr.net\",
  \"password\": \"123456\",
  \"name\": \"qwer\",
  \"language\": \"de\",
  \"profile\": {
    \"firstName\": \"string\",
    \"lastName\": \"string\",
    \"address\": \"string\",
    \"address2\": \"string\",
    \"zip\": \"string\",
    \"city\": \"string\",
    \"country\": \"string\",
    \"phone\": \"string\"
  }
}' 'https://api.nuki.io/account'");

*/
$target_url = "https://api.nuki.io/smartlock/105477041/action";
$ch = curl_init($target_url);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json' , 'Authorization: Bearer d04a2293427ef068ddbdb906b4fb883cc2849ac204690572e7b4047bee6db7b1120ab54bcd91d48b'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json' , 'Authorization: Bearer a1fc329f8d39632df67f1ed3d0e685721e92558186e99ce0d23b4c27dcbbeff77a7010f847dda74f'));

curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, '{"action": "2","option":"0"}');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch,CURLOPT_HEADER,true);

$response_data = curl_exec($ch);
if (curl_errno($ch) > 0) {
    echo 'Ошибка curl: ' . curl_error($ch);
} else {
    echo $response_data .'<h1>1111</h1>';
}

echo '<pre>';
//var_dump($response_data);
echo '</pre>';


$display = open_door_open();
?>
<section class="od" >
<?php
//echo plugins_url().'opendoor/front_page.php';
if($display == '1') :
?>
    <div class="od-open">
        <h1>OPEN!</h1>
    </div>

<?php else :?>
    <div class="od-content">
        <?php
        open_form($display);
        ?>
    </div>
<?php endif  ?>

    <?php  ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <p>
<!--        <button onclick="geoFindMe()">Show my location</button>-->
    </p>
    <div id="out"></div>

    <script type="text/javascript">


//        if(navigator.geolocation) {
//            navigator.geolocation.getCurrentPosition(function(position) {
//                var latitude = position.coords.latitude;
//                var longitude = position.coords.longitude;
//                console.log(latitude+' '+longitude);
//            });
//
//        } else {
//            alert("Geolocation API не поддерживается в вашем браузере");
//        }


        jQuery(document).ready(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    console.log(pos.lat);
                });
            }
        });

    </script>
  <?php   ?>

</section>

