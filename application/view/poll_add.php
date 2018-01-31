<h1>Новое голосование</h1>
<form action="#" method="POST">
<label for="poll_name">Название голосования</label>
<input name="poll_name" id="poll_name" type="text">
<br>
<br>
<label for="poll_quorum">Минимум голосов для кворума</label>
<input name="poll_quorum" id="poll_quorum" type="text">
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