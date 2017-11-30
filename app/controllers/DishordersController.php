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
                'action' => 'create'
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

            $order = DishOrders::findFirst($id);
            if (!$order) {
                $this->flash->error("Order was not found");

                $this->dispatcher->forward([
                    'controller' => "caterings",
                    'action' => 'index'
                ]);

                return;
            }

           $this->view->Id = $order->getId();

            $this->tag->setDefault("Id", $order->getId());
            $this->tag->setDefault("OrderId", $order->getOrderId());
            $this->tag->setDefault("Dish", $order->getDishId());

            if ($order->getTakeaway() == 1) {
                $this->tag->setDefault("Takeaway", "Y");
            }

        }
    }

    /**
     * Saves a dish order edited
     *
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $Id = $this->request->getPost("Id");
        $order = DishOrders::findFirst($Id);

        if (!$order) {
            $this->flash->error("Order does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

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
                'controller' => "dishorders",
                'action' => 'edit',
                'params' => [$order->getId()]
            ]);

            return;
        }

        $this->flash->success("Order was updated successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a dish order
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $order = DishOrders::findFirst($Id);
        if (!$order) {
            $this->flash->error("Order was not found");

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        if (!$order->delete()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("Order was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => "index"
        ]);
    }
}