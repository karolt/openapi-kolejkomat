<?php
namespace OpenApi\QueuesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OpenApi\QueuesBundle\Entity\Queue;
use OpenApi\QueuesBundle\Entity\QueueingCustomer;
use OpenApi\QueuesBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EnqueueController extends Controller
{
    public function enqueueAction(Request $request)
    {
        try {
            $from = $request->get("from");
            $message = $request->get("msg");
            $message = $this->parseUssdMessage($message);

            $queue = $this->getQueue($message);
            $customer = $this->getOrCreateCustomer($from);

            $this->checkCustomerAlreadyInQueue($queue, $customer);
            $this->persistQueueingCustomer($customer, $queue);
            $position = $this->calculatePosition($queue);

            return $this->render("QueuesBundle:YourQueue:enqueue.xml.twig", ['position' => $position]);

        } catch (BadRequestHttpException $e){
            return $this->render("QueuesBundle:YourQueue:enqueue_error.xml.twig", ['message' => $e->getMessage(), 'code' => $e->getStatusCode()]);
        }



    }

    private function calculatePosition($queue)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $queueingCustomers = $em->getRepository('QueuesBundle:QueueingCustomer')->findByQueueAndStates($queue, [QueueingCustomer::STATE_WAITING]);
        $position = count($queueingCustomers);
        return $position;
    }

    private function parseUssdMessage($message)
    { //format wiadomosci to: 138*([0-9][0-9])*WIADOMOSC#
        $messageArr = explode("*", $message);
        $message = $messageArr[2];

        if (!is_numeric($message)) {
            throw new BadRequestHttpException("Message must be numeric");
        }
        return $message;
    }

    private function checkCustomerAlreadyInQueue($queue, $customer)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $queueingCustomer = $em->getRepository('QueuesBundle:QueueingCustomer')->findOneBy(['queue' => $queue, 'customer' => $customer]);

        if ($queueingCustomer instanceof QueueingCustomer) {
            throw new BadRequestHttpException("customer already in queue");
        }
    }

    private function persistQueueingCustomer($customer, $queue)
    {
        $qc = new QueueingCustomer();
        $qc->setCustomer($customer);
        $qc->setJoinedAt(new \DateTime());
        $qc->setQueue($queue);
        $qc->setState(QueueingCustomer::STATE_WAITING);

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $em->persist($qc);
        $em->flush();
    }

    private function getOrCreateCustomer($from)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('QueuesBundle:Customer')->findOneByPhone($from);

        if (!$customer instanceof Customer) {
            $customer = new Customer();
            $customer->setPhone($from);
            $em->persist($customer);
            return array($em, $customer);
        }
        return $customer;
    }

    private function getQueue($message)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $queueId = $message;
        /** @var $queue Queue */
        $queue = $em->getRepository('QueuesBundle:Queue')->findOneById($queueId);

        if (!$queue instanceof Queue) {
            throw new BadRequestHttpException("No such queue");
        }
        return $queue;
    }
}
