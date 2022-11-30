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
    WHERE A.status IN (1,2)";
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

  public function item_borrow_update($data)
  {
    $sql = "UPDATE request_item SET 
    amount = ?,
    confirm = ?
    WHERE request_id = ?
    AND item_id = ?";
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
    $sql = "SELECT A.id request_id,A.user_id,B.name user_name,D.zone_id,
    A.type type_id,IF(A.type = 1,'ยืม','คืน') type_name,A.text,
    CONCAT(DATE_FORMAT(A.start, '%d/%m/%Y'),' - ',DATE_FORMAT(A.end, '%d/%m/%Y')) date_borrow,
    DATE_FORMAT(A.start, '%d/%m/%Y') date_return,
    C.name approver_name,DATE_FORMAT(A.approve_datetime, '[ %d/%m/%Y, %H:%i น. ]') approve_datetime,A.approve_text,
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
    LEFT JOIN province D 
    ON C.province_code = D.code
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetch();
  }

  public function item_fetch($data, $user)
  {
    $sql = "SELECT A.item_id,B.name item_name,B.unit item_unit,SUM(A.amount) total,
    SUM(CASE WHEN A.user_id = {$user} THEN A.amount ELSE 0 END) item_user,
    SUM(A.amount) - SUM(CASE WHEN A.user_id = {$user} THEN A.amount ELSE 0 END) stock,
    E.id request_id,E.amount request_amount,E.confirm request_confirm,E.location request_location,
    E.text request_text,E.remark request_remark
    FROM user_item A
    LEFT JOIN item B
    ON A.item_id = B.id
    LEFT JOIN user_detail C
    ON A.user_id = C.id
    LEFT JOIN province D
    ON C.province_code = D.code
    LEFT JOIN request_item E
    ON A.item_id = E.item_id
    LEFT JOIN request F 
    ON E.request_id = F.id
    WHERE D.zone_id = ?
    AND E.request_id = ?
    GROUP BY A.item_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchAll();
  }

  public function item_borrow_fetch($data)
  {
    $sql = "SELECT A.item_id,B.user_id,C.name item_name,C.unit item_unit,
    SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) item_borrow,
    SUM(CASE WHEN B.type = 2 THEN A.confirm ELSE 0 END) item_return,
    SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) - SUM(CASE WHEN B.type = 2 THEN A.confirm ELSE 0 END) balance
    FROM request_item A
    LEFT JOIN request B 
    ON A.request_id = B.id
    LEFT JOIN item C
    ON A.item_id = C.id
    WHERE B.user_id = ?
    GROUP BY item_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchAll();
  }

  public function item_return_fetch($data, $request)
  {
    $sql = "SELECT A.item_id,B.user_id,C.name item_name,C.unit item_unit,
    SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) item_borrow,
    SUM(CASE WHEN B.type = 2 AND A.request_id = {$request} THEN A.confirm ELSE 0 END) item_return,
    SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) - SUM(CASE WHEN B.type = 2 AND A.request_id = {$request} THEN A.confirm ELSE 0 END) balance
    FROM request_item A
    LEFT JOIN request B 
    ON A.request_id = B.id
    LEFT JOIN item C
    ON A.item_id = C.id
    WHERE B.user_id = ?
    AND B.status = 3
    GROUP BY item_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return  $stmt->fetchAll();
  }

  public function item_balance($data)
  {
    $sql = "SELECT 
    SUM(CASE WHEN A.type = 1 THEN B.confirm ELSE 0 END) - SUM(CASE WHEN A.type = 2 THEN B.confirm ELSE 0 END) balance
    FROM request A 
    LEFT JOIN request_item B 
    ON A.id = B.request_id
    LEFT JOIN user_detail C 
    ON A.user_id = C.id 
    LEFT JOIN province D 
    ON C.province_code = D.code
    WHERE A.status = 3
    AND D.zone_id = ?
    AND B.item_id = ?
    GROUP BY B.item_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return ($row['balance'] ? $row['balance'] : 0);
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

  public function count_borrow($data)
  {
    $sql = "SELECT 
    SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) - SUM(CASE WHEN B.type = 2 THEN A.confirm ELSE 0 END) balance
    FROM request_item A
    LEFT JOIN request B
    ON A.request_id = B.id
    LEFT JOIN item C
    ON A.item_id = C.id
    WHERE B.user_id = ?
    AND B.status = 3";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return ($row['balance'] ? $row['balance'] : 0);
  }

  public function count_location($data)
  {
    $sql = "SELECT COUNT(location)
    FROM request_item
    WHERE request_id = ?
    AND location != '' ";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
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
