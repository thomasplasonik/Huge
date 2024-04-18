<?php
class GroupModel {
    public static function createGroup($group_name) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO groups (group_name) VALUES (:group_name)";
        $query = $database->prepare($sql);
        $query->execute(array(':group_name' => $group_name));

        // Rückgabe der ID der neu erstellten Gruppe
        return $database->lastInsertId();
    }

    public static function addMemberToGroup($group_id, $user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id, ':user_id' => $user_id));
    }

    public static function removeMemberFromGroup($group_id, $user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id, ':user_id' => $user_id));
    }
}
?>