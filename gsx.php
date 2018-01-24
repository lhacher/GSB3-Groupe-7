<?php
	require_once('../../q_path.php');
	require_once($q_def_dir . '/q_vars.php');
	require_once($q_def_dir . '/vars.php');
	require_once($q_lib_dir . '/q_database.php');
	require_once($q_lib_dir . '/q_utils.php');
	require_once($q_lib_dir . '/q_session.php');
	require_once($q_lib_dir . '/q_security.php');


    $state_bgcolors = array(
		'accepte' => '#00EE00',
		'retard' => 'red',
	);
	$state_colors = array(
		'accepte' => 'white',
		'retard' => 'white',
		
	);

	$self = $_SERVER['PHP_SELF'];

	$i_am_adminsav = ($acl['acces_direction'] || $acl['acces_admin_frais']);
    	
	$target_user_id = (($i_am_adminsav && !empty($sel) && !empty($filter)) ? $filter : $_SESSION['id_user']);
	
	
	function build_url($exclude = '', $add = '')
	{
		global  $act, $id, $mois, $filter, $sel, $quickcheck;
		
		$parts['act'] = $act;
		$parts['id'] = $id;
		$parts['mois'] = $mois;
		$parts['filter'] = $filter;
		$parts['sel'] = $sel;
		$parts['quickcheck'] = $quickcheck;
		return q_build_url($parts, $exclude, $add);
	}
	
	$db_res = q_db_query('SELECT id, TRIM(CONCAT(u.prenom, " ", u.nom)) AS nom FROM users AS u WHERE actif = "y" ORDER BY nom', $db_handle);

    	$db_row = q_db_fetch_object($db_res);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Intranet - Symbiose Informatique</title>
<link href="../styles/common.php" rel="stylesheet" type="text/css">
<link href="../styles/tables.php" rel="stylesheet" type="text/css">
    <style>
    .lignecoloree {
        background-color:lawngreen;
    }
    .lignenormale {
        background-color:red;
    }
</style>
</head>
    
<body>
<?php
	require_once('../menu.php');
                if ($i_am_adminsav)
                {
?>

    <form name="theuserform" action="<?php echo build_url(array('filter', 'sel','filled'), '')?>" method="get">
<input type="hidden" name="filled" value="1">
<input type="hidden" name="sel" value="1">
<select name="filter" onChange="theuserform.submit()">
	<?php		
			$db_res = q_db_query('SELECT id, TRIM(CONCAT(u.prenom, " ", u.nom)) AS nom FROM users AS u WHERE actif = "y" ORDER BY nom', $db_handle);
			
			while ($db_row = q_db_fetch_object($db_res))
			{
?>

	<option value="<?php echo htmlentities($db_row->id) ?>"<?php if ($target_user_id == $db_row->id) echo ' selected'?>><?php echo htmlentities(utf8_encode($db_row->nom)) ?></option>
<?php
			}
		
?>
</select>

</form>
<?php
		}
?>	
<table width="100%">
<tr>
        <td align="center">
<table class="tablist" border=0 cellspacing=0>
<caption>ETAT ATELIER</caption>
    <?php
		if ($target_user_id == 999)
		{
?>
	<th class="addborder">Utilisateur</th>
<?php
		}

?>
 
    
<tr>
        <th nowrap align="center">Centre de service</th>
        <th nowrap align="center">jour</th>
        <th nowrap align="center" >Date</th>
        <th nowrap align="center">Non vue </th>
        <th nowrap align="center">Pi&egrave;ce<br>non arriv&eacute;e </th>
        <th nowrap align="center">Non revue </th>
        <th nowrap align="center">Attente<br>devis </th>
        <th nowrap align="center">Commentaire</th>
</tr>
    <?php
if($acl['acces_admin']){
    	$reponse = q_db_query('SELECT * ,DATE_FORMAT(date, "%d/%m/%Y") AS date FROM etat_atelier order by date desc  limit 12 ',$db_handle);
}      
else {
    
	$reponse = q_db_query(' SELECT * ,DATE_FORMAT(date, "%d/%m/%Y") AS date FROM etat_atelier WHERE libelle_etat = "' .$_SESSION['libelle_agence'] . '" order by date desc limit 4',$db_handle);
}
     
while ($donnees = q_db_fetch_object($reponse))
{ 
    
 
    echo"<tr>";
   
    echo"<td> $donnees->libelle_etat</td>";
    
    if($donnees->jour == 'Lundi'){
    echo "<td style='background-color: #00EE00'>
     $donnees->jour </td>";
    }
    else if ($donnees->jour == 'Mardi'){
        echo "<td style='background-color : #00EE00'>  
        $donnees->jour</td>";
    }
    else if ($donnees->jour == 'Mercredi'){
        echo "<td style='background-color : #00EE00'>  
        $donnees->jour</td>";
    }
    else {
        echo "<td style='background-color : #FA5858'>  
        $donnees->jour</td>";
    }
 
    echo"<td align='center'> $donnees->date</td>";
    echo"<td align='center'> $donnees->nbrMachineNonVue</td>";
    echo"<td align='center'> $donnees->nbrPieceNonArrivee</td>";
    echo"<td align='center'> $donnees->nbrMachineNonRevue</td>";
    echo"<td align='center'> $donnees->nbrReponseDevis</td>";
    echo"<td> $donnees->commentaire</td>";
    echo"</tr>";
    }
?>
    
</table>
    </td>
    </tr>
    </table>
                                        

    <br>
    <br>
<br>

<form method="post" action="./action_etatAt.php?#contactFrom">
    
 
    <table border=1 cellpadding=5 align="center">
        
        <input type="hidden" name="id_user"style="width:245px; height:20px" value="<?php 
            
            echo  $_SESSION['id_user'] 
            
            ?>">
        
        
           
                 

 <input type="hidden" name="date"style="width:490px; height:20px" value="
<?php
   
    $annee = date("Y");
    $mois = date("m");
    $jour = date("d");
    $heure = date(" H:i:s");
    
    
    
    echo '' .$annee.'/' .$mois. '/'  .$jour. '/'  .$heure. '' .$week.'';
            
    ?>
    
    "> 
        
    <input type="hidden" name="jour"style="width:490px; height:20px" value="
<?php

    $jours = array ('dimanche', 'lundi', 'mardi' , 'mercredi', 'jeudi', 'vendredi' , 'samedi' );

echo  $jours[date ('w')];
    
    ?>
    
    "> 
          
        
        
<tr><td>Date  </td><td><?php echo '' .$jour.'/' .$mois. '/'  .$annee. ''; ?><input type="hidden" name="jour" style="width:53px; height:20px" value="<?php
    $jours = array ('Dimanche', 'Lundi', 'Mardi' , 'Mercredi', 'Jeudi', 'Vendredi' , 'Samedi' );
    
    echo  $jours[date ('w')];
   

    
    ?>">
    
    
    <input type="hidden" name="" style="width:63px; height:20px" value="
<?php
   
    $annee = date("Y");
    $mois = date("m");
    $jour = date("d");

    echo $annee [date("Y")];
    echo '' .$jour.'/' .$mois. '/'  .$annee. '';
    
    ?>
    
    " >
    </td></tr>
        <tr><td>Centre de service</td><td><input type="hidden" name="service"style="width:118px; height:20px" value="<?php 
            
            echo  $_SESSION['libelle_agence'] 
                
                ?>"> </td> </tr>
    
<tr><td>Nombre de machines non vue  </td><td><input type="number" name="nbrMachine" style="width:120px; height:20px" ></td></tr>
    
<tr><td>Nombre de pi&egrave;ce non arrivee </td><td><input type="number" name="nbrNonArrivee" style="width:120px; height:20px" ></td></tr>
        
<tr><td>Nombre de machines non revue  </td><td><input type="number" name="nbrMachineR" style="width:120px; height:20px" ></td></tr>
    
<tr><td>En attente de r&eacute;ponses aux devis</td><td><input type="number" name="nbrDevis" style="width:120px; height:20px" ></td></tr>

<tr><td>Commentaire</td><td><input type="text" name="commentaire" style="width:120px; height:200px" ></td></tr>


    </table><br>
<div align="center"><input type="submit" style="width:100px; " value="Envoi "></div>
</form>


    
</body>
</html>
