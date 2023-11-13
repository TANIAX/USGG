<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

/**
 * Class for managing news in the application.
 * @author Guillaume cornez
 */

 class News extends Entity
 {
        private int $id;
        private string $title;
        private string $content;
        private string $picture;
        private bool $exists;
        private User $user;
        private Category $category;
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
         * Get the value of title
         */ 
        public function getTitle()
        {
                return $this->title;
        }

        /**
         * Set the value of title
         *
         * @return  self
         */ 
        public function setTitle($title)
        {
                $this->title = $title;

                return $this;
        }

        /**
         * Get the value of content
         */ 
        public function getContent()
        {
                return $this->content;
        }

        /**
         * Set the value of content
         *
         * @return  self
         */ 
        public function setContent($content)
        {
                $this->content = $content;

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

        /**
         * Get the value of user
         */ 
        public function getUser()
        {
                return $this->user;
        }

        /**
         * Set the value of user
         *
         * @return  self
         */ 
        public function setUser($user)
        {
                $this->user = $user;

                return $this;
        }

        /**
         * Get the value of category
         */ 
        public function getCategory()
        {
                return $this->category;
        }

        /**
         * Set the value of category
         *
         * @return  self
         */ 
        public function setCategory($category)
        {
                $this->category = $category;

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
 }