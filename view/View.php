<?php

require_once 'model/OverlayPictures.php';

class View {

    private $file;
    private $title;
    private $overlayPics;

    public function __construct($requestedView) {
        $this->file = 'view/'.$requestedView.'View.php';
        $this->overlayPics = new OverlayPictures();
    }

    public function generate(Array $data, $admin = false) {

        $content = $this->viewGenerator($this->file, $data);

        if (!$admin):
            $request = $this->overlayPics->getPicturesLink(4);
            $pics = $request->fetchAll(PDO::FETCH_NUM);
            $links = [];
            
            foreach($pics as list($img, $subSection, $section)):
                $links[] = 'gallery/'.$section.'/'.$subSection.'/'.$img;
            endforeach;

            $view = $this->viewGenerator('view/template.php', ['title' => $this->title, 'content' => $content, 'overlay' => $links]);
        else:
            $view = $this->viewGenerator('view/adminTemplate.php', ['title' => $this->title, 'content' => $content]);
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