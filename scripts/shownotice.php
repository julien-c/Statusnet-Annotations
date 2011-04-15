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





/*
 * Or just call http://EXAMPLE.COM/api/statuses/show.xml?id=NOTICEID
 *         e.g. http://pnzi.com/api/statuses/show.xml?id=94
           or   http://pnzi.com/api/statuses/public_timeline.xml
 */




define('INSTALLDIR', realpath(dirname(__FILE__) . '/../../../..'));

$shortoptions = 'i:f::';
$longoptions = array('id=', 'format=');

$helptext = <<<END_OF_SHOWNOTICE_HELP
shownotice.php [options]
show a notice

  -i --id       ID of notice
  -f --format   xml or json (optional; default xml)

END_OF_SHOWNOTICE_HELP;

require_once INSTALLDIR.'/scripts/commandline.inc';

$id = get_option_value('i', 'id');
$format = get_option_value('f', 'format');

if (empty($id)) {
    print "Must provide a notice ID.\n";
    exit(1);
}

$format = ($format == "json") ? "json" : "xml";


try {
    
    $url = common_local_url('public') . "api/statuses/show." . $format;
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_POST, 1);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, array("id" => $id));
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    
    $output = curl_exec($ch);
    
    curl_close($ch); 
    
    echo $output;

} catch (Exception $e) {
    print $e->getMessage() . "\n";
    exit(1);
}
