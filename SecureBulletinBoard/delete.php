<?php
function delete($delete_id){
  //データ読み込み
  $fp = fopen('data.csv', 'r');

  flock($fp, LOCK_SH);
  while ($row = fgetcsv($fp)) { // 取り出せる行が有る限りrowに取り出す [array fgetcsv ( resource $handle )]
    $rows[] = $row; // array_push関数と同じ働きをする
  }

  fclose($fp);

  //データが空ならreturn
  if(empty($rows)){
    return;
  }

  //該当行削除
  unset($rows[$delete_id]);

  //データ書き込み
  $fp = fopen('data.csv', 'wd');
  flock($fp, LOCK_SH);
  foreach ($rows as $row) {
    fputcsv($fp, $row);
  }

  fclose($fp);
}

 ?>
