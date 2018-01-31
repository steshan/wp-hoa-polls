<h1><a href="/poll/get/<?php echo $data['pollId']; ?>"><?php echo htmlentities($data['pollName']); ?></a></h1>
<h2>Квартира <?php echo htmlentities($data['roomNumber']); ?></h2>


<br>
<form action="#" method="POST">
    <?php
    foreach ($data['answers'] as $question) {
        $input = $question['questionText'];
       switch ($question['answer']){
            case "YES":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES" checked="checked"><label>ДА</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO"><label>НЕТ</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP"><label>ВОЗДЕРЖАЛСЯ</label>';
                break;
            case "NO":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES"><label>ДА</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO" checked="checked"><label>НЕТ</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP"><label>ВОЗДЕРЖАЛСЯ</label>';
                break;
            case "SKIP":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES"><label>ДА</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO"><label>НЕТ</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP" checked="checked"><label>ВОЗДЕРЖАЛСЯ</label>';
                break;
        }
        echo '<br>';
        echo $input;
    }
    ?>
    <br>
    <br>
    <input type="submit" name="submit" value="Сохранить">
</form>

