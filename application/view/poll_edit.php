<h1>Редактировать голосование</h1>
<form onsubmit="return validatePollEdit();" action="#" method="POST">
<label for="poll_name">Название голосования</label>
<input name="poll_name" id="hoa_poll_name" type="text" value = "<?php echo $data['pollName'] ?>">
<br>
<br>
<label for="poll_quorum">Минимум голосов для кворума</label>
<input name="poll_quorum" id="hoa_poll_quorum" type="text" value = "<?php echo $data['quorum'] ?>">
<br>
<br>
     <input type="submit" name="submit" value="Сохранить">
</form>
<br>