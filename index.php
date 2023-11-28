<?php

$comment_array = array();
$pdo =  null;
$stmt = null;


//DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bbsp', "root", "yuuki128");
} catch (PDOException $e) {
    echo $e->getMessage();
}



//submitbottoon推したときのみ出力
if (!empty($_POST["submitButton"])) {

    //名前のチェック
    if(empty($_POST["username"])){
        echo "名前を入力してください";
        $error_messages["username"] = "名前を入力してください。";
    }
    //コメントのチェック
    if(empty($_POST["comment"])){
        echo "コメントを入力してください";
        $error_messages["comment"] = "コメントを入力してください。";
    }


    if(empty($error_messages)){
     
    $postDate = date("Y-m-d H:i:s");

        try{
            $stmt = $pdo->prepare("INSERT INTO `bbsp-table` (`username`,`comment`,`postDate`) VALUES(:username, :comment, :postDate)");
            $stmt->bindParam(':username',$_POST['username'],PDO::PARAM_STR);
            $stmt->bindParam(':comment',$_POST["comment"],PDO::PARAM_STR);
            $stmt->bindParam(':postDate',$postDate,PDO::PARAM_STR);
        
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
    }   
    }

}
//DBからコメントデータを取得する
$sql = "SELECT `id`,`username`,`comment`,`postDate` FROM `bbsp-table`;";
$comment_array = $pdo->query($sql);


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBSP</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <header id="header">
        <h1 class="site-title">
            <a href="./index.php">Eamy <span class="headercolor">BBS</span></a>
        </h1>
    </header>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment): ?>
                <article>
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username">
                            <?php echo $comment["username"]; ?>
                        </p>
                        <time>
                            <?php echo $comment["postDate"]; ?>
                        </time>
                    </div>
                    <p class="comment">
                        <?php echo $comment["comment"]; ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>

</body>

</html>