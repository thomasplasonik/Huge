<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class NoteModel
{
    /**
     * Get all notes (notes are just example data that the user has created)
     * @return array an array with several objects (the results)
     */
    public static function getAllNotes()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    /**
     * Get a single note
     * @param int $note_id id of the specific note
     * @return object a single object (the result)
     */
    public static function getNote($note_id)
    {
        // Verbindung zur Datenbank herstellen
        $mysqli = new mysqli("localhost", "root", "", "huge");
    
        // Überprüfen der Verbindung
        if ($mysqli->connect_errno) {
            die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
        }
    
        // SQL-Statement vorbereiten
        $stmt = $mysqli->prepare("SELECT user_id, note_id, note_text FROM notes WHERE user_id = ? AND note_id = ? LIMIT 1");
    
        // Parameter binden und Statement ausführen
        $user_id = Session::get('user_id');
        $stmt->bind_param("ii", $user_id, $note_id);
        $stmt->execute();
    
        // Ergebnis abrufen
        $result = $stmt->get_result();
        $note = $result->fetch_assoc();
    
        // Verbindung schließen
        $stmt->close();
        $mysqli->close();
    
        return $note;
    }

    /**
     * Set a note (create a new one)
     * @param string $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public static function createNote($note_text)
    {
        if (!$note_text || strlen($note_text) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        // Prozeduraufruf vorbereiten
        $procedureCall = $database->prepare("CALL insert_note(:note_text, :user_id)");
        $procedureCall->bindParam(':note_text', $note_text, PDO::PARAM_STR);
        $procedureCall->bindParam(':user_id', Session::get('user_id'), PDO::PARAM_INT);

        // Prozeduraufruf ausführen
        $procedureCall->execute();

        // Überprüfen ob die Prozedur erfolgreich aufgerufen wurde
        if ($procedureCall->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_NOTE_SUCCESSFULLY_CREATED'));
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing note
     * @param int $note_id id of the specific note
     * @param string $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function updateNote($note_id, $note_text)
    {
        if (!$note_id || !$note_text) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific note
     * @param int $note_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public static function deleteNote($note_id)
    {
        if (!$note_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

       // Prozeduraufruf vorbereiten
        $procedureCall = $database->prepare("CALL delete_note(:note_id, :user_id)");
        $procedureCall->bindParam(':note_id', $note_id, PDO::PARAM_INT);
        $procedureCall->bindParam(':user_id', Session::get('user_id'), PDO::PARAM_INT);

        // Prozeduraufruf ausführen
        $procedureCall->execute();

        // Überprüfen ob die Prozedur erfolgreich aufgerufen wurde
        if ($procedureCall->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_NOTE_SUCCESSFULLY_DELETED'));
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }
}
