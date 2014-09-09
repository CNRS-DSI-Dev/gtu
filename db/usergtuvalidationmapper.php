<?php
/**
 * ownCloud - ?
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
namespace OCA\Gtu\Db;

use OCP\AppFramework\Db\Mapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IDb;

class UserGtuValidationMapper extends Mapper {
	
	public function __construct(IDb $db) {
		parent::__construct($db, 'user_gtu_validations');
	}
	
	public function findByUid($uid){
		$sql = 'SELECT * FROM `'.$this->getTableName().'` WHERE `uid`=?';
		$userGtu = $this->findEntity($sql, array('uid'=> $uid));
/*		$userGtu = new UserGtuValidation();
		$userGtu->fromRow($row);
*/		return $userGtu;
	}

	public function updateValidation($uid, 	$gtuVersion) {
		try {
			$userGtu = $this->findByUid($uid);
			$sql = 'UPDATE  `'.$this->getTableName().'` SET `gtu_version`=? WHERE `uid`=?';
			$params = array($gtuVersion,$uid);
			$this->execute($sql, $params);
		} catch(DoesNotExistException $ex){
			$sql = 'INSERT INTO `'.$this->getTableName().'` (`uid`, `gtu_version`, `validation_date`) '.
				'VALUES (?, ?, ?)';
			$params = array($uid, $gtuVersion, time());
			$this->execute($sql, $params);
		}

	}
	
	
}
?>