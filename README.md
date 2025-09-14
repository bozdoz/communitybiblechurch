# Community Bible Church

Copy DB
(remote)

```sh
mysqldump --skip-column-statistics -h mysql -u lydi -p --databases lydi > lydi.sql
```

Copy static files
(local)

```sh
scp remote:/path/to/lydi.sql ./
scp remote:/home/lydi/wp-content ./
```

Make superuser:

```sh
docker run --rm cli wp user create admin not@real.com --role=administrator --user_pass=password
```
