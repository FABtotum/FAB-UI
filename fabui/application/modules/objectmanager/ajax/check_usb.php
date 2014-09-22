<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** LOAD FROM USB DISK */
$_destination = '/var/www/fabui/application/modules/objectmanager/temp/media.json';
$_command     = 'sudo python '.PYTHON_PATH.'usb_browser.py  --dest='.$_destination;
shell_exec($_command);


$tree = json_decode(file_get_contents($_destination, FILE_USE_INCLUDE_PATH), TRUE);

if(sizeof($tree)){
?>
  <div class="tree smart-form">
    <ul>
        <?php foreach($tree as $folder): ?>
        
            <li><span data-loaded="false" data-folder="<?php echo $folder; ?>"><i class="fa fa-lg fa-folder-open"></i> <?php echo rtrim(str_replace("/media/", '', $folder), '/'); ?></span>
                <ul></ul>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?   
}

?>