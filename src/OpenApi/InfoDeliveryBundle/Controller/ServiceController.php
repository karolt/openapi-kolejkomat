<?php

namespace OpenApi\InfoDeliveryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OpenApi\InfoDeliveryBundle\Entity\Service;
use OpenApi\InfoDeliveryBundle\Form\ServiceType;

/**
 * Service controller.
 *
 */
class ServiceController extends Controller
{

    /**
     * Lists all Service entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('InfoDeliveryBundle:Service')->findByOwner($this->getUser()->getUsername());

        return $this->render('InfoDeliveryBundle:Service:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Service entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Service();
        $form = $this->createForm(new ServiceType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setOwner($this->getUser()->getUsername());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('service_show', array('id' => $entity->getId())));
        }

        return $this->render('InfoDeliveryBundle:Service:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Service entity.
     *
     */
    public function newAction()
    {
        $entity = new Service();
        $form   = $this->createForm(new ServiceType(), $entity);

        return $this->render('InfoDeliveryBundle:Service:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Service entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfoDeliveryBundle:Service:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        $editForm = $this->createForm(new ServiceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfoDeliveryBundle:Service:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Service entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfoDeliveryBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServiceType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('service_edit', array('id' => $id)));
        }

        return $this->render('InfoDeliveryBundle:Service:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Service entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfoDeliveryBundle:Service')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Service entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('service'));
    }

    /**
     * Creates a form to delete a Service entity by id.
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
}
