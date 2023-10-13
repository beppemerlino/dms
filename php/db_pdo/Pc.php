<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Pc
{
    Public $id;	#1
    Public $nome;	#2
    Public $vendor;	#3
    Public $model;	#4
    Public $cpu_1;	#5
    Public $cpu_2;	#6
    Public $operative_system;	#7
    Public $keyboard;	#8
    Public $mouse;	#9
    Public $foto;	#10
    Public $id_workstation;	#11
    Public $serial_number;	#12
    Public $rif_cespite;	#13
    Public $part_number;	#14
    Public $form_factory;	#15
    Public $ram_size;	#16
    Public $primary_disk_size;	#17
    Public $secondary_disk_size;	#18
    Public $dvd_rom;	#19
    Public $video_card;	#20
    Public $bluetooth;	#21
    Public $ethernet_1;	#22
    Public $ethernet_2;	#23
    Public $ip_address_1;	#24
    Public $ip_address_2;	#25
    Public $mac_ethernet_1;	#26
    Public $mac_ethernet_2;	#27
    Public $hdmi_port;	#28
    Public $dvi_port;	#29
    Public $display_port;	#30
    Public $mdisplay_port;	#31
    Public $thunderbolt_port;	#32
    Public $wifi_card;	#33
    Public $audio_card;	#34
    Public $num_usb;	#35
    Public $num_usb_3;	#36
    Public $power_supply;	#37
    Public $power_cell;	#38

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_pcs` WHERE `id` = :id';
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
            throw new Exception('L\'oggetto Pc ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_pcs` SET
					`nome` = :nome,
					`vendor` = :vendor,
					`model` = :model,
					`cpu_1` = :cpu_1,
					`cpu_2` = :cpu_2,
					`operative_system` = :operative_system,
					`keyboard` = :keyboard,
					`mouse` = :mouse,
					`foto` = :foto,
					`id_workstation` = :id_workstation,
					`serial_number` = :serial_number,
					`rif_cespite` = :rif_cespite,
					`part_number` = :part_number,
					`form_factory` = :form_factory,
					`ram_size` = :ram_size,
					`primary_disk_size` = :primary_disk_size,
					`secondary_disk_size` = :secondary_disk_size,
					`dvd_rom` = :dvd_rom,
					`video_card` = :video_card,
					`bluetooth` = :bluetooth,
					`ethernet_1` = :ethernet_1,
					`ethernet_2` = :ethernet_2,
					`ip_address_1` = :ip_address_1,
					`ip_address_2` = :ip_address_2,
					`mac_ethernet_1` = :mac_ethernet_1,
					`mac_ethernet_2` = :mac_ethernet_2,
					`hdmi_port` = :hdmi_port,
					`dvi_port` = :dvi_port,
					`display_port` = :display_port,
					`mdisplay_port` = :mdisplay_port,
					`thunderbolt_port` = :thunderbolt_port,
					`wifi_card` = :wifi_card,
					`audio_card` = :audio_card,
					`num_usb` = :num_usb,
					`num_usb_3` = :num_usb_3,
					`power_supply` = :power_supply,
					`power_cell` = :power_cell
					 WHERE `tb_pcs`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':cpu_1' => $this->cpu_1,
            ':cpu_2' => $this->cpu_2,
            ':operative_system' => $this->operative_system,
            ':keyboard' => $this->keyboard,
            ':mouse' => $this->mouse,
            ':foto' => $this->foto,
            ':id_workstation' => $this->id_workstation,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':form_factory' => $this->form_factory,
            ':ram_size' => $this->ram_size,
            ':primary_disk_size' => $this->primary_disk_size,
            ':secondary_disk_size' => $this->secondary_disk_size,
            ':dvd_rom' => $this->dvd_rom,
            ':video_card' => $this->video_card,
            ':bluetooth' => $this->bluetooth,
            ':ethernet_1' => $this->ethernet_1,
            ':ethernet_2' => $this->ethernet_2,
            ':ip_address_1' => $this->ip_address_1,
            ':ip_address_2' => $this->ip_address_2,
            ':mac_ethernet_1' => $this->mac_ethernet_1,
            ':mac_ethernet_2' => $this->mac_ethernet_2,
            ':hdmi_port' => $this->hdmi_port,
            ':dvi_port' => $this->dvi_port,
            ':display_port' => $this->display_port,
            ':mdisplay_port' => $this->mdisplay_port,
            ':thunderbolt_port' => $this->thunderbolt_port,
            ':wifi_card' => $this->wifi_card,
            ':audio_card' => $this->audio_card,
            ':num_usb' => $this->num_usb,
            ':num_usb_3' => $this->num_usb_3,
            ':power_supply' => $this->power_supply,
            ':power_cell' => $this->power_cell,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Pc ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_pcs`(
				`id`,
				`nome`,
				`vendor`,
				`model`,
				`cpu_1`,
				`cpu_2`,
				`operative_system`,
				`keyboard`,
				`mouse`,
				`foto`,
				`id_workstation`,
				`serial_number`,
				`rif_cespite`,
				`part_number`,
				`form_factory`,
				`ram_size`,
				`primary_disk_size`,
				`secondary_disk_size`,
				`dvd_rom`,
				`video_card`,
				`bluetooth`,
				`ethernet_1`,
				`ethernet_2`,
				`ip_address_1`,
				`ip_address_2`,
				`mac_ethernet_1`,
				`mac_ethernet_2`,
				`hdmi_port`,
				`dvi_port`,
				`display_port`,
				`mdisplay_port`,
				`thunderbolt_port`,
				`wifi_card`,
				`audio_card`,
				`num_usb`,
				`num_usb_3`,
				`power_supply`,
				`power_cell`
				) VALUES (NULL,
					:nome,
					:vendor,
					:model,
					:cpu_1,
					:cpu_2,
					:operative_system,
					:keyboard,
					:mouse,
					:foto,
					:id_workstation,
					:serial_number,
					:rif_cespite,
					:part_number,
					:form_factory,
					:ram_size,
					:primary_disk_size,
					:secondary_disk_size,
					:dvd_rom,
					:video_card,
					:bluetooth,
					:ethernet_1,
					:ethernet_2,
					:ip_address_1,
					:ip_address_2,
					:mac_ethernet_1,
					:mac_ethernet_2,
					:hdmi_port,
					:dvi_port,
					:display_port,
					:mdisplay_port,
					:thunderbolt_port,
					:wifi_card,
					:audio_card,
					:num_usb,
					:num_usb_3,
					:power_supply,
					:power_cell);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':nome' => $this->nome,
            ':vendor' => $this->vendor,
            ':model' => $this->model,
            ':cpu_1' => $this->cpu_1,
            ':cpu_2' => $this->cpu_2,
            ':operative_system' => $this->operative_system,
            ':keyboard' => $this->keyboard,
            ':mouse' => $this->mouse,
            ':foto' => $this->foto,
            ':id_workstation' => $this->id_workstation,
            ':serial_number' => $this->serial_number,
            ':rif_cespite' => $this->rif_cespite,
            ':part_number' => $this->part_number,
            ':form_factory' => $this->form_factory,
            ':ram_size' => $this->ram_size,
            ':primary_disk_size' => $this->primary_disk_size,
            ':secondary_disk_size' => $this->secondary_disk_size,
            ':dvd_rom' => $this->dvd_rom,
            ':video_card' => $this->video_card,
            ':bluetooth' => $this->bluetooth,
            ':ethernet_1' => $this->ethernet_1,
            ':ethernet_2' => $this->ethernet_2,
            ':ip_address_1' => $this->ip_address_1,
            ':ip_address_2' => $this->ip_address_2,
            ':mac_ethernet_1' => $this->mac_ethernet_1,
            ':mac_ethernet_2' => $this->mac_ethernet_2,
            ':hdmi_port' => $this->hdmi_port,
            ':dvi_port' => $this->dvi_port,
            ':display_port' => $this->display_port,
            ':mdisplay_port' => $this->mdisplay_port,
            ':thunderbolt_port' => $this->thunderbolt_port,
            ':wifi_card' => $this->wifi_card,
            ':audio_card' => $this->audio_card,
            ':num_usb' => $this->num_usb,
            ':num_usb_3' => $this->num_usb_3,
            ':power_supply' => $this->power_supply,
            ':power_cell' => $this->power_cell
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Pc non ha id');
        }
        $query = 'DELETE FROM `tb_pcs` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
