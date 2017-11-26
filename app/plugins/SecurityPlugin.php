<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

class SecurityPlugin extends Plugin
{
/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {
			$acl = new AclList();
			$acl->setDefaultAction(Acl::DENY);
			// Register roles
			$roles = [
				'users'  => new Role(
					'Users',
					'Member privileges, granted after sign in.'
				),
				'guests' => new Role(
					'Guests',
					'Anyone browsing the site who is not signed in is considered to be a "Guest".'
				)
			];
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			//Private area resources
			$privateResources = [
				'caterings'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete', 'list'],
				'clients'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete', 'list'],
				'dishes'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete', 'ingredients', 'list'],
                'ingredients' => ['new', 'add', 'delete', 'edit', 'save', 'create']
			];
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}
			//Public area resources
			$publicResources = [
				'index'      => ['index'],
                'users'      => ['new', 'create', 'save'],
				'errors'     => ['index', 'show401', 'show404', 'show500'],
				'session'    => ['index', 'register', 'start', 'logout', 'end'],
			];
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}
			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}
			//Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}
			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}
		return $this->persistent->acl;
	}

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get("auth");

        if (!$auth) {
            $role = "Guests";
        } else {
            $role = "Users";
        }

        // Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        // Obtain the ACL list
        $acl = $this->getAcl();

        // Check if the Role have access to the controller (resource)
        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed) {
            $dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'show401'
			]);
            $this->session->destroy();
			return false;
        }
    }
}