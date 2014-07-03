# tmlm

Take a message, leave a message

## Assumptions/Setup

The message API (`api/message.php`) and the stats page (`stats.php`) connect to a MySQL server (or some other server compatible with the PDO MySQL interface; I use MariaDB) with a database called `tmlm` and a table called `messages`. `messages` should have two columns, `message` (of type `VARCHAR`, which holds the users' messages) and `used` (of type `BIT`, which represents whether the message has been displayed).

I think that you could make the `message` column any string type if you wanted to. And you could also make the `used` column any integer type capable of containing 0 and 1. Not sure why you'd want to, but it's possible.

The message API and the stats page also refer to `/var/www_be/tmlm/creds.php`, which contains credentials (defined as `$un` and `$pw`) for a user with SELECT, INSERT, and UPDATE privileges on the `messages` table.

`log.php` in the `back` folder should be placed at `/var/www_be/tmlm/log.php`. Everything else should be able to go wherever you want it on the server.

