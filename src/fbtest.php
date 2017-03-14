<?php

class fbtest {

	public static function selectUser($userId)
	{
		$db = new Db();
		
		$db->connect();
		return $db->select("SELECT * FROM fbuser u WHERE u.id=".$db->quote($userId));
	}
	
	public function insertAccessToken($userId, $token)
	{
		$db = new Db();
		
		$db->connect();
		$sql = "INSERT INTO usertoken(fbuser_id, token, date) VALUES(".$db->quote($userId).",".$db->quote($token).",".$db->quote(date("Y-m-d G:i:s")).")";
		return $db->query($sql) or die(mysql_error());
	}
	
	public function insertUser($userId, $name, $picture)
	{
		$db = new Db();
		
		$db->connect();
		$sql = "INSERT INTO fbuser(id, name, picture) VALUES(".$db->quote($userId).",".$db->quote($name).",".$db->quote($picture).")";
		return $db->query($sql) or die(mysql_error());
	}
	
	
}
 