# Community Bible Church

Copy DB
(remote)

```sh
mysqldump -u lydi -p --databases lydi > lydi.sql
```

Copy static files
(local)

```sh
scp remote:/path/to/lydi.sql ./
scp remote:/home/lydi/wp-content ./
```