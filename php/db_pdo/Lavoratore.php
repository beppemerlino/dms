<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Lavoratore
{
    Public $id;	#1
    Public $tipo_lavoratore;	#2
    Public $inquadramento_fiscale;	#3
    Public $note;	#4

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_lavoratori` WHERE `id` = :id';
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

    public function update()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Lavoratore ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_lavoratori` SET
					`tipo_lavoratore` = :tipo_lavoratore,
					`inquadramento_fiscale` = :inquadramento_fiscale,
					`note` = :note
					 WHERE `tb_lavoratori`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':tipo_lavoratore' => $this->tipo_lavoratore,
            ':inquadramento_fiscale' => $this->inquadramento_fiscale,
            ':note' => $this->note,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Lavoratore ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_lavoratori`(
				`id`,
				`tipo_lavoratore`,
				`inquadramento_fiscale`,
				`note`
				) VALUES (NULL,
					:tipo_lavoratore,
					:inquadramento_fiscale,
					:note);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':tipo_lavoratore' => $this->tipo_lavoratore,
            ':inquadramento_fiscale' => $this->inquadramento_fiscale,
            ':note' => $this->note
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Lavoratore non ha id');
        }
        $query = 'DELETE FROM `tb_lavoratori` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
