<?php

//responsavel por buscar os dados no banco e quem extender ele vai se utilizar da logica dele. é uma classe abstrata
//nao pode estanciar ele, somente os filhos que extenderem ele que poderá. 

namespace Code\DB;

use \PDO;

abstract class Entity
{
	/**
	 * @var PDO
	 */
	
	private $conn;

	protected $table;
	protected $tabler;
	protected $tableu;
	

	public function __construct(\PDO $conn) //esta passando uma dependencia que tem que ser do tipo PDO.
	{
		$this->conn = $conn;
	}

	//Metodos principais -> findall: recupera todos os produtos e a função find escolhe um produto.
	public function findAll($fields = '*'): array
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . $this->table;

		$get = $this->conn->query($sql); //identifica que esta recuperando algo

		return $get->fetchAll(PDO::FETCH_ASSOC);
	}

	public function find(int $id, $fields = '*'): array
	{
		return current($this->where(['id' => $id], '', $fields));
	}

	public function where(array $conditions, $operator = ' AND ', $fields = '*') : array
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';

		$binds = array_keys($conditions);

		$where  = null;
		foreach($binds as $v) {
			if(is_null($where)) {
				$where .= $v . ' = :' . $v;
			} else {
				$where .= $operator . $v . ' = :' . $v;
			}
		}

		$sql .= $where;

		$get = $this->bind($sql, $conditions);
		$get->execute();

		return $get->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function sum(int $conditions) : array
	{

		
		$sql = 'SELECT SUM(d.valor) as total_desp, SUM(r.valor) as total_rec, d.updated_at,  r.updated_at, d.users_id, r.users_id, u.id
		from ' . $this->tableu . ' as u 
		join ' . $this->table . ' as d ON u.id = d.users_id AND u.id='.$conditions.
		' join ' . $this->tabler . ' as r ON d.users_id = r.users_id and DATE_FORMAT( d.updated_at, "%d/%c/%Y" ) = DATE_FORMAT( r.updated_at, "%d/%c/%Y" )
		group by d.updated_at';

		$get = $this->conn->query($sql); //identifica que esta recuperando algo

		return $get->fetchAll(PDO::FETCH_ASSOC);
	}
	

	public function insert($data): bool
	{
		$binds = array_keys($data);

		$sql = 'INSERT INTO ' . $this->table . '('. implode(', ', $binds) . ', created_at, updated_at
				) VALUES(:' . implode(', :', $binds) .', NOW(), NOW())';

		$insert = $this->bind($sql, $data);

		return $insert->execute();
	}

	public function update($data): bool
	{
		if(!array_key_exists('id', $data)) {
			throw new \Exception('É preciso informar um ID válido para update!');
		}

		$sql = 'UPDATE ' . $this->table . ' SET ';

		$set = null;
		$binds = array_keys($data);

		foreach($binds as $v) {
			if($v !== 'id') {
				$set .= is_null($set) ? $v . ' = :' . $v : ', ' .  $v . ' = :' . $v ;
			}
		}
		$sql .= $set . ', updated_at = NOW() WHERE id = :id';

		$update = $this->bind($sql, $data);

		return $update->execute();
	}

	public function delete(int $id): bool
	{
		$sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

		$delete = $this->bind($sql, ['id' => $id]);

		return $delete->execute();
	}

	private function bind($sql, $data)
	{
		$bind = $this->conn->prepare($sql);

		foreach ($data as $k => $v) {
			gettype($v) == 'int' ? $bind->bindValue(':' . $k, $v, \PDO::PARAM_INT)
				: $bind->bindValue(':' . $k, $v, \PDO::PARAM_STR);
		}

		return $bind;
	}
}