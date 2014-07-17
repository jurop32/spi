<? 

//chcecking if admin is logged in
session_start();
if(!isset($_SESSION[PAGE_SESSIONID]['id']) || $_SESSION[PAGE_SESSIONID]['privileges']['filemanager'][0] != '1') die('No permission!');

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
                <link rel="stylesheet" type="text/css" media="screen" href="../../styles/jqueryui.css">
                <script type="text/javascript" src="../../scripts/libs/jquery.js"></script>
                <script type="text/javascript" src="../../scripts/libs/jqueryui.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<!--<script type="text/javascript" src="js/i18n/elfinder.ru.js"></script>-->

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : 'php/connector.php',  // connector URL (REQUIRED)
					// lang: 'ru',             // language (OPTIONAL)
                                        resizable: false,
                                        height: 480
				}).elfinder('instance');
			});
		</script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
