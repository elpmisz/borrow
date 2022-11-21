<?php

namespace app\classes;

class Item
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function item_insert($data)
  {
    $sql = "INSERT INTO item(name,type,reference,unit) VALUES(?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function item_fetch($data)
  {
    $sql = "SELECT A.id,A.name,A.type,A.unit,A.status,
    A.reference reference_id,CONCAT('[',B.id,'] ',B.name) reference_name
    FROM item A 
    LEFT JOIN item B
    ON A.reference = B.id
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function item_update($data)
  {
    $sql = "UPDATE item SET
    name = ?,
    type = ?,
    reference = ?,
    unit = ?,
    status = ?,
    updated = NOW()
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function reference_select($keyword)
  {
    $sql = "SELECT A.id,CONCAT('[',A.id,'] ',A.name) name
    FROM item A
    LEFT JOIN item B
    ON A.reference = B.id
    WHERE A.type = 1 AND A.status = 1";
    if ($keyword) {
      $sql .= " AND (A.name LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%')";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
