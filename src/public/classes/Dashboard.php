<?php

namespace app\classes;

class Dashboard
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function item_by_id($data)
  {
    $sql = "SELECT B.name user,D.name item,A.amount,D.unit
    FROM user_item A
    LEFT JOIN user_detail B 
    ON A.user_id = B.id
    LEFT JOIN province C 
    ON B.province_code = C.code 
    LEFT JOIN item D
    ON A.item_id = D.id
    WHERE C.zone_id = ?
    AND A.item_id = ?
    AND A.status = 1
    ORDER BY B.id ASC";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchAll();
  }
}
