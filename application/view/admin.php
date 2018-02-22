<form action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin/import'); ?>"  method="POST" enctype="multipart/form-data">
    <input name="csv_file[]" multiple="" type="file">
    <input name="submit" value="Загрузить" type="submit">
</form>