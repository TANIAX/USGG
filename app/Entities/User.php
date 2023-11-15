<?php

namespace App\Entities;

use DateTime;
use App\Entities\Role;
use CodeIgniter\Entity\Entity;

/**
 * User class for connecting user to the application.
 * @author Guillaume Cornez
 */
class User extends Entity
{
    private int $id;
    private string $totem;
    private string $email;
    private string $password;
    private string $name;
    private string $firstname;
    private DateTime $birthdate;
    private string $phone;
    private string $picture;
    private bool $exists;
    private DateTime $updated_at;
    private DateTime $created_at;
    private array $roles;
    private mixed $user_type;

    #region getters/setters
    /////////////////////////////
    //          GETTERS        //
    //          SETTERS        //
    /////////////////////////////

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of totem
     */ 
    public function getTotem()
    {
        return $this->totem;
    }

    /**
     * Set the value of totem
     *
     * @return  self
     */ 
    public function setTotem($totem)
    {
        $this->totem = $totem;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of birthdate
     */ 
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set the value of birthdate
     *
     * @return  self
     */ 
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @return  self
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of user_type
     */ 
    public function getUser_type()
    {
        return $this->user_type;
    }

    /**
     * Set the value of user_type
     *
     * @return  self
     */ 
    public function setUser_type($user_type)
    {
        $this->user_type = $user_type;

        return $this;
    }

    /**
     * Get the value of exists
     */ 
    public function getExists()
    {
        return $this->exists;
    }

    /**
     * Set the value of exists
     *
     * @return  self
     */ 
    public function setExists($exists)
    {
        $this->exists = $exists;

        return $this;
    }
    #endregion
    
    /////////////////////////////
    //          METHODS        //
    /////////////////////////////

    /*
    * Get the full name of the user    
    */
    public function getFullName()
    {
        return $this->firstname . ' ' . $this->name;
    }

    /**
     * Returns a new User object with restricted data for storing in session.
     *
     * @return User
     */
    public function getRestrictedUser()
    {
        $restrictedUser = new User();
        $restrictedUser->setId($this->id);
        $restrictedUser->setTotem($this->totem);
        $restrictedUser->setEmail($this->email);
        $restrictedUser->setName($this->name);
        $restrictedUser->setFirstname($this->firstname);
        $restrictedUser->setBirthdate($this->birthdate);
        $restrictedUser->setPhone($this->phone);
        $restrictedUser->setPicture($this->picture);
        $restrictedUser->setUpdated_at($this->updated_at);
        $restrictedUser->setCreated_at($this->created_at);
        $restrictedUser->setRoles($this->roles);
        $restrictedUser->setUser_type($this->user_type);
        return $restrictedUser;
    }

    /**
     * Returns the roles of the user as an array of strings.
     *
     * @return array
     */
    public function getRolesAsStrings()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            if($role instanceof Role)
                $roles[] = $role->getName();
            else if(is_string($role))
                $roles[] = $role;
            else if(is_array($role))
                $roles[] = $role['name'];
            else if(is_object($role) && isset($role->name))
                $roles[] = $role->name;
            else
                throw new Exception('The role is not a string or an instance of Role');
                
        }
        return $roles;
    }
}
