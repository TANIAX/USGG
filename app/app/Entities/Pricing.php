<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class for managing pricing in the application.
 * @author Guillaume cornez
 */

class Pricing extends Entity
{
    /**
     * @var int The ID of the pricing.
     */
    private int $id;

    /**
     * @var float The price for tier 1.
     */
    private float $tier_1;

    /**
     * @var float The price for tier 2.
     */
    private float $tier_2;

    /**
     * @var float The price for tier 3.
     */
    private float $tier_3;

    /**
     * @var float The reduction percentage.
     */
    private float $reduction;

    /**
    * @var bool exist.
    */
    private bool $exists; 


    #region Constructor
    /**
     * Pricing constructor.
     *
     * @param int $id The ID of the pricing entity.
     * @param float $tier_1 The price for tier 1.
     * @param float $tier_2 The price for tier 2.
     * @param float $tier_3 The price for tier 3.
     * @param float $reduction The reduction percentage.
     * @param~bool $exists
     * @return void
     */
    public function __construct(int $id = 0, float $tier_1 = 0, float $tier_2 = 0, float $tier_3 = 0, float $reduction = 0, bool $exists = true)
    {
        $this->id = $id;
        $this->tier_1 = $tier_1;
        $this->tier_2 = $tier_2;
        $this->tier_3 = $tier_3;
        $this->reduction = $reduction;
        $this->exists = $exists;
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
     * Get the value of tier_1
     */
    public function gettier1(): float
    {
        return $this->tier_1;
    }
    /**
     * Set the value of tier_1
     *
     * @param float $tier_1
     * @return self
     */
    public function settier1(float $tier_1): self
    {
        $this->tier_1 = $tier_1;
        return $this;
    }

    /**
     * Get the value of tier_2
     */
    public function gettier2(): float
    {
        return $this->tier_2;
    }
    /**
     * Set the value of tier_2
     *
     * @param float $tier_2
     * @return self
     */
    public function settier2(float $tier_2): self
    {
        $this->tier_2 = $tier_2;
        return $this;
    }

    /**
     * Get the value of tier_3
     */
    public function gettier3(): float
    {
        return $this->tier_3;
    }
    /**
     * Set the value of tier_3
     *
     * @param float $tier_3
     * @return self
     */
    public function settier3(float $tier_3): self
    {
        $this->tier_3 = $tier_3;
        return $this;
    }

    /**
     * Get the value of reduction
     */
    public function getReduction(): float
    {
        return $this->reduction;
    }
    /**
     * Set the value of reduction
     *
     * @param float $reduction
     * @return self
     */
    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;
        return $this;
    }

    /**
     * Get the value of exists
     */
    public function getExists(): bool
    {
        return $this->exists;
    }

    /**
     * Set the value of exists
     *
     * @param bool $exists
     * @return self
     */
    public function setExists(bool $exists): self
    {
        $this->exists = $exists;
        return $this;
    }
    #endregion

}