<?php

namespace App\Controllers;

use App\Models\MediaModel;
use App\Models\ShowModel;

class Search extends BaseController
{
    public function index()
    {
        $query = $this->request->getGet('query'); 
        $mediaModel = new MediaModel();
        $showModel = new ShowModel();

        $model = model(ShowModel::class);
        $data = [
            'show'  => $model->getShow(),
            'title' => 'Top shows and movies',
        ];

        if (!empty($query)) {
            // Perform search based on the query
            $media = $mediaModel->like('title', $query, 'both')->findAll();
            $shows = $showModel->like('title', $query, 'both')->findAll();
        } else {
            // If no search query provided, retrieve all records
            $media = $mediaModel->findAll();
            $shows = $showModel->findAll();
        }
        return view('templates/header', $data)
            . view('media/search_results', ['media' => $media, 'shows' => $shows])
            . view('templates/footer');
    }
    public function searchSuggestions()
    {
        $searchTerm = $this->request->getVar('query');
        // Load models
        $mediaModel = new MediaModel();
        $showModel = new ShowModel();
        // Query media table
        $mediaResults = $mediaModel
            ->like('title', $searchTerm, 'both')
            ->limit(5) // Limiting media results
            ->findAll();
        // Query shows table
        $showResults = $showModel
            ->like('title', $searchTerm, 'both')
            ->limit(5) // Limiting show results
            ->findAll();
        // Prepare combined suggestions
        $suggestions = [];
        // Add media suggestions
        foreach ($mediaResults as $media) {
            $suggestions[] = [
                'Title' => $media['title'],
                'Poster' => $media['poster_image'], // Adjust as needed
                'ID' => $media['id'], // Use 'id' instead of 'slug' for media
                'Type' => 'media', // Indicate the type of suggestion
            ];
        }
        // Add show suggestions
        foreach ($showResults as $show) {
            $suggestions[] = [
                'Title' => $show['title'],
                'Poster' => $show['poster'], // Adjust as needed
                'ID' => $show['id'], // Use 'id' instead of 'slug' for shows
                'Type' => 'show', // Indicate the type of suggestion
            ];
        }
        return $this->response->setJSON($suggestions);
    }

}