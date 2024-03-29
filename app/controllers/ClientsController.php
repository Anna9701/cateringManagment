<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ClientsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    /**
     * Searches for clients
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {

        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $id = $this->request->getPost("Id");
        $fistName = $this->request->getPost("FirstName");
        $lastName = $this->request->getPost("LastName");

        $clients = Clients::find(
            [
                "(id = :id:) OR first_name = :firstName: OR last_name = :lastName:",
                "bind" => [
                    "id"    => $id,
                    "firstName" => $fistName,
                    "lastName" => $lastName,
                ]
            ]
        );
        if (count($clients) == 0) {
            $this->flash->notice("The search did not find any clients");

            $this->dispatcher->forward([
                "controller" => "clients",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $clients,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * List all clients
     */
    public function listAction()
    {
        $numberPage = 1;
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }



        $clients = Clients::find();
        if (count($clients) == 0) {
            $this->flash->notice("The search did not find any clients");

            $this->dispatcher->forward([
                "controller" => "clients",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $clients,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Displays clients details
     * @param string $Id
     */
    public function detailsAction($Id) {
        if (!$this->request->isPost()) {

            $client = Clients::findFirst($Id);
            if (!$client) {
                $this->flash->error("Client was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }
        }
    }

    /**
     * Edits a client
     *
     * @param string $Id
     */
    public function editAction($Id)
    {
        if (!$this->request->isPost()) {

            $client = Clients::findFirst($Id);
            if (!$client) {
                $this->flash->error("Client was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }

            $contactData = $client->getContactData()[0];


            $this->view->Id = $client->getId();

            $this->tag->setDefault("Id", $client->getId());
            $this->tag->setDefault("FirstName", $client->getFirstName());
            $this->tag->setDefault("LastName", $client->getLastName());
            $this->tag->setDefault("Phone", $contactData->getPhone());
            $this->tag->setDefault("Fax", $contactData->getFax());
            $this->tag->setDefault("Email", $contactData->getEmail());
        }
    }

    /**
     * Creates a new client
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "index",
                'action' => 'index'
            ]);

            return;
        }

        $client = new Clients();
        $client->setFirstName($this->request->getPost("FirstName"));
        $client->setLastName($this->request->getPost("LastName"));

        $contactData = new ContactData();
        $contactData->setPhone($this->request->getPost("Phone"));
        $contactData->setFax($this->request->getPost("Fax"));
        $contactData->setEmail($this->request->getPost("Email"));

        $client->ContactData = $contactData;

        if (!$client->save()) {

            foreach ($client->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Client was created successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a client edited
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
        $client = Clients::findFirst($Id);

        if (!$client) {
            $this->flash->error("Client does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $client->setFirstName($this->request->getPost("FirstName"));
        $client->setLastName($this->request->getPost("LastName"));
        $contactData = $client->getContactData()[0];
        $contactData->setPhone($this->request->getPost("Phone"));
        $contactData->setFax($this->request->getPost("Fax"));
        $contactData->setEmail($this->request->getPost("Email"));


        if (!$client->save() || !$contactData->save()) {

            foreach ($client->getMessages() as $message) {
                $this->flash->error($message);
            }
            foreach ($contactData->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'edit',
                'params' => [$client->getId()]
            ]);

            return;
        }

        $this->flash->success("Client was updated successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a client
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $client = Clients::findFirst($Id);
        if (!$client) {
            $this->flash->error("Client was not found");

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $client->getContactData()->delete();
        if (!$client->delete()) {

            foreach ($client->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Client was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => "index"
        ]);
    }

    /**
     * Searches for clients places
     * @param string $Id
     */
    public function placesListAction($Id) {
        $numberPage = 1;
     //   $this->session->set("dishId", $Id);

        if (!$this->request->isPost()) {

            $client = Clients::findFirst($Id);
            if (!$client) {
                $this->flash->error("Client was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }
        }

        $places = $client->getPlaces();
        if (count($places) == 0) {
            $numberPage = 0;
        }

        $paginator = new Paginator([
            'data' => $places,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }
}

