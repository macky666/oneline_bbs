<?php
// １．データベースに接続する
$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password='';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');


// POST送信が行われた時
if (!empty($_POST)){

$nickname = htmlspecialchars($_POST['nickname']);
$comment = htmlspecialchars($_POST['comment']);

// SQL文作成

// $sql = "INSERT INTO `posts`(`nickname`, `comment`,`created`) VALUES ('$nickname', '$comment', now())";

$sql = 'INSERT INTO `posts`(`nickname`, `comment`,`created`) VALUES ("'.$nickname.'", "'.$comment.'", now())';
$stmt = $dbh->prepare($sql);
$stmt->execute();

}


// ２．SQL文を実行する
  $sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';

  // SELET文実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  // 変数の初期化
  $posts = array();
  
  // 繰り返し文でデータの取得
  while(1){
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    // 無限ループ防止のために
    if($rec == false){
      // データを最後まで取得したので終了
      break;
    }

    // 取得したデータを配列に格納しておく
    $posts[] = $rec;
  }

  // ３．データベースを切断する
  $dbh = null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>

    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
   
    
    <!-- <ul> -->
       <!-- $posts[0]['nickname'] ?><?php // $posts[][];?> comment 2016-10-13</li> --> 
      <!-- <li>testname 一言つぶやき</li> -->
      <!-- <li>テスト太郎 コメント 2016-10-13</li> -->
    <!-- </ul> -->

  <ul>
  <?php
  foreach ($posts as $post_each){
    echo '<li>';
    echo $post_each['nickname'].' ';
    echo $post_each['comment'].' ';

    // いったん日付型に変換
    $created = strtotime($post_each['created']);
    
    // 書式型に変換
    $created = date('y/m/d',$created);

    // echo $post_each['created'].' ';
    echo $created;
    echo '</li>';
  }
  ?>

  </ul>

   </form>

</body>
</html>