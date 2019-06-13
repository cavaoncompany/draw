
<?php
    include 'connectDB.php';
 
    $conn = OpenCon();
    $validation = false;
    $sql = "SELECT id, no_available FROM prize_info";
    
    $result = mysqli_query($conn, $sql);
   
    $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);
  
    $no1 = $json[0]['no_available'];
    $no2 = $json[1]['no_available'];
    $no3 = $json[2]['no_available'];
    $no4 = $json[3]['no_available'];
    $no5 = $json[4]['no_available'];
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="draw">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>转盘抽奖</title>
      <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- 这里是css部分 -->
    <style>
       #bg{
        
            height: 397px;
            background: url(turntable-bg.png) no-repeat;
            margin: auto;
            margin-top: 20px;
            position:relative;
        }

        img[src^="pointer"] {
            position: absolute;
            z-index: 10;
            top: 110px;
            left: 143px;
        }

        img[src^="turntable"] {
            position: absolute;
            z-index: 5;
            top: 30px;
            left: 40px;
            transition: all 4s;
        }

        .drawpart{
            padding-top: 30px; 
            margin: auto;
        }

        .drawpart p, .drawpart img{
            text-align: center;
        }

        /* .formStyle{
            position: absolute;
            z-index: 20;
            left: 12%;
            top: 70px;
            width: 314px;
            background-color: white;
            padding: 15px;
            border-radius: 1rem;
            display:none;
        } */

        .formStyle span{
            color:grey;
        }
        .firstpart{
            position:relative;
            background-color:  #FEC330;
            width:422px;
            height:510px;
        }

        .formpart{
            text-align: center;
            width:422px;
          
        }
        .logostyle{
            position: fixed;
            bottom: 30px;
            width: 100%;
            left:0;
        }

        .logostyle p{
            margin-left: 20px;
            margin-right: 20px;
            color:grey;
        }
        .btnStyle{
            background-color: #FF302D;
            width:209px;
            height:39px;
            color:white;
            margin-top:30px;
        }
        .resultStyle{
            margin-top:40px;
            font-size: 18px;
        }

        .prizeStyle {
            font-size:24px;
            color:#FF302D;
            display:block;
        }
        
        #result{
            display:none;
        }

        .print-error-msg{
            color:#FF302D !important;
        }
        
        #congratuationModal .modal-dialog, #congratuationModal .modal-content{
            height: 98%;
            text-align:center;
        }

        #congratuationModal h5{
            font-size:20px;
        }
   
    </style>
</head>
<body>
    <div class="container-fluid firstpart" >
        <div style="margin-top:20px; text-align:center">
            <img src="title.png" />
            <p>旅游网站“有奖”问卷调查- 抽奖活动</p>
        </div>
        
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">登录</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <form class="formStyle" action="validate.php" method="POST" id="contactform">
                            <div class="form-group">
                                <label for="name">姓名</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        <div class="form-group">
                            <label for="email">邮箱<span>（提交问卷时填写的邮箱）</span></label>
                            <input type="email" class="form-control" name="email" aria-describedby="emailHelp" required>
                            <span class = "print-error-msg"><ul></ul></span>
                        </div>
                        <div class="form-group">
                            <label for="wechat">微信号<span>（添加入群的微信号）</span></label>
                            <input type="text" class="form-control" name="wechat" required>
                        </div>
                            <button type="submit" class="btn btn-block" name="sendBtn">提交</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="congratuationModal" tabindex="-1" role="dialog" aria-labelledby="congratuationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="congratuationModalLabel">奖品</h5>
                    </div>
                    <div class="modal-body" style="margin-top:200px">
                        <form class="formStyle"  id="resultform" method="POST" action="update.php">
                        <input type="hidden" id="clientemail" name="clientemail"/> 
                        <input type="hidden" id="clientwechat" name="clientwechat"/>
                        <input type="hidden" id="prizeNo" name="prizeNo"/>
                            <div class="prizeResult"><p></p></div>
                            <p>请联系微信客服领取奖品</p>
                        <button class="btn btn-danger btn-block" type="submit" style="margin-top:200px">OK</button>
                        </form>
                
                    </div>
                </div>
            </div>
        </div>

        <div id="bg">
            <img src="pointer.png" alt="pointer">
            <img src="turntable.png" alt="turntable">
        </div>  
    </div>

    <div class="formpart container-fluid">
        <button id="loginBtn" class="btnStyle" data-toggle="modal" data-target="#exampleModal" >点击登录后开始抽奖</button>
        <div id="result">
            <div class="resultStyle"><p></p></div>
            <div class="prizeStyle"> <p>获得奖品:¥</p></div>
        </div>
        <div class="logostyle">
            <p>一个账号只能抽取一次奖品。如有作弊行为，一经发现，我司有权取消参与的中奖资格。</p>

            <img src="logo.png" />
        </div>
    </div>

    <script>
        var oPointer = document.getElementsByTagName("img")[1];
        var oTurntable = document.getElementsByTagName("img")[2];
     
        var cat = 72; //
        var num = 0; //
        var offOn = false; //是否正在抽奖
        var error = '';
    
 
        var resultStyle= document.getElementsByClassName("resultStyle")[0];
        var prizeStyle= document.getElementsByClassName("prizeStyle")[0];
        var clientemail;
        var clientwechat;
        var prize;
        var prizeNo;
       

        oPointer.onclick = function () {
            if (offOn) {
                oTurntable.style.transform = "rotate(0deg)";
                offOn = !offOn;
                let no5 = '<?php echo $no5 ?>';
                let cantcondition5 = (no5==0);
                let no4 = '<?php echo $no4 ?>';
                let cantcondition4 = (no4==0);
                let no3 = '<?php echo $no3 ?>';
                let cantcondition3 = (no3==0);
                let no2 = '<?php echo $no2 ?>';
                let cantcondition2 = (no2==0);
                console.log(cantcondition5);
                ratating(cantcondition2, cantcondition3, cantcondition4, cantcondition5);
            }
        }

        //旋转
        function ratating(cantcondition2,cantcondition3, cantcondition4, cantcondition5) {
            var timer = null;
            var rdm = 0; //随机度数
            clearInterval(timer);
            console.log(cantcondition5);
            console.log('before'+ rdm);
            timer = setInterval(function () {
                console.log('before random'+rdm);
                let verify5 =((Math.floor(rdm%360) <= 216 && Math.floor(rdm%360)>= 144) && cantcondition5)
                if ((Math.floor(rdm / 360) < 3) || verify5){
                    rdm = Math.floor(Math.random() * 3600); 
                    console.log(rdm);
                }
                else {
                    oTurntable.style.transform = "rotate(" + rdm + "deg)";
                    clearInterval(timer); 
                    setTimeout(function () {
                        offOn = !offOn;
                        num = rdm % 360;
                        if (num <= cat * 1) { 
                            prize ="Free 1 Year .au Domain Name worth $35AUD";
                            prizeNo= 4;
                            conditionArr.find((el)=>el.id ===prizeNo).no_available;
                            set_result(prize, clientemail, clientwechat, prizeNo);
                            <?php 
                                if($no1>0){
                                    $no1--;
                                    $sql = "UPDATE prize_info SET no_available = $no1 WHERE prize_info.id = 1";
                                    $result = $conn->query($sql);            
                                }
                            ?>
                        }
                        else if (num <= cat * 2) { 
                           
                            prize ="¥6.6 RMB Red Pocket";
                            prizeNo= 3;
                            set_result(prize, clientemail, clientwechat, prizeNo);
                            <?php 
                            if($no3>0){
                                $no3--;
                                $sql = "UPDATE prize_info SET no_available = $no3 WHERE prize_info.id = 3";
                                $result = $conn->query($sql);            
                            }
                            ?>
                            }
                        else if (num <= cat * 3) { 
                            prize = "Shangri-la High Tea for 2 worth $110AUD";
                            prizeNo = 5;
                            set_result(prize, clientemail, clientwechat, prizeNo);
                            <?php 
                            
                            if($no5>0){
                                $no5--;
                                $sql = "UPDATE prize_info SET no_available = $no5 WHERE prize_info.id = 5";
                                $result = $conn->query($sql);            
                            }
                            ?>
                            }
                        else if (num <= cat * 4) { 
                           
                            prize = "1.8 RMB Red Pocket";
                            prizeNo = 1;
                            set_result(prize, clientemail, clientwechat, prizeNo);
                            <?php 
                            
                            if($no1>0){
                                $no1--;
                                $sql = "UPDATE prize_info SET no_available = $no1 WHERE prize_info.id = 1";
                                $result = $conn->query($sql);            
                            }
                            ?>
                            }
                        else if (num <= cat * 5) { 
                         
                            prize="2.8 RMB Red Pocket";
                            prizeNo = 2;
                            set_result(prize, clientemail, clientwechat, prizeNo);
                            <?php 
                            
                            if($no2>0){
                                $no2--;
                                $sql = "UPDATE prize_info SET no_available = $no2 WHERE prize_info.id = 2";
                                $result = $conn->query($sql);            
                            }
                            ?>
                            }

                    }, 4000);
                }
            }, 30);
        }
        function show_result(hideModal, prizevar){
            $(hideModal).modal('hide');
            $('.resultStyle').find('p').append("您有0次抽奖机会");
            $('.prizeStyle').find('p').append(prizevar);
            $('#result').show();
            $('#loginBtn').hide();
        }

        function set_result(prize,clientemail, clientwechat, prizeNo){
            $('.prizeResult').find('p').append(prize);
                            $('#clientemail').val(clientemail);
                            $('#clientwechat').val(clientwechat);
                            $('#prizeNo').val(prizeNo);
                            $('#congratuationModal').modal('show');
        }

    $('#contactform').on('submit', function(e){
        e.preventDefault();
        $('#sendBtn').attr("disabled", true)
        data= $(this).serialize();
        url=$(this).attr('action');
        $.ajax({
                    url: url,
                    type:'POST',
                    data:data,
                    success:function(data){
                        console.log(data);
                        if($.isEmptyObject(data.exsiterr)&& $.isEmptyObject(data.statuserr) ){
                            
                            $('#exampleModal').modal('hide');
                            $('#loginBtn').attr("disabled", true);  
                            clientemail = data.email;
                            clientwechat = data.wechat;
                            offOn = true;
                        }else if(data.statuserr=="statusErr"){
                           
                                console.log("show result");
                                show_result('#exampleModal', data.prize);
                                
                        }else{   
                            $('.print-error-msg').find('ul').append(data.exsiterr);
                        
                        }
                    }
                });
    })

    //update database
    $('#resultform').on('submit', function(e){
        e.preventDefault(e);
        data= $(this).serialize();
        console.log(data);
        url=$(this).attr('action');
        $.ajax({
                    url: url,
                    type:'POST',
                    data:data,
                    success:function(data){
                        console.log(data);
                        if($.isEmptyObject(data.err)){
                            offOn = false;
                    }else{
                            console.log("update data failed");
                             
                        }
                    }
                });
   
        show_result('#congratuationModal', prize);
    })
    </script>
    <?php 
          CloseCon($conn);
    ?>
</body>
</html>