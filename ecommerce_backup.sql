-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: Ecommerce
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Administradores`
--

DROP TABLE IF EXISTS `Administradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Administradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `senha` char(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Administradores`
--

LOCK TABLES `Administradores` WRITE;
/*!40000 ALTER TABLE `Administradores` DISABLE KEYS */;
INSERT INTO `Administradores` VALUES (1,'Admin','rodrigogenio12@gmail.com','$2y$10$fxG27OW.IFclh3yCH4ItuuiByOeAvng5G0Elo2QSAIVUGQMKv0mWC');
/*!40000 ALTER TABLE `Administradores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Avaliacoes`
--

DROP TABLE IF EXISTS `Avaliacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Avaliacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `comentario` varchar(45) DEFAULT NULL,
  `nota` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Avaliacoes_Usuarios_idx` (`idUsuario`),
  KEY `fk_Avaliacoes_Produtos1_idx` (`idProduto`),
  CONSTRAINT `Produto_idProduto` FOREIGN KEY (`idProduto`) REFERENCES `Produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Usuario_idUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Avaliacoes`
--

LOCK TABLES `Avaliacoes` WRITE;
/*!40000 ALTER TABLE `Avaliacoes` DISABLE KEYS */;
INSERT INTO `Avaliacoes` VALUES (20,10,6,'Produto Muito Bom','3'),(21,10,7,'Produto Muito Bom','4'),(22,11,7,'Produto Top','3'),(23,11,6,'Produto Muito Bom','5'),(24,11,8,'Melhor Computador do Mundo !!','5');
/*!40000 ALTER TABLE `Avaliacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Carrinho`
--

DROP TABLE IF EXISTS `Carrinho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Carrinho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_Carrinho_Usuarios1_idx` (`usuario_id`),
  KEY `fk_Carrinho_Produtos1_idx` (`produto_id`),
  CONSTRAINT `fk_Carrinho_Produtos1` FOREIGN KEY (`produto_id`) REFERENCES `Produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Carrinho_Usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Carrinho`
--

LOCK TABLES `Carrinho` WRITE;
/*!40000 ALTER TABLE `Carrinho` DISABLE KEYS */;
/*!40000 ALTER TABLE `Carrinho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categorias`
--

DROP TABLE IF EXISTS `Categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categorias`
--

LOCK TABLES `Categorias` WRITE;
/*!40000 ALTER TABLE `Categorias` DISABLE KEYS */;
INSERT INTO `Categorias` VALUES (1,'Móveis'),(2,'Cama mesa e Banho'),(3,'Informática'),(4,'Teste');
/*!40000 ALTER TABLE `Categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pedidos`
--

DROP TABLE IF EXISTS `Pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `status_pedido` tinyint(4) NOT NULL DEFAULT '0',
  `meio_pagamento` varchar(45) NOT NULL DEFAULT 'default',
  `idPagamento` varchar(45) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `fk_Pedido_Usuarios1_idx` (`usuario_id`),
  CONSTRAINT `fk_Pedido_Usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pedidos`
--

LOCK TABLES `Pedidos` WRITE;
/*!40000 ALTER TABLE `Pedidos` DISABLE KEYS */;
INSERT INTO `Pedidos` VALUES (27,10,1,'default','default'),(28,8,1,'default','default'),(29,10,1,'default','default'),(30,10,1,'default','default'),(31,10,1,'default','default'),(32,10,1,'default','default'),(35,10,1,'default','default'),(36,10,1,'default','default'),(37,10,1,'default','default'),(39,10,1,'default','default'),(40,10,1,'default','default'),(41,10,1,'default','default'),(42,10,1,'default','default'),(43,10,1,'default','default'),(44,10,1,'default','default'),(45,10,1,'mercadoPago','default'),(46,10,1,'mercadoPago','default'),(47,10,1,'mercadoPago','default'),(48,10,1,'mercadoPago','default'),(49,10,1,'mercadoPago','25002247'),(50,10,1,'default','default'),(51,10,1,'mercadoPago','25002400'),(52,11,1,'default','default'),(53,10,1,'default','default'),(54,11,1,'default','default'),(55,11,1,'mercadoPago','25018752'),(56,10,1,'mercadoPago','25019974'),(57,10,1,'mercadoPago','25020180');
/*!40000 ALTER TABLE `Pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Produtos`
--

DROP TABLE IF EXISTS `Produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `preco` decimal(8,2) DEFAULT NULL,
  `estoque` int(11) DEFAULT NULL,
  `imagens` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Produtos_Categorias1_idx` (`idCategoria`),
  CONSTRAINT `fk_Produtos_Categorias1` FOREIGN KEY (`idCategoria`) REFERENCES `Categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Produtos`
--

LOCK TABLES `Produtos` WRITE;
/*!40000 ALTER TABLE `Produtos` DISABLE KEYS */;
INSERT INTO `Produtos` VALUES (5,2,'Cama ','Uma Bela cam de solteiro',700.00,0,'[\"5e89e91bd720c\"]'),(6,1,'Mesa de jantar','Mesa de Jantar para recepcionar os amigos e familiares.',300.00,82,'[\"5e89e94d3c7ed\", \"5e89e96ec4a8d\", \"5e89e96ec4a8d\", \"5e89e91bd720c\"]'),(7,2,'Jogo de Toalhas','Um jogo de Toalhas para toda a família.',50.00,60,'[\"5e89e96ec4a8d\"]'),(8,3,'PC Master Race','Um Computador destinado ao público gamer',5000.00,96,'[\"5e89eb3c0903b\"]');
/*!40000 ALTER TABLE `Produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `telefone` varchar(45) DEFAULT NULL,
  `cep` char(8) DEFAULT NULL,
  `senha` char(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuarios`
--

LOCK TABLES `Usuarios` WRITE;
/*!40000 ALTER TABLE `Usuarios` DISABLE KEYS */;
INSERT INTO `Usuarios` VALUES (8,'Rodrigo',NULL,'rodrigogcbarros@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$10$8ExUH6df/vEuaBnrU907juknQOWRt9L992pDkVxK8HGtncEEEPXGC'),(10,'Rodrigo','Avenida General Penha Brasil, 3190','rodrigogenio12@gmail.com','Jardim Recanto','São Paulo','1996-09-21','SP','11 992798005','02673000','$2y$10$n4wCQrFh2YFXDQn.tVp3xe2wktwX8OuATlh8Ft6gt9LnMfoBpzEBu'),(11,'Test User','Avenida General Penha Brasil','teste@email.com','Jardim Recanto','São Paulo',NULL,'SP','11 992798005','02673000','$2y$10$B3ItzcwAgN/fRBA/lStnfuUZhXZeoIkAAbnRqHT6994wcQLklPoR6'),(12,'Email',NULL,'email@domain.com',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$10$pyfQWyeHcryDU5KB1gXRJu386Ua68s8XIzq2xQ2QpQPrQfD9JiYY2');
/*!40000 ALTER TABLE `Usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemPedido`
--

DROP TABLE IF EXISTS `itemPedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemPedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_itemPedido_Pedido1_idx` (`idPedido`),
  KEY `fk_itemPedido_Produtos1_idx` (`idProduto`),
  CONSTRAINT `fk_itemPedido_Pedido1` FOREIGN KEY (`idPedido`) REFERENCES `Pedidos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_itemPedido_Produtos1` FOREIGN KEY (`idProduto`) REFERENCES `Produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemPedido`
--

LOCK TABLES `itemPedido` WRITE;
/*!40000 ALTER TABLE `itemPedido` DISABLE KEYS */;
INSERT INTO `itemPedido` VALUES (27,27,6,2,300.00),(28,27,5,1,700.00),(29,28,7,2,50.00),(30,28,5,2,700.00),(31,29,5,1,700.00),(32,29,6,2,300.00),(33,29,7,2,50.00),(34,30,6,2,300.00),(35,30,7,1,50.00),(36,31,7,1,50.00),(37,31,5,1,700.00),(38,32,5,1,700.00),(39,35,7,1,50.00),(40,36,6,1,300.00),(41,36,7,1,50.00),(42,37,7,1,50.00),(43,37,6,1,300.00),(47,39,7,1,50.00),(48,40,7,1,50.00),(49,41,7,2,50.00),(50,42,6,1,300.00),(51,43,7,1,50.00),(52,44,7,1,50.00),(53,45,7,1,50.00),(54,46,7,1,50.00),(55,47,7,1,50.00),(56,48,7,1,50.00),(57,49,7,1,50.00),(58,50,7,1,50.00),(59,51,7,1,50.00),(60,52,6,1,300.00),(61,53,7,1,50.00),(62,54,7,2,50.00),(63,54,6,2,300.00),(64,55,8,1,5.00),(65,56,7,1,50.00),(66,57,7,2,50.00);
/*!40000 ALTER TABLE `itemPedido` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-12 13:36:40
