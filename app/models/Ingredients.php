<?php

class Ingredients extends \Phalcon\Mvc\Model
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
     * @var double
     * @Column(type="double", nullable=false)
     */
    protected $price;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $dishId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $amount_of;

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
     * Method to set the value of field price
     *
     * @param double $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Method to set the value of field amount_of
     *
     * @param integer $amount_of
     * @return $this
     */
    public function setAmountOf($amount_of)
    {
        $this->amount_of = $amount_of;

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
     * Returns the value of field price
     *
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the value of field amount_of
     *
     * @return integer
     */
    public function getAmountOf()
    {
        return $this->amount_of;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("catering");
        $this->setSource("ingredients");
        $this->belongsTo('dishId', '\Dishes', 'id', ['alias' => 'Dishes']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ingredients';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ingredients[]|Ingredients|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ingredients|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
