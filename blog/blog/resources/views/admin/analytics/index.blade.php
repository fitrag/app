@extends('layouts.dashboard')

@section('title', 'Analitik')
@section('subtitle', 'Statistik dan insight blog Anda')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<div style="margin-bottom: 30px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">Dashboard Analitik</h1>
    <p style="color: #64748b; font-size: 14px;">Statistik dan insight blog Anda</p>
</div>
{{-- Kartu Statistik --}}
<div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px; margin-bottom: 32px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; opacity: 0.1; font-size: 80px;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="background: rgba(255, 255, 255, 0.2); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-file-alt" style="font-size: 24px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Total Artikel</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $totalArticles }}</div>
            </div>
        </div>
        <div style="font-size: 12px; opacity: 0.8;">Jumlah total artikel yang telah dipublikasikan</div>
    </div>

    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; opacity: 0.1; font-size: 80px;">
            <i class="fas fa-folder"></i>
        </div>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="background: rgba(255, 255, 255, 0.2); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-folder" style="font-size: 24px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Total Kategori</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $totalCategories }}</div>
            </div>
        </div>
        <div style="font-size: 12px; opacity: 0.8;">Jumlah kategori yang tersedia</div>
    </div>

    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; opacity: 0.1; font-size: 80px;">
            <i class="fas fa-users"></i>
        </div>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="background: rgba(255, 255, 255, 0.2); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="font-size: 24px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Total Pengguna</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $totalUsers }}</div>
            </div>
        </div>
        <div style="font-size: 12px; opacity: 0.8;">Jumlah pengguna yang terdaftar</div>
    </div>
</div>

{{-- Chart Artikel per Kategori --}}
<div style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; padding: 30px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-chart-bar" style="color: white; font-size: 20px;"></i>
        </div>
        <div>
            <h2 style="font-size: 20px; font-weight: 600; color: #1e293b; margin-bottom: 4px;">Distribusi Artikel per Kategori</h2>
            <p style="color: #64748b; font-size: 14px;">Grafik menunjukkan jumlah artikel dalam setiap kategori</p>
        </div>
    </div>

    <!-- Container dengan tinggi eksplisit untuk maintainAspectRatio:false -->
    <div style="position: relative; height: 320px; width: 100%;">
        <canvas id="articlesChart"></canvas>
    </div>
</div>

{{-- Statistik Pengunjung --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Kunjungan per Device</h2>
        <canvas id="deviceChart" height="100"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Kunjungan per Browser</h2>
        <canvas id="browserChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('articlesChart');
    if (!canvas) { return; }
    const ctx = canvas.getContext('2d');

    const labels = @json($chartLabels ?? []);
    const rawData = @json($chartData ?? []);

    const safeLabels = Array.isArray(labels) ? labels : [];
    const safeData = Array.isArray(rawData) ? rawData.map(function(n){ return Number(n) || 0; }) : [];
    const hasData = safeLabels.length > 0 && safeData.length > 0;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hasData ? safeLabels : ['Tidak ada data'],
            datasets: [{
                label: 'Jumlah Artikel',
                data: hasData ? safeData : [0],
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah: ' + context.parsed.y + ' artikel';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#6b7280' },
                    grid: { color: '#f3f4f6', drawBorder: false }
                },
                x: {
                    ticks: { color: '#6b7280' },
                    grid: { display: false }
                }
            }
        }
    });
});

const deviceCtx = document.getElementById('deviceChart').getContext('2d');
new Chart(deviceCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($visitsByDevice->keys()) !!},
        datasets: [{
            data: {!! json_encode($visitsByDevice->values()) !!},
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
        }]
    }
});

const browserCtx = document.getElementById('browserChart').getContext('2d');
new Chart(browserCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($visitsByBrowser->keys()) !!},
        datasets: [{
            data: {!! json_encode($visitsByBrowser->values()) !!},
            backgroundColor: ['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6']
        }]
    }
});
</script>
@endsection
