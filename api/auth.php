<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL
$request = isset($path[1]) ? $path[1] : '';

switch ($method) {
    case 'POST':
        if ($request === 'login' || $request === '') {
            handleLogin();
        } elseif ($request === 'logout') {
            handleLogout();
        } else {
            ApiResponse::sendResponse(
                ApiResponse::error('Endpoint not found', 404)
            );
        }
        break;

    case 'GET':
        if ($request === 'me') {
            handleGetMe();
        } else {
            ApiResponse::sendResponse(
                ApiResponse::error('Endpoint not found', 404)
            );
        }
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function handleLogin() {
    global $koneksi, $data;

    if (empty($data['username']) || empty($data['password'])) {
        ApiResponse::sendResponse(
            ApiResponse::error('Username and password are required', 400)
        );
    }

    $username = sanitize($data['username']);
    $password = $data['password'];
    $type = $data['type'] ?? 'siswa'; // 'siswa' or 'petugas'

    if ($type === 'siswa') {
        $query = "SELECT * FROM siswa WHERE (nisn='$username' OR nis='$username')";
    } else {
        $query = "SELECT * FROM petugas WHERE username='$username'";
    }

    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user || !verifyPassword($password, $user['password'])) {
        ApiResponse::sendResponse(
            ApiResponse::error('Invalid credentials', 401)
        );
    }

    if ($type === 'siswa') {
        $_SESSION['nisn'] = $user['nisn'];
        $response_data = [
            'type' => 'siswa',
            'nisn' => $user['nisn'],
            'nis' => $user['nis'],
            'nama' => $user['nama'],
            'id_kelas' => $user['id_kelas'],
            'token' => generateToken($user)
        ];
    } else {
        $_SESSION['id_petugas'] = $user['id_petugas'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['nama_petugas'] = $user['nama_petugas'];
        $_SESSION['username'] = $username;
        $response_data = [
            'type' => 'petugas',
            'id_petugas' => $user['id_petugas'],
            'username' => $user['username'],
            'nama_petugas' => $user['nama_petugas'],
            'level' => $user['level'],
            'token' => generateToken($user)
        ];
    }

    ApiResponse::sendResponse(
        ApiResponse::success($response_data, 'Login successful', 200)
    );
}

function handleLogout() {
    session_destroy();
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Logout successful', 200)
    );
}

function handleGetMe() {
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Not authenticated', 401)
        );
    }
    ApiResponse::sendResponse(
        ApiResponse::success($user, 'User data retrieved', 200)
    );
}

?>
