<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 11/26/17
 * Time: 12:30 PM
 */

class OrdersController extends \Phalcon\Mvc\Controller
{
    public function indexAction() {

    }

    /**
     * Displays creation form
     * @param string $id
     */
    public function newAction($id) {
        $this->tag->setDefault("CateringId", $id);
    }

    /**
     * Creates a new order
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

        $date = new Dates();
        $date->setStartTime($this->request->getPost("StartTime"));
        $date->setEndTime($this->request->getPost("EndTime"));
        if (!$date->save()) {

            foreach ($date->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'new'
            ]);

            return;
        }

        $order = new Orders();
        $order->setDateId($date->getId());
        $order->setCateringId($this->request->getPost("CateringId"));
        $order->setAmountOfPeople($this->request->getPost("People"));
        $order->setCost($this->request->getPost("Cost"));
        $order->setPriority($this->request->getPost("Priority"));
        if($this->request->getPost("Invoice") == 'Y') {
            $order->setInvoice(1);
        } else {
            $order->setInvoice(0);
        }

        if($this->request->getPost("Payed") == 'Y') {
            $order->setPayedUp(1);
        } else {
            $order->setPayedUp(0);
        }

        $order->setClientId($this->request->getPost("Client"));

        if (!$order->save()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'new'
            ]);

            return;
        }


        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => 'places',
            'params' => [$order->getId()]
        ]);
    }


    /**
     * Edits a order
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

    /**
     * Saves a order edited
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
        $order = Orders::findFirst($Id);

        if (!$order) {
            $this->flash->error("Order does not exist " . $Id);

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


        if($this->request->getPost("Invoice") == 'Y') {
            $order->setInvoice(1);
        } else {
            $order->setInvoice(0);
        }

        if($this->request->getPost("Payed") == 'Y') {
            $order->setPayedUp(1);
        } else {
            $order->setPayedUp(0);
        }
        $order->setPriority($this->request->getPost("Priority"));
        $order->setCost($this->request->getPost("Cost"));
        $order->setAmountOfPeople($this->request->getPost("People"));
        $order->setCateringId($this->request->getPost("CateringId"));
        $date->setStartTime($this->request->getPost("StartTime"));
        $date->setEndTime($this->request->getPost("EndTime"));
        $order->setClientId($this->request->getPost("Client"));

        if (!$date->save()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'edit',
                'params' => [$order->getId()]
            ]);

            return;
        }

        if (!$order->save()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'edit',
                'params' => [$order->getId()]
            ]);

            return;
        }


        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => 'places',
            'params' => [$order->getId()]
        ]);
    }

    public function placesAction($Id) {
        $this->tag->setDefault("OrderId", $Id);
    }

    public function choosePlaceAction() {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $order = Orders::findFirst($this->request->getPost("OrderId"));
        if (!$order) {
            $this->flash->error("Order was not found");

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $order->setPlaceId($this->request->getPost("Place"));
        if (!$order->save()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'places',
                'params' => [$order->getId()]
            ]);

            return;
        }

        $this->flash->success("Success");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => 'index'
        ]);
    }


    /**
     * Deletes a order with date
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $order = Orders::findFirst($Id);
        if (!$order) {
            $this->flash->error("Order was not found");

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $dateId = $order->getDateId();


        if (!$order->delete()) {

            foreach ($order->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'search'
            ]);

            return;
        }

        Dates::findFirst($dateId)->delete();
        $this->flash->success("Order was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => "index"
        ]);
    }
}