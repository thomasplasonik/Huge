<?php

class MessageModel {

    public static function getMessages($sender_id, $receiver_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM messages WHERE (sender = :sender_id AND receiver = :receiver_id) OR (sender = :receiver_id AND receiver = :sender_id)";   
        $query = $database->prepare($sql);
        $query->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));

        $all_messages = array();

        foreach ($query->fetchAll() as $content) {

            if($content->sender == $receiver_id){
                MessageModel::setMessageRead($content->ID);
            }

            $all_messages[$content->ID] = new stdClass();
            $all_messages[$content->ID]->content = $content->content;
            $all_messages[$content->ID]->sender = UserModel::getPublicProfileOfUser($content->sender);
            $all_messages[$content->ID]->receiver = UserModel::getPublicProfileOfUser($content->receiver);
        }
        
        return $all_messages;
    }

    public static function setMessageRead($content){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE messages SET `Viewd` = 1 WHERE ID = :ID";
        $query = $database->prepare($sql);
        $query->execute(array(':ID' => $content));
    }

    public static function addMessage($receiver_id, $content){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO messages (content, Viewd, sender, receiver) VALUES (:content, :Viewd, :sender, :receiver)";
        $query = $database->prepare($sql);
        $query->execute(array(':sender' => Session::get("user_id"),':Viewd' => 0, ':receiver' => $receiver_id, ':content' => $content));
    }

    public static function hasNewMessage($receiver_id, $sender_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id FROM messages WHERE receiver = :receiver AND sender = :sender AND `Viewd`=0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver' => $receiver_id, ':sender' => $sender_id));

        if($query->rowCount() == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function getMessageCount($receiver_id, $sender_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id FROM messages WHERE receiver = :receiver AND sender = :sender AND `read`=0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver' => $receiver_id, ':sender' => $sender_id));
        $count = $query->rowCount();
        
        $query->closeCursor();

        return $count;
    }


}



?>