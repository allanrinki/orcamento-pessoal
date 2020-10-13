create database orcamento_pessoal;

use orcamento_pessoal;



CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `nome` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_expenses_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

select * from categorias;

drop table `categorias`;

select d.valor, r.valor, d.updated_at, r.updated_at  from despesas as d join receitas as r;

select SUM(d.valor) as total_desp, SUM(r.valor) as total_rec, d.updated_at, r.updated_at, d.users_id, r.users_id  
from despesas as d inner join receitas as r ON d.users_id = r.users_id WHERE r.users_id = 1 and d.users_id = 1 
group by r.users_id;

select SUM(d.valor) as total_desp, SUM(r.valor) as total_rec, d.updated_at,  r.updated_at, d.users_id, r.users_id, u.id
from users as u 
join despesas as d ON u.id = d.users_id AND u.id=1 
join receitas as r ON d.users_id = r.users_id and DATE_FORMAT( d.updated_at, '%d/%c/%Y' ) = DATE_FORMAT( r.updated_at, '%d/%c/%Y' )
group by d.updated_at;




INSERT INTO `categorias` (`id`, `nome`, `descricao`, `created_at`, `updated_at`)
VALUES
	(1,'Alimentos','Gastos com alimentos','2018-05-02 20:10:53','2018-05-02 20:10:53');



CREATE TABLE `despesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `categorias_id` int(11) NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `valor` float(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`,`users_id`,`categorias_id`),
  KEY `fk_despesas_users_idx` (`users_id`),
  KEY `fk_despesas_categorias1_idx` (`categorias_id`),
  CONSTRAINT `fk_despesas_categorias1` FOREIGN KEY (`categorias_id`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_despesas_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

drop table receitas;

drop table despesas;

CREATE TABLE `receitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `categorias_id` int(11) NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `valor` float(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`users_id`,`categorias_id`),
  KEY `fk_receitas_users_idx` (`users_id`),
  KEY `fk_receitas_categorias1_idx` (`categorias_id`),
  CONSTRAINT `fk_receitas_categorias1` FOREIGN KEY (`categorias_id`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receitas_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `despesas` (`id`, `users_id`, `categorias_id`, `descricao`, `valor`, `created_at`, `updated_at`)
VALUES
	(2,1,1,'Outra despesa',39.90,'2020-09-08 20:10:36','2020-09-08 20:10:36'),
	(3,1,1,'teste',19.90,'2020-09-08 20:10:36','2020-09-08 20:10:36');

select * from despesas;
select * from receitas;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
	(1,'Allan','Rinki','allan@gmail.com','123456','2020-09-08 20:10:36','2020-09-08 20:10:36');
    
    INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
	(2,'Maria','Rinki','marian@gmail.com','123456','2020-09-08 20:10:36','2020-09-08 20:10:36');
	
drop table users;

select * from users;

