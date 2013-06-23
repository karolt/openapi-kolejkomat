<?php

namespace OpenApi\QueuesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QueueingCustomerRepository extends EntityRepository
{
    /**
     * @param Queue $queue
     * @param $states
     * @return array
     */
    public function findByQueueAndStates(Queue $queue, $states)
    {
        $criteria = [
            'queue'  => $queue->getId(),
            'state'     => $states
        ];

        return $this->findBy($criteria, ['state' => 'DESC', 'joinedAt' => 'ASC']);
    }
}