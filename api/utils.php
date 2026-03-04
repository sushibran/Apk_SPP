<?php
// Utility functions for API

function generateToken($user_data) {
    // Simple token generation (you can use JWT library for production)
    $token = bin2hex(random_bytes(32));
    return $token;
}

function validateToken($token) {
    // Simple token validation - in production use JWT
    if (empty($token)) return false;
    return true;
}

function hashPassword($password) {
    return md5($password);
}

function verifyPassword($password, $hash) {
    return md5($password) === $hash;
}

function sanitize($data) {
    global $koneksi;
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return mysqli_real_escape_string($koneksi, $data);
}

function getAuthUser() {
    if (isset($_SESSION['nisn'])) {
        return [
            'type' => 'siswa',
            'id' => $_SESSION['nisn']
        ];
    } elseif (isset($_SESSION['id_petugas'])) {
        return [
            'type' => 'petugas',
            'id' => $_SESSION['id_petugas'],
            'level' => $_SESSION['level'] ?? null
        ];
    }
    
    // Check Authorization header
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $auth = $headers['Authorization'];
        // Extract token from Bearer scheme
        if (preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
            $token = $matches[1];
            // Validate token (implement based on your token system)
            if (validateToken($token)) {
                // Return user from token
                return json_decode(base64_decode($token), true);
            }
        }
    }
    
    return null;
}

function requireAuth() {
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Unauthorized', 401)
        );
    }
    return $user;
}

function requireAdmin() {
    $user = requireAuth();
    if ($user['type'] !== 'petugas' || $user['level'] !== 'admin') {
        ApiResponse::sendResponse(
            ApiResponse::error('Forbidden - Admin access required', 403)
        );
    }
    return $user;
}

function requireSiswa() {
    $user = requireAuth();
    if ($user['type'] !== 'siswa') {
        ApiResponse::sendResponse(
            ApiResponse::error('Forbidden - Student access required', 403)
        );
    }
    return $user;
}

function queryAll($query) {
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function queryFirst($query) {
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function execute($query) {
    global $koneksi;
    return mysqli_query($koneksi, $query);
}

function getLastId() {
    global $koneksi;
    return mysqli_insert_id($koneksi);
}

function getLastError() {
    global $koneksi;
    return mysqli_error($koneksi);
}

function getNumRows($query) {
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    return mysqli_num_rows($result);
}

?>
