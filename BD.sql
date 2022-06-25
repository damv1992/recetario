CREATE DATABASE recetario COLLATE 'utf8mb4_general_ci';

CREATE TABLE Configuracion (
    IdConfiguracion bigint NOT NULL,
    NombrePagina varchar(200) NOT NULL,
    LogoPagina varchar(500) NOT NULL,
    IconoPagina varchar(500) NOT NULL,
    SobreNosotros mediumtext NOT NULL,
    Usuario varchar(50) NOT NULL,
    Contraseña varchar(50) NOT NULL,
    PRIMARY KEY (IdConfiguracion)
);
CREATE TABLE Categorias (
    IdCategoria bigint NOT NULL,
    NombreCategoria varchar(200) NOT NULL,
    IconoCategoria varchar(500) NOT NULL,
    PRIMARY KEY (IdCategoria)
);
CREATE TABLE Recetas (
    IdReceta bigint NOT NULL,
    NombreReceta varchar(200) NOT NULL,
    FotoReceta varchar(500) NOT NULL,
    Tiempo time NOT NULL,
    Dificultad float,
    Porciones int NOT NULL,
    Categoria bigint NOT NULL,
    PRIMARY KEY (IdCategoria),
    FOREIGN KEY (Categoria) REFERENCES Categorias (IdCategoria)
);
CREATE TABLE Ingredientes (
    IdIngrediente bigint NOT NULL,
    NombreIngrediente varchar(200) NOT NULL,
    Cantidad int NOT NULL,
    UnidadMedida varchar(50),
    Receta bigint NOT NULL,
    PRIMARY KEY (IdIngrediente),
    FOREIGN KEY (Receta) REFERENCES Recetas (IdReceta)
);
CREATE TABLE Preparaciones (
    IdPreparacion bigint NOT NULL,
    PasoNumero int NOT NULL,
    DescripcionPaso varchar(200) NOT NULL,
    PRIMARY KEY (IdPreparacion),
    FOREIGN KEY (Receta) REFERENCES Recetas (IdReceta)
);