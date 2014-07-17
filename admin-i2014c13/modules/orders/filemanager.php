<?php
require_once '../../serverscripts/__loader.php';

if (!isset($_SESSION[PAGE_SESSIONID]['id']))
    die($___LANGUAGER->getText('common', 'sytem_logged_out'));
if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][0] == '0')
    die($___LANGUAGER->getText('common', 'dont_have_permission'));

/* * ***********************************************************************
  Program: File Manager for TinyMCE 3.
  Version: 0.13
  Author: Mad182
  E-mail: Mad182@gmail.com
  WEB: http://sourceforge.net/projects/tinyfilemanager/

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  TO DO:
 * image manipulations - crop/resize, filters, etc. - partly done;
 * fix bugs in IE and Opera	
 * *********************************************************************** */

//user authentification (add your own, if you need one)
//if (NO AUTENTIFICATION) { die('Unauthorized!'); }
//config (if your TinyMCE location is different from example, you should also check paths at line ~360)
$file_root = FRONTEND_ABSDIRPATH. ORDERFILESPATH;  //where to store files, must be created and writable
//pr pridavani novych clankov zvoli adresar noveho clanku
if (isset($_SESSION[PAGE_SESSIONID]['order_dirname'])) {
    $file_root .= $_SESSION[PAGE_SESSIONID]['order_dirname'];
}
$root_path = '';   //path from webroot, without trailing slash. If your page is located in http://www.example.com/john/, this should be '/john'
$thmb_size = 200;        //max size of preview thumbnail
$no_script = false;       //true/false - turns scripts into text files
$lang = $___LANGUAGER->getAdminLangCode();            //language (look in /mfm/lang/ for available)
error_reporting(0);    //'E_ALL' for debugging, '0' for use
//array of known file types (used for icons)
$file_class = array(
    'swf',
    'txt',
    'htm',
    'html',
    'zip',
    'gz',
    'rar',
    'cab',
    'tar',
    '7z',
    'deb',
    'rpm',
    'php',
    'mp3',
    'ogg',
    'mid',
    'avi',
    'mpg',
    'flv',
    'mpeg',
    'pdf',
    'ttf',
    'exe'
);

//upload class (see file for credits)
require(ADMIN_ABSDIRPATH.'filemanager/mfm/class.upload.php');

//lang
$lng = array();
require(ADMIN_ABSDIRPATH.'filemanager/mfm/lang/lang_' . strtolower($lang) . '.php');
header("Content-type: text/html; charset=utf-8");

//stand alone or tynimce?
$mode = 'mce';
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

//replaces special characters for latvian and russian lang., and removes all other
function format_filename($filename) {
    $bads = array(' ', 'ā', 'č', 'ē', 'ģ', 'ī', 'ķ', 'ļ', 'ņ', 'ŗ', 'š', 'ū', 'ž', 'Ā', 'Č', 'Ē', 'Ģ', 'Ī', 'Ķ', 'Ļ', 'Ņ', 'Ŗ', 'Š', 'Ū', 'Ž', '$', '&', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'ЫЬ', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'шщ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $good = array('-', 'a', 'c', 'e', 'g', 'i', 'k', 'l', 'n', 'r', 's', 'u', 'z', 'A', 'C', 'E', 'G', 'I', 'K', 'L', 'N', 'R', 'S', 'U', 'Z', 's', 'and', 'A', 'B', 'V', 'G', 'D', 'E', 'J', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'C', 'S', 'S', 'T', 'T', 'E', 'Ju', 'Ja', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'c', 's', 't', 't', 'y', 'z', 'e', 'ju', 'ja');
    $filename = str_replace($bads, $good, trim($filename));
    $allowed = "/[^a-z0-9\\.\\-\\_\\\\]/i";
    $filename = preg_replace($allowed, '', $filename);
    return $filename;
}

//convert file size to human readable format
function byte_convert($bytes) {
    $symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
    $exp = 0;
    $converted_value = 0;
    if ($bytes > 0) {
        $exp = floor(log($bytes) / log(1024));
        $converted_value = ( $bytes / pow(1024, floor($exp)) );
    }
    return sprintf('%.2f ' . $symbol[$exp], $converted_value);
}

//show recursive directory tree
function print_tree($dir = '.') {
    global $root_path;
    echo '<ul class="dirlist">';
    $d = opendir($dir);
    while ($f = readdir($d)) {
        if (strpos($f, '.') === 0)
            continue;
        $ff = $dir . '/' . $f;
        if (is_dir($ff)) {
            echo '<li><a href="' . $root_path . $ff . '/" onclick="load(\'filemanager.php?viewdir=' . $ff . '\',\'view-files\'); return false;">' . $f . '</a>';
            print_tree($ff);
            echo '</li>';
        }
    }
    echo '</ul>';
}

//show file list of given directory
function print_files($c = '.') {
    global $root_path, $mode, $thmb_size, $file_class, $lng;
    echo('<table id="file-list">');
    $d = opendir($c);
    $i = 0;
    while ($f = readdir($d)) {
        if (strpos($f, '.') === 0)
            continue;
        $ff = $c . '/' . $f;
        $ext = strtolower(substr(strrchr($f, '.'), 1));
        if (!is_dir($ff) && substr($f, 0, 3) != 'tn_') {
            echo '<tr' . ($i % 2 ? ' class="light"' : ' class="dark"') . '>';
            //show preview and different icon, if file is image
            $imageinfo = @getimagesize($ff);
            if ($imageinfo && $imageinfo[2] > 0 && $imageinfo[2] < 4) {
                $resize = '';
                if ($imageinfo[0] > $thmb_size or $imageinfo[1] > $thmb_size) {
                    if ($imageinfo[0] > $imageinfo[1]) {
                        $resize = ' style="width: ' . $thmb_size . 'px;"';
                    } else {
                        $resize = ' style="height: ' . $thmb_size . 'px;"';
                    }
                }
                if ($imageinfo[2] == 1) {
                    $imagetype = "image_gif";
                } elseif ($imageinfo[2] == 2) {
                    $imagetype = "image_jpg";
                } elseif ($imageinfo[2] == 3) {
                    $imagetype = "image_jpg";
                } else {
                    $imagetype = "image";
                }
                echo '<td><a class="file thumbnail ' . $imagetype . '" href="#">' . $f . '<span><img' . $resize . ' src="' . PAGE_URL.str_replace(FRONTEND_ABSDIRPATH,'',$ff) . '" /></span></a>';
                echo '</td>';
                //known file types
            } elseif (in_array($ext, $file_class)) {
                echo '<td><a class="file file_' . $ext . '" href="#">' . $f . '</a>';
                echo '</td>';
                //all other files
            } else {
                echo '<td><a class="file unknown" href="#">' . $f . '</a>';
                echo '</td>';
            }
            echo '<td>' . byte_convert(filesize($ff)) . '</td>';
            echo '<td class="delete"><a href="#" title="' . $lng['delete_title'] . '" onclick="delete_file(\'' . $c . '\',\'' . $f . '\');">' . $lng['delete'] . '</a></td>';
            echo '</tr>';
            $i++;
        }
    }
    echo('</table>');
}

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

$uploadstatus = 0;
if (isset($_GET['status'])) {
    $uploadstatus = $_GET['status'];
}

//handles file uploads
if (isset($_FILES['new_file']) && isset($_POST['return'])) {
    if (is_dir($_POST['return'])) {
        $handle = new upload($_FILES['new_file']);
        if ($handle->uploaded) {
            $handle->file_new_name_body = substr($_FILES['new_file']['name'], 0, strpos($_FILES['new_file']['name'], '.'));

            //setting image resize options if gallery or another
            //if (isset($_POST['generatethumbnail']) && $_POST['generatethumbnail'] == 'yes') {
                $handle->image_resize = true;
                $handle->image_y = 1200;
                $handle->image_x = 1600;
                $handle->image_ratio_no_zoom_in = true;
            //}

            $handle->mime_check = $no_script;
            $handle->no_script = $no_script;
            $handle->file_safe_name = false;
            $handle->process($_POST['return'] . '/');
            if ($handle->processed) {
                //generate thumbnail
                //if (isset($_POST['generatethumbnail']) && $_POST['generatethumbnail'] == 'yes') {
                    $handle->file_new_name_body = 'tn_' . substr($_FILES['new_file']['name'], 0, strpos($_FILES['new_file']['name'], '.'));
                    $handle->image_resize = true;
                    $handle->image_x = 200;
                    $handle->image_ratio_y = true;
                    $handle->file_safe_name = false;
                    $handle->process($_POST['return'] . '/');
                //}
                $handle->clean();
                $uploadstatus = 1;
            } else {
                //uncomment for upload debugging
                //echo 'error : ' . $handle->error;
                $uploadstatus = 2;
            }
        }
    } else {
        $uploadstatus = 3;
    }
}

//remove unnecessary folder
if (isset($_GET['deletefolder'])) {
    $deldir = $_GET['deletefolder'];

    //fix by Arpad SZUCS Jul 6, 2011
    $block = false;
    if (strstr($_SERVER['SERVER_SOFTWARE'], "Win32")) {
        // windows is case insesitive:
        $block = !strcasecmp($deldir, $file_root);
    } else {
        $block = !strcmp($deldir, $file_root);
    }

    if (is_dir($_GET['deletefolder'])) {
        if (!$block && delete_directory($deldir)) {
            header('Location: filemanager.php?status=4');
        } else {
            $uploadstatus = 5;
        }
    } else {
        $uploadstatus = 6;
    }
}

//display only directory tree for dynamic AHAH requests
if (isset($_GET['viewtree'])) {
    ?>
    <ul class="dirlist">
        <li><a href="<?php echo $root_path . $file_root; ?>/" onclick="load('filemanager.php?viewdir=<?php echo $file_root; ?>','view-files'); return false;">Súbory</a> <a href="#" onclick="load('filemanager.php?viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
            <?php print_tree($file_root); ?>
        </li>
    </ul>
    <?php
    exit;
}

//display file list for dynamic requests
if (isset($_GET['viewdir'])) {
    ?>
    <ul id="browser-toolbar">
        <li class="file-refresh"><a href="#" title="<?php echo $lng['refresh_files_title']; ?>" onclick="load('filemanager.php?viewdir=<?php echo $_GET['viewdir']; ?>','view-files'); return false;"><?php echo $lng['refresh']; ?></a></li>
    </ul>

    <form id="load-file" action="" class="load-file" method="post" enctype="multipart/form-data">

        <fieldset>
            <legend><?php echo $lng['new_file_title']; ?></legend>
            <input type="hidden" value="<?php echo $_GET['viewdir']; ?>" name="return" />
            <label><?php echo $lng['form_file']; ?><input type="file" name="new_file" /></label>
            <input type="submit" value="<?php echo $lng['form_submit']; ?>" />
                                    
        </fieldset>		
    </form>
    <?php
    //create directory and show results
    if (isset($_GET['newdir'])) {
        $new_title = format_filename($_GET['newdir']);
        if (!is_dir($_GET['viewdir'] . '/' . $new_title)) {
            if (mkdir($_GET['viewdir'] . '/' . $new_title, 0777)) {
                echo '<p class="successful">&quot;' . $new_title . '&quot;' . $lng['message_created_folder'] . '</p>';
            } else {
                echo '<p class="failed">' . $lng['message_cannot_create'] . '&quot;' . $new_title . '&quot;!<br />' . $lng['message_cannot_write'] . '</p>';
            }
        } else {
            echo '<p class="failed">' . $lng['message_cannot_create'] . '&quot;' . $new_title . '&quot;!<br />' . $lng['message_exists'] . '</p>';
        }
    }

    //remove unnecessary files
    if (isset($_GET['deletefile'])) {
        if (!file_exists($_GET['viewdir'] . '/' . $_GET['deletefile'])) {
            echo '<p class="failed">' . $lng['message_cannot_delete_nonexist'] . '</p>';
        } else {
            if (unlink($_GET['viewdir'] . '/' . $_GET['deletefile'])) {
                echo '<p class="successful">' . $lng['message_deleted'] . '</p>';
                if (file_exists($_GET['viewdir'] . '/tn_' . $_GET['deletefile'])) {
                    if (!unlink($_GET['viewdir'] . '/tn_' . $_GET['deletefile'])) {
                        echo '<p class="failed">Thumbnail not deleted</p>';
                    }
                }
            } else {
                echo '<p class="failed">' . $lng['message_cannot_delete'] . '</p>';
            }
        }
    }

    //show status messages by code
    if (isset($_GET['status'])) {
        //upload file
        if ($_GET['status'] == 1) {
            echo '<p class="successful">' . $lng['message_uploaded'] . '</p>';
        } elseif ($_GET['status'] == 2) {
            echo '<p class="failed">' . $lng['message_upload_failed'] . '</p>';
        } elseif ($_GET['status'] == 3) {
            echo '<p class="failed">' . $lng['message_wrong_dir'] . '</p>';
            //remove directory
        } elseif ($_GET['status'] == 4) {
            echo '<p class="successful">' . $lng['message_folder_deleted'] . '</p>';
        } elseif ($_GET['status'] == 5) {
            echo '<p class="failed">' . $lng['message_cant_delete_folder'] . '</p>';
        } elseif ($_GET['status'] == 6) {
            echo '<p class="failed">' . $lng['message_folder_not_exist'] . '</p>';
        }
    }

    //finally show file list
    print_files($_GET['viewdir']);
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$lang; ?>" lang="<?=$lang; ?>">

    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="content-language" content="<?=$lang; ?>" />
        <title>Articles</title>
        <link rel="stylesheet" href="<?=PAGE_URL.ADMINDIRNAME; ?>filemanager/mfm/style.css" type="text/css" />
        <script type="text/javascript">
            //<![CDATA[
    		
            //load content using AHAH (asynchronous HTML and HTTP)
            function ahah(url, target, callback) {
                document.getElementById(target).innerHTML = '<img src="<?=PAGE_URL.ADMINDIRNAME; ?>filemanager/mfm/loading.gif" alt="" /> <?php echo $lng['loading']; ?>';
                if (window.XMLHttpRequest) {
                    req = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    req = new ActiveXObject("Microsoft.XMLHTTP");
                }
                if (req != undefined) {
                    req.onreadystatechange = function() {ahahDone(url, target, callback);};
                    var url = url + ((url.indexOf("?") == -1) ? "?" : "&") + escape(new Date().toString()); //prevent caching
                    req.open("GET", url, true);
                    req.send("");
                }
            }
    			
            function ahahDone(url, target, callback) {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById(target).innerHTML = req.responseText;
                        if (callback) callback();
                    } else {
                        document.getElementById(target).innerHTML=" AHAH Error:\n"+ req.status + "\n" +req.statusText;
                    }
                }
            }
    			
            function load(name, div, callback) {
                ahah(name,div,callback);
                return false;
            }
    	
            //ask for folder title and request it's creation
            function create_folder(viewdir) {
                var name=prompt("<?php echo $lng['ask_folder_title']; ?>","gallery");
                if (name!=null && name!=""){
                    load('filemanager.php?viewdir=' + viewdir + '&newdir=' + name + '','view-files', create_folder_done);
                }
            }

            //reload directory tree after folder creation
            function create_folder_done() {
                load( '?mfm=1&viewtree=true', 'view-tree' );
            }

    		
            //confirm and delete file
            function delete_file(dir,file) {
                var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
                if (answer){
                    load('filemanager.php?viewdir=' + dir + '&deletefile=' + file,'view-files');
                } 
            }

            //confirm and delete folder
            function delete_folder(dir) {
                var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
                if (answer){
                    location.href = 'filemanager.php?deletefolder=' + dir;
                }
            }

            //show/hide element (for file upload form)
            function toggle_visibility(id) {
                var e = document.getElementById(id);
                if(e != null) {
                    if(e.style.display == 'none') {
                        e.style.display = 'block';
                    } else {
                        e.style.display = 'none';
                    }
                }
            }
            //]]>
        </script>
    </head>

    <?php
    $return = $file_root;
    if (isset($_REQUEST['return'])) {
        $return = $_REQUEST['return'];
    }
    ?>

    <body onload="load('filemanager.php?status=<?php echo $uploadstatus; ?>&amp;viewdir=<?php echo $return; ?>','view-files');">
        <div id="browser-wrapper">
            <div id="view-files" style="width: 98%"></div>
        </div>

    </body>

</html>
