<?php

/**
 * Script untuk membuat storage symlink pada Shared Hosting
 * Akses file ini sekali saja via browser: domainanda.com/storage-link.php
 */

$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

header('Content-Type: text/plain');

echo "--- Laravel Storage Link Helper ---\n\n";

// 1. Cek apakah target (folder asli) ada
if (!file_exists($target)) {
    exit("Error: Folder target tidak ditemukan di: $target\nPastikan Anda sudah mengupload isi folder 'storage/app/public' ke hosting.");
}

// 2. Cek apakah link sudah ada
if (file_exists($link)) {
    if (is_link($link)) {
        exit("Info: Symlink sudah ada dan sudah aktif.\n");
    } else {
        // Jika berupa folder asli, beri saran untuk hapus/rename
        exit("Peringatan: Folder 'public/storage' sudah ada sebagai folder asli, bukan shortcut.\nSilahkan hapus atau rename dulu folder 'public/storage' di server agar script ini bisa membuatkan shortcutnya.");
    }
}

// 3. Buat Symlink
if (symlink($target, $link)) {
    echo "Berhasil! Symlink 'public/storage' -> '$target' telah dibuat.\n";
    echo "Silahkan cek website Anda, gambar seharusnya sudah muncul.\n\n";
    echo "PENTING: Segera hapus file 'storage-link.php' ini demi keamanan!";
} else {
    echo "Gagal: Tidak dapat membuat symlink. Kemungkinan hosting Anda melarang fungsi PHP 'symlink()'.\n";
}
