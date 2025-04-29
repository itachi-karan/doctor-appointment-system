<?php
require_once 'config.php';

/**
 * Base Model providing basic CRUD operations.
 */
abstract class Model {
    protected static $table;

    public static function all($limit = null, $offset = 0) {
        global $db;
        $sql = "SELECT * FROM " . static::$table;
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $db->prepare($sql);
        if ($limit) {
            $stmt->bindValue(':limit', (int)
                $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)
                $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $db;
        $fields = array_keys($data);
        $placeholders = array_map(fn($f) => ":$f", $fields);
        $sql = "INSERT INTO " . static::$table
             . " (" . implode(', ', $fields) . ")"
             . " VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $db->prepare($sql);
        foreach ($data as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        global $db;
        $fields = array_keys($data);
        $sets = array_map(fn($f) => "$f = :$f", $fields);
        $sql = "UPDATE " . static::$table
             . " SET " . implode(', ', $sets)
             . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        foreach ($data as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $db;
        $stmt = $db->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

// Table-specific Models
class User extends Model { 
    protected static $table = 'users';

    /**
     * Find user by email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail($email) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
class Doctor extends Model { protected static $table = 'doctors'; }
class Schedule extends Model { protected static $table = 'schedules'; }
class Patient extends Model { protected static $table = 'patients'; }
class Appointment extends Model { protected static $table = 'appointments'; }
class MedicalRecord extends Model { protected static $table = 'medical_records'; }
class Specialty extends Model { protected static $table = 'specialties'; }
// Model for emergencies
class Accident extends Model { protected static $table = 'accidents'; }
