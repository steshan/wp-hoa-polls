<h1><a href="/poll/get/<?php echo $data['pollId']; ?>"><?php echo htmlentities($data['pollName']); ?></a></h1>

<form action="/answer/fill/<?php echo $data['pollId']; ?>" method="POST">
    <label for="roomNumber">Введите номер квартиры</label>
    <input type="text" name="roomNumber" id="roomNumber">
    <br>
    <br>
    <?php
    foreach ($data['pollQuestions'] as $question) {
        echo $question['questionText'] . '<input type="radio" name="answers[' . $question['id'] . ']" value = "YES"><label>ДА</label><input type="radio" name="answers[' . $question['id'] . ']" value = "NO"><label>НЕТ</label><input type="radio" name="answers[' . $question['id'] . ']" value = "SKIP"><label>Воздержался</label> <br>';
    }
    ?>
    <br>
    <input type="submit" name="submit" value="Сохранить">
</form>