<?
include "../../include/include_function.php"; //DB연결 및 각종 함수 정의

$LectureCode = Replace_Check_XSS2($LectureCode);
$clickVal    = Replace_Check_XSS2($clickVal);

$ID = $_SESSION['LoginMemberID'];

$cmd = false;
$msg;

if($clickVal == "like"){
    $maxno = max_number("idx","CourseLike");
    
    $Sql = "INSERT INTO CourseLike (idx, LectureCode, ID, RegDate)
            VALUES ($maxno, '$LectureCode', '$ID', NOW())";
    $Row = mysqli_query($connect, $Sql);
    
    $cmd = true;
    $msg = "like";
}else{
    $Sql = "DELETE FROM  CourseLike  WHERE ID='$ID' AND LectureCode = '$LectureCode'";
    $Row = mysqli_query($connect, $Sql);
    
    $cmd = true;
    $msg = "unlike";
}

if($Row && $cmd) {
    echo $msg;
}else{
    echo "N";
}

mysqli_close($connect);
?>