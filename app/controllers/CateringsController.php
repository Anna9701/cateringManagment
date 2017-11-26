<?php
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;

class CateringsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Searches for caterings
     */
    public function searchAction()
    {
        $numberPage = 1;
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

       $id = $this->request->getPost("Id");
       $name = $this->request->getPost("Name");

        $caterings = Caterings::find(
            [
                "(id = :id:) OR name = :name:",
                "bind" => [
                    "id"    => $id,
                    "name" => $name,
                ]
            ]
        );
        if (count($caterings) == 0) {
            $this->flash->notice("Did not find any caterings");

            $this->dispatcher->forward([
                "controller" => "caterings",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $caterings,
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



        $caterings = Caterings::find();
        if (count($caterings) == 0) {
            $this->flash->notice("The search did not find any caterings");

            $this->dispatcher->forward([
                "controller" => "caterings",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $caterings,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

  /**
     * Edits a catering
     *
     * @param string $Id
     */
    public function editAction($Id)
    {
        if (!$this->request->isPost()) {

            $catering = Caterings::findFirstById($Id);
            if (!$catering) {
                $this->flash->error("Catering was not found");

                $this->dispatcher->forward([
                    'controller' => "caterings",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $catering->getId();

            $this->tag->setDefault("Id", $catering->getId());
            $this->tag->setDefault("Name", $catering->getName());
            
        }
    }

    /**
     * Saves a catering edited
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
        $catering = Caterings::findFirst($Id);

        if (!$catering) {
            $this->flash->error("Catering does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        $catering->setName($this->request->getPost("Name"));
    
        if (!$catering->save()) {

            foreach ($catering->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'edit',
                'params' => [$catering->getId()]
            ]);

            return;
        }

        $this->flash->success("Catering was updated successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => 'index'
        ]);
    }


    /**
     * Creates a new catering
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
        
        $catering = new Caterings();
        $catering->setName($this->request->getPost("Name"));
        
        // var_dump($catering);
        // die;
        if (!$catering->save()) {

            foreach ($catering->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Catering was created successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a catering
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $catering = Caterings::findFirstById($Id);
        if (!$catering) {
            $this->flash->error("Catering was not found");

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'index'
            ]);

            return;
        }

        if (!$catering->delete()) {

            foreach ($catering->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "caterings",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Catering was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "caterings",
            'action' => "index"
        ]);
    }
}

