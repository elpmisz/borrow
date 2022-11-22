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

  public function request_fetch($data)
  {
    $sql = "SELECT A.id request_id,B.name user_name,A.type type_id,IF(A.type = 1,'ยืม','คืน') type_name,A.text,
    CONCAT(DATE_FORMAT(A.start, '%d/%m/%Y'),' - ',DATE_FORMAT(A.end, '%d/%m/%Y')) date
    FROM request A
    LEFT JOIN user_detail B 
    ON A.user_id = B.id
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetch();
  }

  public function item_fetch($data)
  {
    $sql = "SELECT A.id,C.name item_name,C.unit item_unit,A.amount,A.confirm,A.location,A.text
    FROM request_item A
    LEFT JOIN request B 
    ON A.request_id = B.id
    LEFT JOIN item C
    ON A.item_id = C.id
    WHERE A.request_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchAll();
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}