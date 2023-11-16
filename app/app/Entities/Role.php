<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

/**
 * Class for managing roles in the application.
 * @author Guillaume cornez
 */

 class Role extends Entity
 {
        private int $id;
        private string $name;
        private bool $exists;
        private ?DateTime $updated_at;
        private DateTime $created_at;
    
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
 }