# Laravel Save Points
Adds support for save points to database connections in Laravel 5

Add `Bmatics\TransactionSavePoints\TransactionSavePointServiceProvider` to your app's service providers.

To enable save points for a connection, simply add `'savepoints' => true` to the connection config.

When enabled on a connection, beginning a new transaction while another transaction is already active will create a save point.  Beginning additional transactions on the same connection will create additional save points.  Commiting or rolling back on the connection will release or rollback to the last created save point. Once all save points have been released or rolled back to, you can commit or rollback the entire transaction.

Example:
```php
// begin a new transaction
DB::beginTransaction();

// save a new user with the name Alice to the DB
$user = User::create(['name' => 'Alice', 'email'=>'alice@exmaple.com']);

// create a savepoint
DB::beginTransaction(); 

// Change the user's name to Alex and save to DB
$user->name = "Alex";
$user->save();

// rollback to the savepoint, update of the user's name to 'Alex' rolled back
DB::rollBack();

// commit the transaction, commits the new user record with the name as 'Alice'
DB::commit();
``` 

This package will work with PostgreSQL, MySQL (InnoDB), and SQLite databases.  It may also work with other databases; the following SQL statements must be supported:
- `SAVEPOINT [savepoint_name]`
- `RELEASE SAVEPOINT [savepoint_name]`
- `ROLLBACK TO SAVEPOINT [savepoint_name]`
