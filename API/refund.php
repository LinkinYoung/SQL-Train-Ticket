<?php
    include_once('config.php');

    session_start();

    if (!isset($_SESSION['userName']) || empty($_SESSION['userName'])) {
		print('{"result": "Forbidden"}');
        die();
    }

	if (!isset($_POST['trainNum']) || empty($_POST['trainNum'])) {
        die();
    }

	if (!isset($_POST['date']) || empty($_POST['date'])) {
        die();
    }

    try {
        $dbh = new PDO("mysql:host={$db_config['host']};dbname={$db_config['dbName']}", $db_config['user'], $db_config['pwd'], [PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
    } catch (PDOExveption $e) {
        print('{"result":"Database Fatal"');
        die();
    }

	try{
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$trNu=$_POST['trainNum'];
		$da=$_POST['date'];
//------------------------------------------------------------------------    
    //进行删除和更改等相应操作

			$stmt = $dbh->prepare("UPDATE dailytotal SET sum = sum-1 WHERE trainNum = :trainNum AND date = :date");
			$stmt->bindParam(':trainNum', $trainNum);
			$stmt->bindParam(':date', $date);
			$trainNum = $trNu;
			$date = $da;
			$stmt->execute();
			$stmt = $dbh->prepare("DELETE FROM orders WHERE trainNum = :trainNum AND date = :date AND userName = :userName");
		  	$stmt->bindParam(':userName', $userName);
			$stmt->bindParam(":trainNum", $trainNum);
			$stmt->bindParam(":date", $date);
			$userName = $_SESSION['userName'];
			$trainNum = $_POST['trainNum'];
			$date = $_POST['date'];
			$stmt->execute();

//-----------------------------------------------------------------------------
      //检查sum是不是为0，为0则不可以进行退票操作
			$stmt = $dbh->prepare("SELECT sum FROM dailytotal WHERE trainNum = :trainNum AND date = :date");					
			$stmt->bindParam(':trainNum', $trainNum);
			$stmt->bindParam(':date', $date);
			$trainNum = $trNu;
			$date = $da;
			$stmt->execute();
			$sum = $stmt->fetch(PDO::FETCH_ASSOC);

			if($sum['sum']<0){    //如果sum小于0
				$dbh->rollBack(); 
				print('{"result": "fail"}');
				die();
			}
//---------------------------------------------------------------------------------------      
			$dbh->commit();
			print('{"result": "success"}');
	}catch (Exception $e) { 
       $dbh->rollBack(); 
       print('{"result":"Failed"}'); 
       print($e->getMessage()); 
	}
	

?>