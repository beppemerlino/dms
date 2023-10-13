<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Device
{
    Public $id;	#1
    Public $nome;	#2
    Public $vendor;	#3
    Public $model;	#4
    Public $foto;	#5
    Public $id_workstation;	#6
    Public $serial_number;	#7
    Public $rif_cespite;	#8
    Public $part_number;	#9
    Public $description;	#10

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_devices` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Device ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_devices` SET
					`nome` = :nome,
					`vendor` = :vendor,
					`model` = :model,
					`foto` = :foto,
					`id_workstation` = :id_workstation,
					`serial_number` = :serial_number,
					`rif_cespite` = :rif_cespite,
					`part_number` = :part_number,
					`description` = :description
					 WHERE `tb_devices`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':foto' => $this->foto,
            ':id_workstation' => $this->id_workstation,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':description' => $this->description,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Device ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_devices`(
				`id`,
				`nome`,
				`vendor`,
				`model`,
				`foto`,
				`id_workstation`,
				`serial_number`,
				`rif_cespite`,
				`part_number`,
				`description`
				) VALUES (NULL,
					:nome,
					:vendor,
					:model,
					:foto,
					:id_workstation,
					:serial_number,
					:rif_cespite,
					:part_number,
					:description);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':foto' => $this->foto,
            ':id_workstation' => $this->id_workstation,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':description' => $this->description
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Device non ha id');
        }
        $query = 'DELETE FROM `tb_devices` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
