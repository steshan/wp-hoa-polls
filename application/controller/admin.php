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
            $data['read_only'] = $this->model->isArchived();
            $this->view->generate('admin_rooms.php', 'template.php', $data);
        }
    }

    function action_import(){
        if (isset($_POST['submit']) && $_POST['submit'] == __('Upload', 'hoa_polls')) {
            $this->model = new Model_Admin();
            $table = $this->model->previewCsv($_FILES);
            $this->view->generate('admin_table.php', 'template.php', $table);
        }
        if (isset($_POST['submit']) && $_POST['submit'] == __('Import', 'hoa_polls')) {
            $columns = array();
            foreach ($_POST['column_type'] as $key => $value){
                if (!($value==__('Select a column type', 'hoa_polls'))) {
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
            $data['message'] = __('Data imported', 'hoa_polls');
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }

    function action_delete()
    {
        $this->model = new Model_Admin();
        $is_archived = $this->model->isArchived();
        //echo $is_archived;
        if ($is_archived){
            $data['message'] = __("You can't delete data", 'hoa_polls');
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin');
            $this->view->generate('redirect.php', 'template.php', $data);
        } else {
            $this->model->deleteRooms();
            $data['message'] = __('Data deleted', 'hoa_polls');
            $data['url'] = admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin');
            $this->view->generate('redirect.php', 'template.php', $data);
        }
    }
}