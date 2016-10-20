<?php

namespace ContactBookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Address
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
     * @ORM\Column(name="city", type="string", length=30)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="houseNo", type="string", length=10)
     */
    private $houseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="apartmentNo", type="string", length=10, nullable=true)
     */
    private $apartmentNo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="addresses")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $owner;


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
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set houseNo
     *
     * @param string $houseNo
     * @return Address
     */
    public function setHouseNo($houseNo)
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    /**
     * Get houseNo
     *
     * @return string 
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * Set apartmentNo
     *
     * @param string $apartmentNo
     * @return Address
     */
    public function setApartmentNo($apartmentNo)
    {
        $this->apartmentNo = $apartmentNo;

        return $this;
    }

    /**
     * Get apartmentNo
     *
     * @return string 
     */
    public function getApartmentNo()
    {
        return $this->apartmentNo;
    }

    /**
     * Set owner
     *
     * @param \ContactBookBundle\Entity\Person $owner
     * @return Address
     */
    public function setOwner(\ContactBookBundle\Entity\Person $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \ContactBookBundle\Entity\Person 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
