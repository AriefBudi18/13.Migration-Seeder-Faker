<?php

namespace App\Controllers;

use App\Models\JacketModel;

class Jacket extends BaseController
{
    protected $jacketModel;
    public function __construct()
    {
        $this->jacketModel = new JacketModel();
    }

    public function index()
    {
        // $jacket = $this->jacketModel->findAll();

        $data = [
            'title' => 'Jacket List',
            'jacket' => $this->jacketModel->getJacket()
        ];

        // $jacketModel = new \App\Models\JacketModel();
        // $jacketModel = new JacketModel();


        return view('jacket/index', $data);
    }


    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Jacket',
            'jacket' => $this->jacketModel->getJacket($slug)
        ];

        //jika jacket tidak terdapat pada tabel
        if (empty($data['jacket'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jacket name ' . $slug .
                ' not found.');
        }


        return view('jacket/detail', $data);
    }

    public function create()
    {
        // session();
        $data = [
            'title' => 'Form Add Jacket Data',
            'validation' => \Config\Services::validation()
        ];
        return view('jacket/create', $data);
    }

    public function save()
    {

        // validasi input
        if (!$this->validate([
            'name' => [
                'rules' => 'required|is_unique[jacket.name]',
                'errors' => [
                    'required' => '{field} this is required.',
                    'is_unique' => '{field} this is already registered'
                ]
            ],
            // 'product_code' => [
            //     'rules' => 'required|is_unique[jacket.product_code]',
            //     'errors' => [
            //         'required' => '{field} this is required.',
            //         'is_unique' => '{field} this is already registered'
            //     ]
            // ],
            'pictures' => [
                'rules' => 'max_size[pictures,1024]|is_image[pictures]|mime_in[pictures,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Maximum image size',
                    'is_image' => 'You choose is not a image',
                    'mime_in' => 'You choose is not a image'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/jacket/create')->withInput()->with('validation', $validation);
            return redirect()->to('/jacket/create')->withInput();
        }

        //    ambil gambar
        $filePictures = $this->request->getFile('pictures');
        // apakah tdk ada gmbar yg d upload
        if ($filePictures->getError() == 4) {
            $namaPictures = 'default.jpg';
        } else {
            // generate nama pictures random
            $namaPictures = $filePictures->getRandomName();
            // pindahkan file ke folder img
            $filePictures->move('img', $namaPictures);
        }

        $slug = url_title($this->request->getVar('name'), '-', true);
        $this->jacketModel->save([
            'name' => $this->request->getVar('name'),
            'slug' => $slug,
            'product_code' => $this->request->getVar('product_code'),
            'pictures' => $namaPictures
        ]);

        session()->setFlashdata('message', 'Managed to add.');

        return redirect()->to('/jacket');
    }

    public function delete($id)
    {
        // cari gambar brdasarkan id
        $jacket = $this->jacketModel->find($id);

        // cek jika gmbar itu default.jpg
        if ($jacket['pictures'] != 'default.jpg') {
            // hapus gambar
            unlink('img/' . $jacket['pictures']);
        }

        $this->jacketModel->delete($id);
        session()->setFlashdata('message', 'Managed to delete.');
        return redirect()->to('/jacket');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Edit Jacket Data',
            'validation' => \Config\Services::validation(),
            'jacket' => $this->jacketModel->getJacket($slug)
        ];
        return view('jacket/edit', $data);
    }

    public function update($id)
    {
        // perlunya pengkondisian
        // cek nama jacket
        $jacketLama = $this->jacketModel->getJacket($this->request->getVar('slug'));
        // jika judul yg lama sama dengan judul baru,artinya tdak perlu diubah
        if ($jacketLama['name'] == $this->request->getVar('name')) {
            $rule_name = 'required';
        } else {
            $rule_name = 'required|is_unique[jacket.name]';
        }

        if (!$this->validate([
            'name' => [
                'rules' => $rule_name,
                'errors' => [
                    'required' => '{field} this is required.',
                    'is_unique' => '{field} this is already registered'
                ]
            ],
            'pictures' => [
                'rules' => 'max_size[pictures,1024]|is_image[pictures]|mime_in[pictures,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Maximum image size',
                    'is_image' => 'You choose is not a image',
                    'mime_in' => 'You choose is not a image'
                ]
            ]

        ])) {
            return redirect()->to('/jacket/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $filePictures = $this->request->getFile('pictures');

        // cek gambar apakah masih gambar lama?
        if ($filePictures->getError() == 4) {
            $namaPictures = $this->request->getVar('picturesLama');
        } else {
            // generate nama file random
            $namaPictures = $filePictures->getRandomName();
            // pindahkan gambar
            $filePictures->move('img', $namaPictures);
            // hapus file lama
            unlink('img/' . $this->request->getVar('picturesLama'));
        }

        $slug = url_title($this->request->getVar('name'), '-', true);
        $this->jacketModel->save([
            'id' => $id,
            'name' => $this->request->getVar('name'),
            'slug' => $slug,
            'product_price' => $this->request->getVar('product_price'),
            'product_code' => $this->request->getVar('product_code'),
            'pictures' => $namaPictures
        ]);

        session()->setFlashdata('message', 'Managed to edit.');

        return redirect()->to('/jacket');
    }
}
