<?

include $_SERVER['DOCUMENT_ROOT'] . "/include/include_function.php"; //DB연결 및 각종 함수 정의

require_once ('../../include/KISA_SHA256.php');

$MemberType = Replace_Check_XSS2($MemberType1);
$ID = Replace_Check_XSS2($ID); //아이디
$Pwd = Replace_Check_XSS2($Pwd); //비밀번호
if(isset($ID_Save) && $ID_Save == 'on') {
    // 체크박스가 체크되어 있음
    $ID_Save='Y';
} else {
    // 체크박스가 체크되어 있지 않음
    $ID_Save='N';
}
// $TopLogin = Replace_Check_XSS2($TopLogin); //상단에서 로그인 여부

$enc_pwd = encrypt_SHA256($Pwd); //비밀번호 암호화
$Sql = "SELECT * FROM Member WHERE MemberType='$MemberType' AND BINARY(ID)='$ID' AND UseYN='Y'";
$Result = mysqli_query($connect, $Sql);
$Row = mysqli_fetch_array($Result);

if($Row) {
    if($Row['Sleep']=="Y") {
        ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert("현재 휴면계정 상태입니다.\n\n[휴면계정 복구] 메뉴를 통해 계정을 복구하세요.");
	//-->
	</SCRIPT>
<?
	exit;
	}
	if($Row['MemberOut']=="Y") {
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert("탈퇴한 회원입니다.");
		top.DataResultClose();
	//-->
	</SCRIPT>
<?
	exit;
	}
	if($Row['Pwd']!=$enc_pwd) {
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert("아이디 또는 비밀번호를 잘못 입력했습니다.");
		window.history.back();
	//-->
	</SCRIPT>
<?
	exit;
	}

	if($path == 'C'){ // 사이버 연수원 이동
		if($Row['PassChange']=="N") {
			$url = "/cyber/mypage/memberinfo.html?path=C";
			$TopLogin="N";
		} else if ($Row['TestID']=="Y") {
			$url = "/cyber/mypage/lecture_status.html";
		} else if ($Row['EduManager']=="Y") { //교육담당자는 결제관리로 이동
			$url = "/cyber/mypage/lecture_status.html";
		} else {
			$url = "/cyber/mypage/lecture_status.html";
		}
	}
		// if($Row['TestID']=="Y") {
		// 	$url = "/mypage/lecture.php";
		// }

		// if($Row['EduManager']=="Y") { //교육담당자는 결제관리로 이동
		// 	$url = "/mypage/manager_payment.php";
		// }


		//최종로그인 날짜, IP 등록
		$Sql2 = "UPDATE Member SET LastLogin=NOW(), LastLoginIP='$UserIP' WHERE ID='$ID'";
		mysqli_query($connect, $Sql2);

		//로그인 히스토리 등록
		$Sql3 = "INSERT INTO LoginHistory(ID, Device, IP, RegDate) VALUES('$ID', 'PC', '$UserIP', NOW())";
		mysqli_query($connect, $Sql3);

		//로그인 중복처리를 위한 
		$Sql4 = "DELETE FROM LoginIng WHERE ID='$ID'";
		mysqli_query($connect, $Sql4);

		$maxno = max_number("idx","LoginIng");
		$SessionID = session_id();
		$Sql5 = "INSERT INTO LoginIng(idx, ID, SessionID, IP, RegDate) VALUES($maxno, '$ID', '$SessionID', '$UserIP', NOW())";
		mysqli_query($connect, $Sql5);


/* 20190218 대리수강 방지 2월말까지 잠정적으로 중지
		//대리수강 방지
		if($Row['ProtectID']=="Y") { //대리수강 방지 기능이 사용중이면

			if (isset($_COOKIE["MemberProtectID"])) { //대리수강 방지 쿠키가 존재하면
				if($_COOKIE["MemberProtectID"] != $Row['ID']) {

					//블랙리스트에 등록
					$ProtectID = $_COOKIE["MemberProtectID"];
					$LoginID = $Row['ID'];
					$Sql_b = "INSERT INTO BlackList(ProtectID, LoginID, LoginIP, RegDate) VALUES('$ProtectID', '$LoginID', '$UserIP', NOW())";
					mysqli_query($connect, $Sql_b);
?>
				<script type="text/javascript">
				<!--
					//alert("대리수강 방지를 위해 같은 기기에서 다른 아이디로\n\n로그인할 수 없습니다.\n\n\n\n만약 공동 PC로 수강을 진행하셔야 한다면 교육운영팀에 문의하셔서\n\n대리수강방지 기능을 해지하시기 바랍니다");
				//-->
				</script>
<?
					exit;
				}
			}else{ //대리수강 방지 쿠키가 없다면
				setCookie("MemberProtectID",$Row['ID'],time()+15768000,"/");
			}


		}else{ //대리수강 방지 기능이 미사용중이면
			//setCookie("MemberProtectID","",0,"/"); //초기화는 당부간 보류
		}
*/
		if($ID_Save=="Y") { //아이디 저장 체크시 쿠키에 저장
			setCookie("MemberSavedID",$Row['ID'],time()+15768000,"/");
		}else{
			setCookie("MemberSavedID","",0,"/");
		}

		//로그인 세션 처리
		$_SESSION["LoginMemberID"] = $Row['ID'];
		$_SESSION["LoginName"] = $Row['Name'];
		$_SESSION["LoginEduManager"] = $Row['EduManager'];
		$_SESSION["LoginMemberType"] = $Row['MemberType'];
		$_SESSION["LoginTestID"] = $Row['TestID'];

//echo "LOGIN_OK_SESSION-".	$_SESSION["LoginMemberID"]."<br>";

		// Brad(2021.11.27) : 수강여부 체크 세션
		$_SESSION["IsPlaying"] = "N";

	if($Row['Mandatory'] == 'N') { // 필수동의 안한 사용자라면 동의 화면으로 이동시키기.
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert("사이트 이용을 위해 필수동의항목을 포함한 항목들에 동의해주시기 바랍니다.");
		location.href='/public/member/after_join_terms.html?path=<?= $path ?>&pwchg=<?= $Row['PassChange'] ?>';
	//-->
	</SCRIPT>
<?
	exit;
	}
?>
<script type="text/javascript">
<!--
	<?
	if($TopLogin=="Y") {
	?>
		//alert("<?= "LOGIN_OK_SESSION1-".	$_SESSION["LoginMemberID"]?>");
		top.location.reload();
	<?
	}else{
	?>
		//	alert("<?= "LOGIN_OK_SESSION2-".	$_SESSION["LoginMemberID"]."-".$url?>");

	top.location.href="<?=$url?>";
	<?
	}
	?>
//-->
</script>
<?


}else{
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	alert("아이디 또는 비밀번호를 잘못 입력했습니다.");
	window.history.back();
//-->
</SCRIPT>
<?
}

mysqli_close($connect);
?>