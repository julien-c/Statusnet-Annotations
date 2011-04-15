#!/usr/bin/env php
<?php
/*
 * StatusNet - a distributed open-source microblogging tool
 * Copyright (C) 2008, 2009, StatusNet, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('INSTALLDIR', realpath(dirname(__FILE__) . '/../../../..'));

$shortoptions = 'u:w:';
$longoptions = array('user=', 'password=');

$helptext = <<<END_OF_CREATENOTICE_HELP
createnotice.php 
creates a dummy notice including Annotations

  -n --nickname nickname of user
  -w --password password of user

END_OF_CREATENOTICE_HELP;

require_once INSTALLDIR.'/scripts/commandline.inc';

$nickname = get_option_value('u', 'user');
$password = get_option_value('w', 'password');

if (empty($nickname) || empty($password)) {
    print "Must provide a username and password.\n";
    exit(1);
}


// JSON representation of sample Annotations

$annotations = '[{"product":{"brand":"Canon", "model":"EOS_550D", "price":"EUR650", "category":"camera"}}]';



try {
    
    $status = "Test #test test";
    
    $url = common_local_url('public') . "api/statuses/update.xml";
    
    // curl -u user:password -d "status=playing with cURL and the Twitter API" http://twitter.com/statuses/update.xml
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_USERPWD, $nickname.":".$password);
    
    curl_setopt($ch, CURLOPT_POST, 1);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, array("status" => $status, "annotations" => $annotations));
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    
    $output = curl_exec($ch);
    
    curl_close($ch); 
    
    echo $output;

} catch (Exception $e) {
    print $e->getMessage() . "\n";
    exit(1);
}
