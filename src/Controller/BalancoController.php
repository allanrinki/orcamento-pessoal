<?php
namespace Code\Controller;

use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Categoria;
use Code\Entity\Balanco;
use Code\Entity\User;
use Code\Session\Session;
use Code\View\View;

class BalancoController
{
	use CheckUserLogged;

	public function __construct()
	{
		if(!$this->check()) {
			die("Usuário não logado!");
		}
	}

	public function index()
	{
		$userId = Session::get('user')['id'];
		$view = new View('balanco/index.phtml');
		$view->balanco = (new Balanco(Connection::getInstance()))
						  ->sum($userId);

		return $view->render();
	}	

}