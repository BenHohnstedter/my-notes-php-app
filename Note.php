<?php
class Note
{
    protected int $id = 0;

    protected int $pinned = 0;

    protected string $title = '';
    
    protected string $content = '';

    protected int $bgColor = 0;

    protected string $updateTime = "";

    public function getId():int
    {
        return $this->id;
    }

    public function setPinned(int $pinned):void
    {
        $this->pinned = $pinned;
    }

    public function getPinned():int
    {
        return $this->pinned;
    }

    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    public function getTitle():string
    {
        return htmlspecialchars($this->title);
    }

    public function setContent(string $content):void
    {
        $this->content = $content;
    }

    public function getContent():string
    {
        return htmlspecialchars($this->content);
    }

    public function setBg(string $bgColor):void
    {
        $this->bgColor = $bgColor;
    }

    public function getBg():string
    {
        return $this->bgColor;
    }

    public function setDate(string $updateTime):void
    {
        $this->updateTime = $updateTime;
    }

    public function getDate():string
    {
        return date('H:i - d.m.Y', strtotime($this->updateTime));
    }
}