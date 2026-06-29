<?php

namespace App\Controllers;

use App\Models\MejaModel;
use chillerlan\QRCode\QRCode as ChillerlanQRCode;
use chillerlan\QRCode\QROptions;

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
        $meja = $this->mejaModel->orderBy('nomor_meja', 'ASC')->findAll();
        
        // Define directory to save QR images
        $dirPath = ROOTPATH . 'public/QR_images/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        // Configure QR Code options
        $options = new QROptions([
            'version'      => 5,
            'outputType'   => ChillerlanQRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => ChillerlanQRCode::ECC_L,
            'scale'        => 10,
            'imageBase64'  => false,
        ]);
        $qrcode = new ChillerlanQRCode($options);

        // Get custom IP/host if provided
        $customIp = $this->request->getGet('ip');
        
        foreach ($meja as $m) {
            $nomorMeja = $m['nomor_meja'];
            
            // Build absolute URL
            if (!empty($customIp)) {
                // Ensure proper protocol and trailing slash
                $cleanIp = rtrim($customIp, '/');
                if (!str_starts_with($cleanIp, 'http://') && !str_starts_with($cleanIp, 'https://')) {
                    $cleanIp = 'http://' . $cleanIp;
                }
                $url = $cleanIp . '/index.php/pesan/' . $nomorMeja;
            } else {
                $url = site_url('pesan/' . $nomorMeja);
            }

            $filePath = $dirPath . 'qr_meja_' . $nomorMeja . '.png';
            
            // Render and save QR code as file
            try {
                $qrcode->render($url, $filePath);
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal generate QR Code: ' . $e->getMessage());
                return redirect()->to('/qrcode');
            }
        }

        session()->setFlashdata('success', 'Semua QR Code berhasil di-generate.');
        return redirect()->to('/qrcode' . (!empty($customIp) ? '?ip=' . urlencode($customIp) : ''));
    }
}
