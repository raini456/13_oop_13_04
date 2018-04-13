<?php
require_once './config.php';
require_once './classes/DbClass.php';
require_once './classes/DbClassExt.php';
require_once './classes/FilterForm.php';

$db = new DbClassExt('mysql:host=' . HOST . ';dbname=' . DB, USER, PASSWORD);

//Filter field_name 
$f = new FilterForm();
$f->setFilter('field_name', 513, 'name');
$dataName = $f->filter(INPUT_POST); //$dataName['name'] = 'Grand Hotel Adlon';
//Hotelname eintragen
if (count($dataName) === 1) {
 $db->setTable('tb_hotels');
 $lastId = $db->insert($dataName); //$dataName['name'] = 'Grand Hotel Adlon';
}

if ($lastId > 0) {//Wenn Id von Hotel Eintrag größer 0
 $f2 = new FilterForm();
 $f2->setFilter('field_services', [FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY], 'service_id');
 $dataFormServices = $f2->filter(0); //$dataFormServices = ['service_id' => [1,2,4]]
 $dataFormServices['hotel_id'] = [];
 for ($i = 0; $i < count($dataFormServices['service_id']); $i++) {
  $dataFormServices['hotel_id'][] = $lastId;
 }
 $db->setTable('tb_hotelservice');
 $db->insertArray($dataFormServices);
}








//services filtern
//inserts mit assoziativen Arrays
//$data = [];
////     column = wert
//$data['name'] = 'Westin Grand';
//$data['de'] = 'Swimmingpool';
//$db->insert($data);
//
////inserts mit zweidimensionalen Arrays
//$data = [];
//$data['service_id'] = [2,5];
////$db->insertArray($data);
//$data = [
//    'hotel_id' => [1,1,1],
//    'service_id' => [1,2,4]
//]
//prüfen ob post variable vorhanden
//Einfügen in die hotel tabelle
?>


<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>PHP 13 Hotels DB</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="assets/css/styles.css">    
  <script src="assets/js/jquery-3.3.1.min.js" type="text/javascript"></script>
  <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="assets/js/main.js" type="text/javascript"></script>
 </head>
 <body>
  <div class="container">
   <form method="post" action="index.php">
    <div class="form-group">
     <label for="field_name">Hotelname</label>
     <input value="Grand Hotel Adlon" type="text" class="form-control"
            id="field_name" name="field_name">
    </div>
    <?php
    $db->setTable('tb_services');
    $dataServices = $db->getAllData();
    ?>

    <?php foreach ($dataServices as $service) : ?>
     <div class="form-check form-check-inline">
      <input class="form-check-input" name="field_services[]" type="checkbox" 
             id="service<?= $service['id'] ?>" value="<?= $service['id'] ?>">
      <label class="form-check-label" for="service<?= $service['id'] ?>">
       <?= $service['de'] ?></label>
     </div>
    <?php endforeach; ?>

    <hr>
    <div class="form-group">
     <button class="btn btn-outline-primary">Save</button>
    </div>

   </form>
  </div>
  <hr>
  <pre>
   <?php
//   var_dump($dataFormServices);
   ?>
  </pre>
 </body>
</html>
