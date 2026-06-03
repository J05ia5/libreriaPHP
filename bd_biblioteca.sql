-- ============================================
-- Sistema de Gestion de Biblioteca
-- SIS 256 - Laboratorio 5 - Entrega Completa
-- ============================================

CREATE DATABASE IF NOT EXISTS bd_biblioteca;
USE bd_biblioteca;

CREATE TABLE libros (
    id        INT PRIMARY KEY AUTO_INCREMENT,
    titulo    VARCHAR(200) NOT NULL,
    autor     VARCHAR(150) NOT NULL,
    isbn      VARCHAR(20) UNIQUE,
    categoria VARCHAR(80),
    stock     INT DEFAULT 1
);

CREATE TABLE usuarios (
    id       INT PRIMARY KEY AUTO_INCREMENT,
    nombre   VARCHAR(150) NOT NULL,
    carnet   VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    correo   VARCHAR(100)
);

-- Parte 2: Modulo de Prestamos
CREATE TABLE prestamos (
    id               INT PRIMARY KEY AUTO_INCREMENT,
    id_libro         INT NOT NULL,
    id_usuario       INT NOT NULL,
    fecha_prestamo   DATE NOT NULL,
    fecha_devolucion DATE,
    estado           ENUM('Activo','Devuelto','Vencido') DEFAULT 'Activo',
    observaciones    TEXT,
    FOREIGN KEY (id_libro)   REFERENCES libros(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
