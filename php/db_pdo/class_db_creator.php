<?php

require_once('database.php');
ini_set('display_errors','On');

/**

*L'utente da parte sua inserira' con una chiamata post o get i seguenti parametri:

*	tabella (il nome della tabella da quale si ricavera' la classe model)
*	dbclass (il nome della sottoclasse che eredita dalla classe database)
*	nomeclasse (il nome della classe model e del file)
*
*   esempio http://localhost:8099/dms/php/db_pdo/class_db_creator.php?tabella=tb_commesse&nomeclasse=Commessa

*/

$tabella = (isset($_REQUEST['tabella'])? $_REQUEST['tabella'] : "tbclienti");
$nomeclasse = (isset($_REQUEST['nomeclasse'])? $_REQUEST['nomeclasse'] : "Cliente");

if ($tabella == ""){
    
    echo "Tabella non dichiarata!";
    exit;
    
}


if ($nomeclasse == ""){
    
    echo "Nome Classe non dichiarato!";
    exit;
    
}



$dbh = new Db_inc();
$primarykey = 'id';
$pri_autoincrement = false;
$array_expression = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
foreach ($array_expression as $value) {
    if ($value['Key'] == "PRI") {
        $primarykey = $value['Field'];
        if ($value['Extra'] == "auto_increment"){
            $pri_autoincrement = true;
        }
    }
    
    
}

$k = 0;

$stringa = '<?php'.chr(13).chr(10);

$stringa .= '/**'.chr(13).chr(10);
$stringa .= ' *Questa classe e\' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere';
$stringa .= '*/'.chr(13).chr(10).chr(13).chr(10);

$stringa .= 'class ' .$nomeclasse;
$stringa .= chr(13).chr(10).'{'.chr(13).chr(10);

if ($array_expression) {
    
    foreach ($array_expression as $value) {
        $k ++;
        $field = $value['Field'];
        $stringa .= chr(9)."Public ".chr(36).$field.";".chr(9)."#".strval($k).chr(13).chr(10);
    }
    
    $stringa .= chr(13).chr(10).chr(9)."public function __construct(".chr(36).$primarykey." = false)".chr(13).chr(10).chr(9)."{";
    $stringa .= chr(13).chr(10).chr(9).chr(9)."if(!".chr(36).$primarykey.")".chr(13).chr(10).chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."return;".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh = new Db_inc();".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."query = 'SELECT * FROM `".$tabella."` WHERE `".$primarykey."` = :".$primarykey."';".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth = ".chr(36)."dbh->prepare(".chr(36)."query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth->execute(array(':".$primarykey."' => ".chr(36)."".$primarykey."));".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."data = ".chr(36)."sth->fetch();".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."if (".chr(36)."data) ".chr(13).chr(10).chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."foreach( ".chr(36)."data as ".chr(36)."attr => ".chr(36)."value )".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9).chr(9).chr(36)."this->".chr(36)."attr = ".chr(36)."value;".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9)."}".chr(13).chr(10).chr(13).chr(10);
    
    $stringa .= chr(9)."public function update()".chr(13).chr(10);
    $stringa .= chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."if(!".chr(36)."this->".$primarykey.")".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."throw new Exception('L\'oggetto ".$nomeclasse." ha bisogno del suo ".$primarykey." per chiamare l\'update()');".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."query = 'UPDATE `".$tabella."` SET".chr(13).chr(10);
    
    $res = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
    $k = 0;
    foreach ($res as $value) {
        $k ++;
        if ($value['Field'] != $primarykey){
            if ($k > 2) $stringa .= ",".chr(13).chr(10);
            $stringa .= chr(9).chr(9).chr(9).chr(9).chr(9)."`".$value['Field']."` = :".$value['Field'];
        }
        
        
        
    }
    
    $stringa .= chr(13).chr(10).chr(9).chr(9).chr(9).chr(9).chr(9)." WHERE `".$tabella."`.`".$primarykey."` = :".$primarykey.";';".chr(13).chr(10);
    
    $stringa .= chr(9).chr(9).chr(36)."dbh = new Db_inc();".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth = ".chr(36)."dbh->prepare(".chr(36)."query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth->execute(array(".chr(13).chr(10);
    
    $res = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
    $k = 0;
    
    foreach ($res as $value) {
        $k ++;
        if ($value['Field'] != $primarykey){
            if ($k > 2) $stringa .= ",".chr(13).chr(10);
            $stringa .= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9)."':".$value['Field']."' => ".chr(36)."this->".$value['Field'];
        }
        
    }
    $stringa .= ",".chr(13).chr(10).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9)."':".$primarykey."' => ".chr(36)."this->".$primarykey."));".chr(13).chr(10);
    $stringa .= chr(9)."}".chr(13).chr(10).chr(13).chr(10);
    
    $stringa .= chr(9)."public function insert()".chr(13).chr(10);
    $stringa .= chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."if(".chr(36)."this->".$primarykey.")".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."throw new Exception('L\'oggetto ".$nomeclasse." ha gia\' questo id, non si puo\' inserirne un\'altro');".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."query = 'INSERT INTO `".$tabella."`(".chr(13).chr(10);
    
    $res = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
    $k = 0;
    
    foreach ($res as $value) {
        $k ++;
        
        if ($k >= 2) $stringa .= ",".chr(13).chr(10);
        $stringa .= chr(9).chr(9).chr(9).chr(9)."`".$value['Field']."`";
        
        
    }
    
    $stringa .= chr(13).chr(10).chr(9).chr(9).chr(9).chr(9).") VALUES (";
    
    $res = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
    $k = 0;
    
    foreach ($res as $value)
    {
        $k ++;
        
        if ($k >= 2) $stringa .= ",".chr(13).chr(10);
        
        if ($value['Field'] == $primarykey && $pri_autoincrement)
        {
            $stringa .= "NULL";
        }
        else
        {
            $stringa .= chr(9).chr(9).chr(9).chr(9).chr(9).":".$value['Field'];
        }
        
    }
    
    $stringa .= ");';".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh = new Db_inc();".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth = ".chr(36)."dbh->prepare(".chr(36)."query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth->execute(array(".chr(13).chr(10);
    
    
    $res = $dbh->query("DESCRIBE " . $tabella)->fetchAll();
    $k = 1;
    foreach ($res as $value)
    {
        
        if ($k >= 2) $stringa .= ",".chr(13).chr(10);
        if ($pri_autoincrement)
        {
            if ($value['Field'] != $primarykey) {
                
                $stringa .= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9)."':".$value['Field']."' => ".chr(36)."this->".$value['Field']; #non serve inserire il valore del campo PrimaryKey
                $k ++;
            }
        } else {
            
            $stringa .= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9)."':".$value['Field']."' => ".chr(36)."this->".$value['Field'];
            $k ++;
        }
        
    }
    
    
    $stringa .= chr(13).chr(10).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9)."));".chr(13).chr(10);
    
    $stringa .= chr(9).chr(9).chr(36)."sth = ".chr(36)."dbh->prepare('SELECT LAST_INSERT_ID()');".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth->execute();".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."list(".chr(36)."this->id) = ".chr(36)."sth->fetch();".chr(13).chr(10);
    
    
    $stringa .= chr(13).chr(10).chr(9)."}".chr(13).chr(10);
    
    
    
    
    
    $stringa .= chr(13).chr(10).chr(9)."public function delete()".chr(13).chr(10);
    $stringa .= chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."if(!".chr(36)."this->".$primarykey.")".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."{".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(9)."throw new Exception('L\'oggetto ".$nomeclasse." non ha ".$primarykey."');".chr(13).chr(10);
    $stringa .= chr(9).chr(9)."}".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."query = 'DELETE FROM `".$tabella."` WHERE `".$primarykey."` = :".$primarykey."';".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh = new Db_inc();".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth = ".chr(36)."dbh->prepare(".chr(36)."query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));".chr(13).chr(10);
    $stringa .= chr(9).chr(9).chr(36)."sth->execute(array(':".$primarykey."' => ".chr(36)."this->".$primarykey."));".chr(13).chr(10);
    $stringa .= chr(9)."}".chr(13).chr(10);
    $stringa .= chr(13).chr(10);
    $stringa .= "}".chr(13).chr(10);
    
    
    /*
     */
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment;filename="'.strtolower($nomeclasse).'.php"');
    header('Cache-Control: max-age=0');
    print $stringa;
    exit;
    
} else {
    
    echo "Tabella \"$tabella\" errata!";
    exit;
    
}
?>