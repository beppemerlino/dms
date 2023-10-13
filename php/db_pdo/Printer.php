<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Printer
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
    Public $form_factory;	#11
    Public $ram_size;	#12
    Public $primary_disk_size;	#13
    Public $secondary_disk_size;	#14
    Public $bluetooth;	#15
    Public $ethernet_1;	#16
    Public $ethernet_2;	#17
    Public $ip_address_1;	#18
    Public $ip_address_2;	#19
    Public $mac_ethernet_1;	#20
    Public $mac_ethernet_2;	#21

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_printers` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Printer ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_printers` SET
					`nome` = :nome,
					`vendor` = :vendor,
					`model` = :model,
					`foto` = :foto,
					`id_workstation` = :id_workstation,
					`serial_number` = :serial_number,
					`rif_cespite` = :rif_cespite,
					`part_number` = :part_number,
					`description` = :description,
					`form_factory` = :form_factory,
					`ram_size` = :ram_size,
					`primary_disk_size` = :primary_disk_size,
					`secondary_disk_size` = :secondary_disk_size,
					`bluetooth` = :bluetooth,
					`ethernet_1` = :ethernet_1,
					`ethernet_2` = :ethernet_2,
					`ip_address_1` = :ip_address_1,
					`ip_address_2` = :ip_address_2,
					`mac_ethernet_1` = :mac_ethernet_1,
					`mac_ethernet_2` = :mac_ethernet_2
					 WHERE `tb_printers`.`id` = :id;';
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
            ':form_factory' => $this->form_factory,
            ':ram_size' => $this->ram_size,
            ':primary_disk_size' => $this->primary_disk_size,
            ':secondary_disk_size' => $this->secondary_disk_size,
            ':bluetooth' => $this->bluetooth,
            ':ethernet_1' => $this->ethernet_1,
            ':ethernet_2' => $this->ethernet_2,
            ':ip_address_1' => $this->ip_address_1,
            ':ip_address_2' => $this->ip_address_2,
            ':mac_ethernet_1' => $this->mac_ethernet_1,
            ':mac_ethernet_2' => $this->mac_ethernet_2,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Printer ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_printers`(
				`id`,
				`nome`,
				`vendor`,
				`model`,
				`foto`,
				`id_workstation`,
				`serial_number`,
				`rif_cespite`,
				`part_number`,
				`description`,
				`form_factory`,
				`ram_size`,
				`primary_disk_size`,
				`secondary_disk_size`,
				`bluetooth`,
				`ethernet_1`,
				`ethernet_2`,
				`ip_address_1`,
				`ip_address_2`,
				`mac_ethernet_1`,
				`mac_ethernet_2`
				) VALUES (NULL,
					:nome,
					:vendor,
					:model,
					:foto,
					:id_workstation,
					:serial_number,
					:rif_cespite,
					:part_number,
					:description,
					:form_factory,
					:ram_size,
					:primary_disk_size,
					:secondary_disk_size,
					:bluetooth,
					:ethernet_1,
					:ethernet_2,
					:ip_address_1,
					:ip_address_2,
					:mac_ethernet_1,
					:mac_ethernet_2);';
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
            ':form_factory' => $this->form_factory,
            ':ram_size' => $this->ram_size,
            ':primary_disk_size' => $this->primary_disk_size,
            ':secondary_disk_size' => $this->secondary_disk_size,
            ':bluetooth' => $this->bluetooth,
            ':ethernet_1' => $this->ethernet_1,
            ':ethernet_2' => $this->ethernet_2,
            ':ip_address_1' => $this->ip_address_1,
            ':ip_address_2' => $this->ip_address_2,
            ':mac_ethernet_1' => $this->mac_ethernet_1,
            ':mac_ethernet_2' => $this->mac_ethernet_2
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Printer non ha id');
        }
        $query = 'DELETE FROM `tb_printers` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
