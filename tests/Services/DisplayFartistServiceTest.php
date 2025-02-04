<?php

declare(strict_types=1);

require_once 'src/Services/DisplayFartistService.php';
require_once 'src/Entities/Artist.php';
require_once 'src/Entities/Song.php';
require_once 'src/Models/SongsModel.php';


use PHPUnit\Framework\TestCase;

class DisplayFartistServiceTest extends TestCase {
    public function testDisplayFartist()
    {
        $artistMock = $this->createMock(Artist::class);
        $artistMock->method('getArtistName')->willReturn('bob');
        $artistMock->method('getId')->willReturn(6);

        $songMock = $this->createMock(Song::class);
        $songMock->method('getSongName')->willReturn('song');
        $songMock->method('getPlayCount')->willReturn(50);
        $songMock->method('getLength')->willReturn('3:45');
        $songMock->method('getSongId')->willReturn(24);

        $songsModelMock = $this->createMock(SongsModel::class);
        $songsModelMock->method('getFavouriteSongsByArtist')->willReturn([$songMock]);

        $result = DisplayFartistService::displayFartistSongs([$artistMock], $songsModelMock);

        $expected = "<div class='rounded p-3 bg-cyan-950'>
                        <h4 class='mb-3 text-2xl font-bold'>bob</h4><div class='mx-3 mb-3 flex justify-between items-center'>
                            <div class='w-3/4 pe-3'>
                                <h4 class='font-bold text-lg'>song</h4>
                                <p class='text-sm'>Played 50 times</p>
                            </div>
                            <div class='flex items-center justify-between w-24'>
                                <span class='text-slate-500'>3:45</span>
                                <a href='?playSong=24' class='hover:text-slate-500 hover:cursor-pointer'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6 inline'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'></path>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z'></path>
                                    </svg>
                                </a>
                                <a href='?&id=24' class='hover:text-slate-500 hover:cursor-pointer text-orange-500'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                        <path fill-rule='evenodd' d='M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z' clip-rule='evenodd' />
                                    </svg>
                                </a>
                            </div>
                        </div></div>";

        $this->assertEquals($expected, $result);
    }
}