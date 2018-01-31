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
        $room_column = $columns['Номер квартиры'];
        $area_columnn = $columns['Площадь'];
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
        $sql = Db::getInstance()->prepare('INSERT INTO `rooms`(`roomNumber`, `totalArea`) VALUES(?, ?)');
        try
        {
            Db::getInstance()->beginTransaction();
            foreach ($csv_import as $key => $value) {
                $sql->execute(array($key, $value));
            }
            Db::getInstance()->commit();
        } catch (PDOException $e) {
            Db::getInstance()->rollback();
            throw $e;
        }
    }

    /**
     * Select rooms data from database
     * @return array
     */
    function tableView()
    {
        /*
        $sql = Db::getInstance()->prepare('SELECT `roomNumber`, `totalArea` FROM `rooms`');
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        */
        $rooms_table = Db::getInstance()->prefix . 'hoa_rooms';
        $result = Db::getInstance()->get_results("SELECT `roomNumber`, `totalArea` FROM $rooms_table", ARRAY_A);

        return $result;
    }

    /**
     * Delete rooms area from database
     */
    function deleteRooms()
    {
        $sql = Db::getInstance()->prepare('DELETE FROM `rooms`');
        $sql->execute(array());

    }
}