# CONFIG
- php 8.0
- mysql 8.0
- nodejs 14
- phpmyadmin
- mercure 0.10.4

### folders

- api: api-plateform app
- app: nodejs app
- nginx: config nginx server

# GET STARTED

> make dev ENV=dev

> make prod ENV=prod

# API

- Documentation url : https://127.0.0.1:8000/api
- Collection : Recipe
- Method Allow : POST, GET, PUT, DELETE, PATH
- Ressource URL : /api/recipes

```
"hydra:member": [
    {
      "@context": "string",
      "@id": "string",
      "@type": "string",
      "id": 0,
      "title": "string"
    }
```

# Postman request

### URL : http://127.0.0.1:8000/api/recipes
### Methode : POST
### Body : JSON
```
{
    "title": "FROM POSTMAN"
}
```

