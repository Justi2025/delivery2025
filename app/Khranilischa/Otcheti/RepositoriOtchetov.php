<?php
 

namespace App\Khranilischa\Otcheti;

use App\Models\Sdelki;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Servsi\Otcheti\Prosto\FiltrOtchetovProsto;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RepositoriOtchetov
{
    private const PAGINATION_PER_PAGE = 15;

    /**
     * Reports payments by all methods by day and pickup point
     *
     * @param ?FiltrOtchetovProsto $filter
     * @return LengthAwarePaginator
     */
    public function getPaymentsByDayAndPickupPoints(?FiltrOtchetovProsto $filter = null): LengthAwarePaginator
    {
        $query =  self::getPaymentsByDayAndPickupPointsQb();

        if($filter)
        {
            if ($filter->day !== null) {
                $column = DB::raw('DATE(o.issued_at)');
                $query->where($column, $filter->day);
            }

            if ($filter->office) {
                $query->where('o.shipping_to_id', $filter->office);
            }
        }

        return $query
            ->groupBy('day', 'o.shipping_to_id', 'office')
            ->orderBy('day')
            ->paginate(self::PAGINATION_PER_PAGE);
    }


    public function getPaymentsByDay(FiltrOtchetovProsto $filter): LengthAwarePaginator
    {
        $query = self::getPaymentsForIssuedOrdersQb();

        if ($filter->day !== null) {
            $column = DB::raw('DATE(o.issued_at)');
            $query->where($column, $filter->day);
        }

        return $query->groupBy('day')->orderBy('day')->paginate(self::PAGINATION_PER_PAGE);
    }


    public function getDebtsByCustomers()
    {
        return self::getDebtsByCustomersQb()->paginate();
    }


    public static function getPaymentsByDayAndPickupPointsQb(): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return Sdelki::from('orders as o')
            ->select(
                DB::raw('DATE(o.issued_at) as day'),
                'o.shipping_to_id',
                DB::raw('CONCAT(dp.city, ", ", dp.street, " ", dp.house, ", ", dp.flat) as office'),
                DB::raw('SUM(o.price) as total_to_pay'),
                DB::raw('SUM(o.amount_paid_cash) as total_cash'),
                DB::raw('SUM(o.amount_paid_cashless) as total_cashless'),
                DB::raw('SUM(o.amount_paid_bonus) as total_bonus'),
                DB::raw('SUM(o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus) as total_paid'),
                DB::raw('SUM(o.price - (o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus)) as total_debt')
            )
            ->leftJoin('delivery_points as dp', 'o.shipping_to_id', '=', 'dp.id')
            ->whereIn('o.status', [StatusiSdelok::Vidan, StatusiSdelok::VidanOplachenChastichno]);

    }


    public static function getPaymentsForIssuedOrdersQb(): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return Sdelki::from('orders as o')
            ->select(
                DB::raw('DATE(o.issued_at) as day'),
                DB::raw('SUM(o.price) as total_to_pay'),
                DB::raw('SUM(o.amount_paid_cash) as total_cash'),
                DB::raw('SUM(o.amount_paid_cashless) as total_cashless'),
                DB::raw('SUM(o.amount_paid_bonus) as total_bonus'),
                DB::raw('SUM(o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus) as total_paid'),
                DB::raw('SUM(o.price - (o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus)) as total_debt')
            )
            ->leftJoin('delivery_points as dp', 'o.shipping_to_id', '=', 'dp.id')
            ->whereIn('o.status', [StatusiSdelok::Vidan, StatusiSdelok::VidanOplachenChastichno]);
    }


    public function getDebtsByCustomersQb(): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return DB::table('orders as o')
            ->leftJoin('users as customers', 'customers.id', '=', 'o.client_id')
            ->select([
                'customers.id as customer_id',
                'customers.full_name',
                'o.id as order_id',
                DB::raw('(o.price + o.client_debt) as total_price'),
                DB::raw('(o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus) as total_paid'),
                DB::raw('(o.price + o.client_debt) - (o.amount_paid_cash + o.amount_paid_cashless + o.amount_paid_bonus) as debt')
            ])
            ->where('o.status', '=', StatusiSdelok::VidanOplachenChastichno);
    }
}