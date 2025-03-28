<?php
 

namespace App\Http\Services;

use App\Models\UserActivityLog;
use App\Khranilischa\RepositoriiDeistviiPolzovatelei;
use Illuminate\Http\Request;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;

class ServicDeistviiPolzovatelei
{
    public function __construct(
        private readonly RepositoriiDeistviiPolzovatelei $loggingRepository,
        private readonly Device                          $device,
        private readonly Browser                         $browser,
        private readonly Os                              $os,
    )
    {
    }


    public function log(Request $request, int $user_id): ?UserActivityLog
    {
        $action = $this->userActionLog($request, $user_id);

        if ($action) {
            return $this->loggingRepository->save($action);
        }

        return null;
    }


    public function userActionLog(Request $request, int $user_id): array
    {
        $route = $request->route();
        $is_login_action = $route->getName() === 'auth.login';

        $data = $is_login_action ? [] : collect($request->request->all())->map(static function ($value) {
            return mb_substr(json_encode($value), 0, 32);
        });

        $referer = str_starts_with($request->header('referer'), env('APP_URL'))
            ? str_replace(env('APP_URL'), '', $request->header('referer'))
            : $request->header('referer');


        $device = '';
        if (preg_match('/\(.*?\)/', $request->userAgent(), $matches)) {
            $device = $matches[0] ?? 'device.unknown';
        }

        if ($route->getName()) {
            return [
                'user_id' => $user_id,
                'action_name' => $route->getName(),
                'os' => "$device {$this->os->getName()} {$this->os->getVersion()}",
                'browser' => "{$this->browser->getName()} {$this->browser->getVersion()}",
                'user_agent' => $request->userAgent(),
                'client_ip' => $request->getClientIp(),
                'url' => $request->path(),
                'referer' => $referer,
                'data' => $data,
            ];
        }

        return [];
    }
}
