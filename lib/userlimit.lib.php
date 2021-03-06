<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file		lib/userlimit.lib.php
 *	\ingroup	userlimit
 *	\brief		This file is an example module library
 *				Put some comments here
 */

function userlimitAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load("userlimit@userlimit");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/userlimit/admin/userlimit_setup.php", 1);
    $head[$h][1] = $langs->trans("Parameters");
    $head[$h][2] = 'settings';
    $h++;
    $head[$h][0] = dol_buildpath("/userlimit/admin/userlimit_about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@userlimit:/userlimit/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@userlimit:/userlimit/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'userlimit');

    return $head;
}

function testNbUser($msg=true) {
	global $db,$conf,$langs;
			
	$res = $db->query("SELECT count(*) as nb FROM ".MAIN_DB_PREFIX."user WHERE statut=1");
	$obj = $db->fetch_object($res);
	$nb_user=$obj->nb;
	
	$langs->Load('userlimit@userlimit');
	
/*	if($conf->global->USERLIMIT_MAX<$nb_user && $conf->global->USERLIMIT_STOP_LOGIN) {
		
		
	}
	else*/ if($conf->global->USERLIMIT_MAX<$nb_user) {
		if($msg) setEventMessage($langs->trans('userlimitBlockMsg', $conf->global->USERLIMIT_MAX), 'errors');
		return false;
	}
	else if($conf->global->USERLIMIT_MAX<=$nb_user) {
		if($msg) setEventMessage($langs->trans('userlimitMaxMsg', $conf->global->USERLIMIT_MAX), 'warnings');
		return true;
	}
	else if($conf->global->USERLIMIT_WARN<=$nb_user) {
		if($msg) setEventMessage($langs->trans('userlimitWarnMsg', $nb_user, $conf->global->USERLIMIT_MAX), 'warnings');
		return true;
	}
	
	return true;
	
}
