<?php

namespace app\classes;

class Borrow
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function request_insert($data)
  {
    $sql = "INSERT INTO request(type,user_id,start,end,text) VALUES(?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function item_insert($data)
  {
    $sql = "INSERT INTO request_item(request_id,item_id,amount,confirm,location,text) VALUES(?,?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}
