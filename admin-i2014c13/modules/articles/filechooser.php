<?php
require_once '../../serverscripts/__loader.php';

if (!isset($_SESSION[PAGE_SESSIONID]['id']))
    die($___LANGUAGER->getText('common', 'sytem_logged_out'));
if ($_SESSION[PAGE_SESSIONID]['privileges']['articles'][0] == '0')
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
$file_root = FRONTEND_ABSDIRPATH. ARTICLEIMAGESPATH;  //where to store files, must be created and writable
//pr pridavani novych clankov zvoli adresar noveho clanku
if (isset($_SESSION['articles_dirname'])) {
    $file_root .= $_SESSION['articles_dirname'];
}

if (isset($_GET['mediatype']) && $_GET['mediatype'] == 'iframe') {
    $file_root = FRONTEND_ABSDIRPATH . substr(VIDEOPATH, 0, -1);
}
$root_path = '';   //path from webroot, without trailing slash. If your page is located in http://www.example.com/john/, this should be '/john'
$thmb_size = 100;        //max size of preview thumbnail
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
        if (is_dir($ff) && $_GET['mediatype'] != 'iframe') {
            echo '<li><a href="' . $root_path . $ff . '/" onclick="load(\'filechooser.php?viewdir=' . $ff . '&mediatype=' . $_GET['mediatype'] . '\',\'view-files\'); return false;">' . $f . '</a>';
            print_tree($ff);
            echo '</li>';
        }
    }
    echo '</ul>';
}

//show file list of given directory
function print_files($c = '.') {
    echo('<table id="file-list">');
    if (isset($_GET['mediatype']) && $_GET['mediatype'] == 'iframe') {
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'VIDEOS WHERE published=1 ORDER BY id DESC');
        $videos = $temp->fetchAssoc('id');
        foreach ($videos as $video) {
            echo '<tr>
                <td>
                  <a class="file file_flv" href="#" onclick="submit_url(\'pages/videoplayer.php?video_id=' . $video['id'] . '\');">' . $video['name'] . '</a>
    </td>
    <td></td>
  </tr>';
        }
    } else {
        global $root_path, $mode, $thmb_size, $file_class, $lng;

        $d = opendir($c);
        $i = 0;
        while ($f = readdir($d)) {
            if (strpos($f, '.') === 0)
                continue;
            $ff = $c . '/' . $f;
            $ext = strtolower(substr(strrchr($f, '.'), 1));
            if (!is_dir($ff)) {
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
                    echo '<td><a class="file thumbnail ' . $imagetype . '" href="#" onclick="submit_url(\'' . str_replace(FRONTEND_ABSDIRPATH,'',$ff) . '\');">' . $f . '<span><img' . $resize . ' src="' . PAGE_URL.str_replace(FRONTEND_ABSDIRPATH,'',$ff). '" /></span></a>';
                    echo '</td>';
                    //known file types
                } elseif (in_array($ext, $file_class)) {
                    echo '<td><a class="file file_' . $ext . '" href="#" onclick="submit_url(\'' . str_replace(FRONTEND_ABSDIRPATH,'',$ff) . '\');">' . $f . '</a>';
                    echo '</td>';
                    //all other files
                } else {
                    echo '<td><a class="file unknown" href="#" onclick="submit_url(\'' . str_replace(FRONTEND_ABSDIRPATH,'',$ff) . '\');">' . $f . '</a>';
                    echo '</td>';
                }
                echo '<td>' . byte_convert(filesize($ff)) . '</td>';

                echo '</tr>';
                $i++;
            }
        }
    }
    echo('</table>');
}

//display only directory tree for dynamic AHAH requests
if (isset($_GET['viewtree'])) {
    if (!(isset($_GET['mediatype']) && $_GET['mediatype'] == 'iframe')) {
        ?>
        <ul class="dirlist">
            <li><a href="<?php echo $root_path . $file_root; ?>/" onclick="load('filechooser.php?viewdir=<?php echo $file_root; ?>&mediatype=<?php echo $_GET['mediatype']; ?>','view-files'); return false;">Súbory</a> <a href="#" onclick="load('filechooser.php?viewtree=true&mediatype=<?php echo $_GET['mediatype']; ?>','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
        <?php print_tree($file_root); ?>
            </li>
        </ul>
        <?php
        exit;
    }
}

//display file list for dynamic requests
if (isset($_GET['viewdir'])) {
    if (!(isset($_GET['mediatype']) && $_GET['mediatype'] == 'iframe')) {
        ?>
        <ul id="browser-toolbar">
            <li class="file-refresh"><a href="#" title="<?php echo $lng['refresh_files_title']; ?>" onclick="load('filechooser.php?viewdir=<?php echo $_GET['viewdir']; ?>&mediatype=<?php echo $_GET['mediatype']; ?>','view-files'); return false;"><?php echo $lng['refresh']; ?></a></li>
        </ul>

        <div id="current-loction">
        <?php echo str_replace($file_root, '', htmlspecialchars($_GET['viewdir'] . '/')); ?>
        </div>

        <?php
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
        <title><?php echo $lng['window_title']; ?></title>
        <link rel="stylesheet" href="<?php echo PAGE_URL . ADMINDIRNAME; ?>filemanager/mfm/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo PAGE_URL . ADMINDIRNAME; ?>scripts/libs/tiny_mce/themes/advanced/skins/default/dialog.css" type="text/css" />
        <script type="text/javascript" src="<?php echo PAGE_URL . ADMINDIRNAME; ?>scripts/libs/tiny_mce/tiny_mce_popup.js"></script>
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
    	
    		
<?php
//first one for inserting file name into given field, second for working as tinyMCE plugin
if ($mode == 'standalone' && isset($_GET['field'])) {
    ?>
            function submit_url(URL) {
                window.opener.document.getElementById('<?php echo $_GET['field']; ?>').value = URL;
                self.close();
            }
    <?php
} else {
    ?>
            function submit_url(url) {
                var win = tinyMCEPopup.getWindowArg("window");
                win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
                if (typeof(win.ImageDialog) != "undefined") {
                    // we are, so update image dimensions...
                    if (win.ImageDialog.getImageData)
                        win.ImageDialog.getImageData();

                    // ... and preview if necessary
                    if (win.ImageDialog.showPreviewImage)
                        win.ImageDialog.showPreviewImage(url);
                }
                tinyMCEPopup.close();
                return false;
            }
<?php } ?>
        
        </script>
    </head>

    <?php
    $return = $file_root;
    if (isset($_REQUEST['return'])) {
        $return = $_REQUEST['return'];
    }
    ?>

    <body onload="load('filechooser.php?status=<?php echo $uploadstatus; ?>&amp;viewdir=<?php echo $return; ?>&mediatype=<?php echo $_GET['mediatype']; ?>','view-files');">
        <div id="browser-wrapper">

            <div id="view-tree">
<? if (!(isset($_GET['mediatype']) && $_GET['mediatype'] == 'iframe')) { ?>
                    <ul class="dirlist">
                        <li><a href="<?php echo $root_path . '/' . $file_root; ?>/" onclick="load('filechooser.php?viewdir=<?php echo $file_root; ?>&mediatype=<?php echo $_GET['mediatype']; ?>','view-files'); return false;">Súbory</a> <a href="#" title="<?php echo $lng['refresh_tree_title']; ?>" onclick="load('filechooser.php?viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
    <?php print_tree($file_root); ?>
                        </li>
                    </ul>
<? } ?>
            </div>

            <div id="view-files"></div>
        </div>

    </body>

</html>