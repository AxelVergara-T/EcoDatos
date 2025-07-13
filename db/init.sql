CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    correo VARCHAR(100) UNIQUE NOT NULL,
    clave TEXT NOT NULL
);

INSERT INTO usuarios (correo, clave) VALUES (
    'admin@ecodatos.com',
    '$2y$10$KbQiN7WfLv4AqIFa7x/kRO1U9/xMZicn4G8HJfYylzO5qQ4E8ZqkC'
)
ON CONFLICT (correo) DO NOTHING;

CREATE TABLE IF NOT EXISTS datos (
    id SERIAL PRIMARY KEY,
    rut TEXT NOT NULL,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    religion TEXT
);
