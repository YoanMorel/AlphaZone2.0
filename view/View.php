<?php

class View {

    private $file;
    private $title;

    public function __construct($requestedView) {
        $this->file = 'view/'.$requestedView.'View.php';
    }

    public function generate($data, $admin = false) {
        $content = $this->viewGenerator($this->file, $data);
        if (!$admin):
            $view = $this->viewGenerator('view/template.php', array('title' => $this->title, 'content' => $content));
        else:
            $view = $this->viewGenerator('view/adminTemplate.php', array('title' => $this->title, 'content' => $content));
        endif;

        echo $view;
    }

    private function viewGenerator($file, $data) {
        if (file_exists($file)):
            if($data):
                extract($data);
            endif;

            ob_start();
            require $file;

            return ob_get_clean();
        else:
            throw new Exception('Fichier '.$file.' non récupéré');
        endif;
    }
}