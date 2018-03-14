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
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $sql = Db::getInstance()->prepare("SELECT name FROM $polls_table WHERE Id = %d", $this->id);
        $result = Db::getInstance()->get_row($sql, ARRAY_A);
        return $result['name'];
    }

    /**
     * Return the quorum setting for the poll
     * @return float
     */
    public function getQuorum()
    {
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $sql = Db::getInstance()->prepare("SELECT quorum FROM $polls_table WHERE Id = %d", $this->id);
        $result = Db::getInstance()->get_row($sql, ARRAY_A);
       return $result['quorum'];
    }

    /**
     * Get all questions text and question id.
     * @return array
     */
    function getQuestions()
    {
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT id, questionText FROM $questions_table WHERE pollId = %d", $this->id);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
        return $result;
    }

    /**
     * Get number voted rooms.
     * @return int
     */
    function getNumberRooms()
    {
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT `roomNumber` FROM $answers_table LEFT JOIN $questions_table ON $answers_table.`questionId` = $questions_table.`id` WHERE $questions_table.`pollId` = %d GROUP BY $answers_table.`roomNumber`", $this->id);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
        $rooms = (count($result));
        return $rooms;  //количество проголосовавших квартир
    }

    /**
     * Get area of voted rooms.
     * @return float
     */
    function getVotersArea()
    {
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT $answers_table.roomNumber, $rooms_table.totalArea FROM $answers_table LEFT JOIN $questions_table ON $answers_table.questionId = $questions_table.id LEFT JOIN $rooms_table ON $answers_table.roomNumber = $rooms_table.roomNumber WHERE $questions_table.pollId = %d GROUP BY $answers_table.roomNumber", $this->id);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
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
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $sql = Db::getInstance()->prepare("SELECT SUM($rooms_table.totalArea) AS area FROM $answers_table LEFT JOIN $rooms_table ON $answers_table.roomNumber = $rooms_table.roomNumber WHERE $answers_table.answer = %s AND $answers_table.questionId = %d", $answer, $questionId);
        $result = Db::getInstance()->get_row($sql, ARRAY_A);
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
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT questionId, answer, questionText FROM $answers_table LEFT JOIN $questions_table ON $questions_table.id = $answers_table.questionId WHERE pollId = %d AND roomNumber = %d", $this->id, $room);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
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
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT questionId, roomNumber, answer FROM $answers_table LEFT JOIN $questions_table ON $questions_table.id = $answers_table.questionId WHERE pollId = %d ORDER BY roomNumber", $this->id);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
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
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_row("SELECT MAX(roomNumber) AS room FROM $rooms_table", ARRAY_A);
        return $result['room'];
    }
    
    /**
     * Get polls list
     * @return array
     */
    static function getAllPolls() {
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
      try {
            $polls_table = Db::getInstance()->prefix . 'hoa_polls';
            $questions_table = Db::getInstance()->prefix . 'hoa_questions';
            Db::getInstance()->query("BEGIN");
            Db::getInstance()->insert($polls_table, array('name'=>$data['poll_name'], 'quorum'=>$data['poll_quorum']));
            $lastId = Db::getInstance()->insert_id;
            foreach ($data['poll_questions'] as $question) {
                Db::getInstance()->insert($questions_table, array('pollId'=>$lastId, 'questionText'=>$question));
            }
            Db::getInstance()->query("COMMIT");
        } catch (PDOException $e) {
            Db::getInstance()->query("ROLLBACK");
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
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("SELECT * FROM $answers_table INNER JOIN $questions_table ON $answers_table.`questionId` = $questions_table.`id` WHERE `pollId` = %d AND `roomNumber` = %d", $this->id, $room);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);
        if (count($result) !== 0) {
            throw new InvalidArgumentException('this room is already voted');
        } else {
            try {
                Db::getInstance()->query("BEGIN");
                foreach ($data['answers'] as $key => $value) {
                    Db::getInstance()->insert($answers_table, array('questionId'=>$key, 'roomNumber'=>$room, 'answer'=>$value));
                }
                Db::getInstance()->query("COMMIT");
            } catch (PDOException $e) {
                Db::getInstance()->query("ROLLBACK");
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
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
            try {
                Db::getInstance()->query("BEGIN");
                foreach ($answers as $key => $value) {
                    Db::getInstance()->update($answers_table, array('answer'=>$value), array('roomNumber'=>$room, 'questionId'=>$key), array('%s'), array('%d', '%d'));
                }
                Db::getInstance()->query("COMMIT");
            } catch (PDOException $e) {
                Db::getInstance()->query("ROLLBACK");
                throw $e;
            }
    }

    /**
     * Delete poll answers for selected room
     * @param $room int
     */
    function deleteAnswers($room)
    {
        $answers_table = Db::getInstance()->prefix . 'hoa_answers';
        $questions_table = Db::getInstance()->prefix . 'hoa_questions';
        $sql = Db::getInstance()->prepare("DELETE FROM $answers_table WHERE id IN (SELECT * FROM (SELECT $answers_table.id FROM $answers_table LEFT JOIN $questions_table ON $answers_table.questionId = $questions_table.id WHERE $answers_table.roomNumber = %d AND $questions_table.pollId = %d) AS `p`)", $room, $this->id);
        $result = Db::getInstance()->get_results($sql, ARRAY_A);

    }

    /**
     * Delete selected poll
     */
    function deletePoll()
    {
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        Db::getInstance()->delete($polls_table, array('id'=>$this->id));
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
            $polls_table = Db::getInstance()->prefix . 'hoa_polls';
            $pollId = $this->id;
            Db::getInstance()->update($polls_table, array('name' => $data['poll_name'], 'quorum' => $data['poll_quorum']), array('id' =>$pollId));
    }

    /**
     * Set poll as archived
     */
    function archivePoll()
    {
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        Db::getInstance()->update($polls_table, array('read_only' => "1"), array('id'=> $this->id));

    }

    /**
     * Check if poll is archived
     * @return bool
     */
    function isArchivedPoll()
    {
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $sql = Db::getInstance()->prepare("SELECT `read_only` from $polls_table where `Id` = %d", $this->id);
        $result = Db::getInstance()->get_row($sql, ARRAY_A);
        if ($result['read_only'] == 0) {
            return false;
        } else {
            return true;
        }
    }
}

