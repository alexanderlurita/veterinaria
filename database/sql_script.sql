DROP DATABASE IF EXISTS veterinariadb;

CREATE DATABASE veterinariadb;

USE veterinariadb;

CREATE TABLE clientes
(
	idcliente		INT AUTO_INCREMENT PRIMARY KEY,
	apellidos 		VARCHAR(50)		NOT NULL,
	nombres			VARCHAR(50)		NOT NULL,
	dni				CHAR(8) 			NOT NULL,
	claveacceso		VARCHAR(200)	NOT NULL
) ENGINE = INNODB;

CREATE TABLE animales
(
	idanimal			INT AUTO_INCREMENT PRIMARY KEY,
	nombreanimal	VARCHAR(20)		NOT NULL
) ENGINE = INNODB;

CREATE TABLE razas
(
	idraza			INT AUTO_INCREMENT PRIMARY KEY,
	idanimal			INT 				NOT NULL,
	nombreraza		VARCHAR(30)		NOT NULL,
	CONSTRAINT fk_idanimal_razas FOREIGN KEY (idanimal) REFERENCES animales (idanimal)
) ENGINE = INNODB;

CREATE TABLE mascotas
(
	idmascota		INT AUTO_INCREMENT PRIMARY KEY,
	idcliente		INT 				NOT NULL,
	idraza			INT 				NOT NULL,
	nombre			VARCHAR(30)		NOT NULL,
	fotografia		VARCHAR(100)	NULL,
	color				VARCHAR(30)		NOT NULL,
	genero			CHAR(1)			NOT NULL,
	CONSTRAINT fk_idcliente_mascotas FOREIGN KEY (idcliente) REFERENCES clientes (idcliente),
	CONSTRAINT fk_idraza_mascotas FOREIGN KEY (idraza) REFERENCES razas (idraza)
) ENGINE = INNODB;

INSERT INTO clientes (apellidos, nombres, dni, claveacceso) VALUES
	('Lurita', 'Alexander', '73790885', '$2y$10$U1S.7fB0CNrdIeH4L7kTaeiNa2vdiFfR8P1UJSzSnCjIVyVkEFGSi'),
	('Perez', 'Fernanda', '21304010', '$2y$10$ZkFhCk.PMnzU/XqW5Y97EuMXIyvr5SAWt86u9y0WdOp/7GEloCrFi'),
	('Magallanes', 'Luis', '72456021', '$2y$10$vW3p1QXxN//4opYNHCRfo.e28G71qmabv3VK5d3J4HHdjFCx5FUs.');
	
INSERT INTO animales (nombreanimal) VALUES
	('Perro'),
	('Gato'),
	('Hámster');

INSERT INTO razas (idanimal, nombreraza) VALUES
	(1, 'Bulldog'),
	(1, 'Dalmata'),
	(2, 'Azul ruso'),
	(2, 'Siamés'),
	(3, 'Ruso'),
	(3, 'Roborowski');
	
INSERT INTO mascotas (idcliente, idraza, nombre, fotografia, color, genero) VALUES
		(1, 2, 'Firulais', '0c8e0a697916ff0e283f6cb58e2091a3a4d4df0a.jpg', 'Blanco y negro', 'M'),
		(1, 4, 'Daisy', '95b3a8a802b8e0db8611d3ec2b994a37486c2584.jpg', 'Blanco y marrón oscuro', 'H'),
		(2, 3, 'Loki', 'cb4d12e5d8ef3e76d761b82f31fe9f90c3b592a8.jpg', 'Gris claro', 'M'),
		(3, 5, 'Sandy', 'ec6c78386f3147d199e1af1a4566e3529a4dea5f.jpg', 'Negro y gris claro', 'M');
		
	
-- PROCEDIMIENTOS
DELIMITER $$
CREATE PROCEDURE spu_clientes_registrar
(
	IN _apellidos 		VARCHAR(50),
	IN _nombres			VARCHAR(50),	
	IN _dni				CHAR(8), 		
	IN _claveacceso	VARCHAR(200)
)
BEGIN
	INSERT INTO clientes (apellidos, nombres, dni, claveacceso) VALUES
		(_apellidos, _nombres, _dni, _claveacceso);
END $$

DELIMITER $$
CREATE PROCEDURE spu_mascotas_registrar
(
	IN _idcliente		INT,
	IN _idraza			INT,
	IN _nombre			VARCHAR(20),
	IN _fotografia		VARCHAR(100),
	IN _color			VARCHAR(30),
	IN _genero			CHAR(1)
)
BEGIN
	IF _fotografia = '' THEN SET _fotografia = NULL; END IF;
	
	INSERT INTO mascotas (idcliente, idraza, nombre, fotografia, color, genero) VALUES
		(_idcliente, _idraza, _nombre, _fotografia, _color, _genero);
END $$

DELIMITER $$
CREATE PROCEDURE spu_clientes_buscar(IN _dni CHAR(8))
BEGIN 
	SELECT idcliente, apellidos, nombres 
		FROM clientes
		WHERE dni = _dni;
END $$

DELIMITER $$
CREATE PROCEDURE spu_mascotas_buscar_cliente(IN _dni CHAR(8))
BEGIN
	SELECT 	mascotas.idmascota,
				animales.nombreanimal,
				mascotas.nombre
		FROM mascotas
		INNER JOIN razas ON razas.idraza = mascotas.idraza
		INNER JOIN animales ON animales.idanimal = razas.idanimal
		INNER JOIN clientes ON clientes.idcliente = mascotas.idcliente
		WHERE clientes.dni = _dni;
END $$

DELIMITER $$
CREATE PROCEDURE spu_mascotas_buscar(IN _idmascota INT)
BEGIN
	SELECT 	mascotas.idmascota,
				mascotas.nombre,
				animales.nombreanimal 'animal',
				razas.nombreraza 'raza',
				mascotas.fotografia,
				mascotas.color,
				mascotas.genero
			FROM mascotas
			INNER JOIN razas ON razas.idraza = mascotas.idraza
			INNER JOIN animales ON animales.idanimal = razas.idanimal
			INNER JOIN clientes ON clientes.idcliente = mascotas.idcliente
			WHERE idmascota = _idmascota;
END $$

DELIMITER $$
CREATE PROCEDURE spu_clientes_iniciar_sesion(IN _dni CHAR(8))
BEGIN
	SELECT *
		FROM clientes
		WHERE dni = _dni;
END $$

DELIMITER $$
CREATE PROCEDURE spu_razas_listar()
BEGIN
	SELECT 	razas.idraza,
					CONCAT(animales.nombreanimal, ' - ', razas.nombreraza) AS 'animalraza'
		FROM razas
		INNER JOIN animales ON animales.idanimal = razas.idanimal;
END $$

/*
select * from animales;
select * from razas;
select * from clientes;
select * from mascotas;
*/