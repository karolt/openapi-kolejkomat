<?php
namespace OpenApi\QueuesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OpenApi\QueuesBundle\Entity\Queue;
use OpenApi\QueuesBundle\Entity\QueueingCustomer;
use OpenApi\QueuesBundle\Entity\Customer;

class YourQueueController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $queue Queue */
        $queue = $em->getRepository('QueuesBundle:Queue')->findOneByOwner($this->getUser()->getUsername());

        /** @var $qcRepo \OpenApi\QueuesBundle\Entity\QueueingCustomerRepository */
        $qcRepo = $em->getRepository('QueuesBundle:QueueingCustomer');
        $queueingCustomers = $qcRepo->findByQueueAndStates($queue, [QueueingCustomer::STATE_WAITING, QueueingCustomer::STATE_BEING_SERVED]);

        return $this->render("QueuesBundle:YourQueue:index.html.twig", ['queue' => $queue, 'queueingCustomers' => $queueingCustomers]);
    }


    /**
     * @param \OpenApi\QueuesBundle\Entity\QueueingCustomer $queingCustomer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function customerServedAction(QueueingCustomer $queingCustomer)
    {
        $em = $this->getDoctrine()->getManager();
        $queingCustomer->setState(QueueingCustomer::STATE_SERVED);
        $em->persist($queingCustomer);
        $em->flush();

        return $this->forward("QueuesBundle:YourQueue:index");

    }

    public function serveNextAction(Queue $queue)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $qcRepo \OpenApi\QueuesBundle\Entity\QueueingCustomerRepository */
        $qcRepo = $em->getRepository('QueuesBundle:QueueingCustomer');
        $queueingCustomers = $qcRepo->findByQueueAndStates($queue, [QueueingCustomer::STATE_WAITING]);

        /** @var $firstInQueue QueueingCustomer */
        $firstInQueue = array_shift($queueingCustomers);
        $firstInQueue->setState(QueueingCustomer::STATE_BEING_SERVED);

        $em->persist($firstInQueue);
        $em->flush();

        return $this->forward("QueuesBundle:YourQueue:index");
    }
}
