<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

/**
 * Class for managing file downloads in the application.
 */
class File extends Entity
{
    /**
     * @var int The ID of the file.
     */
    private int $id;

    /**
     * @var string The name of the file.
     */
    private string $name;

    /**
     * @var string The path to the file.
     */
    private string $path;

    /**
     * @var bool Indicates if the file exists.
     */
    private bool $exists;

    /**
     * @var string The type of file (e.g., 'guide', 'manual').
     */
    private string $file_type;


    /**
     * @var DateTime The date and time when the file was created.
     */
    private DateTime $created_at;

    #region Constructor
    /**
     * Constructor for the File class.
     *
     * @param int $id The ID of the file.
     * @param string $name The name of the file.
     * @param bool $exists Indicates if the file exists.
     * @param string $file_type The type of file.
     * @param DateTime|null $created_at The creation date of the file.
     */
    public function __construct(int $id = 0, string $name = "", string $path = "", bool $exists = true, string $file_type = "", DateTime $created_at = null) {
        $this->id = $id;
        $this->name = $name;
        $this->path = $path;
        $this->exists = $exists;
        $this->file_type = $file_type;
        $this->created_at = $created_at ?? new DateTime();
    }
    #endregion

    #region Getters and Setters

    // ID
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    // Name
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // Path
    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    // Exists
    public function getExists(): bool
    {
        return $this->exists;
    }

    public function setExists(bool $exists): self
    {
        $this->exists = $exists;
        return $this;
    }

    // File Type
    public function getFileType(): string
    {
        return $this->file_type;
    }

    public function setFileType(string $file_type): self
    {
        $this->file_type = $file_type;
        return $this;
    }

    // Created At
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
    #endregion
}
