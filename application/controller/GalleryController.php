<?php

class GalleryController extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if(Session::userisLoggedIn()){
            $this->View->render('gallery/index', array(
                'images' => GalleryModel::GetUserImages()
            ));
        } else {
            Redirect::home();
        }
        
    }


    public function viewImage($image_id) {
        if(Session::userIsLoggedIn()) {
            $this->View->render('gallery/ImageView', array(
                'imageLink' => Config::get('URL') . 'gallery/showImage/' . $image_id
            ));
        } else {
            Redirect::to('gallery/index');
        }
    }

    public function viewPublicImage() {
        $hash = Request::get('pic');
        if(!empty($hash)) {
            $this->View->render('gallery/ImageView', array(
                'imageLink' => Config::get('URL') . 'gallery/showPublicImage/?pic=' . $hash
            ));
        } else {
            Redirect::to('gallery/index');
        }
    }

    public function upload(){
        if(Session::userIsLoggedIn()) {
            $image = $_FILES["image"];
            $imageData = getImagesize($image['tmp_name']);
            $mimeType = $imagedata['mime'];
            if(in_array($mimeType, array('image/jpeg', 'image/png', 'image/gif'))) {
                $userPath = Config::get('PATH_IMAGES') . Session::get('user_id');
                if(!is_dir($userPath)) {
                    mkdir($userPath, 0777, true);
                }
                $id = GalleryModel::AddImage($image['name']);
                self::genThumpail($userPath, $id, $image);
                self::saveImage($userPath, $id, $image);
            }
        }
        Redirect::to("gallery/index");
    }

    private function genThumpmail($imgUserPath, $imgID, $image) {
        $thumpmailPath = $imgUserPath . '/thump';
        if(!is_dir($thumpmailPath)) {
            mkdir($thumpmailPath, 0777, true);
        }
        $thumpmailPath = $thumpmailPath . '/' . $imgID;
    }
}