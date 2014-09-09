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


\OCP\Util::addScript('gtu', 'gtu');
\OCP\Util::addStyle( "settings", "settings" );
\OCP\Util::addStyle( "", "apps" );
\OCP\Util::addStyle( "gtu", "gtu" );
?>
<div class="section">
	<h2><?php p($l->t('General Terme of Usage configuration'));?></h2>
	<form id="gtuFormID" class="gtu_form">
		<div class="block">
		<label class="label" for="version">Version:</label>
			<input type="number"  name="version" value="<?php p($_['version'])?>" title="<?php p($l->t('Version of current GTU'));?>" >

		</div>
		<div class="block"><label class="label" for="text">Text:</label>
		<input type="text"   name="text" value="<?php p($_['text'])?>" title="<?php p($l->t('text of current GTU'));?>" >

		</div>
		<div class="block"><label class="label" for="url">url:</label>
			<input type="url"   name="url" value="<?php p($_['url'])?>" title="<?php p($l->t('url of current GTU'));?>" >

		</div>
		<div class="block"><label class="label" for="url">Screen Message</label>
			<input type="text"   name="msg" value="<?php p($_['msg'])?>" title="<?php p($l->t('msg of current GTU'));?>">

		</div>
		<div class="block"><label class="label" for="url">Homepage URL</label>
			<input type="text"   name="start_page_url" value="<?php p($_['start_page_url'])?>"
			title="<?php p($l->t('start page url of current GTU'));?>">
			
		</div>
		<div class="block"><label class="label" for="url">Homepage message</label>
			<input type="text"   name="start_page_message" value="<?php p($_['start_page_message'])?>"
			title="<?php p($l->t('start page url of current GTU'));?>">
			
		</div>
	</form>
</div>