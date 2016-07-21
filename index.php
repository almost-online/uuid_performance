<?php
require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
function getUuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
$link = new PDO('mysql:host=localhost;dbname=test', 'root','qazxswcd');

function createTmpTable($name, $link) {
   
$Stmt = $link->prepare('create temporary table '.$name.' (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `uid` CHAR(32) NOT NULL,
 PRIMARY KEY (`id`)
)
ENGINE=InnoDB
;');
return $Stmt->execute();
}

function insertUUID($name, $link, $UUID='') {
$tmp = $UUID?"?":"uuid()";
$Stmt = $link->prepare('insert into '.$name.'(uid) values ('.$tmp.');');
if($UUID) $Stmt->bindParam(1,$UIID, \PDO::PARAM_STR);
return $Stmt->execute();
}


createTmpTable('t1', $link);
$a = microtime(true);
insertUUID('t1', $link, getUuid());
insertUUID('t1', $link, getUuid());
$t1 = microtime(true) - $a;

createTmpTable('t2', $link);
$a = microtime(true);
insertUUID('t2', $link, Uuid::uuid1()->toString());
insertUUID('t2', $link, Uuid::uuid1()->toString());
$t2 = microtime(true) - $a;

createTmpTable('t3', $link);
$a = microtime(true);
insertUUID('t3', $link);
insertUUID('t3', $link);
$t3 = microtime(true) - $a;

var_dump([$t1, $t2, $t3]);
