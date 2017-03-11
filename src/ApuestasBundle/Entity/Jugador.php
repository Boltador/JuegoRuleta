<?php

namespace ApuestasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jugador
 *
 * @ORM\Table(name="jugador")
 * @ORM\Entity
 */
class Jugador
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="dinero", type="integer", nullable=false)
     */
    private $dinero;

    /**
     * @var integer
     *
     * @ORM\Column(name="dinero_en_juego", type="integer", nullable=false)
     */
    private $dineroEnJuego;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Jugador
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set dinero
     *
     * @param integer $dinero
     * @return Jugador
     */
    public function setDinero($dinero)
    {
        $this->dinero = $dinero;

        return $this;
    }

    /**
     * Get dinero
     *
     * @return integer 
     */
    public function getDinero()
    {
        return $this->dinero;
    }

    /**
     * Set dineroEnJuego
     *
     * @param integer $dineroEnJuego
     * @return Jugador
     */
    public function setDineroEnJuego($dineroEnJuego)
    {
        $this->dineroEnJuego = $dineroEnJuego;

        return $this;
    }

    /**
     * Get dineroEnJuego
     *
     * @return integer 
     */
    public function getDineroEnJuego()
    {
        return $this->dineroEnJuego;
    }
}
