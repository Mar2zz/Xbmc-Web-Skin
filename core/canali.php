<?php

/* ---------------------------------------------------------------
|
| INIZIO XBMC WEB ADMIN 
| AUTORE   : Nicola Coppola
| VERSIONE : 0.1
| 
| Supporto : mac86project.altervista.org/xmbc
|
--------------------------------------------------------------- */

// Carico tutt i file php Richiesti 
require('config_freamwork.php');
require('json_call.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Includo il Css per in base al broswer  !-->
	<link href="css/style.css" rel="stylesheet" type="text/css"/>
<!-- Includo il freamwork  !-->
	<script type="text/javascript" src="js/core.js"></script>
</head>
<body>

<?php 

include('function/scroll.php');

require('lingua_setting.php');

require ('../language/'.$lingua.'/'.$lingua.'.php');
?>

<div id="sfoglia_file_menu">
	
	
	<div id="info_file">
				
					<div id="title"   >
					<a href="tvvdr.php" 
					onMouseOver="window.parent.document.getElementById('img_cambia').src=('core/img/back.png');" 
					onMouseOut="window.parent.document.getElementById('img_cambia').src=('core/img/xbmc.png');">&ensp;&ensp;&ensp; <?php echo $t_livetv_videos ; ?>  
					
					</a></div> 
					
					<div id="playlist"><a href="tvvdr.php"></a></div>
					<div id="type"    ><a href="tvvdr.php"><img src="img/back.png" width="28" height="28"/></a></div>
					
	</div>
	<?php
	
	$filename = "Epg4.db";
	unlink($filename);
	
	$file = '/Users/empty_skull/Library/Application Support/XBMC/userdata/Database/Epg4.db';
	
	$newfile = 'Epg4.db';

	if (!copy($file, $newfile)) {
   			 echo "failed to copy $file...\n";
	}
	
	
	
	vnsi();
	$curl    = vnsi("$json");
	
	//echo $curl;
	
	$array   = json_decode($curl,true);
	$results = $array['result']["files"];
	
	foreach ($results as $value){ 
		
		if ( $_GET['Submit'] == "Cerca"){
		
			$ricerca = $_GET['ricerca'];
			$ricerca2= ucfirst(strtolower($ricerca)); 
			
			if ( preg_match("%$ricerca%", $value['label']) or preg_match("%$ricerca2%", $value['label']) ) {
				
				$response='
				
				
				<div id="info_file">
				
					<div id="title"   >
					<a href="tvvdr_cerca.php" 
					onMouseOver="window.parent.document.getElementById(\'img_cambia\').src=(\'core/img/back.png\');" 
					onMouseOut="window.parent.document.getElementById(\'img_cambia\').src=(\'core/img/xbmc.png\');">&ensp;&ensp;&ensp; '.$back.'
					
					</a></div> 
					
					<div id="playlist"><a href="tvvdr_cerca.php"></a></div>
					<div id="type"    ><a href="tvvdr_cerca.php"><img src="img/back.png" width="28" height="28"/></a></div>
					
				</div>
				
				
				';
				
				$caneleTV = $value['label'];
				
				$connection = new pdo("sqlite:Epg4.db");
				$query="SELECT * FROM epg WHERE sName LIKE '$caneleTV' ";
				$result = $connection->query($query);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//print_r($row);
			
    
				$canale_ora = $row['idEpg'];
				$time_now = strtotime("now");
				//echo $time_now;
				$query1="
				
				SELECT *
				FROM 'epgtags' 
				WHERE idEpg LIKE '$canale_ora' AND iStartTime <= '$time_now'
				LIMIT 1
				
				";
				
				$result1 = $connection->query($query1);
				$row1 = $result1->fetch(PDO::FETCH_ASSOC);
				$informazioni=($row1['sTitle']);
				$descrizione =($row1['sPlot']);
			
			
		
			echo '
				
				
				<div id="info_file">
			
					<div id="title"   ><a href="play.php?metodo=file&id='.$value['file'].'&title='.$value['label'].'" target="control"> &ensp;&ensp;&ensp; '.$caneleTV.'</a></div> 
					<div id="playlist"></div>
					<div id="type"    >'.$informazioni.'</div>
					
				</div>
				
				
				';
			
			}
		
		
		}else{
		 		
				
				$caneleTV = $value['label'];
				
				$connection = new pdo("sqlite:Epg4.db");
				$query="SELECT * FROM epg WHERE sName LIKE '$caneleTV' ";
				$result = $connection->query($query);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//print_r($row);
			
    
				$canale_ora = $row['idEpg'];
				$time_now = strtotime("now");
				//echo $time_now;
				$query1="
				
				SELECT *
				FROM 'epgtags' 
				WHERE idEpg LIKE '$canale_ora' AND iStartTime >= '$time_now'
				LIMIT 1
				
				";
				
				$result1 = $connection->query($query1);
				$row1 = $result1->fetch(PDO::FETCH_ASSOC);
				$informazioni=($row1['sTitle']);
				$descrizione =($row1['sPlot']);
			
			
		
			echo '
				
				
				<div id="info_file">
			
					<div id="title"   ><a href="play.php?metodo=file&id='.$value['file'].'&title='.$value['label'].'" target="control"> &ensp;&ensp;&ensp; '.$caneleTV.'</a></div> 
					<div id="playlist"></div>
					<div id="type"    >'.$informazioni.'</div>
					
				</div>
				
				
				';
		
		
	
		}
		
	}
	
	
echo $response;

?>
</div>

</body>
</html>