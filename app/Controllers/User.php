<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];
        return view('user/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah User'
        ];
        return view('user/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,kasir]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
        ]);

        session()->setFlashdata('success', 'User baru berhasil ditambahkan.');
        return redirect()->to('/user');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/user');
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user
        ];
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/user');
        }

        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'role'     => 'required|in_list[admin,kasir]'
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $userData = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role')
        ];

        if (!empty($password)) {
            $userData['password'] = $password;
        }

        $this->userModel->update($id, $userData);

        session()->setFlashdata('success', 'User berhasil diperbarui.');
        return redirect()->to('/user');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/user');
        }

        if ($id == session()->get('id_user')) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
            return redirect()->to('/user');
        }

        $this->userModel->delete($id);
        session()->setFlashdata('success', 'User berhasil dihapus.');
        return redirect()->to('/user');
    }
}
