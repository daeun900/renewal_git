<? 
if(empty($_SESSION['LoginMemberID'])) { 
?>
<script type="text/javascript">
<!--
	top.location.href="/public/member/login.html";
//-->
</script>
<?
    exit;
}
?>