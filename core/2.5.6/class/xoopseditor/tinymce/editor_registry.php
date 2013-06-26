<?php
/**
 *  TinyMCE adapter for XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      editor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: editor_registry.php 8066 2011-11-06 05:09:33Z beckmi $
 */

return $config = array(
        "name"      =>  "tinymce",
        "class"     =>  "XoopsFormTinymce",
        "file"      =>  XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php",
        "title"     =>  _XOOPS_EDITOR_TINYMCE,
        "order"     =>  5,
        "nohtml"    =>  0
    );
?>
