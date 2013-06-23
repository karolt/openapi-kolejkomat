<?php

namespace OpenApi\QueuesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Queue
 */
class Queue
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var QueueingCustomer
     */
    private $queueingCustomers;


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
     * Set owner
     *
     * @param string $owner
     * @return Queue
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return string 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set customers
     *
     * @param QueueingCustomer[] $customers
     * @return Queue
     */
    public function setQueueingCustomers($customers)
    {
        $this->queueingCustomers = $customers;

        return $this;
    }

    /**
     * Get customers
     *
     * @return QueueingCustomer[]
     */
    public function getQueueingCustomers()
    {
        return $this->queueingCustomers;
    }

    public function addQueingCustomer(QueueingCustomer $queingCustomer)
    {
        $this->queueingCustomers[] = $queingCustomer;
    }

    public function addCustomer(Customer $customer)
    {
        $qc = new QueueingCustomer();
        $qc->setCustomer($customer);
        $qc->setJoinedAt(new \DateTime());
        $qc->setQueue($this);

        $this->queingCustomers[] = $qc;

    }
}
