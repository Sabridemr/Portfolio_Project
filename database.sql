
CREATE DATABASE IF NOT EXISTS `portfolio_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `portfolio_db`;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$12$wlNkIiqyWtGYQGymiQ7L5.EwoDYeuY0p6nT8Q5RupEjachtB7KTOa');


CREATE TABLE IF NOT EXISTS `contacts` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) NOT NULL,
  `email`      VARCHAR(255) NOT NULL,
  `subject`    VARCHAR(255) NOT NULL,
  `message`    TEXT         NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contacts` (`name`, `email`, `subject`, `message`, `created_at`) VALUES
('John Doe',        'john@example.com', 'Project Inquiry', 'Hi Sabri, I came across your portfolio and I am very impressed by your work. I would love to discuss a potential collaboration on a web project. Please get back to me when you have a chance!', '2026-05-13 07:02:49'),
('Test Kullanıcı',  'test@example.com', 'Test Mesajı',     'Bu bir test mesajıdır, en az 20 karakter olmalı.',                                                                                                                                                      '2026-05-13 07:03:22');



CREATE TABLE IF NOT EXISTS `projects` (
  `id`          INT                                        NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)                               NOT NULL,
  `description` TEXT                                       NOT NULL,
  `tech_stack`  VARCHAR(500)                               NOT NULL DEFAULT '',
  `category`    ENUM('frontend','backend','fullstack')     NOT NULL DEFAULT 'fullstack',
  `image_url`   VARCHAR(500)                               DEFAULT NULL,
  `github_url`  VARCHAR(500)                               DEFAULT NULL,
  `live_url`    VARCHAR(500)                               DEFAULT NULL,
  `status`      ENUM('live','development','archived')      NOT NULL DEFAULT 'live',
  `created_at`  DATETIME                                   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects` (`title`, `description`, `tech_stack`, `category`, `image_url`, `github_url`, `live_url`, `status`) VALUES
(
  'E-Commerce Platform',
  'Full-stack e-commerce application with a Node.js/Express REST API backend and a modern React client. Features product listing, cart management, user authentication, and order flow.',
  'Node.js, Express, React, TypeScript, MongoDB',
  'fullstack',
  'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/EccomerceServer',
  'https://github.com/Sabridemr/EccomerceClient',
  'live'
),
(
  'Web Application',
  'A responsive multi-page web application built from scratch with semantic HTML5, modern CSS3 and vanilla JavaScript. Includes user registration and dynamic UI components.',
  'HTML5, CSS3, JavaScript',
  'frontend',
  'https://images.unsplash.com/photo-1547658719-da2b51169166?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/web-app',
  '#',
  'live'
),
(
  'Crypto Tracker – SwiftUI',
  'Native iOS cryptocurrency tracking app built with SwiftUI. Fetches live coin data via REST API, displays price charts, and supports a watchlist with persistent storage.',
  'Swift, SwiftUI, CoinGecko API',
  'frontend',
  'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/CryptoListSwiftUI',
  '#',
  'live'
),
(
  'AI Chatbot App',
  'Conversational AI chatbot web application powered by LangChain and OpenAI. Supports multi-turn dialogue, context memory, and a clean chat interface.',
  'Python, LangChain, OpenAI API, Flask',
  'fullstack',
  'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/chatbot-app',
  '#',
  'live'
),
(
  'LangChain Vector Store',
  'Document Q&A system using LangChain with vector embeddings. Ingests PDFs and text files into a vector store, enabling semantic search and RAG-based question answering.',
  'Python, LangChain, FAISS, OpenAI',
  'backend',
  'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/VectorStoreProject',
  '#',
  'live'
),
(
  'N-Layered Architecture – .NET',
  'Enterprise-grade .NET Web API demonstrating clean N-Layered architecture with separate Data Access, Business Logic, and API layers. Includes Entity Framework Core and repository pattern.',
  'C#, .NET, Entity Framework Core, SQL Server',
  'backend',
  'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/NLayeredArthitecture',
  '#',
  'live'
),
(
  'Kipas ChatBot',
  'AI-powered customer support chatbot built for real-world use. Integrates natural language understanding with a rule-based fallback system and a web-based chat widget.',
  'Python, NLP, JavaScript, REST API',
  'fullstack',
  'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/KipasChatBotProject',
  '#',
  'live'
),
(
  'SwiftUI Network Layer',
  'Reusable and interchangeable network abstraction layer for SwiftUI apps. Implements protocol-oriented design for easy mocking, testing, and swapping of API providers.',
  'Swift, SwiftUI, Combine, URLSession',
  'frontend',
  'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop',
  'https://github.com/Sabridemr/NetworkInterChangableSwiftUI',
  '#',
  'live'
);
