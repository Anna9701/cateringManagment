<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 11/29/17
 * Time: 9:52 AM
 */

class DishordersController extends \Phalcon\Mvc\Controller
{


    public function indexAction()
    {

    }

    /**
     * Displays creation form
     * @param string $id
     */
    public function newAction($id) {
        $this->tag->setDefault("OrderId", $id);
    }

    /**
     * Creates a new dishOrder
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $order = new DishOrders();
        $order->setOrderId($this->request->getPost("OrderId"));
        $order->setDishId($this->request->getPost("Dish"));
        if ($this->request->getPost("Takeaway") == 'Y') {
            $order->setTakeaway(1);
        } else {
            $order->setTakeaway(0);
        }


        if (!$order->save()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dishOrders",
                'action' => 'new'
            ]);


            return;
        }

        $this->flash->success("Dish order was created successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => 'index'
        ]);
    }

    /**
     * Edits a dishOrder
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $order = Orders::findFirst($id);
            if (!$order) {
                $this->flash->error("Order was not found");

                $this->dispatcher->forward([
                    'controller' => "caterings",
                    'action' => 'index'
                ]);

                return;
            }

            $date = Dates::findFirst($order->getDateId());
            if (!$date) {
                $this->flash->error("Date was not found");

                $this->dispatcher->forward([
                    'controller' => "caterings",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $order->getId();

            $this->tag->setDefault("Id", $order->getId());
            $this->tag->setDefault("Client", $order->getClientId());
            $this->tag->setDefault("CateringId", $order->getCateringId());
            $this->tag->setDefault("People", $order->getAmountOfPeople());
            $this->tag->setDefault("Cost", $order->getCost());
            $this->tag->setDefault("Priority", $order->getPriority());
            $this->tag->setDefault("StartTime", $date->getStartTime());
            $this->tag->setDefault("EndTime", $date->getEndTime());
            $this->tag->setDefault("DateId", $date->getId());

            if ($order->getPayedUp() == 1) {
                $this->tag->setDefault("Payed", "Y");
            }
            if ($order->getInvoice() == 1) {
                $this->tag->setDefault("Invoice", "Y");
            }


        }
    }
}