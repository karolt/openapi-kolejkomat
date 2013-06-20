<?php

namespace OpenApi\InfoDeliveryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OpenApi\InfoDeliveryBundle\Entity\OrderEntry;
use OpenApi\InfoDeliveryBundle\Form\OrderEntryType;

/**
 * OrderEntry controller.
 *
 */
class OrderEntryController extends Controller
{

    /**
     * Lists all OrderEntry entities.
     *
     */
    public function indexAction()
    {

        return $this->render('InfoDeliveryBundle:OrderEntry:index.html.twig', array(
            'new' => $this->getOrdersWithState(OrderEntry::STATE_NEW),
            'inProgress' => $this->getOrdersWithState(OrderEntry::STATE_IN_PROGRESS),
            'closed' => $this->getOrdersWithState(OrderEntry::STATE_CLOSED),
        ));
    }

    private function getOrdersWithState($state)
    {
        $em = $this->getDoctrine()->getManager();
        $new = $em->getRepository('InfoDeliveryBundle:OrderEntry')->findBy(array('owner' => $this->getUser()->getUsername(), 'state' => $state));
        return $new;
    }

    /**
     * Creates a new OrderEntry entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new OrderEntry();
        $form = $this->createForm(new OrderEntryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setState(OrderEntry::STATE_NEW);
            $entity->setOwner($this->getUser()->getUsername());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('order_show', array('id' => $entity->getId())));
        }

        return $this->render('InfoDeliveryBundle:OrderEntry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new OrderEntry entity.
     *
     */
    public function newAction()
    {
        $entity = new OrderEntry();
        $form   = $this->createForm(new OrderEntryType(), $entity);

        return $this->render('InfoDeliveryBundle:OrderEntry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OrderEntry entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:OrderEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderEntry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfoDeliveryBundle:OrderEntry:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OrderEntry entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:OrderEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderEntry entity.');
        }

        $editForm = $this->createForm(new OrderEntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfoDeliveryBundle:OrderEntry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing OrderEntry entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:OrderEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderEntry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrderEntryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('order_edit', array('id' => $id)));
        }

        return $this->render('InfoDeliveryBundle:OrderEntry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a OrderEntry entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfoDeliveryBundle:OrderEntry')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrderEntry entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     * Creates a form to delete a OrderEntry entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Deletes a OrderEntry entity.
     *
     */
    public function markStateAction(Request $request, $id, $state)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $entity OrderEntry */
        $entity = $em->getRepository('InfoDeliveryBundle:OrderEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderEntry entity.');
        }

        if ($state == OrderEntry::STATE_CLOSED || $state == OrderEntry::STATE_IN_PROGRESS) {
            /** @var $sendSmsApi \OpenApi\OpenMiddlewareBundle\Api\SendSms */
            $sendSmsApi = $this->get('open_middleware.send_sms');

            $sms = new \OpenApi\OpenMiddlewareBundle\Sms("+48513050541", "+48513050595", $this->buildSmsMessage($state, $entity));
            $sendSmsApi->send($sms);
        }

        $entity->setState($state);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
    }

    private function buildSmsMessage($state, $entity)
    {
        if ($state == OrderEntry::STATE_CLOSED) {
            return  "Twóje zamówienie (numer:" . $entity->getId() . ") zostało zrealizowane. Zapraszamy po odbiór. --- ".$this->getUser()->getUsername();
        }

        if ($state == OrderEntry::STATE_IN_PROGRESS) {
            return  "Twóje zamówienie (numer:" . $entity->getId() . ") jest w trakcie realizacji. Już wkrótce wszystko będzie gotowe :) --- ".$this->getUser()->getUsername();
        }

        throw new \RuntimeException("trying to build message for an unknown state");
    }
}
