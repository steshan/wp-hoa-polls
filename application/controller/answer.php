<?php

class Controller_Answer extends Controller
{
    function action_index()
    {
        Route::ErrorPage404();
    }

    function action_fill()
    {
        if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $request = $routes[2];
            }
        }
        $data['pollId'] = $request;
        $this->model = new Model_Poll($request);
        $data['rooms'] = $this->model->getLastRoomNumber();
        if (isset($_POST['submit']) && $_POST['submit'] == __('Save', 'hoa_polls')) {
            try
            {
                $this->model->addAnswers($_POST);
                $data['message'] = __('The answer is saved.', 'hoa_polls');
                $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/%s', $request));
                $this->view->generate('redirect.php', 'template.php', $data);
            }
            catch (InvalidArgumentException $e) {

               if ($e->getMessage() == 'this room is already voted') {
                    $data['message'] = sprintf(__('Room №%s is already voted.', 'hoa_polls'), $_POST['roomNumber']);
                    $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=answer/edit/%s/%s', $request, $_POST['roomNumber']));
                    $this->view->generate('redirect.php', 'template.php', $data);
               } else {
                    $data['message'] = $e->getMessage();
                    $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/%s', $request));
                    $this->view->generate('redirect.php', 'template.php', $data);
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
        if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $data['pollId'] = $routes[2];
            }
            if (!empty($routes[3])) {
                $data['roomNumber'] = $routes[3];
            }
        }
        $this->model = new Model_Poll($data['pollId']);
        if (!$this->model->isArchivedPoll()) {
            $data['pollName'] = $this->model->getName();
            $data['answers'] = $this->model->getRoomAnswers($data['roomNumber']);
            if (isset($_POST['submit']) && $_POST['submit'] == __('Save', 'hoa_polls')) {
                $this->model->editAnswers($_POST['answers'], $data['roomNumber']);
                $data['message'] = __('The answer is saved.', 'hoa_polls');
                $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=poll/get/%s', $data['pollId']));
                $this->view->generate('redirect.php', 'template.php', $data);
            } else {
                $this->view->generate('answer_edit.php', 'template.php', $data);
            }
        } else {
            $data['message'] = __("You can't edit this poll.", 'hoa_polls');
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_delete()
    {
         if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $data['pollId'] = $routes[2];
            }
            if (!empty($routes[3])) {
                $data['roomNumber'] = $routes[3];
            }
        }
        $this->model = new Model_Poll($data['pollId']);
        if (!$this->model->isArchivedPoll()) {
            $this->model->deleteAnswers($data['roomNumber']);
            $data['pollQuestions'] = $this->model->getQuestions();
            $data['pollResult'] = $this->model->getPollResult();
            $data['pollAnswers'] = $this->model->getPollAnswers();
            $data['pollArchived'] = $this->model->isArchivedPoll();
            $data['message'] = __('The answer is deleted.', 'hoa_polls');
            $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=poll/get/%s', $data['pollId']));
            $this->view->generate('redirect.php', 'template.php', $data);
        } else {
            $data['message'] = __("You can't change results of poll.", 'hoa_polls');
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }
}