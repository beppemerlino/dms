<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Rendicontazione
{
    Public $id;	#1
    Public $data;	#2
    Public $id_commessa;	#3
    Public $id_utente;	#4
    Public $num_ore;	#5
    Public $start_date;	#6
    Public $end_date;	#7
    Public $note;	#8

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_rendicontazioni` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Rendicontazione ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_rendicontazioni` SET
					`data` = :data,
					`id_commessa` = :id_commessa,
					`id_utente` = :id_utente,
					`num_ore` = :num_ore,
					`start_date` = :start_date,
					`end_date` = :end_date,
					`note` = :note
					 WHERE `tb_rendicontazioni`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':data' => $this->data,
            ':id_commessa' => $this->id_commessa,
            ':id_utente' => $this->id_utente,
            ':num_ore' => $this->num_ore,
            ':start_date' => $this->start_date,
            ':end_date' => $this->end_date,
            ':note' => $this->note,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Rendicontazione ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_rendicontazioni`(
				`id`,
				`data`,
				`id_commessa`,
				`id_utente`,
				`num_ore`,
				`start_date`,
				`end_date`,
				`note`
				) VALUES (NULL,
					:data,
					:id_commessa,
					:id_utente,
					:num_ore,
					:start_date,
					:end_date,
					:note);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':data' => $this->data,
            ':id_commessa' => $this->id_commessa,
            ':id_utente' => $this->id_utente,
            ':num_ore' => $this->num_ore,
            ':start_date' => $this->start_date,
            ':end_date' => $this->end_date,
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
            throw new Exception('L\'oggetto Rendicontazione non ha id');
        }
        $query = 'DELETE FROM `tb_rendicontazioni` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
