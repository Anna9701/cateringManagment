<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class DishesController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    /**
     * Edits a ingredient
     *
     * @param string $Id
     */
    public function editAction($Id)
    {
        if (!$this->request->isPost()) {

            $ingredient = Ingredients::findFirst($Id);
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

