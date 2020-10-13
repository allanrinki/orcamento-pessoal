<?php
namespace Code\View;

//classe responsavel por gerenciar as views do projeto;

class View
{
	private $view; 
	private $data = [];

	public function __construct($view)
	{
		$this->view = $view;
	}

	//metodo magico = quando tentar acertar um atributo que não existe nessa classe, nossa função vai ser executada, auxilia a mandar parametros para a view.
	public function __set($index, $value)
	{
		$this->data[$index] = $value;
	}


//quando tentar acessar ou seja recuperar o valor de um atributo que nao existe o metodo vai ser executado.
	public function __get($index)
	{
		return $this->data[$index];
	}

	public function render()
	{
		ob_start(); //buffer do browser

		require VIEWS_PATH . $this->view; //contem o caminho até as views e o $this->view é o caminho.

		return ob_get_clean(); //limpar o buffer do browser e recupera o valor.
	}
}

//este codigo sera responsavel por carregar a view e exibir o valor ali no momento que carregar um controller.