<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function ordersReport()
    {
        $data = $this->reportData();

        return view('admin.reports.index', $data);
    }

    public function exportPdf()
    {
        $data = $this->reportData();
        $lines = [];

        $lines[] = 'FOOD ORDERING SYSTEM';
        $lines[] = 'AUTO-GENERATED REPORT';
        $lines[] = 'Generated: ' . now()->format('Y-m-d H:i:s');
        $lines[] = '';
        $lines[] = 'SUMMARY';
        $lines[] = 'Total Sales: PHP ' . number_format($data['totalSales'], 2);
        $lines[] = 'Total Orders: ' . $data['totalOrders'];
        $lines[] = 'Total Customers: ' . $data['totalCustomers'];
        $lines[] = 'Total Menu Items: ' . $data['totalMenuItems'];
        $lines[] = '';
        $lines[] = 'ORDERS BY STATUS';

        foreach ($data['byStatus'] as $status) {
            $lines[] = ucfirst(str_replace('_', ' ', $status->status)) . ': ' . $status->count;
        }

        $lines[] = '';
        $lines[] = 'BEST-SELLING ITEMS';

        foreach ($data['bestSelling'] as $item) {
            $lines[] = ($item->menuItem->name ?? 'Deleted item') . ' - ' . $item->total_qty . ' sold';
        }

        $lines[] = '';
        $lines[] = 'RECENT ORDERS';

        foreach ($data['recent'] as $order) {
            $lines[] = '#' . $order->id
                . ' | ' . ($order->user->name ?? 'Guest')
                . ' | PHP ' . number_format($order->total_amount, 2)
                . ' | ' . ucfirst(str_replace('_', ' ', $order->status))
                . ' | ' . $order->created_at?->format('Y-m-d');
        }

        $pdf = $this->makeSimplePdf($lines);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="auto-generated-report.pdf"',
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $data = $this->reportData();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="auto-generated-report.csv"',
        ];

        return response()->stream(function () use ($data) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['FOOD ORDERING SYSTEM - AUTO GENERATED REPORT']);
            fputcsv($file, ['Generated At', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Total Sales', $data['totalSales']]);
            fputcsv($file, ['Total Orders', $data['totalOrders']]);
            fputcsv($file, ['Total Customers', $data['totalCustomers']]);
            fputcsv($file, ['Total Menu Items', $data['totalMenuItems']]);
            fputcsv($file, []);

            fputcsv($file, ['ORDERS BY STATUS']);
            fputcsv($file, ['Status', 'Count']);

            foreach ($data['byStatus'] as $status) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $status->status)), $status->count]);
            }

            fputcsv($file, []);
            fputcsv($file, ['BEST SELLING ITEMS']);
            fputcsv($file, ['Menu Item', 'Quantity Sold']);

            foreach ($data['bestSelling'] as $item) {
                fputcsv($file, [$item->menuItem->name ?? 'Deleted item', $item->total_qty]);
            }

            fputcsv($file, []);
            fputcsv($file, ['RECENT ORDERS']);
            fputcsv($file, ['Order ID', 'Customer', 'Total Amount', 'Status', 'Date']);

            foreach ($data['recent'] as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Guest',
                    $order->total_amount,
                    ucfirst(str_replace('_', ' ', $order->status)),
                    $order->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    private function reportData(): array
    {
        $totalSales = Order::where('status', 'delivered')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalMenuItems = MenuItem::count();

        $byStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        $recent = Order::with('user', 'orderItems.menuItem')
            ->latest()
            ->limit(10)
            ->get();

        $bestSelling = OrderItem::selectRaw('menu_item_id, sum(quantity) as total_qty')
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered');
            })
            ->with('menuItem')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $bestSellingMax = max(1, (int) $bestSelling->max('total_qty'));
        $statusMax = max(1, (int) $byStatus->max('count'));

        $salesStartDate = now()->subDays(6)->startOfDay();

        $salesRows = Order::selectRaw('DATE(COALESCE(delivered_at, updated_at)) as report_date, SUM(total_amount) as total_sales')
            ->where('status', 'delivered')
            ->whereRaw('DATE(COALESCE(delivered_at, updated_at)) >= ?', [$salesStartDate->toDateString()])
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get()
            ->keyBy('report_date');

        $salesByDate = collect();

        for ($date = $salesStartDate->copy(); $date->lte(now()->startOfDay()); $date->addDay()) {
            $dateKey = $date->toDateString();

            $salesByDate->push((object) [
                'report_date' => $dateKey,
                'total_sales' => (float) ($salesRows[$dateKey]->total_sales ?? 0),
            ]);
        }

        $salesByDateMax = max(1, (float) $salesByDate->max('total_sales'));

        return compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalMenuItems',
            'byStatus',
            'recent',
            'bestSelling',
            'bestSellingMax',
            'statusMax',
            'salesByDate',
            'salesByDateMax'
        );
    }

    private function makeSimplePdf(array $lines): string
    {
        $content = "BT
/F1 16 Tf
50 790 Td
";
        $first = true;

        foreach ($lines as $index => $line) {
            if ($index === 2) {
                $content .= "/F1 10 Tf
";
            }

            if (! $first) {
                $content .= "0 -18 Td
";
            }

            $content .= '(' . $this->escapePdfText($line) . ") Tj
";
            $first = false;
        }

        $content .= "ET";

        $objects = [];
        $objects[] = "1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
";
        $objects[] = "2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
";
        $objects[] = "3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>
endobj
";
        $objects[] = "4 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
";
        $objects[] = "5 0 obj
<< /Length " . strlen($content) . " >>
stream
" . $content . "
endstream
endobj
";

        $pdf = "%PDF-1.4
";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref
0 " . (count($objects) + 1) . "
";
        $pdf .= "0000000000 65535 f 
";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n 
";
        }

        $pdf .= "trailer
<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>
";
        $pdf .= "startxref
" . $xrefOffset . "
%%EOF";

        return $pdf;
    }

    private function escapePdfText(string $text): string
    {
        $text = str_replace('₱', 'PHP ', $text);
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
