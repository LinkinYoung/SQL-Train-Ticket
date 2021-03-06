<?php
	include_once('config.php');

    session_start();

	if (!isset($_SESSION['userName']) || empty($_SESSION['userName'])) {
		    print('{"result": "Forbidden"}');
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

//-----------------------------------------------------------------------------------
//判断是不是管理员
	$stmt = $dbh->prepare("SELECT admin FROM user WHERE userName = :userName");
	$stmt->bindParam(':userName', $userName);
	$userName = $_SESSION['userName'];
	$stmt->execute();
	$admin = $stmt->fetch(PDO::FETCH_ASSOC);

	if($admin['admin'] == 0 ){      //如果该用户不是管理员
		print('{"result":"Database Fatal"');
        die();
	}	
//---------------------------------------------------------------------------------------
	//查找相应的消息
	$stmt = $dbh->prepare("SELECT * FROM train ");
	$stmt->bindParam(':userName', $userName);
	$userName = $_SESSION['userName'];
	$stmt->execute();
  
	$train = $stmt->fetchALL(PDO::FETCH_ASSOC);
  
  $result = array();
  $result['result'] = 'success';
  $result['train'] = $train;
  print(json_encode($result));
	$dbh->commit();


	}catch (Exception $e) { 
       print('{"result":"Failed"}'); 
       print($e->getMessage());
       }
?>

