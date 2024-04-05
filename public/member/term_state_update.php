<?php 
    include $_SERVER['DOCUMENT_ROOT'] . "/include/include_function.php"; //DB연결 및 각종 함수 정의
    
    $ID = $_SESSION['LoginMemberID'];
    
    if($chk1 != 'on' || $chk2 != 'on' || $chk3 != 'on' ){ //필수항목에 하나라도 동의하지 않은 경우
?>
<script type="text/javascript">
	alert('필수항목에 동의해주세요');
	history.go(-1);
</script>
<?
    }else{ //필수제외 동의한 경우 통과
        if($chk4 == 'on'){
            $sendingAgreed = 'Y';
        }else{
            $sendingAgreed = 'N';
        }
        
        if($chk5 == 'on'){ //마케팅 모두 동의일 경우
            $marketingAgreed = 'Y';
        }else{
            if($chk4Email == 'on'){
                $marketingAgreed = 'E';
            }else if($chk4Sms == 'on'){
                $marketingAgreed = 'S';
            }else{
                $marketingAgreed = 'N';              
            }
        }
        
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $query4Update = "UPDATE Member 
                        SET Mandatory = 'Y', MandatoryDate = NOW(), 
                            Mailling = '$sendingAgreed', MailingDate = NOW(), 
                            Marketing = '$marketingAgreed', MarketingDate = NOW()
                        WHERE ID = '$ID'";
        
        $row = mysqli_query($connect, $query4Update);
        if($row){
            //초기 비번인지 확인
            if($path == 'C'){
                if($pwchg == 'N'){
                    $url = "/cyber/mypage/memberinfo.html?path=C";
                }else{
                    $url = "/cyber/main/main.html";
                }
            }else{
                echo '사업주 부분 url 적어주기.';
            }
?>
<script>            
            top.location.href = '<?=$url?>';
</script>    
<?php 
        }else{
?>
<script>
            alert('오류가 발생했습니다. 다시 시도해주세요.');
            return;
</script>
<?        
        }
    }// 필수항목 동의한 경우 end-----------
?>