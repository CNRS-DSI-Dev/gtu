<?php
/**
 * ownCloud -
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
namespace OCA\Gtu\Hook;
use OCA\Gtu\Db\UserGtuValidation;
use OCP\AppFramework\Http\JSONResponse;

class GtuHooks {

    var $userGtuValidationMapper;
    var $session;

    public function  __construct($userGtuValidationMapper, $session) {
        $this->userGtuValidationMapper = $userGtuValidationMapper;
        $this->session = $session;
    }

    /**
    * post_deleteUser
    */
    public function onPostDeleteUser($user) {
        \OCP\Util::writeLog('GtuHooks', 'onPostDeleteUser ',\OCP\Util::INFO);
        $uid = $user->getUID();
        try {
            $ugv = $this->userGtuValidationMapper->findByUid($uid);
        }
        catch(\Exception $e) {
            return new JSONResponse([
                'status' => 'success',
                'data' => [
                    'message' => '',
                ],
            ]);
        }
        $this->userGtuValidationMapper->delete($ugv);
        return new JSONResponse([
                'status' => 'success',
                'data' => [
                    'message' => '',
                ],
            ]);
    }

    public function onLogout() {
        $this->session->remove('gtu_ok');
    }

    public function register($session) {
        $session->listen('\OC\User', 'postDelete', function ($user) {
            $this->onPostDeleteUser($user);
        });
        $session->listen('\OC\User', 'logout', function () {
            $this->onLogout();
        });
    }
}

