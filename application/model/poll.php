<?php

class Model_Poll extends Model {
    private $id;


    
    function __construct($pollId) {
        $this->id = $pollId;
    }
    
    /**
     * Return the total area of all rooms in whole building
     * @return integer
     */
    public function getAllArea()
    {
        /*$sql = Db::getInstance()->prepare('SELECT SUM(`totalArea`) AS `area` FROM `rooms`');
        $sql->execute(array($this->id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */

        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_row("SELECT SUM(`totalArea`) AS `area` FROM $rooms_table", ARRAY_A);
        return $result['area'];
    }

    /**
     * Return poll name.
     * @return string
     */
    public function getName()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `name` FROM `polls` WHERE `Id` = ?');
        $sql->execute(array($this->id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */

        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $result = Db::getInstance()->get_row("SELECT `name` FROM $polls_table WHERE `Id` = $this->id", ARRAY_A);
        return $result['name'];
    }

    /**
     * Return the quorum setting for the poll
     * @return float
     */
    public function getQuorum()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `quorum` FROM `polls` WHERE `Id` = ?');
        $sql->execute(array($this->id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */

        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $result = Db::getInstance()->get_row("SELECT `quorum` FROM $polls_table WHERE `Id` = $this->id", ARRAY_A);
        return $result['quorum'];
    }

    /**
     * Get all questions text and question id.
     * @return array
     */
    function getQuestions()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `id`, `questionText` FROM `questions` WHERE `pollId` = ?');
        $sql->execute(array($this->id));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */

        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT `id`, `questionText` FROM $questions_table WHERE `pollId` =$this->id", ARRAY_A);
        return $result;
    }

    /**
     * Get number voted rooms.
     * @return int
     */
    function getNumberRooms()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `roomNumber` FROM `answers` LEFT JOIN `questions` ON `answers`.`questionId` = `questions`.`id` WHERE `questions`.`pollId` = ? GROUP BY `answers`.`roomNumber`');
        $sql->execute(array($this->id));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */

        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT `roomNumber` FROM $answers_table LEFT JOIN $questions_table ON $answers_table.`questionId` = $questions_table.`id` WHERE $questions_table.`pollId` = $this->id GROUP BY $answers_table.`roomNumber`", ARRAY_A);
        $rooms = (count($result));
        return $rooms;  //количество проголосовавших квартир
    }

    /**
     * Get area of voted rooms.
     * @return float
     */
    function getVotersArea()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `answers`.`roomNumber`, `rooms`.`totalArea` FROM `answers` LEFT JOIN `questions` ON `answers`.`questionId` = `questions`.`id` LEFT JOIN `rooms` ON `answers`.`roomNumber` = `rooms`.`roomNumber` WHERE `questions`.`pollId` = ? GROUP BY `answers`.`roomNumber`');
        $sql->execute(array($this->id));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */

        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT $answers_table.roomNumber, $rooms_table.totalArea FROM $answers_table LEFT JOIN $questions_table ON $answers_table.questionId = $questions_table.id LEFT JOIN $rooms_table ON $answers_table.roomNumber = $rooms_table.roomNumber WHERE $questions_table.pollId = $this->id GROUP BY $answers_table.roomNumber", ARRAY_A);

        $sum = 0;
        foreach ($result as $row) {
            $sum += $row['totalArea'];
        }
        return $sum;
    }

    /**
     * Get vote result for particular question and answer type
     * @param integer $questionId Id of question.
     * @param string $answer Answer type ('YES', 'NO', 'SKIP').
     * @return float
     */
    function getResults($questionId, $answer)
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT SUM(`rooms`.`totalArea`) AS `area` FROM `answers` LEFT JOIN `rooms` ON `answers`.`roomNumber` = `rooms`.`roomNumber` WHERE `answers`.`answer` = ? AND `answers`.`questionId` = ?');
        $sql->execute(array($answer, $questionId));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */

        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_row("SELECT SUM($rooms_table.totalArea) AS area FROM $answers_table LEFT JOIN $rooms_table ON $answers_table.roomNumber = $rooms_table.roomNumber WHERE $answers_table.answer = $answer AND $answers_table.questionId = $questionId", ARRAY_A);

        return $result['area'];
    }

    /**
     * Get vote results for every question in a poll
     * @return array
     */
    function getPollResult() {
        $questions = $this->getQuestions();
        $totalArea = $this->getVotersArea();
        $percent_voted = 100 * $this->getVotersArea() / $this->getAllArea();
        $percent_quorum = $this->getQuorum();
        $data = array(
            'title' => $this->getName(),
            'hasQuorum' => $percent_voted >= $percent_quorum ? TRUE : FALSE,
            'percentVoted' => round($percent_voted, 2),
            'numberOfVoters' => $this->getNumberRooms()
        );
        $questions_data = array();
        if ($totalArea > 0) {
            foreach ($questions as $question) {
                array_push($questions_data, array(
                        'title' => $question['questionText'],
                        'yes' => round(100 * $this->getResults($question['id'], 'YES') / $totalArea, 2),
                        'no' => round(100 * $this->getResults($question['id'], 'NO') / $totalArea, 2),
                        'skip' => round(100 * $this->getResults($question['id'], 'SKIP') / $totalArea, 2)
                    )
                );
            }
        }
        $data['questions'] = $questions_data;
        return $data;
    }

    /**
     * Get answers for every question per one room
     * @param $room - room number
     * @return array
     */
    function getRoomAnswers($room){

        $questions = $this->getQuestions();
        /*
        $sql = Db::getInstance()->prepare('SELECT `questionId`, `answer`, `questionText` FROM `answers` LEFT JOIN `questions` ON `questions`.`id` = `answers`.`questionId` WHERE `pollId` = ? AND `roomNumber` =?');
        $sql->execute(array($this->id, $room));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */

        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT questionId, answer, questionText FROM $answers_table LEFT JOIN $questions_table ON $questions_table.id = $answers_table.questionId WHERE pollId = $this->id AND roomNumber = $room", ARRAY_A);


        $data = array();
        foreach ($result as $row) {
            foreach ($questions as $question)  {
                if ($question['id'] == $row['questionId']) {
                    $data[$question['id']] = $row['answer'];
                }
            }
        }
        return $result;

    }

    /**
     * Get answers for every question per each room
     * @return array
     */
    function getPollAnswers() {
        $questions = $this->getQuestions();
        /*
        $sql = Db::getInstance()->prepare('SELECT `questionId`, `roomNumber`, `answer` FROM `answers` LEFT JOIN `questions` ON `questions`.`id` = `answers`.`questionId` WHERE `pollId` = ?');
        $sql->execute(array($this->id));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT questionId, roomNumber, answer FROM $answers_table LEFT JOIN $questions_table ON $questions_table.id = $answers_table.questionId WHERE pollId = $this->id", ARRAY_A);

        $data = array();
        $l10n = array('YES' => 'Да', 'NO' => 'Нет', 'SKIP' => 'Воздержался');
        foreach ($result as $row) {
            foreach ($questions as $question)  {
                if ($question['id'] == $row['questionId']) {
                    $room = $row['roomNumber'];
                    $data[$room][0] = $room;
                    $data[$room][$question['id']] = $l10n[$row['answer']];
                }
            }
        }
        return $data;
    }

    /**
     * Get the last room number
     * @return int
     */
    static function getLastRoomNumber(){
        /*$sql = Db::getInstance()->prepare('SELECT MAX(`roomNumber`) AS `room` FROM `rooms`');
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_row("SELECT MAX(roomNumber) AS room FROM $rooms_table", ARRAY_A);
        return $result['room'];
    }
    
    /**
     * Get polls list
     * @return array
     */
    static function getAllPolls() {
        /*$sql = Db::getInstance()->prepare('SELECT `id`, `name`, `read_only` FROM `polls`');
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);*/
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $result = Db::getInstance()->get_results("SELECT id, name, read_only FROM $polls_table", ARRAY_A);
        return $result;
    }

    /**
     * Add new poll in database
     * @param $data array
     * @return bool
     */
    static function addNewPoll($data) {
        if (!(isset($data['poll_name']) && $data['poll_name'] !== '')) {
            throw new InvalidArgumentException('Poll name should be non-empty string');
        }
        if (!(isset($data['poll_quorum']) && is_numeric($data['poll_quorum']) && $data['poll_quorum'] >= 0 && $data['poll_quorum'] <= 100)) {
            throw new InvalidArgumentException('Quorum should be numeric value between 0 and 100');
        }
        if (!(isset($data['poll_questions']) && count($data['poll_questions']) > 0)) {
            throw new InvalidArgumentException('Questions should be array of strings');
        }
        foreach ($data['poll_questions'] as $question) {
            if ($question == '') {
                throw new InvalidArgumentException('Question should not be empty string');
            }
        }
        $sql = Db::getInstance()->prepare('INSERT INTO `polls`(`name`, `quorum`) VALUES(?, ?)');
        try {
            Db::getInstance()->beginTransaction();
            $sql->execute(array($data['poll_name'], $data['poll_quorum']));
            $lastId = Db::getInstance()->lastInsertId();
            foreach ($data['poll_questions'] as $question) {
                $sql = Db::getInstance()->prepare('INSERT INTO `questions`(`pollId`, `questionText`) VALUES(?, ?)');
                $sql->execute(array($lastId, $question));
            }
            Db::getInstance()->commit();
        } catch (PDOException $e) {
            Db::getInstance()->rollback();
            return false;
        }
        return true;
    }

    /**
     * Add poll answers in database
     * @param $data array
     * @return bool
     */
    function addAnswers($data) {
        $maxRoomNumber = Model_Poll::getLastRoomNumber();
        $numberOfQuestions = count($this->getQuestions());
        if (!(isset($data['roomNumber']) && is_numeric($data['roomNumber']) && $data['roomNumber'] >= 1 && $data['roomNumber'] <= $maxRoomNumber)) {
            throw new InvalidArgumentException('roomNumber should be integer value between 1 and number of rooms');
        }
        if (!(isset($data['answers']) && count($data['answers']) == $numberOfQuestions)) {
            throw new InvalidArgumentException('Not all questions answered');
        }
        $room = $data['roomNumber'];

        /*
        $sql = Db::getInstance()->prepare('SELECT * FROM `answers` INNER JOIN `questions` ON `answers`.`questionId` = `questions`.`id` WHERE `pollId` = ? AND `roomNumber` = ?');
        $sql->execute(array($this->id, $room));
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */

        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $result = Db::getInstance()->get_results("SELECT * FROM $answers_table INNER JOIN $questions_table ON $answers_table.`questionId` = $questions_table.`id` WHERE `pollId` = $this->id AND `roomNumber` = $room", ARRAY_A);

        if (count($result) !== 0) {
            throw new InvalidArgumentException('this room is already voted');
        } else {
            $sql = Db::getInstance()->prepare('INSERT INTO `answers`(`questionId`, `roomNumber`, `answer`) VALUES(?, ?, ?)');
            try {
                Db::getInstance()->beginTransaction();
                foreach ($data['answers'] as $key => $value) {
                    $sql->execute(array($key, $room, $value));
                }
                Db::getInstance()->commit();
            } catch (PDOException $e) {
                Db::getInstance()->rollback();
                throw $e;
            }
        }
    }

    /**
     * Edit poll answers for selected room
     * @param $answers array
     * @param $room int
     */
    function editAnswers($answers, $room)
    {
            $sql = Db::getInstance()->prepare('UPDATE `answers` SET `answer` = ? WHERE `roomNumber` = ? AND `questionId` = ?');
            try {
                Db::getInstance()->beginTransaction();
                foreach ($answers as $key => $value) {
                    $sql->execute(array($value, $room, $key));
                }
                Db::getInstance()->commit();
            } catch (PDOException $e) {
                Db::getInstance()->rollback();
                throw $e;
            }
    }

    /**
     * Delete poll answers for selected room
     * @param $room int
     */
    function deleteAnswers($room)
    {
            $sql = Db::getInstance()->prepare('DELETE FROM `answers` WHERE `id` IN (SELECT * FROM (SELECT `answers`.`id` FROM `answers` LEFT JOIN `questions` ON `answers`.`questionId` = `questions`.`id` WHERE `answers`.`roomNumber` = ? AND `questions`.`pollId` = ?) AS `p`)');
            $sql->execute(array($room, $this->id));
    }

    /**
     * Delete selected poll
     */
    function deletePoll()
    {
            $sql = Db::getInstance()->prepare('DELETE FROM `polls` WHERE `id` = ?');
            $sql->execute(array($this->id));
    }

    /**
     * Edit selected poll
     * @param $data array
     */
    function editPoll($data)
    {
            if (!(isset($data['poll_name']) && $data['poll_name'] !== '')) {
                throw new InvalidArgumentException('Poll name should be non-empty string');
            }
            if (!(isset($data['poll_quorum']) && is_numeric($data['poll_quorum']) && $data['poll_quorum'] >= 0 && $data['poll_quorum'] <= 100)) {
                throw new InvalidArgumentException('Quorum should be numeric value between 0 and 100');
            }
            $sql = Db::getInstance()->prepare('UPDATE `polls` SET `name` = ?, `quorum` = ? WHERE `id` = ?');
            $sql->execute(array($data['poll_name'], $data['poll_quorum'], $this->id));
    }

    /**
     * Set poll as archived
     */
    function archivePoll()
    {
        $sql = Db::getInstance()->prepare('UPDATE `polls` SET `read_only` = "1" WHERE `id` =  ?');
        $sql->execute(array($this->id));
    }

    /**
     * Check if poll is archived
     * @return bool
     */
    function isArchivedPoll()
    {
        /*
        $sql = Db::getInstance()->prepare('Select `read_only` from `polls` where `Id` = ?');
        $sql->execute(array($this->id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        */

        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $result = Db::getInstance()->get_row("SELECT `read_only` from $polls_table where `Id` = $this->id", ARRAY_A);
        if ($result['read_only'] == 0) {
            return false;
        } else {
            return true;
        }
    }
}

