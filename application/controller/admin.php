<?php

class Controller_Admin extends Controller {
    function __construct() {
        parent::__construct();
        session_start();
    }

    function action_index() {
        $this->model = new Model_Admin();
        $data = $this->model->tableView();
        if (count($data) == 0){
            $this->view->generate('admin.php', 'template.php');
        }
        else{
            $this->view->generate('admin_rooms.php', 'template.php', $data);
        }
    }

    function action_import(){
        if (isset($_POST['submit']) && $_POST['submit'] == 'Загрузить') {
            $this->model = new Model_Admin();
            $table = $this->model->previewCsv($_FILES);
            $this->view->generate('admin_table.php', 'template.php', $table);
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Импортировать данные') {
            $columns = array();
            foreach ($_POST['column_type'] as $key => $value){
                if (!($value=="Выберите тип столбца")) {
                     $columns[$value] =  $key;
                }
            }
            $this->model = new Model_Admin();
            try
            {
                $this->model->importCsv($columns);
            } catch (Exception $e) {
                $data['message'] = $e->getMessage();
                $this->view->generate('50x.php', 'template.php', $data);
            }
            $data['message'] = "Данные импортированы";
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_delete()
    {
        $this->model = new Model_Admin();
        $this->model->deleteRooms();
        $data['message'] = "Данные о квартирах удалены.";
        $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin');
        $this->view->generate('redirect.php', 'template.php', $data);
    }
}