<h1>Новое голосование</h1>
<form onsubmit="return validatePollAdd();" action="#" method="POST">
<label for="poll_name">Название голосования</label>
<input onchange="resetErrors('hoa_poll_name');" name="poll_name" id="hoa_poll_name" type="text">
<br>
<br>
<label for="poll_quorum">Минимум голосов для кворума</label>
<input onchange="resetErrors('hoa_poll_quorum');" name="poll_quorum" id="hoa_poll_quorum" type="text">
<br>
<br>
<strong>Список вопросов</strong><br>
<br>
<div id="poll_questions">
</div>
<br>
<button onclick="addPollQuestion(); return false;">Новый вопрос</button>
<br><br>
<input type="submit" name="submit" value="Сохранить">
</form>
<br>