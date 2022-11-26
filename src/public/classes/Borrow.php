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

  public function auth_request($level, $zone)
  {
    $sql = "SELECT COUNT(*)
    FROM request A
    LEFT JOIN user_detail B
    ON A.user_id = B.id
    LEFT JOIN user_login C
    ON A.user_id = C.user_id
    LEFT JOIN province D
    ON B.province_code = D.code
    WHERE A.status = 1";
    if ($level === 9) {
      $sql .= " AND C.level = 2 ";
    } else {
      $sql .= " AND C.level = 1 AND D.zone_id = {$zone} ";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return  $stmt->fetchColumn();
  }

  public function request_insert($data)
  {
    $sql = "INSERT INTO request(type,user_id,start,end,text,status) VALUES(?,?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function request_update($data)
  {
    $sql = "UPDATE request SET 
    start = ?,
    end = ?,
    text = ?,
    updated = NOW()
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function request_approve($data)
  {
    $sql = "UPDATE request SET 
    approver = ?,
    approve_text = ?,
    approve_datetime = NOW(),
    status = ?
    WHERE id = ?";
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

  public function item_update($data)
  {
    $sql = "UPDATE request_item SET 
    amount = ?,
    confirm = ?,
    location = ?,
    text = ?
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function item_approve($data)
  {
    $sql = "UPDATE request_item SET 
    confirm = ?,
    remark = ?
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt;
  }

  public function item_count($data)
  {
    $sql = "SELECT COUNT(*) FROM request_item WHERE request_id = ? AND item_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchColumn();
  }

  public function request_fetch($data)
  {
    $sql = "SELECT A.id request_id,B.name user_name,A.type type_id,IF(A.type = 1,'ยืม','คืน') type_name,A.text,
    CONCAT(DATE_FORMAT(A.start, '%d/%m/%Y'),' - ',DATE_FORMAT(A.end, '%d/%m/%Y')) date,
    C.name approver_name,DATE_FORMAT(A.approve_datetime, '%d/%m/%Y, %H:%i น.') approve_datetime,A.approve_text,
    CASE A.status
      WHEN 1 THEN 'รออนุมัติ'
      WHEN 2 THEN 'รอรับคืน'
      WHEN 3 THEN 'ดำเนินการเรียบร้อยแล้ว'
      WHEN 4 THEN 'ยกเลิก'
      ELSE NULL
    END status_name
    FROM request A
    LEFT JOIN user_detail B 
    ON A.user_id = B.id
    LEFT JOIN user_detail C
    ON A.approver = C.id
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetch();
  }

  public function item_fetch($data)
  {
    $sql = "SELECT A.id,A.item_id,B.user_id,C.name item_name,C.unit item_unit,A.amount,A.confirm,A.location,A.text,A.remark
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

  public function count_item($data)
  {
    $sql = "SELECT SUM(A.amount) total
    FROM user_item A
    LEFT JOIN user_detail B 
    ON A.user_id = B.id
    LEFT JOIN province C 
    ON B.province_code = C.code 
    LEFT JOIN item D
    ON A.item_id = D.id
    WHERE A.user_id != ?
    AND C.zone_id = ?
    AND A.item_id = ?
    GROUP BY A.item_id, C.zone_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return ($row['total'] ? $row['total'] : 0);
  }

  public function borrow_item($data)
  {
    $sql = "SELECT SUM(confirm) total
    FROM request_item A 
    LEFT JOIN request B
    ON A.request_id = B.id
    LEFT JOIN item C
    ON A.item_id = C.id
    LEFT JOIN user_detail D
    ON B.user_id = D.id
    LEFT JOIN province E
    ON D.province_code = E.code
    WHERE B.status = 3
    AND B.type = 1
    AND E.zone_id = ?
    AND A.item_id = ?
    GROUP BY A.item_id, E.zone_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return ($row['total'] ? $row['total'] : 0);
  }

  public function json_location($data)
  {
    $sql = "SELECT TRIM(SUBSTRING_INDEX(location,',',1)) AS latitude,
    TRIM(SUBSTRING_INDEX(location,',',-1)) AS longitude
    FROM request_item
    WHERE request_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function json_fetch_item($data)
  {
    $sql = "SELECT TRIM(SUBSTRING_INDEX(location,',',1)) AS latitude,
    TRIM(SUBSTRING_INDEX(location,',',-1)) AS longitude,
    CONCAT(B.name, 'จำนวน ',A.amount,' ',B.unit) as item_name,
    C.text as request_text,
    date_format(C.start,'%d/%m/%Y') as start,
    date_format(C.end,'%d/%m/%Y') as end,
    D.name user_name
    FROM request_item A
    LEFT JOIN item B
    ON A.item_id = B.id
    LEFT JOIN request C
    ON A.request_id = C.id
    LEFT JOIN user_detail D 
    ON C.user_id = D.id
    WHERE A.request_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return json_encode($stmt->fetchAll());
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}
