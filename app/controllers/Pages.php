<?php

    class Pages extends Controller
    {
        public $postModel;

        public function __construct() {
            $this->postModel = $this->model('post');
        }

        public function index() {


            $posts = $this->postModel->getPosts();

            $data = [
                'title' => 'ciao',
                'posts' => $posts,
            ];

            $this->view('pages/index', $data);
        }

        public function about()
        {
            $data = [
                'title' => 'About Us',
            ];
            $this->view('pages/about', $data);
        }
    }