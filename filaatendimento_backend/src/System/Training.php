<?php

namespace App\System;

class Training
{

    private $table = "training";
    private $id;
    private $title;
    private $url_thumb;
    private $url_video;
    private $content;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getUrlThumb()
    {
        return $this->url_thumb;
    }

    public function setUrlThumb($url_thumb)
    {
        $this->url_thumb = $url_thumb;

        return $this;
    }

    public function getUrlVideo()
    {
        return $this->url_video;
    }

    public function setUrlVideo($url_video)
    {
        $this->url_video = $url_video;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }



    public function store()
    {

        try {

            $sql = "INSERT INTO $this->table (title,url_thumb,url_video,content) VALUES (:title,:url_thumb,:url_video,:content)";

            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":title", $this->getTitle(), PDO::PARAM_STR);
            $statement->bindValue(":url_thumb", $this->getUrlThumb(), PDO::PARAM_STR);
            $statement->bindValue(":url_video", $this->getUrlVideo(), PDO::PARAM_STR);
            $statement->bindValue(":content", $this->getContent(), PDO::PARAM_STR);
            $statement->execute();

            return $statement->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete()
	{
		$sql = "DELETE FROM $this->table WHERE id = :id";

		$statement = Conexao::getInstance()->prepare($sql);
		$statement->bindValue(":id", $this->getId(), PDO::PARAM_INT);
		$statement->execute();

		return $statement->rowCount();
	}

    public function all($campos = null, $where = null, $order_by = null, $limit = null)
	{

		$campos 	= (!$campos ?  '*' : $campos);
		$where 		= (!$where ? '' : $where);
		$order_by 	= (!$order_by ? 'ORDER BY created_at DESC' : $order_by);
		$limit 		= (!$limit ? '' : $limit);

		$sql = "SELECT $campos FROM $this->table $where $order_by $limit";

		$statement = Conexao::getInstanceSlave()->prepare($sql);
		$result = $statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    public function id($id, $campos = null, $where = null)
	{

		$campos 	= (!$campos ?  '*' : $campos);
		$where 		= (!$where ? '' : $where);

		$sql = "SELECT $campos FROM $this->table WHERE id=:id $where";

		$statement = Conexao::getInstanceSlave()->prepare($sql);
        
        $statement->bindValue(":id", $id, PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetch(PDO::FETCH_ASSOC);
	}


    public function update()
	{

		$sql = "UPDATE $this->table SET title = :title, url_thumb = :url_thumb, url_video= :url_video, content = :content  WHERE id=:id";

		$statement = Conexao::getInstance()->prepare($sql);
		$statement->bindValue(":id", $this->getId(), PDO::PARAM_INT);
        $statement->bindValue(":title", $this->getTitle(), PDO::PARAM_STR);
		$statement->bindValue(":url_thumb", $this->getUrlThumb(), PDO::PARAM_STR);
		$statement->bindValue(":url_video", $this->getUrlVideo(), PDO::PARAM_STR);
        $statement->bindValue(":content", $this->getContent(), PDO::PARAM_STR);
		$statement->execute();

		return $statement->rowCount();
	}


}
