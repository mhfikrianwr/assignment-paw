<?php

define('UPLOAD_DIR', __DIR__ . '/data/uploads/');
define('ALLOWED_EXT', ['jpg', 'jpeg', 'png']);
define('ALLOWED_MIME', ['image/jpeg', 'image/png']);
define('MAX_SIZE', 5 * 1024 * 1024);
define('FRONTEND_URL', '../frontend/index.php');

function redirect(string $status, string $message = '', string $filename = ''): void
{
    $query = http_build_query([
        'status'   => $status,
        'message'  => $message,
        'filename' => $filename,
    ]);
    header('Location: ' . FRONTEND_URL . '?' . $query);
    exit;
}

function sanitizeFilename(string $filename): string
{
    $ext  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $name = pathinfo($filename, PATHINFO_FILENAME);

    $name = strtolower($name);
    $name = str_replace(' ', '_', $name);
    $name = preg_replace('/[^a-z0-9_\-]/', '', $name);
    $name = trim($name, '_-');

    if (empty($name)) {
        $name = 'image';
    }

    $timestamp = date('Ymd_His');

    return "{$name}_{$timestamp}.{$ext}";
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('error', 'Metode request tidak valid.');
}

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
    redirect('error', 'Tidak ada file yang dipilih.');
}

$file = $_FILES['foto'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    $phpErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File melebihi batas upload_max_filesize di php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'File melebihi batas MAX_FILE_SIZE di form.',
        UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan.',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
        UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.',
    ];
    $errMsg = $phpErrors[$file['error']] ?? 'Terjadi kesalahan saat upload.';
    redirect('error', $errMsg);
}

if ($file['size'] > MAX_SIZE) {
    redirect('error', 'Ukuran file melebihi batas maksimum 5 MB.');
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ALLOWED_EXT, true)) {
    redirect('error', 'Format file tidak didukung. Gunakan JPG atau PNG.');
}

$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
if (!in_array($mimeType, ALLOWED_MIME, true)) {
    redirect('error', 'Tipe file tidak valid. Pastikan file adalah gambar JPG atau PNG.');
}

$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    redirect('error', 'File bukan merupakan gambar yang valid.');
}

if (!is_dir(UPLOAD_DIR)) {
    if (!mkdir(UPLOAD_DIR, 0755, true)) {
        redirect('error', 'Gagal membuat direktori penyimpanan.');
    }
}

if (!is_writable(UPLOAD_DIR)) {
    redirect('error', 'Direktori penyimpanan tidak memiliki izin tulis.');
}

$newFilename = sanitizeFilename($file['name']);
$destination = UPLOAD_DIR . $newFilename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect('error', 'Gagal menyimpan file. Silakan coba lagi.');
}

redirect('success', '', $newFilename);