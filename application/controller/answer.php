<?php

class Controller_Answer extends Controller
{
    function action_index()
    {
        Route::ErrorPage404();
    }

    function action_fill()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $data['pollId'] = $request[3];
        $this->model = new Model_Poll($request[3]);
        if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
            try
            {
                $this->model->addAnswers($_POST);
                $data['message'] = "Ответ сохранен.";
                $data['url'] = sprintf("/answer/fill/%s", $request[3]);
                $this->view->generate('redirect.php', 'template.php', $data);
            }
            catch (InvalidArgumentException $e)
            {
                if ($e->getMessage() == "this room is already voted") {
                    $data['message'] = sprintf("Квартира №%s уже проголосовала.", $_POST['roomNumber']);
                    $data['url'] = sprintf("/answer/edit/%s/%s", $request[3], $_POST['roomNumber']);
                    $this->view->generate('redirect.php', 'template.php', $data);
                } else {
                    throw $e;
                }
            }
        } else {
            $data['pollQuestions'] = $this->model->getQuestions();
            $data['pollName'] = $this->model->getName();
            $this->view->generate('answer_add.php', 'template.php', $data);
        }
    }

    function action_edit()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $data['pollId'] = $request[3];
        $data['roomNumber']= $request[4];
        $this->model = new Model_Poll($request[3]);
        if (!$this->model->isArchivedPoll()) {
            $data['pollName'] = $this->model->getName();
            $data['answers'] = $this->model->getRoomAnswers($data['roomNumber']);

            if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
                $this->model->editAnswers($_POST['answers'], $data['roomNumber']);
                $data['message'] = "Ответ сохранен.";
                $data['url'] = sprintf("/poll/get/%s", $data['pollId']);
                $this->view->generate('redirect.php', 'template.php', $data);
            } else {
                $this->view->generate('answer_edit.php', 'template.php', $data);
            }
        } else {
            $data['message'] = "Редактирование результатов голосования запрещено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_delete()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $this->model = new Model_Poll($request[3]);
        if (!$this->model->isArchivedPoll()) {
            $this->model->deleteAnswers($request[4]);
            $data['pollId'] = $request[3];
            $data['pollQuestions'] = $this->model->getQuestions();
            $data['pollResult'] = $this->model->getPollResult();
            $data['pollAnswers'] = $this->model->getPollAnswers();
            $data['pollArchived'] = $this->model->isArchivedPoll();
            $data['message'] = "Ответ удален.";
            $data['url'] = sprintf("/poll/get/%s", $data['pollId']);
            $this->view->generate('redirect.php', 'template.php', $data);
            //$this->view->generate('poll.php', 'template.php', $data);
        } else {
            $data['message'] = "Редактирование результатов голосования запрещено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }
}