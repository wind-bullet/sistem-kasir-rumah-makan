<?php

namespace App\Controllers;

use App\Models\MejaModel;

class QRCode extends BaseController
{
    protected $mejaModel;

    public function __construct()
    {
        $this->mejaModel = new MejaModel();
    }

    public function index()
    {
        // Get all tables
        $meja = $this->mejaModel->orderBy('nomor_meja', 'ASC')->findAll();

        $data = [
            'title' => 'Manajemen QR Code Meja',
            'meja'  => $meja,
            'ip'    => $this->request->getGet('ip') ?? ''
        ];

        return view('qrcode/index', $data);
    }

    public function generate()
    {
        helper('qr');
        $meja = $this->mejaModel->orderBy('nomor_meja', 'ASC')->findAll();
        $customIp = $this->request->getGet('ip');

        // Check/create directory
        $dirPath = ROOTPATH . 'public/QR_images/';
        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                session()->setFlashdata('error', 'Gagal membuat direktori public/QR_images/. Periksa permission.');
                return redirect()->to('/qrcode');
            }
        }

        if (!is_writable($dirPath)) {
            session()->setFlashdata('error', 'Direktori public/QR_images/ tidak dapat ditulis (not writable).');
            return redirect()->to('/qrcode');
        }

        // Loop through all tables
        foreach ($meja as $m) {
            $nomorMeja = $m['nomor_meja'];
            if (!generate_qr_meja($nomorMeja, $customIp)) {
                session()->setFlashdata('error', "Gagal generate QR Code untuk Meja {$nomorMeja}.");
                return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
            }
        }

        // Also generate takeaway QR code
        if (!generate_qr_takeaway($customIp)) {
            session()->setFlashdata('error', 'Gagal generate QR Code untuk Takeaway.');
            return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
        }

        session()->setFlashdata('success', 'Semua QR Code (termasuk Takeaway) berhasil di-generate.');
        return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
    }

    public function generateSingle($nomorMeja)
    {
        helper('qr');
        $customIp = $this->request->getGet('ip');

        // Find if meja exists
        $meja = $this->mejaModel->where('nomor_meja', $nomorMeja)->first();
        if (!$meja) {
            session()->setFlashdata('error', 'Meja tidak ditemukan.');
            return redirect()->to('/qrcode');
        }

        if (generate_qr_meja($nomorMeja, $customIp)) {
            session()->setFlashdata('success', "QR Code Meja {$nomorMeja} berhasil di-generate.");
        } else {
            session()->setFlashdata('error', "Gagal generate QR Code Meja {$nomorMeja}. Pastikan folder public/QR_images/ writable.");
        }

        return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
    }

    public function generateTakeaway()
    {
        helper('qr');
        $customIp = $this->request->getGet('ip');

        if (generate_qr_takeaway($customIp)) {
            session()->setFlashdata('success', 'QR Code Takeaway berhasil di-generate.');
        } else {
            session()->setFlashdata('error', 'Gagal generate QR Code Takeaway. Pastikan folder public/QR_images/ writable.');
        }

        return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
    }
}

