# Application de Gestion des Tâches

Une application simple de gestion de tâches construite avec Symfony qui propose à la fois une interface web et une API REST.

## Interface Web

[Accéder à l'interface web](http://localhost:8000/task/)

## API REST

**Base URL :** `http://localhost:8000/api/tasks`

### Points d'Accès API

#### 1. Récupérer Toutes les Tâches
```http
GET /api/tasks
```
Filtres disponibles : `all` (défaut), `pending` (en attente), `completed` (terminées)

#### 2. Récupérer une Tâche
```http
GET /api/tasks/{id}
```

#### 3. Créer une Tâche
```http
POST /api/tasks
```
**Données requises :** `title`, `description`, `status`

#### 4. Modifier une Tâche
```http
PUT /api/tasks/{id}
```
**Données modifiables :** `title`, `description`, `status`

#### 5. Basculer le Statut
```http
PATCH /api/tasks/{id}/toggle
```

#### 6. Supprimer une Tâche
```http
DELETE /api/tasks/{id}
```

## Installation

1. Cloner le dépôt
2. Installer les dépendances :
   ```sh
   composer install
   ```
3. Configurer la base de données dans `.env`
4. Créer la base de données :
   ```sh
   php bin/console doctrine:database:create
   ```
5. Exécuter les migrations :
   ```sh
   php bin/console doctrine:migrations:migrate
   ```
6. Lancer le serveur :
   ```sh
   symfony server:start
   ```

## Technologies
- Symfony 6
- Bootstrap 5
- API REST

## Jeux de Test

### 1. Créer une tâche
```sh
curl -X POST "http://localhost:8000/api/tasks" -H "Content-Type: application/json" -d '{"title": "Faire les courses", "description": "Acheter du pain", "status": false}'
```

### 2. Lister les tâches
```sh
curl -X GET "http://localhost:8000/api/tasks"
```

### 3. Marquer comme terminée
```sh
curl -X PATCH "http://localhost:8000/api/tasks/1/toggle"
```

### 4. Modifier une tâche
```sh
curl -X PUT "http://localhost:8000/api/tasks/1" -H "Content-Type: application/json" -d '{"title": "Courses", "description": "Pain et lait", "status": true}'
```

### 5. Supprimer une tâche
```sh
curl -X DELETE "http://localhost:8000/api/tasks/1"
```
