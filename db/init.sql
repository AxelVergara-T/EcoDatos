CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    correo VARCHAR(100) UNIQUE NOT NULL,
    clave TEXT NOT NULL
);

INSERT INTO usuarios (correo, clave) VALUES (
    'admin@ecodatos.com',
    '$2y$10$Ly6kyGjGO80cXeibLd7aauli.EKZRn1ogrM9A/kGUXh/gOiaQSnRS'
)
ON CONFLICT (correo) DO NOTHING;

CREATE TABLE IF NOT EXISTS datos (
    id SERIAL PRIMARY KEY,
    rut TEXT NOT NULL,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    religion TEXT
);