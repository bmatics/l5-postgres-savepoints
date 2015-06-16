<?php
namespace Bmatics\TransactionSavePoints;

use Illuminate\Support\ServiceProvider;

class TransactionSavePointServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if ($this->app->bound('events'))
		{
			$this->app['events']->listen('connection.*.beganTransaction', 'Bmatics\\TransactionSavePoints\\TransactionSavePointHandler@handleBegin');
			$this->app['events']->listen('connection.*.committed', 'Bmatics\\TransactionSavePoints\\TransactionSavePointHandler@handleCommit');
			$this->app['events']->listen('connection.*.rollingBack', 'Bmatics\\TransactionSavePoints\\TransactionSavePointHandler@handleRollback');
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
	}

}