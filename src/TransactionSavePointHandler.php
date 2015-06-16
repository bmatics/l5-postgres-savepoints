<?php
namespace Bmatics\TransactionSavePoints;

use Illuminate\Database\Connection;

class TransactionSavePointHandler
{
    /**
     * Handle save point for begining nested transaction.
     *
     * @param  Illuminate\Database\Connection  $event
     * @return void
     */
    public function handleBegin(Connection $connection)
    {
        if ($connection->getConfig('savepoints')) {
            $transactionLevel = $connection->transactionLevel();

            if ($transactionLevel > 1) {
                $connection->statement('SAVEPOINT LEVEL'.($transactionLevel-1));
            }           
        }

    }

    /**
     * Handle save point for committing nested transaction.
     *
     * @param  Illuminate\Database\Connection  $event
     * @return void
     */
    public function handleCommit(Connection $connection)
    {
        if ($connection->getConfig('savepoints')) {
            $transactionLevel = $connection->transactionLevel();

            if ($transactionLevel > 0) {
                $connection->statement('RELEASE SAVEPOINT LEVEL'.$transactionLevel);
            }
        }
    }

    /**
     * Handle save point for rolling back nested transaction.
     *
     * @param  Illuminate\Database\Connection  $event
     * @return void
     */
    public function handleRollback(Connection $connection)
    {
        if ($connection->getConfig('savepoints')) {
            $transactionLevel = $connection->transactionLevel();

            if ($transactionLevel > 0) {
                $connection->statement('ROLLBACK TO SAVEPOINT LEVEL'.$transactionLevel);
            }
        }
    }

}