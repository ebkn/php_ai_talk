<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>talk_api</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="contents-wrapper">
      <div class="container">
        <?php
          $input = $_POST["input"];

          $post_data = array(
              'apikey' => "xxxxxxxxxxxxxxxxxxxxxxxxxx",
              'query'=> $input
          );
          //curl実行
          $ch = curl_init("https://api.a3rt.recruit-tech.co.jp/talk/v1/smalltalk");
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          $json = curl_exec($ch);
          curl_close($ch);

          //「TALK API」から返ってきたデータ
          $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
          $obj = json_decode($json, true);
          $results =  $obj["results"][0];
          $reply = $results["reply"];

          if ($input && $reply){
            $mysqli = new mysqli("localhost", "xxxxxxxxxx", "xxxxxxxxxx");
            $mysqli->select_db("xxxxxxxxxxx");
            $mysqli->set_charset("utf8");
            $results = $mysqli->query("insert into message (message) values ('" . $input . "')");
            $mysqli->close();

            $mysqli = new mysqli("localhost", "xxxxxxxxxxxx", "xxxxxxxxxx");
            $mysqli->select_db("xxxxxxxxxxx");
            $mysqli->set_charset("utf8");
            $results = $mysqli->query("insert into message (message) values ('" . $reply . "')");
            $mysqli->close();
          }
        ?>

        <?php
          $mysqli = new mysqli("localhost", "xxxxxxxxxxxxx", "xxxxxxxxxxxx");
          $mysqli->select_db("xxxxxxxxxxk");
          $mysqli->set_charset("utf8");
          $list = $mysqli->query("select * from message");

          while ($line = $list->fetch_array(MYSQLI_ASSOC)) {
            if($line["id"]%2 == 1){
              echo "<h2 class='message my_message'>" . $line["message"] . "</h2>";
            } elseif ($line["id"]%2 == 0) {
              echo "<h2 class='message ai_message'>" . $line["message"] . "</h2>";
            }
          }
          $mysqli->close();
        ?>
      </div>
    </div>

    <footer>
      <form id="input_form" action="ai_talk.php" method="post">
        <input id="input_message" type="text" name="input" placeholder="メッセージを送信する">
        <input id="btn" type="submit" value="送信">
      </form>
    </footer>
  </body>
</html>

