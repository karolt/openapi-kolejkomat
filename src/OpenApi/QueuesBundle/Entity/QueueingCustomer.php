<?php

namespace OpenApi\QueuesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueueingCustomer
 */
class QueueingCustomer
{
    const STATE_WAITING = 1;
    const STATE_BEING_SERVED = 2;
    const STATE_SERVED = 3;
    const STATE_OMMITED = 4;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $queue;

    /**
     * @var integer
     */
    private $customer;

    /**
     * @var
     */
    private $state;

    /**
     * @var \DateTime
     */
    private $joinedAt;


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
     * Set queue
     *
     * @param integer $queue
     * @return QueueingCustomer
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    
        return $this;
    }

    /**
     * Get queue
     *
     * @return integer 
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set customer
     *
     * @param integer $customer
     * @return QueueingCustomer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return integer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set joinedAt
     *
     * @param \DateTime $joinedAt
     * @return QueueingCustomer
     */
    public function setJoinedAt($joinedAt)
    {
        $this->joinedAt = $joinedAt;
    
        return $this;
    }

    /**
     * Get joinedAt
     *
     * @return \DateTime 
     */
    public function getJoinedAt()
    {
        return $this->joinedAt;
    }

    /**
     * @param  $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return
     */
    public function getState()
    {
        return $this->state;
    }


}
