<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 11/26/17
 * Time: 12:30 PM
 */

class PlacesController extends \Phalcon\Mvc\Controller
{
    public function indexAction() {

    }

    /**
     * Displays creation form
     * @param string $id
     */
    public function newAction($id) {
        $this->tag->setDefault("ClientId", $id);
    }

    /**
     * Creates a new place
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $place = new Places();
        $place->setClientId($this->request->getPost("ClientId"));
        $place->setPostalCode($this->request->getPost("Code"));
        $place->setHallNumber($this->request->getPost("Hall"));
        $place->setFloor($this->request->getPost("Floor"));
        $place->setAddress($this->request->getPost("Address"));

        if (!$place->save()) {

            foreach ($place->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Place was created successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Edits a place
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $place = Places::findFirst($id);
            if (!$place) {
                $this->flash->error("Place was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $place->getId();

            $this->tag->setDefault("Id", $place->getId());
            $this->tag->setDefault("ClientId", $place->getClientId());
            $this->tag->setDefault("Code", $place->getPostalCode());
            $this->tag->setDefault("Address", $place->getAddress());
            $this->tag->setDefault("Floor", $place->getFloor());
            $this->tag->setDefault("Hall", $place->getHallNumber());
        }
    }

    /**
     * Saves a place edited
     *
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $Id = $this->request->getPost("Id");
        $place = Places::findFirst($Id);

        if (!$place) {
            $this->flash->error("Place does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $place->setAddress($this->request->getPost("Address"));
        $place->setClientId($this->request->getPost("ClientId"));
        $place->setFloor($this->request->getPost("Floor"));
        $place->setHallNumber($this->request->getPost("Hall"));
        $place->setPostalCode($this->request->getPost("Code"));

        if (!$place->save()) {

            foreach ($place->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "places",
                'action' => 'edit',
                'params' => [$place->getId()]
            ]);

            return;
        }

        $this->flash->success("Place was updated successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a place
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $place = Places::findFirst($Id);
        if (!$place) {
            $this->flash->error("Place was not found");

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        if (!$place->delete()) {

            foreach ($place->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Place was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => "index"
        ]);
    }
}