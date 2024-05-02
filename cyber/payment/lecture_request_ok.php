<?
include "../../include/include_function.php";

$applyType = Replace_Check_XSS2($applyType); //신청유형
$supportType = Replace_Check_XSS2($supportType); //지원유형
$LectureStart = Replace_Check_XSS2($LectureStart); //학습시작일자
$LectureEnd = Replace_Check_XSS2($LectureEnd); //학습종료일자
$LectureCode = Replace_Check_XSS2($LectureCode); //과정코드
$ContentsName = Replace_Check_XSS2($ContentsName); //과정명
$Price = Replace_Check_XSS2($Price); //판매가
$SubPrice = Replace_Check_XSS2($SubPrice); //정부지원금
$PointPrice = Replace_Check_XSS2($PointPrice); //학습포인트
$CouponPrice = Replace_Check_XSS2($CouponPrice); //쿠폰
$RealPrice = Replace_Check_XSS2($RealPrice); //자비부담금

if(empty($_SESSION['LoginMemberID'])) {
?>
<script type="text/javascript">
<!--
	alert("로그인후에 결제가 가능합니다.");
	top.location.reload();
//-->
</script>
<?
    exit;
}

//[1]결제내역이 존재하는지 확인
$Sql = "SELECT COUNT(*) FROM LectureRequest2 WHERE ID='$LoginMemberID' AND LectureCode='$LectureCode' AND Del='N' AND (Status='A' OR Status='B')";
$Result = mysqli_query($connect, $Sql);
$Row = mysqli_fetch_array($Result);
$Requst_Cpunt = $Row[0];

if($Requst_Cpunt>0) {
    echo "exist";
}else{
    //[2]결제내역이 없을 경우 INSERT(LectureRequest2)
    $maxno = max_number("idx","LectureRequest2");
    
    $Sql = "INSERT INTO LectureRequest2(idx, ID, ApplyType, SupportType, LectureCode, ContentsName, LectureStart, LectureEnd,
                                        Price, SubPrice, PointPrice, CouponPrice, RealPrice, Status, Payment, Del, RegDate)
            VALUES($maxno, '$LoginMemberID', '$applyType', '$supportType', '$LectureCode', '$ContentsName', '$LectureStart', '$LectureEnd',
                   $Price, $SubPrice, $PointPrice, $CouponPrice, $RealPrice, 'A', 'A', 'N', NOW())";
    $Row = mysqli_query($connect, $Sql);
    
    if($Row) echo $maxno; else echo "N";
    //echo $Sql;
}

mysqli_close($connect);
?>