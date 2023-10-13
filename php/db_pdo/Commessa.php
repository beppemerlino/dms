<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Commessa
{
    Public $id;	#1
    Public $codice;	#2
    Public $anno;	#3
    Public $cliente;	#4
    Public $localizzazione;	#5
    Public $tipo_lavoro;	#6
    Public $chiusa;	#7

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_commesse` WHERE `id` = :id';
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $data = $sth->fetch();
        if ($data)
        {
            foreach( $data as $attr => $value )
            {
                $this->$attr = $value;
            }
        }
    }

    public static function findByCodice($codice)
    {
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT `tb_commesse`.`id` AS `id` FROM `tb_commesse` WHERE `codice` = :codice";
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':codice' => $codice));

        list($id) = $sth->fetch();

        if(!$id) {

            throw new Exception("COMMESSA NON TROVATA!");
        }
        return new Commessa($id);
    }

    public function update()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Commessa ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_commesse` SET
					`codice` = :codice,
					`anno` = :anno,
					`cliente` = :cliente,
					`localizzazione` = :localizzazione,
					`tipo_lavoro` = :tipo_lavoro,
					`chiusa` = :chiusa
					 WHERE `tb_commesse`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':codice' => $this->codice,
            ':anno' => $this->anno,
            ':cliente' => $this->cliente,
            ':localizzazione' => $this->localizzazione,
            ':tipo_lavoro' => $this->tipo_lavoro,
            ':chiusa' => $this->chiusa,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Commessa ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_commesse`(
				`id`,
				`codice`,
				`anno`,
				`cliente`,
				`localizzazione`,
				`tipo_lavoro`,
				`chiusa`
				) VALUES (NULL,
					:codice,
					:anno,
					:cliente,
					:localizzazione,
					:tipo_lavoro,
					:chiusa);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':codice' => $this->codice,
            ':anno' => $this->anno,
            ':cliente' => $this->cliente,
            ':localizzazione' => $this->localizzazione,
            ':tipo_lavoro' => $this->tipo_lavoro,
            ':chiusa' => $this->chiusa
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Commessa non ha id');
        }
        $query = 'DELETE FROM `tb_commesse` WHERE `id` = :id AND (SELECT `id_commessa` FROM `tb_rendicontazioni` WHERE `id_commessa` = :id_commessa LIMIT 0, 1) IS NULL';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id, ":id_commessa" => $this->id));
        $count = $sth->rowCount();

        if($count == 0)
        {
            throw new Exception('La commessa non puo\' essere cancellata!');
        }
    }

}
