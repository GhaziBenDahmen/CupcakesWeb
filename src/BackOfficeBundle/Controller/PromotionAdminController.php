<?php

namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\Actuality;
use ECommerceBundle\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class PromotionAdminController extends Controller

{
    /**
     * Lists all Actuality entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $promotion = $em->getRepository('ECommerceBundle:Promotion')->findAll();

        return $this->render('ECommerceBundle:Promotion:index.html.twig', array(
            'promotions' => $promotion,
        ));
    }



    /**
     * Creates a new Actuality entity.
     *
     */
    public function newAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createCreateForm($promotion);
        $form->handleRequest($request);

        return $this->render('ECommerceBundle:Promotion:new.html.twig', array(
            'promotions' => $promotion,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Creates a form to create a Actuality entity.
     *
     * @param Promotion $promotion The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Promotion $promotion)
    {
        $form = $this->createForm('ECommerceBundle/Form/PromotionType',$promotion, array(
            'action' => $this->generateUrl('_back_office_promotion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }
    public function createAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createCreateForm($promotion);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirect($this->generateUrl('_back_office_promotion_index'));
        }

        return $this->render('ECommerceBundle:Promotion:new.html.twig', array(
            'promotions' => $promotion,
            'form'   => $form->createView(),
        ));
    }




    /**
     * Finds and displays a Actuality entity.
     *
     */
    public function showAction(Promotion $promotion)
    {
        $deleteForm = $this->createDeleteForm($promotion);

        return $this->render('ECommerceBundle:Promotion:show.html.twig', array(
            'promotions' => $promotion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Promotion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $promotion = $em->getRepository('ECommerceBundle:Promotion')->find($id);

        if (!$promotion) {
            throw $this->createNotFoundException('Unable to find Actuality entity.');
        }

        $editForm = $this->createEditForm($promotion);


        return $this->render('ECommerceBundle:Promotion:edit.html.twig', array(
            'promotions'      => $promotion,
            'edit_form'   => $editForm->createView(),

        ));
    }

    /**
     * Creates a form to edit a Promotion entity.
     *
     * @param Promotion $promotion The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Promotion $promotion)
    {
        $form = $this->createForm('ECommerceBundle\Form\PromotionType',$promotion, array(
            'action' => $this->generateUrl('_back_office_promotion_update', array('id' => $promotion->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $promotion = $em->getRepository('ECommerceBundle:Promotion')->find($id);

        if (!$promotion) {
            throw $this->createNotFoundException('Unable to find Actuality entity.');
        }


        $editForm = $this->createEditForm($promotion);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('_back_office_promotion_index'));
        }

        return $this->render('ECommerceBundle:Promotion:edit.html.twig', array(
            'promotions'      => $promotion,
            'edit_form'   => $editForm->createView(),

        ));
    }
    /**
     * Deletes a BackActuality entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $promotion = $em->getRepository('ECommerceBundle:Promotion')->find($id);
        $em->remove($promotion);
        $em->flush();
        return $this->redirectToRoute('_back_office_promotion_index');
    }

    /**
     * Creates a form to delete a promotions entity.
     *
     * @param Promotion $promotion The Promotion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Promotion $promotion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_back_office_promotion_delete', array('id' => $promotion->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
