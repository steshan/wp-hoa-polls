<?php

class Model_Admin extends Model
{
    /**
     * Return csv file as array
     * @param $file array
     * @return array
     */
    function previewCsv($file)
    {
        if (isset($file['csv_file']) && file_exists($file['csv_file']['tmp_name'][0])) {
            $file_name = $file['csv_file']['tmp_name'][0];
            $handle = fopen($file_name, 'r');
            $_SESSION['import_csv_file'] = file_get_contents($file_name);
            while (false !== ($row = fgetcsv($handle, 1024, ';'))) {
                $table[] = $row;
            }
            fclose($handle);
            return $table;
        } else {
            throw new Exception('File not found.');
        }
    }

    /**
     * Import selected columns from csv file into database
     * @param $columns array
     */
    function importCsv($columns)
    {
        $room_column = $columns[__('Room number', 'hoa_polls')];
        $area_columnn = $columns[__('Room area', 'hoa_polls')];
        $csv_lines = explode(PHP_EOL, $_SESSION['import_csv_file']);
        foreach ($csv_lines as $csv_line) {
            $csv_cells[] = str_getcsv($csv_line, ';');
        }
        $csv_import = array();
        foreach ($csv_cells as $csv_line) {
            if (isset($csv_line[$room_column]) and isset($csv_line[$area_columnn])) {
                $csv_import[$csv_line[$room_column]] = $csv_line[$area_columnn];
            }
        }
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        try
        {
            Db::getInstance()->query("BEGIN");
            foreach ($csv_import as $key => $value) {
                Db::getInstance()->insert($rooms_table, array('roomNumber'=>$key, 'totalArea'=>$value));
            }
            Db::getInstance()->query("COMMIT");
        } catch (PDOException $e) {
            Db::getInstance()->query("ROLLBACK");
            throw $e;
        }
    }

    /**
     * Select rooms data from database
     * @return array
     */
    function tableView()
    {
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_results("SELECT `roomNumber`, `totalArea` FROM $rooms_table", ARRAY_A);
        return $result;
    }

    /**
     * Delete rooms area from database
     */
    function deleteRooms()
    {
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        Db::getInstance()->query("DELETE FROM $rooms_table");
    }

     /**
     * Check if we have at least one archived poll
     * @return bool
     */
    function isArchived()
    {
        $polls_table = Db::getInstance()->prefix . 'hoa_polls';
        $result = Db::getInstance()->get_row("SELECT count(`id`) as read_count from $polls_table where `read_only` = 1", ARRAY_A);
        //print_r($result);
        if ($result['read_count'] == 0) {
            return false;
        } else {
            return true;
        }
    }

}