<?php
class Config
{
    protected int $noteLimitPerSite = 10;

    public function setLimit(int $noteLimitPerSite): void
    {
        $this->noteLimitPerSite = $noteLimitPerSite;
    }

    public function getLimit(): int
    {
        return $this->noteLimitPerSite;
    }

    public function setDb(): array
    {
        return [
            'dbn' => 'mysql:host=mariadb;dbname=my-note-oop',
            'user' => 'root',
            'password' => 'mariaadmin',
        ];
    }

    public function getSortings():array
    {
        return [
            0 => [
                'label' => 'update latest',
                'sql' => 'update_time DESC',
            ],
            1 => [
                'label' => 'update oldest',
                'sql' => 'update_time ASC',
            ],
            2 => [
                'label' => 'title A-Z',
                'sql' => 'title ASC',
            ],
            3 => [
                'label' => 'title Z-A',
                'sql' => 'title DESC',
            ],
            4 => [
                'label' => 'color',
                'sql' => 'bg_color ASC',
            ],
        ];
    }

    public function getBgColors(): array
    {
        return [
            0 => [
                'label' => 'purple',
                'class' => 'bg-primary',
            ],
            1 => [
                'label' => 'green',
                'class' => 'bg-success',
            ],
            2 => [
                'label' => 'red',
                'class' => 'bg-danger',
            ],
            3 => [
                'label' => 'gray',
                'class' => 'bg-secondary',
            ],
        ];
    }
}