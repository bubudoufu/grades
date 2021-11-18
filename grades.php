<?php
// pdoインスタンス生成
function getPdoInstance()
{
try{
    $pdo = new PDO('sqlite:./test.db'); //  PDOの引数に(sqlite:データベースのパス)で指定する
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーが起きた時例外を投げる
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // 連想配列形式でデータを取得する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 指定した型に合わせる

    return $pdo;
    
    }catch(PDOException $e){
    //echo $e->getMessage();
    exit('エラーが発生しました');
    }
}

$pdo = getPdoInstance();

// データ取得
function getDb($pdo)
{
    $stmt = $pdo->query("SELECT id, turn, win, lose, draw FROM test UNION SELECT 3, '通算', SUM(win), SUM(lose), SUM(draw) FROM test");
    $stmts = $stmt->fetchAll();
    return $stmts;
}

$json = getDb($pdo);
$data=json_encode($json);
echo $data;

// データ登録
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // 先手か後手か 0=先行 1=後攻
    $turn = filter_input(INPUT_POST, 'turn');
    // 勝敗の結果 0=負け 1=勝ち 2=引き分け
    $result = filter_input(INPUT_POST, 'result');
    $sql; 

    if($result == 1){
        $sql = "UPDATE test SET win = win + 1 WHERE id = ?";
    }elseif($result == 0){
        $sql = "UPDATE test SET lose = lose + 1 WHERE id = ?";
    }elseif($result == 2){
        $sql = "UPDATE test SET draw = draw + 1 WHERE id = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$turn]);
    
    exit;
}