<h1><?php echo htmlentities($data['message']); ?></h1>

<script language="JavaScript">
 window.setTimeout(function(){
     window.location.href = "<?php echo $data['url']; ?>";
 }, 2000);
</script>