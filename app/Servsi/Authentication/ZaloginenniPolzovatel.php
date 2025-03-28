<?php
 

namespace App\Servsi\Authentication;

use App\Khranilischa\RoliPolzovatelei;

class ZaloginenniPolzovatel
{

    public function __construct(
        public readonly int              $id,
        public readonly RoliPolzovatelei $role,
        public readonly bool             $is_vip = false,
        public readonly ?int             $office_id = null
    )
    {
    }

    // Todo: check types
    public function isAdmin(): bool
    {
        return $this->role === RoliPolzovatelei::Ahmad;
    }

    public function isManager(): bool
    {
        return $this->role === RoliPolzovatelei::ZamAhmada;
    }

    public function isCustomer(): bool
    {
        return $this->role === RoliPolzovatelei::Klient;
    }

    public function isCourier()
    {
        return $this->role === RoliPolzovatelei::Voditel;
    }

    public function isOperator(): bool
    {
        return $this->role === RoliPolzovatelei::Kassir;
    }

    public function isEmployee()
    {
        return in_array($this->role, [
            RoliPolzovatelei::Ahmad, RoliPolzovatelei::ZamAhmada, RoliPolzovatelei::Kassir, RoliPolzovatelei::Voditel, RoliPolzovatelei::Skladovschik
        ]);
    }
}