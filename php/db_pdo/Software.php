<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Software
{
    Public $id;	#1
    Public $vendor;	#2
    Public $model;	#3
    Public $foto;	#4
    Public $id_pc;	#5
    Public $serial_number;	#6
    Public $rif_cespite;	#7
    Public $part_number;	#8
    Public $description;	#9
    Public $expired_date;	#10

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_softwares` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Software ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_softwares` SET
					`vendor` = :vendor,
					`model` = :model,
					`foto` = :foto,
					`id_pc` = :id_pc,
					`serial_number` = :serial_number,
					`rif_cespite` = :rif_cespite,
					`part_number` = :part_number,
					`description` = :description,
					`expired_date` = :expired_date
					 WHERE `tb_softwares`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':foto' => $this->foto,
            ':id_pc' => $this->id_pc,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':description' => $this->description,
            ':expired_date' => $this->expired_date,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Software ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_softwares`(
				`id`,
				`vendor`,
				`model`,
				`foto`,
				`id_pc`,
				`serial_number`,
				`rif_cespite`,
				`part_number`,
				`description`,
				`expired_date`
				) VALUES (NULL,
					:vendor,
					:model,
					:foto,
					:id_pc,
					:serial_number,
					:rif_cespite,
					:part_number,
					:description,
					:expired_date);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':foto' => $this->foto,
            ':id_pc' => $this->id_pc,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':description' => $this->description,
            ':expired_date' => $this->expired_date
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Software non ha id');
        }
        $query = 'DELETE FROM `tb_softwares` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
