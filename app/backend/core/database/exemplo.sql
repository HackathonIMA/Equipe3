-- Produto
CREATE TABLE `produto` (
  `produto_id` int(4) NOT NULL AUTO_INCREMENT,
  `produto_categoria_id` int(4) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `detalhes` text,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` bit(1) DEFAULT b'1',
  `apagado` bit(1) DEFAULT b'0',
  PRIMARY KEY (`produto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- data
INSERT INTO produto (produto_id, produto_categoria_id, nome, valor, detalhes, data_criacao, ativo, apagado) VALUES (2, null, 'Smart Watch', 230.00, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam et eveniet doloribus animi? Ipsa quidem dolore fugiat quasi ullam tempora. Enim rerum officia illum minima inventore recusandae aspernatur debitis, obcaecati.', '2015-04-15 15:16:24', true, true);
INSERT INTO produto (produto_id, produto_categoria_id, nome, valor, detalhes, data_criacao, ativo, apagado) VALUES (3, null, 'Google TV', 99.00, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam et eveniet doloribus animi? Ipsa quidem dolore fugiat quasi ullam tempora. Enim rerum officia illum minima inventore recusandae aspernatur debitis, obcaecati.', '2015-04-15 15:23:51', true, true);
INSERT INTO produto (produto_id, produto_categoria_id, nome, valor, detalhes, data_criacao, ativo, apagado) VALUES (4, null, 'Churros da Esquina', 3.00, 'Dizem que é muito bom.', '2015-04-15 16:18:53', true, false);
