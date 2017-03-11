<?php

namespace ApuestasBundle\Controller;

use ApuestasBundle\Entity\Jugador;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Jugador controller.
 *
 * @Route("jugador")
 */
class JugadorController extends Controller
{

    /**
     * Creates a new jugador entity.
     *
     * @Route("/new", name="jugador_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $jugador = new Jugador();
        $form = $this->createForm('ApuestasBundle\Form\JugadorType', $jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $jugador->setDinero('10000');
            $jugador->setDineroEnJuego('0');
            $em->persist($jugador);
            $em->flush($jugador);

            return $this->redirectToRoute('terminar_juego');
        }

        return $this->render('jugador/new.html.twig', array(
            'jugador' => $jugador,
            'form' => $form->createView(),
            ));
    }

    /**
     * Displays a form to edit an existing jugador entity.
     *
     * @Route("/{id}/edit", name="jugador_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Jugador $jugador)
    {
        $deleteForm = $this->createDeleteForm($jugador);
        $editForm = $this->createForm('ApuestasBundle\Form\JugadorEditType', $jugador);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('terminar_juego');
        }

        return $this->render('jugador/edit.html.twig', array(
            'jugador' => $jugador,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            ));
    }

    /**
     * Deletes a jugador entity.
     *
     * @Route("/{id}", name="jugador_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Jugador $jugador)
    {
        $form = $this->createDeleteForm($jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jugador);
            $em->flush();
        }

        return $this->redirectToRoute('jugador_index');
    }

    /**
     * Creates a form to delete a jugador entity.
     *
     * @param Jugador $jugador The jugador entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Jugador $jugador)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('jugador_delete', array('id' => $jugador->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }

    /**
     * @Route("/eliminar", name="eliminar_jugador")
     * 
     */
    public function EliminarJugador(Request $request) {
        $man = $this->getDoctrine()->getManager();
        $manJugador = $this->getDoctrine()->getRepository("ApuestasBundle:Jugador");
        $jugador = $manJugador->find($request->get("jugador_id"));

        $man->remove($jugador);
        $man->flush();

        $response = new Response(\json_encode(true));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
