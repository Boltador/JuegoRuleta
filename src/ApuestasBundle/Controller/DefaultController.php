<?php

namespace ApuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jugadores = $em->getRepository('ApuestasBundle:Jugador')->findAll();
        $estado = 0;
        if(isset($_SESSION['estado'])){
            $estado = 1;
        }  
        return $this->render('ApuestasBundle:Default:index.html.twig', array(
            'jugadores' => $jugadores,
            'estado' => $estado
            ));
    }

    /**
     * @Route("/juego", name="juego")
     */
    public function juegoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jugadores = $em->getRepository('ApuestasBundle:Jugador')->findAll();

        if(!isset($_SESSION['estado'])){
            $_SESSION['estado'] = 1;
            foreach ($jugadores as $jugador) {
                if($jugador->getDinero() > 10000){
                    $jugador->setDineroEnJuego('10000');
                    $jugador->setDinero($jugador->getDinero() - 10000);
                } elseif ($jugador->getDinero() <= 10000 && $jugador->getDinero() > 0) {
                    $jugador->setDineroEnJuego($jugador->getDinero());
                    $jugador->setDinero('0');
                } elseif ($jugador->getDinero() == 0){
                    $jugador->setDineroEnJuego('0');
                }
            }
            $em->flush();

        }

        return $this->render('ApuestasBundle:Default:juego.html.twig', array(
          'jugadores' => $jugadores
          ));
    }

    /**
     * @Route("/girar-ruleta", name="girar_ruleta")
     * @Method({"GET", "POST"})
     */
    public function girarRuletaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $jugadores = $em->getRepository('ApuestasBundle:Jugador')->findAll(); // Se obtiene lista de jugadores
        $numero_ganador = rand(1, 100); // Se obtiene un random para calcular el color ganador
        $color_ganador = 0; // 1 es Verde, 2 es Rojo y 3 es Negro
        $multiplicador = 2; // Variable para multiplicar la apuesta

        /* Se calcula el rango de cada color y se define el color ganador */
        if($numero_ganador == 1 || $numero_ganador == 2){
            $color_ganador = 1;
            $multiplicador = 15;
        } elseif ($numero_ganador > 2 && $numero_ganador <= 51) {
            $color_ganador = 2;
        } elseif ($numero_ganador > 51 && $numero_ganador <= 100) {
            $color_ganador = 3;
        } 

        $resultados = array();

        /* Se obtiene el resultado de cada jugador */
        foreach($jugadores as $indice=>$jugador){
            if($jugador->getDineroEnJuego() > 0){

                $id_jugador = $jugador->getId();
                $color_apostado = $request->get("color$id_jugador");
                $porcentaje_apostado = $request->get("slider_monto$id_jugador");
                if(is_null($request->get("slider_monto$id_jugador"))){
                    $monto_apostado = $jugador->getDineroEnJuego();
                } else {
                $monto_apostado = round(($jugador->getDineroEnJuego() * $request->get("slider_monto$id_jugador")) / 100); // Se obtiene el dinero apostado por el jugador
            }
            $dinero_ganado = 0; // Se inicializa variable recompensa 

            if($color_apostado == $color_ganador){
                $dinero_ganado = $monto_apostado * $multiplicador;
                $jugador->setDineroEnJuego(($jugador->getDineroEnJuego() + $dinero_ganado));
                $resultados[$indice] = array("gano" => 1, "color_apostado" => $color_apostado, "dinero_apostado" => $monto_apostado ,"dinero_ganado" => $dinero_ganado);
            } else {
                $jugador->setDineroEnJuego(($jugador->getDineroEnJuego() - $monto_apostado));
                $resultados[$indice] = array("gano" => 0, "color_apostado" => $color_apostado, "dinero_apostado" => $monto_apostado);
            }
        } else {
            $resultados[$indice] = array("gano" => 0);
        }
    }

    $em->flush();

    return $this->render('ApuestasBundle:Default:juego.html.twig', array(
      'jugadores' => $jugadores,
      'color' => $color_ganador,
      'resultados' => $resultados
      ));
}

    /**
     * @Route("/terminar-juego", name="terminar_juego")
     */
    public function terminarJuegoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jugadores = $em->getRepository('ApuestasBundle:Jugador')->findAll();

        foreach ($jugadores as $jugador) {
            if($jugador->getDineroEnJuego() > 0){
                $jugador->setDinero($jugador->getDinero() + $jugador->getDineroEnJuego());
                $jugador->setDineroEnJuego('0');
            }
        }
        $em->flush();

        if(isset($_SESSION)){
            unset($_SESSION['estado']);
        }  

        return $this->redirect($this->generateUrl('inicio'));
    }

    /**
     * @Route("/llenar-jugador", name="llenar_jugador")
     * 
     */
    public function LlenarJugador(Request $request) {
        $man = $this->getDoctrine()->getManager();
        $jugadores = $man->getRepository('ApuestasBundle:Jugador')->findAll();
        $manJugador = $this->getDoctrine()->getRepository("ApuestasBundle:Jugador");
        $jugador = $manJugador->find($request->get("jugador_id"));

        if($jugador->getDineroEnJuego() == 0){
            if(($jugador->getDinero() - 10000) > 0){
                $jugador->setDineroEnJuego(10000);
                $diferencia = $jugador->getDinero() - 10000;
                $jugador->setDinero($diferencia);
            } else {
                $jugador->setDineroEnJuego($jugador->getDinero());
                $jugador->setDinero(0);
            }
            $man->flush();
        }

        $response = new Response(\json_encode(true));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
