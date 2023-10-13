<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Workstation
{
    Public $id;	#1
    Public $nome;	#2
    Public $ubicazione;	#3
    Public $image;	#4
    Public $id_utente;	#5
    Public $blueprint;	#6
    Public $note;	#7

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_workstations` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Workstation ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_workstations` SET
					`nome` = :nome,
					`ubicazione` = :ubicazione,
					`image` = :image,
					`id_utente` = :id_utente,
					`blueprint` = :blueprint,
					`note` = :note
					 WHERE `tb_workstations`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':ubicazione' => $this->ubicazione,
            ':image' => $this->image,
            ':id_utente' => $this->id_utente,
            ':blueprint' => $this->blueprint,
            ':note' => $this->note,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Workstation ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_workstations`(
				`id`,
				`nome`,
				`ubicazione`,
				`image`,
				`id_utente`,
				`blueprint`,
				`note`
				) VALUES (NULL,
					:nome,
					:ubicazione,
					:image,
					:id_utente,
					:blueprint,
					:note);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':ubicazione' => $this->ubicazione,
            ':image' => $this->image,
            ':id_utente' => $this->id_utente,
            ':blueprint' => $this->blueprint,
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
            throw new Exception('L\'oggetto Workstation non ha id');
        }
        $query = 'DELETE FROM `tb_workstations` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
