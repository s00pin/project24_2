<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    public function index()
    {
        $model = model(NewsModel::class);
        $data = [
            'news'  => $model->getNews(),
            'title' => 'News archive',
        ];
        return view('templates/header', $data)
            . view('news/index')
            . view('templates/footer');
    }

    public function show($slug = null)
    {
        $model = model(NewsModel::class);
        $data['news'] = $model->getNews($slug);
        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $slug);
        }
        $data['title'] = $data['news']['title'];
        return view('templates/header', $data)
            . view('news/view')
            . view('templates/footer');
    }

    public function new()
    {
        helper('form');
        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create')
            . view('templates/footer');
    }

    public function create()
    {
        helper('form');
        $data = $this->request->getPost(['title', 'body']);
        if (! $this->validateData($data, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body'  => 'required|max_length[5000]|min_length[10]',
        ])) {
            return $this->new();
        }
        $post = $this->validator->getValidated();
        $model = model(NewsModel::class);
        $model->save([
            'title' => $post['title'],
            'slug'  => url_title($post['title'], '-', true),
            'body'  => $post['body'],
        ]);
        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/success')
            . view('templates/footer');
    }

    public function edit($id = null)
    {
        helper('form');
        $model = model(NewsModel::class);
        $data['news'] = $model->find($id);
        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $id);
        }
        $data['title'] = 'Edit news item';
        return view('templates/header', $data)
            . view('news/edit')
            . view('templates/footer');
    }

    public function update($id = null)
    {
        helper('form');
        $model = model(NewsModel::class);
        $data = $this->request->getPost(['title', 'body']);
        if (! $this->validateData($data, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body'  => 'required|max_length[5000]|min_length[10]',
        ])) {
            return $this->edit($id);
        }
        $post = $this->validator->getValidated();
        $model->updateNews($id, [
            'title' => $post['title'],
            'slug'  => url_title($post['title'], '-', true),
            'body'  => $post['body'],
        ]);
        return view('templates/header', ['title' => 'Edit news item'])
            . view('news/success')
            . view('templates/footer');
    }

    public function delete($id)
    {
        $model = model(NewsModel::class);
        $model->deleteNews($id);
        return redirect()->to('/news');
    }
}
