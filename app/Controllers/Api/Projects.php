<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Projects extends ResourceController
{
    protected $modelName = 'App\Models\ProjectModel';
    protected $format    = 'json';

    /**
     * Mengambil semua data proyek
     */
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    /**
     * Mengambil satu data proyek berdasarkan ID
     */
    public function show($id = null)
    {
        $data = $this->model->find($id);
        
        if ($data) {
            return $this->respond($data);
        }

        return $this->failNotFound('Project dengan ID ' . $id . ' tidak ditemukan');
    }

    /**
     * Menyimpan data proyek baru
     */
    public function create()
    {
        $data = $this->request->getVar();

        if ($this->model->insert($data)) {
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Data project berhasil ditambahkan'
                ]
            ];
            return $this->respondCreated($response);
        }

        return $this->fail($this->model->errors());
    }

    /**
     * Mengupdate data proyek berdasarkan ID
     */
    public function update($id = null)
    {
        $existing = $this->model->find($id);

        if (!$existing) {
            return $this->failNotFound('Project dengan ID ' . $id . ' tidak ditemukan');
        }

        $data = $this->request->getRawInput();

        if ($this->model->update($id, $data)) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data project berhasil diupdate'
                ]
            ];
            return $this->respond($response);
        }

        return $this->fail($this->model->errors());
    }

    /**
     * Menghapus data proyek berdasarkan ID
     */
    public function delete($id = null)
    {
        $existing = $this->model->find($id);

        if (!$existing) {
            return $this->failNotFound('Project dengan ID ' . $id . ' tidak ditemukan');
        }

        if ($this->model->delete($id)) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data project berhasil dihapus'
                ]
            ];
            return $this->respondDeleted($response);
        }

        return $this->fail('Gagal menghapus data project');
    }
}
