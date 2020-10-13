<?php
namespace Code\Controller;

use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Categoria;
use Code\Entity\Despesa;
use Code\Entity\User;
use Code\Session\Session;
use Code\View\View;

class DespesasController
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
		$view = new View('despesas/index.phtml');
		$view->despesas = (new Despesa(Connection::getInstance()))
						  ->where(['users_id' => $userId]);

		return $view->render();
	}

	public function new()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$connection = Connection::getInstance();
		if($method == 'POST') {
			$data = $_POST;
			$data['users_id'] = Session::get('user')['id'];
			$despesa = new Despesa($connection);
			$despesa->insert($data);

			return header('Location: ' . HOME . '/despesas');
		}

		$view = new View('despesas/new.phtml');

		$view->categorias = (new Categoria($connection))->findAll();
		$view->users = (new User($connection))->findAll();

		return $view->render();
	}

	public function edit($id)
	{
		$view = new View('despesas/edit.phtml');
		$method = $_SERVER['REQUEST_METHOD'];
		$connection = Connection::getInstance();

		if($method == 'POST') {
			$data = $_POST;
			$data['id'] = $id;

			$despesa = new Despesa($connection);
			$despesa->update($data);

			return header('Location: ' . HOME . '/despesas');
		}

		$view->categorias = (new Categoria($connection))->findAll();
		$view->users = (new User($connection))->findAll();
		$view->despesa = (new Despesa($connection))->find($id);

		return $view->render();
	}

	public function remove($id)
	{
		$despesa = new Despesa(Connection::getInstance());
		$despesa->delete($id);

		return header('Location: ' . HOME . '/despesas');
	}

}