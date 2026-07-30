<?php
class NoteRepo
{
    protected PDO $db;

    public function __construct(PDO $pdo)
    {
       $this->db = $pdo;
    }

    public function findAll($searchInput = '', $sortOrder = 'title ASC', $offset = 0, $limit = 0):array
    {
        $searched = "%$searchInput%";
        $sql = "SELECT id, pinned, title, LEFT(content, 300) AS content, bg_color AS bgColor, update_time AS updateTime 
                                            FROM notes 
                                            WHERE title LIKE :title OR content LIKE :content
                                            ORDER BY pinned DESC, $sortOrder";
        $sql .= $limit>0?" LIMIT :myoffset,:limit":'';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('title', $searched);
        $stmt->bindParam('content', $searched);
        if ($limit>0) {
            $stmt->bindParam('myoffset', $offset);
            $stmt->bindParam('limit', $limit);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Note');
    }

    public function findById(int $id):Note
    {
        $stmt = $this->db->prepare("SELECT id, pinned, title, content, bg_color AS bgColor, update_time AS updateTime 
                                            FROM notes 
                                            WHERE id = :id");
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Note');

        return $stmt->fetch();
    }

    public function addNoteRepo($title, $content, $bgColor):void
    {
        $stmt = $this->db->prepare("INSERT INTO notes(title, content, bg_color) VALUES (:title,:content,:bgColor)");
        $stmt->bindParam('title', $title);
        $stmt->bindParam('content', $content);
        $stmt->bindParam('bgColor', $bgColor);
        $stmt->execute();
    }

    public function editNoteRepo($noteId, $title, $content, $bgColor):void
    {
        $date = date('Y-m-d H:i');
        $stmt = $this->db->prepare("UPDATE notes SET title = :title, content = :content, bg_color = :bgColor, update_time = :date WHERE id = :id");
        $stmt->bindParam('title', $title);
        $stmt->bindParam('content', $content);
        $stmt->bindParam('bgColor', $bgColor);
        $stmt->bindParam('date', $date);
        $stmt->bindParam('id', $noteId);
        $stmt->execute();
    }

    public function deleteNoteRepo($noteId):void
    {
        $stmt = $this->db->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam('id', $noteId);
        $stmt->execute();
    }

    public function changePinnedState($id, $pinned):void
    {
        if (isset($pinned)) {
            $stmt = $this->db->prepare("UPDATE notes SET pinned = :pinned WHERE id = :id");
            $stmt->bindParam('pinned', $pinned);
            $stmt->bindParam('id', $id);
            $stmt->execute();
        }
    }
}