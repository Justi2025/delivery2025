<?php


namespace App\Http;

enum AuthStatusCode: int
{
    case LoginOrPasswordIncorrect = 0x100;
    case UserNotActivated = 0x101;
}
