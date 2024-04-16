<?php

class MessagerController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(!Session::userIsLoggedIn()){
            Redirect::home();
            return;
        }
        $this->View->render('messenger/index', array(
            'users' => UserModel::getPublicProfilesOfAllUsers()
        ));
    }

    public function showchat($receiver_id){
        if (isset($receiver_id) && Session::userIsLoggedIn()) {
            $this->View->render('messenger/showchat', array(
                'sender' => UserModel::getPublicProfileOfUser(Session::get('user_id')),
                'receiver' => UserModel::getPublicProfileOfUser($receiver_id),
                'messages' => MessageModel::getMessages(Session::get('user_id'), $receiver_id)
                )
            );
        } else {
            Redirect::home();
        }
    }
    public static function shouldDisplayNewMessageNotification($sender, $receiver){
        return MessageModel::hasNewMessage($receiver, $sender);
    }

    public function chat_action(){
        MessageModel::addMessage(Request::post("receiver_id"), Request::post("message"));
        Redirect::to("messager/showchat/". Request::post("receiver_id"));
    }
   
}
