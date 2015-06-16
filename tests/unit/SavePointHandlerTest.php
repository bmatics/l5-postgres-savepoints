<?php

use Illuminate\Database\Connection;

class SavePointHandlerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $handler;

    protected function _before()
    {
        $this->handler = new Bmatics\TransactionSavePoints\TransactionSavePointHandler;
    }

    protected function _after()
    {
        Mockery::close();
    }

    // tests
    public function testSetSavePoint()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(2)
            ->shouldReceive('statement')->once()->with('SAVEPOINT LEVEL1');

        $this->handler->handleBegin($connection);
    }

    public function testReleaseSavePoint()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(1)
            ->shouldReceive('statement')->once()->with('RELEASE SAVEPOINT LEVEL1');

        $this->handler->handleCommit($connection);
    }

    public function testRollbackSavePoint()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(1)
            ->shouldReceive('statement')->once()->with('ROLLBACK TO SAVEPOINT LEVEL1');

        $this->handler->handleRollback($connection);
    }

    public function testSetSavePointLevel2()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(3)
            ->shouldReceive('statement')->once()->with('SAVEPOINT LEVEL2');

        $this->handler->handleBegin($connection);
    }

    public function testReleaseSavePointLevel2()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(2)
            ->shouldReceive('statement')->once()->with('RELEASE SAVEPOINT LEVEL2');

        $this->handler->handleCommit($connection);
    }

    public function testRollbackSavePointLevel2()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(2)
            ->shouldReceive('statement')->once()->with('ROLLBACK TO SAVEPOINT LEVEL2');

        $this->handler->handleRollback($connection);
    }

    public function testBeginTransaction()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(1)
            ->shouldReceive('statement')->never();            

        $this->handler->handleBegin($connection);
    }

    public function testCommitTransaction()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(0)
            ->shouldReceive('statement')->never(); 

        $this->handler->handleCommit($connection);
    }

    public function testRollbackTransaction()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->once()->with('savepoints')->andReturn(true)
            ->shouldReceive('transactionLevel')->once()->withNoArgs()->andReturn(0)
            ->shouldReceive('statement')->never(); 

        $this->handler->handleRollback($connection);
    }

    public function testSavepointsConfigNotSet()
    {
        $connection = Mockery::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getConfig')->times(3)->with('savepoints')->andReturn(false)
            ->shouldReceive('transactionLevel')->never()
            ->shouldReceive('statement')->never(); 

        $this->handler->handleBegin($connection);
        $this->handler->handleCommit($connection);
        $this->handler->handleRollback($connection);
    }

}