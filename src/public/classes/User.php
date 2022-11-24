<?php

namespace app\classes;

class User
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function password_verify($data, $password)
  {
    $sql = "SELECT B.password 
    FROM user_detail A 
    LEFT JOIN user_login B 
    ON A.id = B.user_id 
    WHERE B.name = ?
    AND A.status = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return  password_verify($password, $row['password']);
  }

  public function user_count()
  {
    $sql = "SELECT COUNT(*) as total,
    SUM(CASE WHEN level = 2 THEN 1 ELSE 0 END) district,
    SUM(CASE WHEN level = 1 THEN 1 ELSE 0 END) province,
    SUM(CASE WHEN level = 9 THEN 1 ELSE 0 END) admin
    FROM user_login";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }

  public function user_detail_insert($data)
  {
    $sql = "INSERT INTO user_detail(name,email,contact,province_code) VALUES(?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function user_login_insert($data)
  {
    $sql = "INSERT INTO user_login(user_id,name,password,level) VALUES(?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function user_name_fetch($data)
  {
    $sql = "SELECT A.id user_id,A.name user_name,A.email user_email,A.contact user_contact,A.province_code,
    B.level user_level
    FROM user_detail A 
    LEFT JOIN user_login B 
    ON A.id = B.user_id
    WHERE B.name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function user_id_fetch($data)
  {
    $sql = "SELECT A.id user_id,A.name user_name,A.email user_email,A.contact user_contact,A.status,
    B.name login_name,B.level user_level,A.province_code,C.name province_name,C.zone_id,
    CASE 
      WHEN B.level = 1 THEN 'ผู้ใช้ระดับจังหวัด'
      WHEN B.level = 2 THEN 'ผู้ใช้ระดับเขต'
      WHEN B.level = 9 THEN 'ผู้ดูแลระบบ'
      ELSE NULL
    END level_name
    FROM user_detail A 
    LEFT JOIN user_login B 
    ON A.id = B.user_id
    LEFT JOIN province C 
    ON A.province_code = C.code
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function user_detail_update($data)
  {
    $sql = "UPDATE user_detail SET
    name = ?,
    email = ?,
    contact = ?,
    province_code = ?,
    status = ?,
    updated = NOW()
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function user_login_update($data)
  {
    $sql = "UPDATE user_login SET
    name = ?,
    level = ?,
    updated = NOW()
    WHERE user_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function province_select($keyword)
  {
    $sql = "SELECT code,name FROM province WHERE status = 1";
    if ($keyword) {
      $sql .= " AND (A.name LIKE '%{$keyword}%' OR A.name_en LIKE '%{$keyword}%')";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function zone_filter($keyword)
  {
    $sql = "SELECT zone_id,CONCAT('เขต ', zone_id) zone_name FROM province WHERE status = 1 AND zone_id != 0";
    if ($keyword) {
      $sql .= " AND zone_id LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY zone_id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function item_select($keyword)
  {
    $sql = "SELECT A.id,A.name 
    FROM item A
    LEFT JOIN item B
    ON A.reference = B.id
    WHERE A.type = 2 AND A.status = 1";
    if ($keyword) {
      $sql .= " AND (A.name LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%')";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function item_count($data)
  {
    $sql = "SELECT COUNT(*) FROM user_item WHERE user_id = ? AND item_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function item_insert($data)
  {
    $sql = "INSERT INTO user_item(user_id,text,item_id,amount,remark) VALUES(?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function item_update($data)
  {
    $sql = "UPDATE user_item SET 
    text = ?,
    amount = ?,
    remark = ?
    WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function item_fetch($data)
  {
    $sql = "SELECT C.id item_id,A.name item_name,A.reference,B.name reference_name,
    A.unit item_unit,C.amount item_amount,C.remark item_remark,C.text
    FROM item A
    LEFT JOIN item B
    ON A.reference = B.id
    LEFT JOIN user_item C 
    ON A.id = C.item_id
    WHERE C.user_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function item_detail($data)
  {
    $sql = "SELECT A.id item_id,A.name item_name,A.reference,B.name reference_name,A.unit item_unit
    FROM item A
    LEFT JOIN item B
    ON A.reference = B.id 
    WHERE A.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function change_password($data)
  {
    $sql = "UPDATE user_login SET
    password = ?,
    updated = NOW()
    WHERE user_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt;
  }

  public function image_upload($tmp, $path)
  {
    $imageInfo   = (isset($tmp) ? getimagesize($tmp) : '');
    $imageWidth   = 500;
    $imageHeight = (isset($imageInfo) ? round($imageWidth * $imageInfo[1] / $imageInfo[0]) : '');
    $imageType    = $imageInfo[2];

    if ($imageType === IMAGETYPE_PNG) {
      $imageResource = imagecreatefrompng($tmp);
      $imageX = imagesx($imageResource);
      $imageY = imagesy($imageResource);
      $imageTarget = imagecreatetruecolor($imageWidth, $imageHeight);
      imagecopyresampled($imageTarget, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageX, $imageY);
      imagewebp($imageTarget, $path, 100);
      imagedestroy($imageTarget);
    } else {
      $imageResource = imagecreatefromjpeg($tmp);
      $imageX = imagesx($imageResource);
      $imageY = imagesy($imageResource);
      $imageTarget = imagecreatetruecolor($imageWidth, $imageHeight);
      imagecopyresampled($imageTarget, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageX, $imageY);
      imagewebp($imageTarget, $path, 100);
      imagedestroy($imageTarget);
    }
  }

  public function image_unlink($id)
  {
    $sql = "SELECT picture FROM user_detail WHERE id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($id);
    $row = $stmt->fetch();
    if (!empty($row['picture'])) {
      return unlink(__DIR__ . "/../assets/img/profile/{$row['picture']}");
    } else {
      return false;
    }
  }

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}