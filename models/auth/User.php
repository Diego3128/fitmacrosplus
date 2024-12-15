<?php

namespace Model\Auth;

use Model\ActiveRecord;

class User extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user';
    // each column name of a certain table (same names)
    protected static $dbColumns = ['id', 'name', 'lastname', 'email', 'password', 'token', 'isadmin', 'verified'];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $token;
    public $isadmin;
    public $verified;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->lastname = $args['lastname'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->isadmin = $args['admin'] ?? '0';
        $this->verified = $args['verified'] ?? '0';
    }
    //validation alerts
    public function validateInputs(): array
    {
        static::$alerts = [];

        $this->validateUserName();

        $this->validateEmail();

        $this->validatePassword();

        return self::$alerts;
    }
    //validate name and lastname
    private function validateUserName()
    {

        if (!$this->name) self::$alerts["error"][] = "El nombre es obligatorio";

        if (strlen($this->name) > 50) self::$alerts["error"][] = "El nombre es muy largo. Maximo 50 caracteres";

        if (!$this->lastname) self::$alerts["error"][] = "El apellido es obligatorio";

        if (strlen($this->lastname) > 50) self::$alerts["error"][] = "El apellido es muy largo. Maximo 50 caracteres";
    }
    //validate email
    private function validateEmail()
    {
        if (!$this->email) {
            self::$alerts["error"][] = "El correo es obligatorio";
        }

        if (strlen($this->email) > 80) {
            return self::$alerts["error"][] = "El email es muy largo. Maximo 80 caracteres.";
        }

        if (!preg_match("/^[\w\.\-]+@[a-zA-Z\d\-]+\.[a-zA-Z]{2,}$/", $this->email)) self::$alerts["error"][] = "El formato de correo es invalido";
    }
    //validate password
    private function validatePassword()
    {
        if (!$this->password) {
            return self::$alerts["error"][] = "La contraseña es necesaria";
        }
        if (strlen($this->password) < 6) {
            self::$alerts["error"][] = "La contraseña es muy corta. Al menos 6 caracteres.";
        }
        if (strlen($this->password) > 60) {
            self::$alerts["error"][] = "La contraseña es muy larga. Maximo 60 caracteres.";
        }
    }
    //validate login
    public function validateLogin(): array
    {
        static::$alerts = [];

        $this->validateEmail();

        if (!$this->password) self::$alerts["error"][] = "La contraseña es necesaria";

        return self::$alerts;
    }

    //check if the email exists in the table
    public function userExists()
    {
        $query = "SELECT email FROM " . static::$tableName . " WHERE email = '" . $this->email . "' LIMIT 1";

        $result = self::$db->query($query);

        // debugAndFormat($result);

        if ($result->num_rows > 0) self::$alerts["error"][] = "El correo ya está registrado";
    }
    //hash password
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    //generate token for email validation
    public function generateToken()
    {
        $this->token = uniqid(prefix: "fitmacros+", more_entropy: true);
    }
    //check if user is verified and if the password matches the hash
    public function checkVerifiedAndPassword($password)
    {
        $result = password_verify(password: $password, hash: $this->password);

        if (!$result) {
            self::$alerts['error'][] = 'Contraseña incorrecta';
            return false;
        }

        if ($this->verified !== '1') {
            self::$alerts['error'][] = 'La cuenta no está verificada';
            return false;
        } else {
            return true;
        }
    }
}
