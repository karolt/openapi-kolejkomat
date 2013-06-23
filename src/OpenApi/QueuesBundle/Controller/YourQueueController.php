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
        //akcja nie ma sensu jesli klient nie ma obecnie stanu 'w trakcie obslugi'
        if ($queingCustomer->getState() != QueueingCustomer::STATE_BEING_SERVED) {
            return $this->forward("QueuesBundle:YourQueue:index");
        }

        $em = $this->getDoctrine()->getManager();
        $queingCustomer->setState(QueueingCustomer::STATE_SERVED);
        $em->persist($queingCustomer);
        $em->flush();

        //$to = $queingCustomer->getCustomer()->getPhone();
        $to = $this->container->getParameter('open_middleware.phone2');
        $msg= "Dziekujemy za skorzystanie z naszych uslug. Zapraszamy ponownie :)";
        $this->sendUssdMessage($to, $msg);

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

        //$to = $firstInQueue->getCustomer()->getPhone();
        $to = $this->container->getParameter('open_middleware.phone2');
        $msg= "Szybciutko! juz Twoja kolej! Zapraszamy! :)";
        $this->sendUssdMessage($to, $msg);


        $em->persist($firstInQueue);
        $em->flush();

        return $this->forward("QueuesBundle:YourQueue:index");
    }

    private function sendUssdMessage($to, $msg)
    {
        /** @var $ussdApi \OpenMiddleware\Bundle\Api\UssdApi */
        $ussdApi = $this->get('open_middleware.api.ussd');

        $msg = new \OpenMiddleware\Bundle\Ussd($to, $msg);
        $ussdApi->send($msg);
    }
}
