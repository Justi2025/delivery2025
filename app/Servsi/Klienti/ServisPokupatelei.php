<?php
 

namespace App\Servsi\Klienti;

use App\Khranilischa\BonusiKlientov\BonusRepo;
use App\Khranilischa\Klienti\CustomersRepositoryInterface;
use App\Khranilischa\Klienti\Dtos\PokupatelProstoObiect;
use App\Khranilischa\Klienti\Dtos\PoiskPokupaliaProstoObiect;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use App\Servsi\Faili\ServisZagruzkiFailov;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class ServisPokupatelei
{
    public function __construct(
        private readonly CustomersRepositoryInterface $customersRepository,
        private readonly BonusRepo                    $bonusRepository,
        private readonly ServisZagruzkiFailov         $fileUploadService

    )
    {
    }

    public function poluchitVsekhPostranichno()
    {
        return $this->customersRepository->poluchitFsekhPokupatelei();
    }


    public function sozdatKlienta(PokupatelProstoObiect $customer, ZaloginenniPolzovatel $user, ?UploadedFile $file)
    {
        $customer
            ->setPassword(Hash::make($customer->getPassword()))
            //->setPhoneVerifiedAt(time())
            ->setCreatorId($user->id);

        if($file) {
            $uploaded = $this->fileUploadService->zagruzit($file, $user);
            $customer->setPassportImage($uploaded->generated_name);
        }

        return $this->customersRepository->sozdat($customer);
    }


    /**
     * Find customer by search criteria
     *
     * @param PoiskPokupaliaProstoObiect $searchDto
     * @return mixed
     */
    public function naitiPokupatelia(PoiskPokupaliaProstoObiect $searchDto)
    {
        return $this->customersRepository->naitiPoUsloviu($searchDto);
    }


    public function obnovit(PokupatelProstoObiect $customerDataDto)
    {
        // Todo: what if manager will try to reset admin password?
        return $this->customersRepository->obnovitKlienta($customerDataDto);
    }


    public function poluchitKlientaPoIdentifikatory(int $id): PokupatelProstoObiect
    {
        return $this->customersRepository->naitiKlientaPoIdentificatoru($id);
    }


    /**
     * @throws \Exception
     */
    public function polushitIstoriuBonusPokupatelia(int $customer_id): LengthAwarePaginator
    {
        return $this->bonusRepository->getCustomerBonusHistory($customer_id);
    }
}