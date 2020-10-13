<?php
namespace Code\Controller;

use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Categoria;
use Code\Entity\Receita;
use Code\Entity\User;
use Code\Session\Session;
use Code\View\View;

class CategoriasController
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
		$view = new View('categorias/index.phtml');
		$view->categorias = (new Categoria(Connection::getInstance()))
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
			$categoria = new Categoria($connection);
			$categoria->insert($data);

			return header('Location: ' . HOME . '/categorias');
		}

		$view = new View('categorias/new.phtml');

		$view->categorias = (new Categoria($connection))->findAll();
		$view->users = (new User($connection))->findAll();

		return $view->render();
	}

	public function edit($id)
	{
		$view = new View('categorias/edit.phtml');
		$method = $_SERVER['REQUEST_METHOD'];
		$connection = Connection::getInstance();

		if($method == 'POST') {
			$data = $_POST;
			$data['id'] = $id;

			$categoria = new Categoria($connection);
			$categoria->update($data);

			return header('Location: ' . HOME . '/categorias');
		}

	
		$view->users = (new User($connection))->findAll();
		$view->categorias = (new Categoria($connection))->find($id);

		return $view->render();
	}

	public function remove($id)
	{
		$categoria = new Categoria(Connection::getInstance());
		$categoria->delete($id);

		return header('Location: ' . HOME . '/categorias');
	}

}