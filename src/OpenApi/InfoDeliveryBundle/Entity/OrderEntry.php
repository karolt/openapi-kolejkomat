<?php

namespace OpenApi\InfoDeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderEntry
 */
class OrderEntry
{
    const STATE_NEW = 0;
    const STATE_IN_PROGRESS = 1;
    const STATE_CLOSED = 2;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var float
     */
    private $value;

    /**
     * @var int
     */
    private $state;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var Customer
     */
    private $customer;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set service
     *
     * @param string $service
     * @return OrderEntry
     */
    public function setService($service)
    {
        $this->service = $service;
    
        return $this;
    }

    /**
     * Get service
     *
     * @return string 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return OrderEntry
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return OrderEntry
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \OpenApi\InfoDeliveryBundle\Entity\Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \OpenApi\InfoDeliveryBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }


}
