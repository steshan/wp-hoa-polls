<h1><a href="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/get/') . $data['pollId']; ?>"><?php echo htmlentities($data['pollName']); ?></a></h1>

<form onsubmit="return validateAnswerAdd();" action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/') . $data['pollId']; ?>" method="POST">
    <label for="roomNumber">Введите номер квартиры</label>
    <input onchange="resetErrors('hoa_room_number');" type="text" name="roomNumber" id="hoa_room_number">
    <br>
    <br>
    <div onchange="resetErrors('hoaAnswerAdd');" id="hoaAnswerAdd">
    <?php
    foreach ($data['pollQuestions'] as $question) {
        echo $question['questionText'] . '<input type="radio" name="answers[' . $question['id'] . ']" value = "YES"><label>ДА</label><input type="radio" name="answers[' . $question['id'] . ']" value = "NO"><label>НЕТ</label><input type="radio" name="answers[' . $question['id'] . ']" value = "SKIP"><label>Воздержался</label> <br>';
    }
    ?>
    </div>
    <br>
    <input type="submit" name="submit" value="Сохранить">
</form>