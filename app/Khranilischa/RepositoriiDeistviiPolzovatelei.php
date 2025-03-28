<?php
 

namespace App\Khranilischa;

use App\Models\UserActivityLog;
use App\Khranilischa\Logirovanie\Dtos\UserActivityLogsFilterDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RepositoriiDeistviiPolzovatelei
{
    public function get(int $user_id): LengthAwarePaginator
    {
        return DB::table('user_activity_logs', 'logs')
            ->leftJoin('users as u', 'u.id', '=', 'logs.user_id')
            ->where('logs.user_id', '=', $user_id)
            ->select([
                'u.full_name',
                'logs.*'
            ])
            ->orderBy('logs.created_at', 'desc')
            ->paginate();
    }


    public function getUniqueIPs()
    {
        $sql = "
            select distinct logs.client_ip
            from user_activity_logs logs
            order by INET_ATON(logs.client_ip)
        ";

        return DB::select($sql);
    }

    public function getUniqueUrls()
    {
        $sql = "
            select distinct logs.url
            from user_activity_logs logs
            order by 1
        ";

        return DB::select($sql);
    }

    public function getAllLogs(UserActivityLogsFilterDto $filter = null): LengthAwarePaginator|array
    {
        $query = DB::table('user_activity_logs', 'logs')
            ->leftJoin('users as u', 'u.id', '=', 'logs.user_id')
            //->where('role', '!=', 'admin')
            ->orderBy('logs.created_at', 'desc')
            ->select([
                'logs.user_id',
                'u.full_name',
                'u.role as user_role',
                'logs.action_name',
                'logs.os',
                'logs.browser',
                'logs.client_ip',
                'logs.url',
                'logs.referer',
                'logs.created_at'
            ]);


        if(null !== $filter)
        {
            if($filter->users) {
                $query->whereIn('u.id', $filter->users);
            }

            if($filter->actions) {
                $query->whereIn('logs.action_name', $filter->actions);
            }

            if($filter->ips) {
                $query->whereIn('logs.client_ip', $filter->ips);
            }

            if($filter->urls) {
                $query->whereIn('logs.url', $filter->urls);
            }

            if($filter->start_dt) {
                $start = Carbon::createFromTimestamp($filter->start_dt / 1000); // Todo: validation to dto props
                $query->where('logs.created_at', '>=', $start);
            }

            if($filter->end_dt) {
                $end = Carbon::createFromTimestamp($filter->end_dt / 1000);
                $query->where('logs.created_at', '<=', $end);
            }
        }

        return $query->paginate();
    }


    public function getMosActiveUsers(): array
    {
        return DB::select("
            select
                u.id as user_id,
                u.full_name,
                count(logs.id) as activity
            from users u
            left join user_activity_logs logs on logs.user_id = u.id
            where u.status = 'activated' and u.role != 'admin'
            group by u.id, u.full_name
            order by activity desc
        ");
    }

    public function getUsersIpInfo()
    {
        throw new \Exception('Not implemented properly');

        $sql = "
            select
                ips.uid,
                 u.full_name,
                ips.ip
            from (select distinct l.user_id   as uid,
                                  l.client_ip as ip
                  from user_activity_logs l
                  order by l.client_ip) ips
            left join users u on u.id = ips.uid
            order by u.id
        ";

        $rows = collect(DB::select($sql));
        $ips = $rows->pluck('ip');

        // return $ips;

        $headers = [
            "Content-Type: application/json",
            sprintf("Content-Length: %d", strlen(json_encode($ips)))
        ];

        $context = stream_context_create([
            'http' => [
                'header' => join("\r\n", $headers),
                'method' => 'POST',
                'content' => $ips,
                'timeout' => rand(1, 2),
            ]
        ]);

        return json_decode(file_get_contents("https://ipinfo.io/batch?token=d1438d49d83d57", false, $context));

    }

    public function save(array $data): UserActivityLog
    {
        return UserActivityLog::create($data);
    }
}
