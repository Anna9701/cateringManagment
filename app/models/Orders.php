<?php

class Orders extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $cateringId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $clientId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $placeId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $dateId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $priority;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $amount_of_people;

    /**
     *
     * @var double
     * @Column(type="double", nullable=false)
     */
    protected $cost;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $invoice;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $payed_up;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field cateringId
     *
     * @param integer $cateringId
     * @return $this
     */
    public function setCateringId($cateringId)
    {
        $this->cateringId = $cateringId;

        return $this;
    }

    /**
     * Method to set the value of field clientId
     *
     * @param integer $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Method to set the value of field placeId
     *
     * @param integer $placeId
     * @return $this
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * Method to set the value of field dateId
     *
     * @param integer $dateId
     * @return $this
     */
    public function setDateId($dateId)
    {
        $this->dateId = $dateId;

        return $this;
    }

    /**
     * Method to set the value of field priority
     *
     * @param integer $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Method to set the value of field amount_of_people
     *
     * @param integer $amount_of_people
     * @return $this
     */
    public function setAmountOfPeople($amount_of_people)
    {
        $this->amount_of_people = $amount_of_people;

        return $this;
    }

    /**
     * Method to set the value of field cost
     *
     * @param double $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Method to set the value of field invoice
     *
     * @param integer $invoice
     * @return $this
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Method to set the value of field payed_up
     *
     * @param integer $payed_up
     * @return $this
     */
    public function setPayedUp($payed_up)
    {
        $this->payed_up = $payed_up;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field cateringId
     *
     * @return integer
     */
    public function getCateringId()
    {
        return $this->cateringId;
    }

    /**
     * Returns the value of field clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Returns the value of field placeId
     *
     * @return integer
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Returns the value of field dateId
     *
     * @return integer
     */
    public function getDateId()
    {
        return $this->dateId;
    }

    /**
     * Returns the value of field priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Returns the value of field amount_of_people
     *
     * @return integer
     */
    public function getAmountOfPeople()
    {
        return $this->amount_of_people;
    }

    /**
     * Returns the value of field cost
     *
     * @return double
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Returns the value of field invoice
     *
     * @return integer
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Returns the value of field payed_up
     *
     * @return integer
     */
    public function getPayedUp()
    {
        return $this->payed_up;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("catering");
        $this->setSource("orders");
        $this->hasMany('id', 'Dishes', 'orderId', ['alias' => 'Dishes']);
        $this->belongsTo('cateringId', '\Caterings', 'id', ['alias' => 'Caterings']);
        $this->belongsTo('clientId', '\Clients', 'id', ['alias' => 'Clients']);
        $this->belongsTo('placeId', '\Places', 'id', ['alias' => 'Places']);
        $this->belongsTo('dateId', '\Dates', 'id', ['alias' => 'Dates']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'orders';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders[]|Orders|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
