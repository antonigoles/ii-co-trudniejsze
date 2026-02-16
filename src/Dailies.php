<?php

namespace App;

class Dailies 
{
    /**
     * Progresses daily for user and returns today streak
     * @return int
     */
    public static function progress_user(): int
    {
        // TODO: Finish this

        if (OAuth::should_reauthenticate()) {
            throw new \Exception('Auth error');
        }

        $user_id = OAuth::fetch_user_id();
        $connection = DatabaseConnection::get();

        $result = $connection->query(
            'SELECT * FROM daily_progress WHERE owner_id = :user_id', 
            [
                "user_id" => $user_id
            ]
        );
        
        $todays_streak = 0;

        if (count($result) <= 0) {
            // we need to create new record
            $result = $connection->query(
                'INSERT INTO daily_progress 
                VALUES (:user_id, 0, 0, NOW())
            ',
                [
                    "user_id" => $user_id
                ]
            );
        } else {
            $last_update_rounded_to_a_day = floor(intval($result['last_update']) / (24 * 60 * 60));
            $today_rounded_to_a_day = floor( time() / (24 * 60 * 60));

            if ($last_update_rounded_to_a_day == $today_rounded_to_a_day) {
                $todays_streak = $result['todays_progress'];
            }
        }

        $todays_streak++;

        $connection->query(
            'UPDATE daily_progress 
            SET todays_progress
            VALUES (:user_id, 0, 0, NOW())
        ',
            [
                "user_id" => $user_id
            ]
        );

        return $todays_streak;
    }
}

?>