<?php

class Controller_Poll extends Controller
{
    function action_index()
    {
        Route::ErrorPage404();
    }
    
    function action_get()
    {
        $request = $this->getRequest();
        if (!empty($request)) {
            $this->model = new Model_Poll($request);
            $data['pollQuestions'] = $this->model->getQuestions();
            $data['pollResult'] = $this->model->getPollResult();
            $data['pollAnswers'] = $this->model->getPollAnswers();
            $data['pollArchived'] = $this->model->isArchivedPoll();
            $data['pollId'] = $request;
            $this->view->generate('poll.php', 'template.php', $data);
        } else {
            Route::ErrorPage404();
        }
    }

    function action_add()
    {
        if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
            try {
                Model_Poll::addNewPoll($_POST);
                $data['message'] = "Голосование добавлено.";
                $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
                $this->view->generate('redirect.php', 'template.php', $data);
            } catch (InvalidArgumentException $e) {
                if ($e->getMessage() == "Poll name should be non-empty string") {
                    $data['message'] = "Введите название голосования.";
                    $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/add');
                    $this->view->generate('redirect.php', 'template.php', $data);
                } elseif ($e->getMessage() == "Quorum should be numeric value between 0 and 100") {
                    $data['message'] = "Кворум должно быть число больше 0 и меньше 100.";
                    $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/add');
                    $this->view->generate('redirect.php', 'template.php', $data);
                } elseif ($e->getMessage() == "Questions should be array of strings") {
                    $data['message'] = "Добавьте вопросы голосования.";
                    $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/add');
                    $this->view->generate('redirect.php', 'template.php', $data);
                } elseif ($e->getMessage() == "Question should not be empty string") {
                    $data['message'] = "Вопрос не может быть пустым.";
                    $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/add');
                    $this->view->generate('redirect.php', 'template.php', $data);
                } else {
                    throw $e;
                }
            }
        } else {
            $this->view->generate('poll_add.php', 'template.php');
        }
    }

    function action_delete()
    {
        if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $request = $routes[2];
            }
        }
        $this->model = new Model_Poll($request);
        if (!$this->model->isArchivedPoll()) {
            $this->model->deletePoll();
            $data['message'] = "Голосование удалено.";
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
            $this->view->generate('redirect.php', 'template.php', $data);
        } else {
            $data['message'] = "Удаление голосования запрещено.";
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_edit()
    {
      if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $request = $routes[2];
            }
        }
        $this->model = new Model_Poll($request);
        if (!$this->model->isArchivedPoll()) {
            $data['pollName'] = $this->model->getName();
            $data['quorum'] = $this->model->getQuorum();
            if (isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
                try {
                    $this->model->editPoll($_POST);
                    $data['message'] = "Голосование сохранено.";
                    $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
                    $this->view->generate('redirect.php', 'template.php', $data);

                }
                catch (InvalidArgumentException $e) {
                    if ($e->getMessage() == "Poll name should be non-empty string") {
                        $data['message'] = 'Введите название голосования';
                        $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=poll/edit/%s', $request));
                        $this->view->generate('redirect.php', 'template.php', $data);
                    } elseif ($e->getMessage() == "Quorum should be numeric value between 0 and 100") {
                        $data['message'] = 'Кворум должно быть число больше 0 и меньше 100.';
                        $data['url'] = admin_url(sprintf('admin.php?page=homeowners-association-polls&hoa_path=poll/edit/%s', $request));
                        $this->view->generate('redirect.php', 'template.php', $data);
                    } else {
                        throw $e;
                    }


                }
            } else {
                $this->view->generate('poll_edit.php', 'template.php', $data);
            }
        } else {
            $data['message'] = "Редактирование голосования запрещено.";
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_archive()
    {
       if (isset($_GET['hoa_path'])) {
            $routes = explode('/', $_GET['hoa_path']);
            if (!empty($routes[2])) {
                $request = $routes[2];
            }
        }
        $this->model = new Model_Poll($request);
        $this->model->archivePoll();
        $data['message'] = "Редактирование запрещено.";
        $data['url'] = admin_url('admin.php?page=homeowners-association-polls');
        $this->view->generate('redirect.php', 'template.php', $data);
    }
}

