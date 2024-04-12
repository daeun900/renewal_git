<?
if(empty($_SESSION['LoginMemberID'])) {
?>
<script type="text/javascript">
<!--
	//alert("<?= "LOGIN_OK_SESSION2-".	$_SESSION["LoginMemberID"] ?>");
	top.location.href="/public/member/login.html";
//-->
</script>
<?
exit;
}
?>