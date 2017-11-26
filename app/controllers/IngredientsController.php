<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class IngredientsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function createAction($id){
        $this->view->Id = $id;
        $this->tag->setDefault("Id", $id);
    }

    /**
     * Creates a new ingredient
     */
    public function addAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "index",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("Id");
        $dish = Dishes::findFirst($id);
        if (!$dish) {
            $this->flash->error("Dish was not found");

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }

        $ingredient = new Ingredients();
        $ingredient->setName($this->request->getPost("IngredientName"));
        $ingredient->setAmountOf($this->request->getPost("AmountOf"));
        $ingredient->setPrice($this->request->getPost("Price"));
        $ingredient->setDishId($dish->getId());
      

        if (!$ingredient->save()) {

            foreach ($ingredient->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "ingredients",
                'action' => 'add'
            ]);

            return;
        }

        $this->flash->success("Ingredient was added successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => 'index'
        ]);
    }


    /**
     * Edits a ingredient
     *
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $ingredient = Ingredients::findFirst($id);
            if (!$ingredient) {
                $this->flash->error("Ingredient was not found");

                $this->dispatcher->forward([
                    'controller' => "dishes",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $ingredient->getId();

            $this->tag->setDefault("Id", $ingredient->getId());
            $this->tag->setDefault("Name", $ingredient->getName());
            $this->tag->setDefault("Price", $ingredient->getPrice());
            $this->tag->setDefault("AmountOf", $ingredient->getAmountOf());
        }
    }

    /**
     * Saves a ingredient edited
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
        $ingredient = Ingredients::findFirst($Id);

        if (!$ingredient) {
            $this->flash->error("Ingredient does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }

        $ingredient->setName($this->request->getPost("Name"));
        $ingredient->setPrice($this->request->getPost("Price"));
        $ingredient->setAmountOf($this->request->getPost("AmountOf"));

        if (!$ingredient->save()) {

            foreach ($ingredient->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "ingredients",
                'action' => 'edit',
                'params' => [$ingredient->getId()]
            ]);

            return;
        }

        $this->flash->success("Ingredient was updated successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a ingredient
     *
     * @param string $Id
     */
    public function deleteAction($Id)
    {
        $ingredient = Ingredients::findFirst($Id);
        if (!$ingredient) {
            $this->flash->error("Ingredient was not found");

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'index'
            ]);

            return;
        }

        if (!$ingredient->delete()) {

            foreach ($ingredient->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dishes",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Ingredient was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "dishes",
            'action' => "index"
        ]);
    }

}

