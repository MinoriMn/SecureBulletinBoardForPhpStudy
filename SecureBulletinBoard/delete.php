<?php
function delete($delete_id){
  $fp = fopen('data.csv', 'r');

  flock($fp, LOCK_SH);
  while ($row = fgetcsv($fp)) { // 取り出せる行が有る限りrowに取り出す [array fgetcsv ( resource $handle )]
    $rows[] = $row; // array_push関数と同じ働きをする
  }

  fclose($fp);

  $fp = fopen('data.csv', 'wd');

  flock($fp, LOCK_SH);
  unset($rows[$delete_id]);
  foreach ($rows as $row) {
    fputcsv($fp, $row);
  }

  fclose($fp);
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['someAction'])){
  delete(0);
}
 ?>
