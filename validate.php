<?php 
        include 'connectDB.php';
        $conn = OpenCon();
        $statusErr = $noExistErr = "";
        $email = $_POST['email'];
        $wechat = $_POST['wechat'];
        $name = $_POST['name'];
        $prize = '';
        $success = false;
        
        $sql = "SELECT prize, `status` FROM client_info WHERE email='$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows>0) {
            // output data of each row
                while($row = $result->fetch_assoc()) {
                 
                    $status = $row["status"];
         
                    if($status == "1"){
                        $statusErr="statusErr";
                        $prize = $row['prize'];
                      
                        if($prize == 6){
                            $success = false;
                            $prize = 'sorry,something wrong with our record';
                        }else{
                          $sql = "SELECT prize_name FROM prize_info WHERE id=$prize";
                          $prizeresult = $conn->query($sql);
                          $prizerow = $prizeresult->fetch_assoc();
                          $prize = $prizerow["prize_name"];
                          $success = true;
                      }
                    }else{
                      $success =true;
                    };      
                }
            } else {
                $email = " ";
                $wechat = " ";
                $noExistErr = "账号不存在，请检查邮箱是否正确";
            }
            header('Content-type: application/json');
            $data = ["exsiterr"=> $noExistErr, "statuserr"=> $statusErr, "prize"=>$prize, "success"=>$success,'email'=>$email, 'wechat'=>$wechat, 'clientname'=>$name];
             
            echo  json_encode($data);
            CloseCon($conn);
     
?>