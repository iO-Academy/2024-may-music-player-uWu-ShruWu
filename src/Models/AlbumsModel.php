<?php

declare(strict_types=1);

class AlbumsModel {
    public PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Album[]
     */
    public function getAlbumsByArtistId(int $artistId): array
    {
        $query = $this->db->prepare('SELECT `albums`.`id` AS "albumId", `albums`.`album_name` AS "albumName",  
        `albums`.`artwork_url` AS "artworkURL", `albums`.`artist_id` AS "artistId" FROM `albums` 
        WHERE `artist_id` = :artistId;');
        $query->setFetchMode(PDO::FETCH_CLASS, Album::class);
        $query->execute(['artistId' => $artistId]);

        $albumsResults = $query->fetchAll();

        $songquery = $this->db->prepare("SELECT `album_id`, COUNT(`album_id`) AS 'song_count' FROM `songs` GROUP BY `album_id`;");
        $songquery->execute();
        $songresults = $songquery->fetchAll();

        foreach ($albumsResults as $album)
        {
            foreach ($songresults as $song)
            {
                if ($album->getAlbumId() === $song['album_id'])
                {
                    $album->setSongCount($song['song_count']);
                }
            }
        }
        return $albumsResults;
    }

    /**
     * @return Album[]
     */
    public function getMostPlayedAlbums(): array
    {
        $query = $this->db->prepare('SELECT SUM(songs.play_count) AS "playCount", `songs`.`album_id` AS "albumId", `albums`.`album_name` AS "albumName", 
       `albums`.`artwork_url` AS "artworkURL", `albums`.`artist_id` AS "artistId" FROM `songs`
        INNER JOIN `albums`
        ON `songs`.`album_id` = `albums`.`id`
        GROUP BY `album_id`
        ORDER BY SUM(songs.play_count) DESC
        LIMIT 5;');
        $query->setFetchMode(PDO::FETCH_CLASS, Album::class);
        $query->execute();
        return $query->fetchAll();
    }
}

