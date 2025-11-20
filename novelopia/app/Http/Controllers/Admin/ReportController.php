<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Novel;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '7_days');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Set date range based on period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case '7_days':
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::now();
                break;
            case '30_days':
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
                break;
            case '90_days':
                $startDate = Carbon::now()->subDays(90);
                $endDate = Carbon::now();
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $startDate = Carbon::parse($startDate);
                    $endDate = Carbon::parse($endDate);
                } else {
                    $startDate = Carbon::now()->subDays(7);
                    $endDate = Carbon::now();
                }
                break;
            default:
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::now();
        }
        
        // Get statistics data
        $stats = $this->getStats($startDate, $endDate);
        $growthData = $this->getGrowthData($startDate, $endDate);
        $topNovels = $this->getTopNovels($startDate, $endDate);
        $userRegistrations = $this->getUserRegistrations($startDate, $endDate);
        
        return view('admin.reports.index', compact(
            'stats', 
            'growthData', 
            'topNovels', 
            'userRegistrations', 
            'startDate', 
            'endDate',
            'period'
        ));
    }

    private function getStats($startDate, $endDate)
    {
        return [
            'total_users' => User::count(),
            'total_novels' => Novel::count(),
            'total_chapters' => Chapter::count(),
            'total_views' => Novel::sum('view_count'),
            'total_likes' => Novel::sum('like_count'),
            'active_users' => User::where('is_active', true)->count(),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_novels' => Novel::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_chapters' => Chapter::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }

    private function getGrowthData($startDate, $endDate)
    {
        $dates = [];
        $userData = [];
        $novelData = [];
        $chapterData = [];
        
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $dates[] = $dateString;
            
            $userData[] = User::whereDate('created_at', $currentDate)->count();
            $novelData[] = Novel::whereDate('created_at', $currentDate)->count();
            $chapterData[] = Chapter::whereDate('created_at', $currentDate)->count();
            
            $currentDate->addDay();
        }
        
        return [
            'dates' => $dates,
            'users' => $userData,
            'novels' => $novelData,
            'chapters' => $chapterData,
        ];
    }

    private function getTopNovels($startDate, $endDate)
    {
        return Novel::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getUserRegistrations($startDate, $endDate)
    {
        $registrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return $registrations;
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'csv');
        $period = $request->get('period', '7_days');
        
        // Generate report data
        $data = $this->generateExportData($period);
        
        if ($type === 'csv') {
            return $this->exportAsCsv($data);
        } elseif ($type === 'pdf') {
            return $this->exportAsPdf($data);
        }
        
        return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
    }

    private function generateExportData($period)
    {
        // Implementation for generating export data
        return [
            'generated_at' => Carbon::now(),
            'period' => $period,
            'stats' => $this->getStats(Carbon::now()->subDays(7), Carbon::now()),
        ];
    }

    private function exportAsCsv($data)
    {
        $filename = 'report_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Write CSV data
        fputcsv($handle, ['Laporan Platform NovelVerse']);
        fputcsv($handle, ['Tanggal Generate', $data['generated_at']->format('d-m-Y H:i:s')]);
        fputcsv($handle, []);
        fputcsv($handle, ['Statistik Umum']);
        fputcsv($handle, ['Total User', $data['stats']['total_users']]);
        fputcsv($handle, ['Total Novel', $data['stats']['total_novels']]);
        fputcsv($handle, ['Total Chapter', $data['stats']['total_chapters']]);
        
        fclose($handle);
        exit;
    }

    private function exportAsPdf($data)
    {
        // For simplicity, we'll return a simple response
        // In production, you would use a PDF library like DomPDF or TCPDF
        return response()->json([
            'message' => 'Fitur PDF akan diimplementasikan',
            'data' => $data
        ]);
    }
}