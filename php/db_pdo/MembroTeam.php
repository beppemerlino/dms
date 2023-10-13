<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class MembroTeam
{
    Public $id;	#1
    Public $id_team;	#2
    Public $id_utente;	#3
    Public $note;	#4

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_membri_team` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto MembroTeam ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_membri_team` SET
					`id_team` = :id_team,
					`id_utente` = :id_utente,
					`note` = :note
					 WHERE `tb_membri_team`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':id_team' => $this->id_team,
            ':id_utente' => $this->id_utente,
            ':note' => $this->note,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto MembroTeam ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_membri_team`(
				`id`,
				`id_team`,
				`id_utente`,
				`note`
				) VALUES (NULL,
					:id_team,
					:id_utente,
					:note);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':id_team' => $this->id_team,
            ':id_utente' => $this->id_utente,
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
            throw new Exception('L\'oggetto MembroTeam non ha id');
        }
        $query = 'DELETE FROM `tb_membri_team` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
