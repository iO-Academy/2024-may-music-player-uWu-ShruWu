<?php

declare(strict_types=1);

class DisplayPlayButtonService {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public static function playCount($artistId, $songId): string
    {
        $playButton = "<a href='?id={$artistId}&playSong={$songId}' class='hover:text-slate-500 hover:cursor-pointer'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6 inline'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'></path>
                                <path stroke-linecap='round' stroke-linejoin='round' d='M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z'></path>
                            </svg>
                       </a>";

        return $playButton;
    }
}