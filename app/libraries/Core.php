<?php
    // App core class, creates URL and loads core controller.
    // URL format /controller/method/params

    class Core
    {
        // if no other controllers, it would upload PagesController
        protected $url;
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct() {
            $this->getUrl();
            $this->getController();
            $this->getMethod();
            $this->getParams();
            $this->invokeControllerMethod();
        }

        // Extracts, sanitizes and splits the URL into an array if the 'url' parameter is set in the request
        public function getUrl() {
            if(isset($_GET['url'])) {
                $this->url = rtrim($_GET['url'], '/');
                $this->url = filter_var($this->url, FILTER_SANITIZE_URL);
                $this->url =  explode('/', $this->url);
            }
        }

        // This method check if exist the controller by searching from url
        public function getController() {
            if(isset($this->url[0]) && file_exists('../app/controllers/' . ucwords($this->url[0]) . '.php')) {
                $this->currentController = ucwords($this->url[0]);
                unset($this->url[0]);
            }

            require_once '../app/controllers/' . $this->currentController . '.php';
            $this->currentController = new $this->currentController;
        }

        public function getMethod() {
            if(isset($this->url[1]) && method_exists($this->currentController, $this->url[1])) {
                $this->currentMethod = $this->url[1];
                unset($this->url[1]);
            }
        }

        public function getParams() {
            $this->params = $this->url ? array_values($this->url) : [];
        }

        // this method invoke the method of controller passing params
        public function invokeControllerMethod() {
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }
    }