<?php

use chillerlan\QRCode\QRCode as ChillerlanQRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;

if (!function_exists('generate_qr_meja')) {
    function generate_qr_meja($nomorMeja, $customIp = '')
    {
        $dirPath = ROOTPATH . 'public/QR_images/';
        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                return false;
            }
        }
        if (!is_writable($dirPath)) {
            return false;
        }

        $options = new QROptions([
            'version'          => 5,
            'outputInterface'  => QRGdImagePNG::class,
            'eccLevel'         => EccLevel::L,
            'scale'            => 10,
            'outputBase64'     => false,
        ]);
        $qrcode = new ChillerlanQRCode($options);

        if (!empty($customIp)) {
            $cleanIp = rtrim($customIp, '/');
            if (!str_starts_with($cleanIp, 'http://') && !str_starts_with($cleanIp, 'https://')) {
                $cleanIp = 'http://' . $cleanIp;
            }
            $url = $cleanIp . '/pesan/' . $nomorMeja;
        } else {
            $url = site_url('pesan/' . $nomorMeja);
        }

        $filePath = $dirPath . 'qr_meja_' . $nomorMeja . '.png';

        try {
            $qrcode->render($url, $filePath);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Gagal generate QR Code meja ' . $nomorMeja . ': ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('generate_qr_takeaway')) {
    function generate_qr_takeaway($customIp = '')
    {
        $dirPath = ROOTPATH . 'public/QR_images/';
        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                return false;
            }
        }
        if (!is_writable($dirPath)) {
            return false;
        }

        $options = new QROptions([
            'version'          => 5,
            'outputInterface'  => QRGdImagePNG::class,
            'eccLevel'         => EccLevel::L,
            'scale'            => 10,
            'outputBase64'     => false,
        ]);
        $qrcode = new ChillerlanQRCode($options);

        if (!empty($customIp)) {
            $cleanIp = rtrim($customIp, '/');
            if (!str_starts_with($cleanIp, 'http://') && !str_starts_with($cleanIp, 'https://')) {
                $cleanIp = 'http://' . $cleanIp;
            }
            $url = $cleanIp . '/pesan/takeaway';
        } else {
            $url = site_url('pesan/takeaway');
        }

        $filePath = $dirPath . 'qr_takeaway.png';

        try {
            $qrcode->render($url, $filePath);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Gagal generate QR Code Takeaway: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('delete_qr_meja')) {
    function delete_qr_meja($nomorMeja)
    {
        $filePath = ROOTPATH . 'public/QR_images/qr_meja_' . $nomorMeja . '.png';
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}
