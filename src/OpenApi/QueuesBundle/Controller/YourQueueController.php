<?php
namespace OpenApi\QueuesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OpenApi\QueuesBundle\Entity\Queue;
use OpenApi\QueuesBundle\Entity\QueueingCustomer;

class YourQueueController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $queue Queue */
        $queue = $em->getRepository('QueuesBundle:Queue')->findOneByOwner($this->getUser()->getUsername());

        $criteria = [
            'queue'  => $queue->getId(),
            'state'     => [QueueingCustomer::STATE_WAITING, QueueingCustomer::STATE_BEING_SERVED]
        ];
        $queueingCustomers = $em->getRepository('QueuesBundle:QueueingCustomer')->findBy($criteria);

        return $this->render("QueuesBundle:YourQueue:index.html.twig", ['queue' => $queue, 'queueingCustomers' => $queueingCustomers]);
    }
}
