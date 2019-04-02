<?php
session_start();//CSRF対策
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>掲示板</title>
  <?php
  // XSS対策
  $hsc = function ($str) {
      return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  };

  require_once('delete.php');
  if(isset($_POST['delete'])){
    delete(0);
  }

  //値の取得
  $name = (string)filter_input(INPUT_POST, 'name');
  $text = (string)filter_input(INPUT_POST, 'text');
  $token = (string)filter_input(INPUT_POST, 'token');

  // デバッグ:値の確認用
  echo "posted:{$name} {$text}<br>";
  echo 'REQUEST_METHOD:' . $_SERVER['REQUEST_METHOD'];

  // POSTとして送信されてきたときのみ実行
  // (通常アクセスはGET，フォーム送信はPOST)
  $fp = fopen('data.csv', 'a+b');

  rewind($fp); // ポインタを先頭に移動させる
  flock($fp, LOCK_EX);
  while ($row = fgetcsv($fp)) { // 取り出せる行が有る限りrowに取り出す [array fgetcsv ( resource $handle )]
    $rows[] = $row; // array_push関数と同じ働きをする
  }

  //id取得
  if(!empty($rows)){
    $id = $rows[count($rows) - 1][2] + 1;
  }else{
    $id = 0;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && sha1(session_id()) === $token /*tokenと今のsession_idのハッシュ値が同じ*/) {
    // flock($fp, LOCK_EX); // 排他ロックを行う
    fputcsv($fp, [$name, $text, $id]);
    $rows[] = [$name, $text, $id];
  }

  flock($fp, LOCK_UN);
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
      <input type="hidden" name="token" value="<?=$hsc(sha1(session_id()))/*idをハッシュ値にしてpostする*/?>">
    </form>
  </section>
  <section>
    <h2>投稿一覧</h2>
    <form class="" action="" method="post">
      <button type="submit" name="delete" value="GO">削除</button>
    </form>
    <?php
    if(!empty($rows)){
      echo '<ul>';
      foreach ($rows as $row) {
        echo '<li>'."{$hsc($row[1])}"."({$hsc($row[0])})"." id={$row[2]}".'</li>'; //hsc関数を通して脆弱性回避
      }
      echo '</ul>';
    }else{
      echo '<p>投稿はまだありません</p>';
    }
     ?>
  </section>
</body>
</html>
