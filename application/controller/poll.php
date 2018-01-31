<?php

class Controller_Poll extends Controller
{
    function action_index()
    {
        Route::ErrorPage404();
    }
    
    function action_get()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        
        if (!empty($request[3])) {
            $this->model = new Model_Poll($request[3]);
            $data['pollQuestions'] = $this->model->getQuestions();
            $data['pollResult'] = $this->model->getPollResult();
            $data['pollAnswers'] = $this->model->getPollAnswers();
            $data['pollArchived'] = $this->model->isArchivedPoll();
            $data['pollId'] = $request[3];
            $this->view->generate('poll.php', 'template.php', $data);
        } else {
            Route::ErrorPage404();
        }
    }

    function action_add()
    {
        if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
            Model_Poll::addNewPoll($_POST);
            $data['message'] = "Голосование добавлено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        } else {
            $this->view->generate('poll_add.php', 'template.php');
        }
    }

    function action_delete()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $this->model = new Model_Poll($request[3]);
        if (!$this->model->isArchivedPoll()) {
            $this->model->deletePoll();
            $data['message'] = "Голосование удалено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        } else {
            $data['message'] = "Удаление голосования запрещено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_edit()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $this->model = new Model_Poll($request[3]);
        if (!$this->model->isArchivedPoll()) {
            $data['pollName'] = $this->model->getName();
            $data['quorum'] = $this->model->getQuorum();
            if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
                $this->model->editPoll($_POST);
                $data['message'] = "Голосование сохранено.";
                $data['url'] = sprintf('/');
                $this->view->generate('redirect.php', 'template.php', $data);
            } else {
                $this->view->generate('poll_edit.php', 'template.php', $data);
            }
        } else {
            $data['message'] = "Редактирование голосования запрещено.";
            $data['url'] = '/';
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_archive()
    {
        $request = explode('/', $_SERVER['REQUEST_URI']);
        $this->model = new Model_Poll($request[3]);
        $this->model->archivePoll();
        $data['message'] = "Редактирование запрещено.";
        $data['url'] = '/';
        $this->view->generate('redirect.php', 'template.php', $data);
    }
}

