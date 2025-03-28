<?php
 

namespace App\Khranilischa\ProverkaNomera;

class ProverkaNomeraProstoObjiect
{

    public function __construct(
        public readonly string $phone,
        public readonly string $code,
        public readonly string $token,
        public readonly string $ip,
        public readonly ?string $user_agent,
        public readonly ?string $service,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            $data['phone'],
            $data['code'],
            $data['token'],
            $data['ip'],
            $data['user_agent'] ?? null,
            $data['service'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
            'code' => $this->code,
            'token' => $this->token,
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'service' => $this->service,
            'attempts_count' => 1 // Todo
        ];
    }
}