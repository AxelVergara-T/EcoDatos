
 # Descripción
Este sistema permite registrar, editar y eliminar datos de usuarios, incluyendo RUT cifrado y religión hasheada, garantizando la seguridad y privacidad de la información.

# Requisitos
- Computador con conexión a internet
- VsCode instalado
- Docker instalado
- Docker compose instalado


Instalación
1. Clona el repositorio:
git clone https://github.com/AxelVergara-T/Ecodatos.git

2. Entra al directorio del proyecto:
cd ecodatos

3. Levanta los servicios con Docker:
docker compose build
docker-compose up -d 

4. Espera un minuto que la bbdd inicie correctamente.

4. Accede desde tu navegador: http://localhost:8080
Uso
- Inicia sesión con tus credenciales en la pantalla de login.
- Accede al formulario para registrar nuevos usuarios.
- Edita o elimina registros existentes desde la tabla.
Tecnologías utilizadas
- PHP
- PostgreSQL
- Docker
- HTML/CSS
Seguridad
- Cifrado AES-128-CBC para el RUT con clave secreta.
- Hash SHA-256 para el campo religión.
- Validación y sanitización de entradas para prevenir ataques como SQL Injection y XSS.
- Sesiones seguras y autenticación de usuarios.
Variables de entorno
Crea un archivo .env con el siguiente contenido como ejemplo:

PEPPER_KEY=clave_secreta_123
POSTGRES_USER=admin
POSTGRES_PASSWORD=adminpass
POSTGRES_DB=mi_base_de_datos

Autor
Proyecto desarrollado por : Belén gutierrez 
                            Iris Cayún
                            Francisco Díaz
                            Axel Vergara