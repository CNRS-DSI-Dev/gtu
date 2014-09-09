<?php
/**
 * ownCloud - routes.php
 *
 * @author Marc DeXeT
 * @copyright 2014 DSI CNRS https://www.dsi.cnrs.fr
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
 namespace OCA\Gtu\AppInfo;
 
 $app = new GtuApp();
 $app->registerRoutes($this, array(
 	'routes' => array(
 			array('name' => 'page#validate'			, 'url' => '/validate'),
 			array('name' => 'gtu_api#get'			, 'url' =>	'/api/params'		,   'verb' => 'GET'),
 			array('name' => 'gtu_api#post'			, 'url' =>	'/api/params'		,   'verb' => 'POST'),
 			array('name' => 'gtu_api#find_active'	, 'url' =>	'/api/agreement'	,   'verb' => 'GET'),
 			array('name' => 'gtu_api#post_agreement'	, 'url' =>	'/api/agreement',   'verb' => 'POST'),
 		)
 	)
 );