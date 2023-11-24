# db-fundamentals
Repo to host the Database Fundamentals coursework

# Local dev setup

1. Ensure you're branch is based on most updated `origin/live`.

2. Ensure a database named `db-fundamentals` exists in phpMyAdmin.

3. Visit [seed](http://localhost/db-fundamentals/create_tables.php) page to create tables and seed.

# Authorization and Authentication

There are 3 normal users and 1 superuser - details can be seen at [seed](http://localhost/db-fundamentals/create_tables.php) page.

**Normal user**

- Email: `user1@example.com`
- Pw: `pw`

**Superuser**

- Email: `superuser@example.com`
- Pw: `pw`

To check if a user is logged in:

```php
<?php include_once("utilities.php")?>

if (logged_in()) {
    // logged in         
} else {
    // not logged in
}
```

To check if a user is superuser:

```php
if ($_SESSION['isSuperuser'] == True) {
    // superuser   
} else {
    // not superuser
}
```

On successful login, the following `$_SESSION` variables are set:

```php
$_SESSION['userId'] = $row['userId'];
$_SESSION['isSuperuser'] = $row['isSuperuser'];
$_SESSION['logged_in'] = True;
```

# Setting up email

1. Follow [this](https://www.youtube.com/watch?v=4TmD4ly7V_E) video.
2. Using XAMP, make sure to run Mercury.
3. Adjust these [Mercury Settings](https://stackoverflow.com/questions/6809369/warning-mail-function-mail-smtp-server-response-553-we-do-not-relay-non-l).

# Testing/ integrating the feedback form:
The feedback form can be accessed at:
    `http://www.localhost/db-fundamentals/feedback.php?product_id=<PRODUCT_ID>`

The URL is meant to be sent to the highest bidder via email once an auction ends, provided that the bid price >= reserve price. The form will only show up if this combination of conditions hold. Otherwise, the page will display an error message with explanation.

You can access the feedback form for `product_id=1` with `user1@example.com`, and `product_id=4` with `user2@example.com`.