<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\MenuPrice;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:reservation,sales,inventory,crm',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        switch ($reportType) {
            case 'reservation':
                return $this->generateReservationReport($startDate, $endDate);
            case 'sales':
                return $this->generateSalesReport($startDate, $endDate);
            case 'inventory':
                return $this->generateInventoryReport($startDate, $endDate);
            case 'crm':
                return $this->generateCrmReport($startDate, $endDate);
            default:
                abort(400, 'Invalid report type');
        }
    }

    private function generateReservationReport($startDate, $endDate)
    {
        $reservations = Reservation::with(['user'])
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date')
            ->get();

        $reservationData = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'event_name' => $reservation->event_name,
                'event_date' => $reservation->event_date->format('Y-m-d'),
                'customer_name' => $reservation->user->name,
                'department' => $reservation->user->department,
                'number_of_persons' => $reservation->number_of_persons,
                'status' => ucfirst($reservation->status),
                'created_at' => $reservation->created_at->format('Y-m-d H:i'),
            ];
        });

        return view('admin.reports.show', compact(
            'reservationData',
            'startDate',
            'endDate'
        ))->with('reportType', 'reservation');
    }

    private function generateSalesReport($startDate, $endDate)
    {
        // Get approved reservations within date range
        $reservations = Reservation::with(['items.menu', 'user'])
            ->where('status', 'approved')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->get();

        // Calculate sales data
        $salesData = [];
        $totalRevenue = 0;
        $totalReservations = $reservations->count();

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

            $totalRevenue += $reservationTotal;
        }

        $salesData = collect($salesData);

        return view('admin.reports.show', compact(
            'salesData',
            'totalRevenue',
            'totalReservations',
            'startDate',
            'endDate'
        ))->with('reportType', 'sales');
    }

    private function generateInventoryReport($startDate, $endDate)
    {
        // Get approved reservations within date range
        $reservations = Reservation::with(['items.menu.items.recipes.inventoryItem'])
            ->where('status', 'approved')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->get();

        $inventoryUsage = [];

        foreach ($reservations as $reservation) {
            foreach ($reservation->items as $reservationItem) {
                $menu = $reservationItem->menu;
                foreach ($menu->items as $menuItem) {
                    foreach ($menuItem->recipes as $recipe) {
                        $inventoryItem = $recipe->inventoryItem;
                        $usedQuantity = $recipe->quantity * $reservationItem->quantity;

                        if (!isset($inventoryUsage[$inventoryItem->id])) {
                            $inventoryUsage[$inventoryItem->id] = [
                                'name' => $inventoryItem->name,
                                'unit' => $inventoryItem->unit,
                                'total_used' => 0,
                                'reservations_count' => 0,
                            ];
                        }

                        $inventoryUsage[$inventoryItem->id]['total_used'] += $usedQuantity;
                        $inventoryUsage[$inventoryItem->id]['reservations_count']++;
                    }
                }
            }
        }

        $inventoryData = collect($inventoryUsage)->values();

        return view('admin.reports.show', compact(
            'inventoryData',
            'startDate',
            'endDate'
        ))->with('reportType', 'inventory');
    }

    private function generateCrmReport($startDate, $endDate)
    {
        $customers = \App\Models\User::where('role', 'customer')
            ->with(['reservations' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('event_date', [$startDate, $endDate]);
            }])
            ->get();

        $crmData = $customers->map(function ($customer) {
            $totalReservations = $customer->reservations->count();
            $approvedReservations = $customer->reservations->where('status', 'approved')->count();
            $totalSpent = $customer->reservations->where('status', 'approved')->sum(function ($reservation) {
                return $reservation->items->sum(function ($item) {
                    $price = MenuPrice::getPriceMap()[$item->menu->type][$item->menu->meal_time] ?? 0;
                    return $price * $item->quantity;
                });
            });

            return [
                'name' => $customer->name,
                'email' => $customer->email,
                'total_reservations' => $totalReservations,
                'approved_reservations' => $approvedReservations,
                'total_spent' => $totalSpent,
                'last_reservation' => $customer->reservations->max('event_date')?->format('Y-m-d') ?? 'N/A',
            ];
        })->filter(function ($customer) {
            return $customer['total_reservations'] > 0;
        });

        return view('admin.reports.show', compact(
            'crmData',
            'startDate',
            'endDate'
        ))->with('reportType', 'crm');
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:reservation,sales,inventory,crm',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $viewData = compact('startDate', 'endDate');
        $filename = $reportType . '_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        switch ($reportType) {
            case 'reservation':
                $reservations = Reservation::with(['user'])
                    ->whereBetween('event_date', [$startDate, $endDate])
                    ->orderBy('event_date')
                    ->get();

                $reservationData = $reservations->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'event_name' => $reservation->event_name,
                        'event_date' => $reservation->event_date->format('Y-m-d'),
                        'customer_name' => $reservation->user->name,
                        'department' => $reservation->user->department,
                        'number_of_persons' => $reservation->number_of_persons,
                        'status' => ucfirst($reservation->status),
                        'created_at' => $reservation->created_at->format('Y-m-d H:i'),
                    ];
                });

                $viewData['reservationData'] = $reservationData;
                $viewData['reportType'] = 'reservation';
                break;

            case 'sales':
                // Get approved reservations within date range
                $reservations = Reservation::with(['items.menu', 'user'])
                    ->where('status', 'approved')
                    ->whereBetween('event_date', [$startDate, $endDate])
                    ->get();

                // Calculate sales data
                $salesData = [];
                $totalRevenue = 0;
                $totalReservations = $reservations->count();

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

                    $totalRevenue += $reservationTotal;
                }

                $salesData = collect($salesData);

                $viewData['salesData'] = $salesData;
                $viewData['totalRevenue'] = $totalRevenue;
                $viewData['totalReservations'] = $totalReservations;
                $viewData['reportType'] = 'sales';
                break;

            case 'inventory':
                // Get approved reservations within date range
                $reservations = Reservation::with(['items.menu.items.recipes.inventoryItem'])
                    ->where('status', 'approved')
                    ->whereBetween('event_date', [$startDate, $endDate])
                    ->get();

                $inventoryUsage = [];

                foreach ($reservations as $reservation) {
                    foreach ($reservation->items as $reservationItem) {
                        $menu = $reservationItem->menu;
                        foreach ($menu->items as $menuItem) {
                            foreach ($menuItem->recipes as $recipe) {
                                $inventoryItem = $recipe->inventoryItem;
                                $usedQuantity = $recipe->quantity * $reservationItem->quantity;

                                if (!isset($inventoryUsage[$inventoryItem->id])) {
                                    $inventoryUsage[$inventoryItem->id] = [
                                        'name' => $inventoryItem->name,
                                        'unit' => $inventoryItem->unit,
                                        'total_used' => 0,
                                        'reservations_count' => 0,
                                    ];
                                }

                                $inventoryUsage[$inventoryItem->id]['total_used'] += $usedQuantity;
                                $inventoryUsage[$inventoryItem->id]['reservations_count']++;
                            }
                        }
                    }
                }

                $inventoryData = collect($inventoryUsage)->values();

                $viewData['inventoryData'] = $inventoryData;
                $viewData['reportType'] = 'inventory';
                break;

            case 'crm':
                $customers = \App\Models\User::where('role', 'customer')
                    ->with(['reservations' => function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('event_date', [$startDate, $endDate]);
                    }])
                    ->get();

                $crmData = $customers->map(function ($customer) {
                    $totalReservations = $customer->reservations->count();
                    $approvedReservations = $customer->reservations->where('status', 'approved')->count();
                    $totalSpent = $customer->reservations->where('status', 'approved')->sum(function ($reservation) {
                        return $reservation->items->sum(function ($item) {
                            $price = MenuPrice::getPriceMap()[$item->menu->type][$item->menu->meal_time] ?? 0;
                            return $price * $item->quantity;
                        });
                    });

                    return [
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'total_reservations' => $totalReservations,
                        'approved_reservations' => $approvedReservations,
                        'total_spent' => $totalSpent,
                        'last_reservation' => $customer->reservations->max('event_date')?->format('Y-m-d') ?? 'N/A',
                    ];
                })->filter(function ($customer) {
                    return $customer['total_reservations'] > 0;
                });

                $viewData['crmData'] = $crmData;
                $viewData['reportType'] = 'crm';
                break;
        }

        $pdf = Pdf::loadView('admin.reports.pdf', $viewData);

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:reservation,sales,inventory,crm',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $filename = $reportType . '_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';

        switch ($reportType) {
            case 'reservation':
                return Excel::download(new \App\Exports\ReservationReportExport($startDate, $endDate), $filename);
            case 'sales':
                return Excel::download(new SalesReportExport($startDate, $endDate), $filename);
            case 'inventory':
                return Excel::download(new \App\Exports\InventoryReportExport($startDate, $endDate), $filename);
            case 'crm':
                return Excel::download(new \App\Exports\CrmReportExport($startDate, $endDate), $filename);
            default:
                abort(400, 'Invalid report type');
        }
    }
}
