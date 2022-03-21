<?php

class ScanMapper
{
    /**
     * Get the last scan id for each anime in the given country
     *
     * @param PDO $pdo The PDO object that we created earlier.
     * @param string $country The country tag of the anime you want to get the last ids from.
     *
     * @return array|false An array of the last scan id for each anime.
     */
    static function getLastIds(PDO $pdo, string $country) {
        $request = $pdo->prepare("SELECT scans.id
FROM jais.scans
         INNER JOIN jais.animes a on scans.anime_id = a.id
         INNER JOIN jais.countries c on a.country_id = c.id
WHERE c.tag = :country
ORDER BY scans.release_date DESC, scans.anime_id DESC, scans.number DESC, scans.id_episode_type DESC,
         scans.id_lang_type DESC");
        $request->execute(array('country' => $country));
        return $request->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get all the scans with the given ids
     *
     * @param PDO $pdo The PDO object that will be used to execute the query.
     * @param string $country The country code of the language you want to get.
     * @param string|null $ids The ids of the scans you want to get.
     *
     * @return array|false An array of arrays. Each array contains the following:
     *     - platform
     *     - platform_url
     *     - platform_image
     *     - anime_id
     *     - anime
     *     - anime_image
     *     - release_date
     *     - number
     *     - episode_type
     *     - lang_type
     *     - url
     */
    static function getScansWithIds(PDO $pdo, string $country, ?string $ids)
    {
        if ($ids == null && empty($ids))
            return [];

        $request = $pdo->prepare("SELECT p.name             AS platform,
       p.url              AS platform_url,
       p.image            AS platform_image,
       a.id               AS anime_id,
       a.name             AS anime,
       a.image            AS anime_image,
       scans.release_date AS release_date,
       scans.number       AS number,
       et.$country        AS episode_type,
       lt.$country        AS lang_type,
       scans.url          AS url
FROM jais.scans
         INNER JOIN jais.platforms p on scans.platform_id = p.id
         INNER JOIN jais.animes a on scans.anime_id = a.id
         INNER JOIN jais.episode_types et on scans.id_episode_type = et.id
         INNER JOIN jais.lang_types lt on lt.id = scans.id_lang_type
WHERE scans.id IN ($ids)
ORDER BY scans.release_date DESC, anime_id DESC, number DESC, id_episode_type DESC, id_lang_type DESC");
        $request->execute(array());
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
}