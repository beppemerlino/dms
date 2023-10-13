<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class CommessaTeam
{
    Public $id;	#1
    Public $id_team;	#2
    Public $id_commessa;	#3
    Public $note;	#4

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_commesse_teams` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto CommessaTeam ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_commesse_teams` SET
					`id_team` = :id_team,
					`id_commessa` = :id_commessa,
					`note` = :note
					 WHERE `tb_commesse_teams`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':id_team' => $this->id_team,
            ':id_commessa' => $this->id_commessa,
            ':note' => $this->note,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto CommessaTeam ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_commesse_teams`(
				`id`,
				`id_team`,
				`id_commessa`,
				`note`
				) VALUES (NULL,
					:id_team,
					:id_commessa,
					:note);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':id_team' => $this->id_team,
            ':id_commessa' => $this->id_commessa,
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
            throw new Exception('L\'oggetto CommessaTeam non ha id');
        }
        $query = 'DELETE FROM `tb_commesse_teams` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
