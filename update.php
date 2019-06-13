<?php 
        include 'connectDB.php';
        $conn = OpenCon();
        $err= " ";

        var_dump($_POST);
        $email = $_POST['clientemail'];
        $wechat = $_POST['clientwechat'];
        $No =(int)$_POST['prizeNo'];
        $name = $_POST['clientname']; 
        $success = true;
        $sql ="UPDATE client_info SET prize = $No, `status`= 1, wechat_id= '$wechat', `name`='$name' WHERE email='$email'";
        $result = $conn->query($sql);
        
        $sql = "SELECT no_available FROM prize_info WHERE id=$No";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $availableno = $row['no_available'] - 1;
        $sql = "UPDATE prize_info SET no_available = $availableno  WHERE prize_info.id = $No";
        $result = $conn->query($sql);   

        header('Content-type: application/json');
        $data = ["err"=> $err, "prize"=>$No, "success"=>$success];
        echo  json_encode($data);
        CloseCon($conn);
      ?>
