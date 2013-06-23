<?php
namespace OpenApi\QueuesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OpenApi\QueuesBundle\Entity\Queue;
use OpenApi\QueuesBundle\Entity\QueueingCustomer;
use OpenApi\QueuesBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;

class EnqueueController extends Controller
{
    public function enqueueAction(Request $request)
    {
        $from = $request->get("from");
        $message = $request->get("msg");

        //format wiadomosci to: 138*([0-9][0-9])*WIADOMOSC#
        $messageArr = explode("*", $message);
        $message = $messageArr[2];

        if (!is_numeric($message)) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("Message must be numeric");
        }


        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $queueId = $message;
        /** @var $queue Queue */
        $queue = $em->getRepository('QueuesBundle:Queue')->findOneById($queueId);

        if (!$queue instanceof Queue) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("No such queue");
        }


        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('QueuesBundle:Customer')->findOneByPhone($from);

        if (!$customer instanceof Customer) {
            $customer = new Customer();
            $customer->setPhone($from);
            $em->persist($customer);
        }


        $qc = new QueueingCustomer();
        $qc->setCustomer($customer);
        $qc->setJoinedAt(new \DateTime());
        $qc->setQueue($queue);
        $qc->setState(QueueingCustomer::STATE_WAITING);

        $em->persist($qc);
        $em->flush();

        return $this->render("QueuesBundle:YourQueue:enqueue.xml.twig", ['position' => 15]);
    }
}
