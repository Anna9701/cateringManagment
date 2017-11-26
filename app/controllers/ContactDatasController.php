<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 11/26/17
 * Time: 9:49 AM
 */

class ContactDatasController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {

    }

    /**
     * Edits a data
     *
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $contactData = ContactData::findFirst($id);
            if (!$contactData) {
                $this->flash->error("Data was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->Id = $contactData->getId();

            $this->tag->setDefault("Id", $contactData->getId());
            $this->tag->setDefault("Phone", $contactData->getPhone());
            $this->tag->setDefault("Fax", $contactData->getFax());
            $this->tag->setDefault("Email", $contactData->getEmail());
        }
    }

    /**
     * Saves a data edited
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
        $contactData = ContactData::findFirst($Id);

        if (!$contactData) {
            $this->flash->error("Data does not exist " . $Id);

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $contactData->setEmail($this->request->getPost("Email"));
        $contactData->setFax($this->request->getPost("Fax"));
        $contactData->setPhone($this->request->getPost("Phone"));

        if (!$contactData->save()) {

            foreach ($contactData->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contactDatas",
                'action' => 'edit',
                'params' => [$contactData->getId()]
            ]);

            return;
        }

        $this->flash->success("Data was updated successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }
}