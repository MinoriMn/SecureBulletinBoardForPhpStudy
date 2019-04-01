<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>掲示板</title>

  <?php
  // デバッグ:値の確認用
  echo "posted:${_POST['name']} ${_POST['text']}<br>";
  echo 'REQUEST_METHOD:' . $_SERVER['REQUEST_METHOD'];
  // POSTとして送信されてきたときのみ実行
  // (通常アクセスはGET，フォーム送信はPOST)
  $fp = fopen('data.csv', 'a+b');
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      fputcsv($fp, [$_POST['name'], $_POST['text']]);
  }

  rewind($fp); // ポインタを先頭に移動させる
  while ($row = fgetcsv($fp)) { // 取り出せる行が有る限りrowに取り出す [array fgetcsv ( resource $handle )]
    $rows[] = $row; // array_push関数と同じ働きをする
  }
  fclose($fp);

  ?>
</head>
<body>
  <h1>掲示板</h1>
  <section>
    <h2>新規投稿</h2>
    <form class="" action="" method="post">
      名前: <input type="text" name="name" value=""><br>
      本文: <input type="text" name="text" value=""><br>
      <button type="submit">投稿</button>
    </form>
  </section>
  <section>
    <h2>投稿一覧</h2>
    <?php
    if(!empty($rows)){
      echo '<ul>';
      foreach ($rows as $row) {
        echo "<li>${row[1]}(${row[0]})</li>";
      }
      echo '</ul>';
    }else{
      echo '<p>投稿はまだありません</p>';
    }
     ?>
  </section>
</body>
</html>
