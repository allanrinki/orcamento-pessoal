<?php
namespace Code\Controller;

use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Categoria;
use Code\Entity\Receita;
use Code\Entity\User;
use Code\Session\Session;
use Code\View\View;

class ReceitasController
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
		$view = new View('receitas/index.phtml');
		$view->receitas = (new Receita(Connection::getInstance()))
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
			$receita = new Receita($connection);
			$receita->insert($data);

			return header('Location: ' . HOME . '/receitas');
		}

		$view = new View('receitas/new.phtml');

		$view->categorias = (new Categoria($connection))->findAll();
		$view->users = (new User($connection))->findAll();

		return $view->render();
	}

	public function edit($id)
	{
		$view = new View('receitas/edit.phtml');
		$method = $_SERVER['REQUEST_METHOD'];
		$connection = Connection::getInstance();

		if($method == 'POST') {
			$data = $_POST;
			$data['id'] = $id;

			$receita = new Receita($connection);
			$receita->update($data);

			return header('Location: ' . HOME . '/receitas');
		}

		$view->categorias = (new Categoria($connection))->findAll();
		$view->users = (new User($connection))->findAll();
		$view->receita = (new Receita($connection))->find($id);

		return $view->render();
	}

	public function remove($id)
	{
		$receita = new Receita(Connection::getInstance());
		$receita->delete($id);

		return header('Location: ' . HOME . '/receitas');
	}

}