<?php
/*
 * This file is part of the Vine (http://...).
 *
 * (c) Sido van Gennip <sido@angelmechanics.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vine\Extension;

use Silex\Application;
use Silex\ExtensionInterface;

/**
 * MongoDB PHP Native Driver extension
 * 
 * @author Sido van Gennip <sido@angelmechanics.com>
 */
class MongoExtension implements ExtensionInterface
{

	public function register(Application $app)
	{
		$app['mongo'] = $app->share(function () use ($app) {
			if (!isset($app['mongo.connection.options'])) {
				$app['mongo.connection.options'] = 'localhost:27017';
			}
			return new \Mongo($app['mongo.connection.options']);
		});
	}

}