<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;

class DatabaseService
{
    public static function getRequiredDatabasesConnections($startDate, $endDate): array
    {
        $databases = [];

        if ($startDate >= "2017-03-29" && $startDate <= "2017-08-08") {
            $databases[] = 'vc-db-08Aug2017';
        } elseif ($startDate >= "2017-06-24" && $startDate <= "2017-12-31") {
            $databases[] = 'vc-db-31dec2017';
        } elseif ($startDate >= "2017-12-24" && $startDate <= "2018-06-30") {
            $databases[] = 'vc-db-30jun2018';
        } elseif ($startDate >= "2018-06-30" && $startDate <= "2018-12-30") {
            $databases[] = 'vc-db-31Dec2018';
        } elseif ($startDate >= "2018-09-17" && $startDate <= "2019-03-19") {
            $databases[] = 'vc-db-2019v1';
        } elseif ($startDate >= "2018-12-30" && $startDate <= "2019-06-30") {
            $databases[] = 'vc-db-01Jul2019';
        } elseif ($startDate >= "2019-05-24" && $startDate <= "2019-12-01") {
            $databases[] = 'vc-db-01Dec2019';
        } elseif ($startDate >= "2019-12-21" && $startDate <= "2020-06-28") {
            $databases[] = 'vc-db-28Jun2020';
        } else {
            $databases[] = 'vc-db';
        }

        if ($startDate < "2017-08-08" && $endDate > "2017-08-08") {
            $databases[] = 'vc-db-31dec2017';
        }
        if ($startDate < "2017-12-31" && $endDate > "2017-12-31") {
            $databases[] = 'vc-db-30jun2018';
        }
        if ($startDate < "2018-06-30" && $endDate > "2018-06-30") {
            $databases[] = 'vc-db-31Dec2018';
        }
        if ($startDate < "2018-12-30" && $endDate > "2018-12-30") {
            $databases[] = 'vc-db-2019v1';
        }
        if ($startDate < "2019-03-19" && $endDate > "2019-03-19") {
            $databases[] = 'vc-db-01Jul2019';
        }
        if ($startDate < "2019-06-30" && $endDate > "2019-06-30") {
            $databases[] = 'vc-db-01Dec2019';
        }
        if ($startDate < "2019-12-01" && $endDate > "2019-12-01") {
            $databases[] = 'vc-db-28Jun2020';
        }
        if ($startDate < "2020-06-28" && $endDate > "2020-06-28") {
            $databases[] = 'vc-db';
        }

        return $databases;
    }

}
