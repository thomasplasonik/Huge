<?php

class MessageModel {

    //Lade alle Nachrichten von der Datenbank von dem Eingeloggten User und dem Chat user
    public static function getMessages($sender_id, $receiver_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM messages WHERE (sender = :sender_id AND receiver = :receiver_id) OR (sender = :receiver_id AND receiver = :sender_id)";   
        $query = $database->prepare($sql);
        $query->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));

        $all_messages = array();

        foreach ($query->fetchAll() as $content) {

            if($content->sender == $receiver_id){
                MessageModel::setMessageReaded($content->ID);
            }

            $all_messages[$content->ID] = new stdClass();
            $all_messages[$content->ID]->content = $content->content;
            $all_messages[$content->ID]->sender = UserModel::getPublicProfileOfUser($content->sender);
            $all_messages[$content->ID]->receiver = UserModel::getPublicProfileOfUser($content->receiver);
        }
        
        return $all_messages;
    }

    //Sobald der User die nachricht gelesen hat, setze Viewd auf 1
    public static function setMessageReaded($content){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE messages SET `Viewd` = 1 WHERE ID = :ID";
        $query = $database->prepare($sql);
        $query->execute(array(':ID' => $content));
    }

    //speicher die Nachricht in der DB
    public static function addMessage($receiver_id, $content){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO messages (content, Viewd, sender, receiver) VALUES (:content, :Viewd, :sender, :receiver)";
        $query = $database->prepare($sql);
        $query->execute(array(':sender' => Session::get("user_id"),':Viewd' => 0, ':receiver' => $receiver_id, ':content' => $content));
    }

    //Lade neue Nachrichten
    public static function NewMessage($receiver_id, $sender_id){
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

    public static function CountUnreadMessagesForChat($receiver_id, $sender_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
    
        $sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE receiver = :receiver_id AND sender = :sender_id AND `Viewd` = 0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver_id' => $receiver_id, ':sender_id' => $sender_id));
    
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['unread_count'];
    }

    public static function isChatRead($sender_id, $receiver_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
    
        $sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE sender = :sender_id AND receiver = :receiver_id AND `Viewd` = 0";
        $query = $database->prepare($sql);
        $query->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));
    
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['unread_count'] == 0; //Bei 0 = alle nachrichten glesen
    }
    










    //Gruppenchat NOCH NICHT FERTIG
    public static function getGroupMessages($group_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM messages WHERE group_id = :group_id";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id));

        $all_messages = array();

        foreach ($query->fetchAll() as $content) {
            $all_messages[$content->ID] = new stdClass();
            $all_messages[$content->ID]->content = $content->content;
            $all_messages[$content->ID]->sender = UserModel::getPublicProfileOfUser($content->sender);
            $all_messages[$content->ID]->receiver = UserModel::getPublicProfileOfUser($content->receiver);
        }

        return $all_messages;
    }

    public static function addGroupMessage($group_id, $sender_id, $content) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO messages (group_id, content, Viewd, sender, receiver) VALUES (:group_id, :content, :Viewd, :sender, :receiver)";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id, ':sender' => $sender_id, ':Viewd' => 0, ':receiver' => 0, ':content' => $content));
    }

    public static function isGroupChatRead($group_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE group_id = :group_id AND `Viewd` = 0";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id));

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['unread_count'] == 0; // Bei 0 = alle Nachrichten gelesen
    }
    


}



?>