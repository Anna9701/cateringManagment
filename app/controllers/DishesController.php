<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class DishesController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    /**
     * Searches for ingredients of dish
     * @param string $Id
     */
    public function ingredientsAction($Id) {
        $numberPage = 1;
        $this->session->set("dishId", $Id);

        if (!$this->request->isPost()) {

            $dish = Dishes::findFirst($Id);
            if (!$dish) {
                $this->flash->error("Dish was not found");

                $this->dispatcher->forward([
                    'controller' => "dishes",
                    'action' => 'index'
                ]);

                return;
            }
        }

        $ingredients = $dish->getIngredients();
        if (count($ingredients) == 0) {
            $numberPage = 0;
        }

        $paginator = new Paginator([
            'data' => $ingredients,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Searches for dishes
     */
    public function searchAction()
    {
        $numberPage = 1;
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $id = $this->request->getPost("Id");
        $name = $this->request->getPost("Name");

        $dishes = Dishes::find(
            [
                "(id = :id:) OR name = :name:",
                "bind" => [
                    "id"    => $id,
                    "name" => $name,
                ]
            ]
        );

        if (count($dishes) == 0) {
            $this->flash->notice("Did not find any dishes");

            $this->dispatcher->forward([
                "controller" => "dishes",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $dishes,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * List all dishes
     */
    public function listAction()
    {
        $numberPage = 1;
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $dishes = Dishes::find();
        if (count($dishes) == 0) {
            $this->flash->notice("The search did not find any dishes");

            $this->dispatcher->forward([
                "controller" => "dishes",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $dishes,
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
     * Edits a dish
     *
     * @param string $Id
     */
    public function editAction($Id)
    {
        if (!$this->request->isPost()) {

            $dish = Dishes::findFirst($Id);
            if (!$dish) {
                $this->flash->error("Dish was not found");

                $this->dispatcher->forward([
                    'controller' => "dishes",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $dish->getId();

            $this->tag->setDefault("Id", $dish->getId());
            $this->tag->setDefault("Name", $dish->getName());
        }
    }

    /**
     * Creates a new dish
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

        $dish = new Dishes();
        $dish->setName($this->request->getPost("DishName"));

        $ingredients = [];
        $ingredients[0] = new Ingredients();
        $ingredients[0]->setAmountOf($this->request->getPost("AmountOf"));
        $ingredients[0]->setPrice($this->request->getPost("Price"));
        $ingredients[0]->setName($this->request->getPost("IngredientName"));

        $dish->Ingredients = $ingredients;

        if (!$dish->save()) {

            foreach ($dish->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Dish was created successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a dish edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }


        $Id = $this->request->getPost("Id");
        $dish = Dishes::findFirst($Id);

        if (!$dish) {
            $this->flash->error("Dish does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }

        $dish->setName($this->request->getPost("Name"));


        if (!$dish->save()) {

            foreach ($dish->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'edit',
                'params' => [$dish->getId()]
            ]);

            return;
        }

        $this->flash->success("Dish was updated successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a dish
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $dish = Dishes::findFirst($Id);
        if (!$dish) {
            $this->flash->error("Dish was not found");

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }

        $dish->getIngredients()->delete();
        if (!$dish->delete()) {

            foreach ($dish->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Dish was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => "index"
        ]);
    }

}

