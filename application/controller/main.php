<?php

class Controller_Main extends Controller {
    function action_index() {
        $data = Model_Poll::getAllPolls();
        $this->view->generate('main.php', 'template.php', $data);
    }
}

