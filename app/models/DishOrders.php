<?php

class DishOrders extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $orderId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $dishId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $takeaway;

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
     * Method to set the value of field orderId
     *
     * @param integer $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Method to set the value of field dishId
     *
     * @param integer $dishId
     * @return $this
     */
    public function setDishId($dishId)
    {
        $this->dishId = $dishId;

        return $this;
    }

    /**
     * Method to set the value of field takeaway
     *
     * @param integer $takeaway
     * @return $this
     */
    public function setTakeaway($takeaway)
    {
        $this->takeaway = $takeaway;

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
     * Returns the value of field orderId
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Returns the value of field dishId
     *
     * @return integer
     */
    public function getDishId()
    {
        return $this->dishId;
    }

    /**
     * Returns the value of field takeaway
     *
     * @return integer
     */
    public function getTakeaway()
    {
        return $this->takeaway;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("catering");
        $this->setSource("dishOrders");
        $this->belongsTo('orderId', '\Orders', 'id', ['alias' => 'Orders']);
        $this->belongsTo('dishId', '\Dishes', 'id', ['alias' => 'Dishes']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'dishOrders';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DishOrders[]|DishOrders|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DishOrders|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
