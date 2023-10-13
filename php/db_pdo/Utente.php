<?php
/**
 *Questa classe e' stata creata con "class_db_creator.php" e crea un model di una tabella specifica di un database che si vuole scegliere*/

class Utente
{
    Public $id;	#1
    Public $username;	#2
    Public $password;	#3
    Public $nome;	#4
    Public $cognome;	#5
    Public $sesso;	#6
    Public $titolo;	#7
    Public $mansione;	#8
    Public $email;	#9
    Public $email2;	#10
    Public $group;	#11
    Public $telefono;	#12
    Public $note;	#13
    Public $attivo;	#14
    Public $tipo_lavoratore;	#15
    Public $foto;	#16

    public function __construct($id = false)
    {
        if(!$id)
        {
            return;
        }
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM `tb_utenti` WHERE `id` = :id';
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

    public static function findByUsernamePassword($username, $password)
    {
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT `tb_utenti`.`id` AS `id` FROM `tb_utenti` WHERE `username` = :username AND `password` = MD5(:password) AND `attivo` = 1";
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':username' => $username, ':password' => $password));

        list($id) = $sth->fetch();

        if(!$id) {

            throw new Exception("UTENTE NON TROVATO!");
        }
        return new Utente($id);
    }



    public function update()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Utente ha bisogno del suo id per chiamare l\'update()');
        }
        $query = 'UPDATE `tb_utenti` SET
					`username` = :username,
					`password` = :password,
					`nome` = :nome,
					`cognome` = :cognome,
					`sesso` = :sesso,
					`titolo` = :titolo,
					`mansione` = :mansione,
					`email` = :email,
					`email2` = :email2,
					`group` = :group,
					`telefono` = :telefono,
					`note` = :note,
					`attivo` = :attivo,
					`tipo_lavoratore` = :tipo_lavoratore,
					`foto` = :foto
					 WHERE `tb_utenti`.`id` = :id;';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':username' => $this->username,
            ':password' => $this->password,
            ':nome' => $this->nome,
            ':cognome' => $this->cognome,
            ':sesso' => $this->sesso,
            ':titolo' => $this->titolo,
            ':mansione' => $this->mansione,
            ':email' => $this->email,
            ':email2' => $this->email2,
            ':group' => $this->group,
            ':telefono' => $this->telefono,
            ':note' => $this->note,
            ':attivo' => $this->attivo,
            ':tipo_lavoratore' => $this->tipo_lavoratore,
            ':foto' => $this->foto,
            ':id' => $this->id));
    }

    public function insert()
    {
        if($this->id)
        {
            throw new Exception('L\'oggetto Utente ha gia\' questo id, non si puo\' inserirne un\'altro');
        }
        $query = 'INSERT INTO `tb_utenti`(
				`id`,
				`username`,
				`password`,
				`nome`,
				`cognome`,
				`sesso`,
				`titolo`,
				`mansione`,
				`email`,
				`email2`,
				`group`,
				`telefono`,
				`note`,
				`attivo`,
				`tipo_lavoratore`,
				`foto`
				) VALUES (NULL,
					:username,
					:password,
					:nome,
					:cognome,
					:sesso,
					:titolo,
					:mansione,
					:email,
					:email2,
					:group,
					:telefono,
					:note,
					:attivo,
					:tipo_lavoratore,
					:foto);';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':username' => $this->username,
            ':password' => $this->password,
            ':nome' => $this->nome,
            ':cognome' => $this->cognome,
            ':sesso' => $this->sesso,
            ':titolo' => $this->titolo,
            ':mansione' => $this->mansione,
            ':email' => $this->email,
            ':email2' => $this->email2,
            ':group' => $this->group,
            ':telefono' => $this->telefono,
            ':note' => $this->note,
            ':attivo' => $this->attivo,
            ':tipo_lavoratore' => $this->tipo_lavoratore,
            ':foto' => $this->foto
        ));
        $sth = $dbh->prepare('SELECT LAST_INSERT_ID()');
        $sth->execute();
        list($this->id) = $sth->fetch();

    }

    public function delete()
    {
        if(!$this->id)
        {
            throw new Exception('L\'oggetto Utente non ha id');
        }
        $query = 'DELETE FROM `tb_utenti` WHERE `id` = :id';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $this->id));
    }

}
