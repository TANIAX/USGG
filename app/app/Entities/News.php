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
        /**
         * @var int The ID of the news.
         */
        private int $id;

        /**
         * @var string The title of the news.
         */
        private string $title;

        /**
         * @var string The content of the news.
         */
        private string $content;

        /**
         * @var string The picture URL of the news.
         */
        private string $picture;

        /**
         * @var bool Indicates if the news exists.
         */
        private bool $exists;

        /**
         * @var User The user associated with the news.
         */
        private User $user;

        /**
         * @var Category The category of the news.
         */
        private Category $category;

        /**
         * @var ?DateTime The date and time when the news was last updated.
         */
        private ?DateTime $updated_at;

        /**
         * @var DateTime The date and time when the news was created.
         */
        private DateTime $created_at;


        #region Constructor
        /**
         * Constructor for the News class.
         *
         * @param int $id The ID of the news.
         * @param string $title The title of the news.
         * @param string $content The content of the news.
         * @param string $picture The picture associated with the news.
         * @param bool $exists Indicates if the news exists.
         * @param User|null $user The user associated with the news.
         * @param Category|null $category The category associated with the news.
         * @param DateTime|null $created_at The creation date of the news.
         * @param DateTime|null $updated_at The last update date of the news.
         */
        public function __construct(int $id = 0, string $title = "", string $content = "", string $picture = "", bool $exists = false, User $user = null, Category $category = null, DateTime $created_at = null, DateTime $updated_at = null)
        {
                $this->id = $id;
                $this->title = $title;
                $this->content = $content;
                $this->picture = $picture;
                $this->exists = $exists;
                $this->user = $user;
                $this->category = $category;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
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
        #endregion
}