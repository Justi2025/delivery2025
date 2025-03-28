<?php
 

namespace App\Http\Controllers;

use App\Khranilischa\Logirovanie\Dtos\UserActivityLogsFilterDto;
use App\Khranilischa\RepositoriiDeistviiPolzovatelei;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class GlavniPoLogamPolzovatelei extends Controller
{

    public function __construct(
        private readonly RepositoriiDeistviiPolzovatelei $repositoriiDeistviiPolzovatelei
    )
    {
    }

    public function index(Request $request)
    {
        if($request->isMethod(RequestAlias::METHOD_POST)) {
            return $this->repositoriiDeistviiPolzovatelei->getAllLogs(UserActivityLogsFilterDto::from($request->all()));
        }

        return $this->repositoriiDeistviiPolzovatelei->getAllLogs();
    }

    public function getMostActiveUsers(): array
    {
        return $this->repositoriiDeistviiPolzovatelei->getMosActiveUsers();
    }

    public function show(int $user_id)
    {
        return $this->repositoriiDeistviiPolzovatelei->get($user_id);
    }

    public function getUsersIpInfo()
    {
        return $this->repositoriiDeistviiPolzovatelei->getUsersIpInfo();
    }
}
