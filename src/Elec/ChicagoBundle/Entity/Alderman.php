<?php

namespace Elec\ChicagoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alderman
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Elec\ChicagoBundle\Entity\AldermanRepository")
 */
class Alderman
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="party", type="string", length=255)
     */
    private $party;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstElected", type="date")
     */
    private $firstElected;

    /**
     * Many-To-One
     *
     * @var Ward $ward
     *
     * @ORM\ManyToOne(targetEntity="Ward")
     * @ORM\JoinColumn(name="Ward", referencedColumnName="id")
     */
    private $ward;


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
     * Set name
     *
     * @param string $name
     * @return Alderman
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set party
     *
     * @param string $party
     * @return Alderman
     */
    public function setParty($party)
    {
        $this->party = $party;
    
        return $this;
    }

    /**
     * Get party
     *
     * @return string 
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     * Set firstElected
     *
     * @param \DateTime $firstElected
     * @return Alderman
     */
    public function setFirstElected($firstElected)
    {
        $this->firstElected = $firstElected;
    
        return $this;
    }

    /**
     * Get firstElected
     *
     * @return \DateTime 
     */
    public function getFirstElected()
    {
        return $this->firstElected;
    }

    /**
     * Set ward
     *
     * @param integer $ward
     * @return Alderman
     */
    public function setWard($ward)
    {
        $this->ward = $ward;
    
        return $this;
    }

    /**
     * Get ward
     *
     * @return integer 
     */
    public function getWard()
    {
        return $this->ward;
    }
}
