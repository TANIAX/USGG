<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

/**
 * Class for managing usertype in the application.
 * @author Guillaume cornez
 */

class UserType extends Entity
{
        /**
         * @var int The ID of the user type.
         */
        private int $id;

        /**
         * @var string The name of the user type.
         */
        private string $name;

        /**
         * @var bool Indicates whether the user type exists.
         */
        private bool $exists;

        /**
         * @var DateTime|null The date and time when the user type was last updated.
         */
        private ?DateTime $updated_at;

        /**
         * @var DateTime The date and time when the user type was created.
         */
        private DateTime $created_at;


        #region Constructor
        /**
         * Constructor of the UserType class.
         * @param int $id The ID of the user type.
         * @param string $name The name of the user type.
         * @param bool $exists Indicates whether the user type exists.
         * @param DateTime|null $updated_at The date and time when the user type was last updated.
         * @param DateTime $created_at The date and time when the user type was created.
         */
        public function __construct(int $id = 0, string $name = "", bool $exists = false, DateTime $created_at = null, DateTime $updated_at = null)
        {
                $this->id = $id;
                $this->name = $name;
                $this->exists = $exists;
                $this->updated_at = $updated_at;
                $this->created_at = $created_at;
        }
        #endregion

        #region Getters and Setters
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
}