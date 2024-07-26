<?php

require_once 'src/Services/DatabaseConnector.php';
require_once 'src/Models/ArtistsModel.php';
require_once 'src/Models/AlbumsModel.php';
require_once 'src/Entities/Artist.php';
require_once 'src/Entities/Album.php';
require_once 'src/Services/DisplayThreeArtistsService.php';
require_once 'src/Services/Choose3ArtistsService.php';
require_once 'src/Models/SongsModel.php';
require_once 'src/Services/DisplayPlayButtonService.php';

$db = DatabaseConnector::connect();

$artistsModel = new ArtistsModel($db);
$artist = new Artist();
$artists = $artistsModel->getAllArtists();
$albumsModel = new AlbumsModel($db);
$album = new Album();
$displayArtists = Choose3ArtistsService::choose3Artists($artists);
$popAlbums = $albumsModel->getMostPlayedAlbums();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="play.js"></script>
</head>
<body>
    <div class="h-screen w-full bg-blue-950 flex text-white">
        <nav class="h-screen border-r bg-cyan-950 border-slate-500 flex flex-col justify-evenly items-center">
            <a href="index.php" class="p-12 hover:text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
            <a href="search.php" class="p-12 hover:text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </a>
            <a href="favourites.php" class="p-12 hover:text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                </svg>
            </a>
            <button id="minimise" class="p-12 hover:text-slate-500 group open">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 group-[.open]:hidden">
                    <path fill-rule="evenodd" d="M2.25 4.5A.75.75 0 0 1 3 3.75h14.25a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Zm14.47 3.97a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 1 1-1.06 1.06L18 10.81V21a.75.75 0 0 1-1.5 0V10.81l-2.47 2.47a.75.75 0 1 1-1.06-1.06l3.75-3.75ZM2.25 9A.75.75 0 0 1 3 8.25h9.75a.75.75 0 0 1 0 1.5H3A.75.75 0 0 1 2.25 9Zm0 4.5a.75.75 0 0 1 .75-.75h5.25a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 group-[:not(.open)]:hidden">
                    <path fill-rule="evenodd" d="M2.25 4.5A.75.75 0 0 1 3 3.75h14.25a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Zm0 4.5A.75.75 0 0 1 3 8.25h9.75a.75.75 0 0 1 0 1.5H3A.75.75 0 0 1 2.25 9Zm15-.75A.75.75 0 0 1 18 9v10.19l2.47-2.47a.75.75 0 1 1 1.06 1.06l-3.75 3.75a.75.75 0 0 1-1.06 0l-3.75-3.75a.75.75 0 1 1 1.06-1.06l2.47 2.47V9a.75.75 0 0 1 .75-.75Zm-15 5.25a.75.75 0 0 1 .75-.75h9.75a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
            </button>
        </nav>
        <main class="group grow h-screen">
            <section class="group-[.minimised]:h-[calc(100%-6rem)] h-3/4 p-12 overflow-auto">
                <h2 class="text-4xl font-bold">Home</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-12 gap-5">
                    <div class="">
                        <h3 class="text-xl font-bold mb-3">Artists</h3>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <?php
                            echo DisplayThreeArtistsService::displayThreeArtistsService($displayArtists, $albumsModel);
                            ?>
                            <div class="rounded bg-cyan-950 p-3 flex items-center">
                                <h4 class="text-2xl text-slate-500">+ 15 more</h4>
                            </div>
                        </div>
                        <a href="artists.php" class="float-right border rounded-md bg-cyan-950 px-2 py-1 hover:bg-cyan-800">See all
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 inline">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="">
                        <h3 class="text-xl font-bold mb-3">Most Popular Albums</h3>
                        <?php
                        foreach($popAlbums as $album)
                        {
                            $artistById = $artistsModel->getArtistById($album->getArtistId());
                            echo "<div class='border-b border-slate-500 pb-5 mb-3 flex justify-between items-center'>
                                <img src='{$album->getArtworkURL()}' class='size-14' />
                                <div class='w-3/4 px-3'>
                                <h4 class='font-bold text-lg'>{$album->getAlbumName()}</h4>
                                <p class='text-sm'>{$artistById->getArtistName()}</p>
                                </div>
                            <a href='artist.php?id={$artistById->getId()}' class='hover:text-slate-500 hover:cursor-pointer'>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />
                                </svg></a>
                                </div>";
                        }
                        ?>
                    </div>
                </div>
                </div>
            </section>
            <section class="group-[.minimised]:py-2 group-[.minimised]:h-24 h-1/4 border-t bg-cyan-950 border-slate-500 p-6">
                <div class="group-[.minimised]:hidden flex justify-between mb-5">
                    <h3 class="text-2xl">Now Playing</h3>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" />
                        </svg>
                    </div>
                </div>
                <div class="flex justify-between items-start md:items-stretch">
                    <img src="https://via.placeholder.com/400x400/386641/6A994E?text=The+Memory+of+Trees" class="group-[.minimised]:hidden hidden sm:block w-20 md:w-32 mr-5" alt="album cover" />
                    <div class="flex justify-between flex-wrap grow content-between">
                        <div class="group-[.minimised]:hidden w-full md:w-auto">
                            <h4 class="text-xl">Song title</h4>
                            <h5>Artist</h5>
                        </div>
                        <div class="group-[.minimised]:w-full group-[.minimised]:p-0 group-[.minimised]:m-0 group-[.minimised]:mb-2 flex justify-between w-full md:w-1/2 md:mr-12 pr-8 pt-3 mb-3">
                            <button class="hover:text-slate-500" id="playPrev">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                                </svg>
                            </button>
                            <button class="hover:text-slate-500" id="play">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5V18M15 7.5V18M3 16.811V8.69c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811Z" />
                                </svg>
                            </button>
                            <button class="hover:text-slate-500" id="playNext">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="w-full flex justify-between">
                            <div class="mr-6" id="playTime">0:00</div>
                            <div class="bg-white rounded-md w-full h-6 overflow-hidden mr-6">
                                <div class="bg-blue-500 w-0 h-full" id="playProgress"></div>
                            </div>
                            <button class="hover:text-slate-500" id="repeat">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </body>
</html>
