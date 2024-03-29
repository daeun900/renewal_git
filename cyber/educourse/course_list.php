<?
include "../../include/include_function.php"; //DB연결 및 각종 함수 정의

$pageStart = Replace_Check_XSS2($pageStart);
$sw  = Replace_Check_XSS2($sw);
$keyChkVal   = Replace_Check_XSS2($keyChkVal);

if($sw){
    $where[] = "a.ContentsName LIKE '%$sw%'";
}
if($keyChkVal){
    $where[] = "a.Keyword LIKE '%$keyChkVal%'";
}
$where[] = "a.PackageYN='N'";
$where[] = "a.Del='N'";
$where[] = "a.ctype='B'";

$where = implode(" AND ",$where);
if($where) $where = "WHERE $where";

$SQL = "SELECT a.* FROM CourseCyber a $where  ORDER BY a.RegDate DESC, a.idx DESC  LIMIT $pageStart, 8";
$QUERY = mysqli_query($connect, $SQL);
if($QUERY && mysqli_num_rows($QUERY)){
    while($ROW = mysqli_fetch_array($QUERY)){
        extract($ROW);
        $Keyword = str_replace(' ', '', $Keyword);
        $Keyword_array = explode('#',$Keyword);
        $ImgUrl = "/upload/Course/".$PreviewImage;
?>
<li>
	<div class="course_img" style="background-image: url(<?=$ImgUrl?>); background-color: #e8ece7;" onclick="window.open('../edudetail/edudetail.html')" onclick="window.open('../edudetail/edudetail.html')">
		<span><?=$Keyword_array[1]?></span>					
		<button class="course_save_btn"><img src="../img/educourse/white heart.png" alt=""></button>
	</div>
	<div class="course_title"><?=$ContentsName?></div>
	<div class="course_tag">
    	<? 
    	while (list($key,$value)=each($Keyword_array)){
    	   if($key > 0){
    	?>
    	 	<span><?=$value?></span>
    	<?
    	   }
    	}
    	?>
	</div>
</li>
<?
	}
}

mysqli_close($connect);
?>