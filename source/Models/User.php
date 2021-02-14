<?php


namespace Source\Models;


use JetBrains\PhpStorm\Pure;
use Source\Models\Authorization\Role;
use Toniette\DataLayer\DataLayer;

/**
 * Class User
 * @package Source\Models
 */
class User extends DataLayer
{
    /**
     * User constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct("users", ["name", "email", "password", "role"]);
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $exists = (new User)->find("email = :email", ["email" => $this->email])->fetch();

        //E-mail verify
        if (!is_email($this->email)) {
            $this->fail = new \Exception("INVALID E-MAIL - Verify your e-mail and try again");
            return false;
        } else if ($exists && $exists->id != $this->id) {
            $this->fail = new \Exception("INVALID E-MAIL - There is already an account linked to this email");
            return false;
        }

        //Password verify
        if ((!empty($this->id) && $this->password != (new User)->findById($this->id)->password)
            || empty($this->id)) {
            if (is_passwd($this->password)) {
                $this->password = passwd_hash($this->password);
                parent::save();
                return true;
            }
            $this->fail = new \Exception("INVALID PASSWORD - Your password must contain between 8 and 40 characters,
        a lowercase letter, a capital letter, a number and a special character");
            return false;
        } else {
            parent::save();
            return true;
        }

    }

    public function role(): Role
    {
      return (new Role())->findById($this->role);
    }
}