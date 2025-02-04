<?php
declare(strict_types=1);

require_once 'src/Models/ArtistsModel.php';
require_once 'src/Entities/Artist.php';
require_once 'src/Entities/Album.php';
require_once 'src/Models/AlbumsModel.php';
require_once 'src/Services/DisplayThreeArtistsService.php';


use PHPUnit\Framework\TestCase;

class DisplayThreeArtistsServiceTest extends TestCase{

    public function testDisplayThreeArtistsService(): void {

        $albumMock = $this->createMock(Album::class);
        $albumMock->method('getArtworkURL')->willReturn('www.google.com');
        $albumsModelMock = $this->createMock(AlbumsModel::class);
        $albumsModelMock->method('getAlbumsByArtistId')->willReturn([$albumMock]);

        $artistMock = $this->createMock(Artist::class);
        $artistMock->method('getArtistName')->willReturn('bob');
        $artistMock->method('getId')->willReturn(3);
        $artistMock->method('getAlbumCount')->willReturn(2);

        $result = DisplayThreeArtistsService::displayThreeArtistsService([$artistMock],
            $albumsModelMock);

        $expected = "<a href='artist.php?id=3' class='rounded bg-cyan-950 p-3 hover:bg-cyan-800 hover:cursor-pointer'>
                        <div class='flex gap-2 h-8'><img class='rounded' src='www.google.com' /></div>
                     <h4 class='text-xl font-bold'>bob</h4>
                      <p>2 Albums</p>
                     </a>";

        $this->assertEquals($expected,$result);
    }
}
