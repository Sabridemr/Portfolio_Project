-- MySQL dump 10.13  Distrib 8.0.46, for Linux (aarch64)
--
-- Host: localhost    Database: portfolio_db
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` (`username`, `password`) VALUES ('admin','$2y$12$wlNkIiqyWtGYQGymiQ7L5.EwoDYeuY0p6nT8Q5RupEjachtB7KTOa');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'John Doe','john@example.com','Project Inquiry','Hi Sabri, I came across your portfolio and I am very impressed by your work. I would love to discuss a potential collaboration on a web project. Please get back to me when you have a chance!','2026-05-13 07:02:49'),(2,'Test Kullanıcı','test@example.com','Test Mesajı','Bu bir test mesajıdır, en az 20 karakter olmalı.','2026-05-13 07:03:22');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tech_stack` varchar(500) NOT NULL DEFAULT '',
  `category` enum('frontend','backend','fullstack') NOT NULL DEFAULT 'fullstack',
  `image_url` varchar(500) DEFAULT NULL,
  `github_url` varchar(500) DEFAULT NULL,
  `live_url` varchar(500) DEFAULT NULL,
  `status` enum('live','development','archived') NOT NULL DEFAULT 'live',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'E-Commerce Platform','Full-stack e-commerce application with a Node.js/Express REST API backend and a modern React client. Features product listing, cart management, user authentication, and order flow.','Node.js, Express, React, TypeScript, MongoDB','fullstack','https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop','https://github.com/Sabridemr/EccomerceServer','https://github.com/Sabridemr/EccomerceClient','live','2026-05-13 07:18:10'),(2,'Web Application','A responsive multi-page web application built from scratch with semantic HTML5, modern CSS3 and vanilla JavaScript. Includes user registration and dynamic UI components.','HTML5, CSS3, JavaScript','frontend','https://images.unsplash.com/photo-1547658719-da2b51169166?w=600&h=400&fit=crop','https://github.com/Sabridemr/web-app','#','live','2026-05-13 07:18:10'),(3,'Crypto Tracker â€” SwiftUI','Native iOS cryptocurrency tracking app built with SwiftUI. Fetches live coin data via REST API, displays price charts, and supports a watchlist with persistent storage.','Swift, SwiftUI, CoinGecko API','frontend','https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600&h=400&fit=crop','https://github.com/Sabridemr/CryptoListSwiftUI','#','live','2026-05-13 07:18:10'),(4,'AI Chatbot App','Conversational AI chatbot web application powered by LangChain and OpenAI. Supports multi-turn dialogue, context memory, and a clean chat interface.','Python, LangChain, OpenAI API, Flask','fullstack','https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=400&fit=crop','https://github.com/Sabridemr/chatbot-app','#','live','2026-05-13 07:18:10'),(5,'LangChain Vector Store','Document Q&A system using LangChain with vector embeddings. Ingests PDFs and text files into a vector store, enabling semantic search and RAG-based question answering.','Python, LangChain, FAISS, OpenAI','backend','https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop','https://github.com/Sabridemr/VectorStoreProject','#','live','2026-05-13 07:18:10'),(6,'N-Layered Architecture â€” .NET','Enterprise-grade .NET Web API demonstrating clean N-Layered architecture with separate Data Access, Business Logic, and API layers. Includes Entity Framework Core and repository pattern.','C#, .NET, Entity Framework Core, SQL Server','backend','https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&h=400&fit=crop','https://github.com/Sabridemr/NLayeredArthitecture','#','live','2026-05-13 07:18:10'),(7,'Kipas ChatBot','AI-powered customer support chatbot built for real-world use. Integrates natural language understanding with a rule-based fallback system and a web-based chat widget.','Python, NLP, JavaScript, REST API','fullstack','https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop','https://github.com/Sabridemr/KipasChatBotProject','#','live','2026-05-13 07:18:10'),(8,'SwiftUI Network Layer','Reusable and interchangeable network abstraction layer for SwiftUI apps. Implements protocol-oriented design for easy mocking, testing, and swapping of API providers.','Swift, SwiftUI, Combine, URLSession','frontend','https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop','https://github.com/Sabridemr/NetworkInterChangableSwiftUI','#','live','2026-05-13 07:18:10');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-13  7:22:32
