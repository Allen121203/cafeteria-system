<?php

namespace App\Exports;

use App\Models\Reservation;
use App\Models\MenuPrice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate->startOfDay();
        $this->endDate = $endDate->endOfDay();
    }

    public function collection()
    {
        $reservations = Reservation::with(['items.menu', 'user'])
            ->where('status', 'approved')
            ->whereBetween('event_date', [$this->startDate, $this->endDate])
            ->get();

        // Calculate sales data similar to controller
        $salesData = [];

        foreach ($reservations as $reservation) {
            $reservationTotal = 0;
            $items = [];

            foreach ($reservation->items as $item) {
                $price = MenuPrice::getPriceMap()[$item->menu->type][$item->menu->meal_time] ?? 0;
                $itemTotal = $price * $item->quantity;
                $reservationTotal += $itemTotal;

                $items[] = [
                    'menu_name' => $item->menu->name,
                    'type' => ucfirst($item->menu->type),
                    'meal_time' => ucfirst(str_replace('_', ' ', $item->menu->meal_time)),
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total' => $itemTotal,
                ];
            }

            $salesData[] = [
                'reservation_id' => $reservation->id,
                'event_name' => $reservation->event_name,
                'event_date' => $reservation->event_date->format('Y-m-d'),
                'customer_name' => $reservation->user->name,
                'number_of_persons' => $reservation->number_of_persons,
                'items' => $items,
                'reservation_total' => $reservationTotal,
            ];
        }

        return collect($salesData);
    }

    public function headings(): array
    {
        return [
            'Reservation ID',
            'Event Name',
            'Event Date',
            'Customer Name',
            'Number of Persons',
            'Menu Item',
            'Type',
            'Meal Time',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Reservation Total',
        ];
    }

    public function map($reservation): array
    {
        $rows = [];

        foreach ($reservation['items'] as $item) {
            $rows[] = [
                $reservation['reservation_id'],
                $reservation['event_name'],
                $reservation['event_date'],
                $reservation['customer_name'],
                $reservation['number_of_persons'],
                $item['menu_name'],
                $item['type'],
                $item['meal_time'],
                $item['quantity'],
                $item['unit_price'],
                $item['total'],
                $reservation['reservation_total']
            ];
        }

        // If no items, add a row with reservation info and 0 total
        if (empty($rows)) {
            $rows[] = [
                $reservation['reservation_id'],
                $reservation['event_name'],
                $reservation['event_date'],
                $reservation['customer_name'],
                $reservation['number_of_persons'],
                '',
                '',
                '',
                '',
                '',
                '',
                0,
            ];
        }

        return $rows[0];
    }
}
