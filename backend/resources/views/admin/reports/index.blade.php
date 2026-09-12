@extends('admin.layouts.master')

@section('title', 'Reports Dashboard')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Reports Dashboard</h2>
            <p class="text-stone-500 font-medium">Comprehensive overview of your store performance and analytics</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select class="bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm font-bold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm">
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
                <option>This Year</option>
                <option>All Time</option>
            </select>

            <button class="btn-primary">
                <i class="fas fa-file-export mr-2 text-xs"></i>Export All Reports
            </button>
        </div>
    </div>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- KPI Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total Revenue</p>
                <p class="text-3xl font-black text-stone-800">$24,568</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>12.5%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total Orders</p>
                <p class="text-3xl font-black text-stone-800">1,248</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>8.2%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Avg. Order Value</p>
                <p class="text-3xl font-black text-stone-800">$89.50</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>5.3%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Conversion Rate</p>
                <p class="text-3xl font-black text-stone-800">3.2%</p>
                <div class="flex items-center mt-3 text-rose-500 font-bold text-xs bg-rose-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-down mr-1.5"></i>0.8%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-percentage text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Overview Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Sales Report Summary -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-stone-800">Sales Report</h3>
            <a href="{{ route('admin.reports.sales') }}"
               class="text-red-600 hover:text-red-800 text-sm font-bold uppercase tracking-wider">Details <i class="fas fa-chevron-right text-[10px] ml-1"></i></a>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Total Sales</span>
                <span class="font-bold text-stone-800">$24,568</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Orders</span>
                <span class="font-bold text-stone-800">1,248</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Avg. Daily Sales</span>
                <span class="font-bold text-stone-800">$819</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-stone-500 font-medium text-sm">Refund Rate</span>
                <span class="font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-lg">2.3%</span>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-stone-50 text-[11px] text-stone-400 font-bold uppercase tracking-widest flex items-center">
            <i class="fas fa-clock mr-2 text-red-400"></i>Updated: Today, 9:42 AM
        </div>
    </div>

    <!-- Customers Report Summary -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-stone-800">Customers</h3>
            <a href="{{ route('admin.reports.customers') }}"
               class="text-red-600 hover:text-red-800 text-sm font-bold uppercase tracking-wider">Details <i class="fas fa-chevron-right text-[10px] ml-1"></i></a>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Total Customers</span>
                <span class="font-bold text-stone-800">5,423</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">New This Month</span>
                <span class="font-bold text-stone-800">248</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Repeat Rate</span>
                <span class="font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-lg">42%</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-stone-500 font-medium text-sm">Avg. Lifetime Value</span>
                <span class="font-bold text-stone-800">$450</span>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-stone-50 text-[11px] text-stone-400 font-bold uppercase tracking-widest flex items-center">
            <i class="fas fa-users mr-2 text-red-400"></i>Growth: +15.3% this month
        </div>
    </div>

    <!-- Products Report Summary -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-stone-800">Products</h3>
            <a href="{{ route('admin.reports.products') }}"
               class="text-red-600 hover:text-red-800 text-sm font-bold uppercase tracking-wider">Details <i class="fas fa-chevron-right text-[10px] ml-1"></i></a>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Total Products</span>
                <span class="font-bold text-stone-800">856</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Products Sold</span>
                <span class="font-bold text-stone-800">12,458</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-stone-50">
                <span class="text-stone-500 font-medium text-sm">Out of Stock</span>
                <span class="font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-lg">23</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-stone-500 font-medium text-sm">Low Stock</span>
                <span class="font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded-lg">45</span>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-stone-50 text-[11px] text-stone-400 font-bold uppercase tracking-widest flex items-center">
            <i class="fas fa-box mr-2 text-red-400"></i>Inv. Value: $189,250
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Revenue Trend -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-bold text-stone-800">Revenue Trend</h3>
            <select class="bg-stone-50 border border-stone-200 rounded-xl px-3 py-1.5 text-xs font-bold text-stone-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
            </select>
        </div>

        <div class="h-64 bg-stone-50 rounded-3xl border border-stone-100 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4">
                <i class="fas fa-chart-area text-red-400 text-2xl"></i>
            </div>
            <p class="text-stone-400 font-bold uppercase tracking-widest text-xs">Revenue Trend Data</p>
            <p class="text-[10px] text-stone-400 mt-1">Showing daily revenue for the last 30 days</p>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-bold text-stone-800">Top Categories</h3>
            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest bg-stone-50 px-2 py-1 rounded-lg">By Revenue</span>
        </div>

        <div class="space-y-6">
            @php
            $categories = [
                ['name'=>'Electronics','revenue'=>12500,'percentage'=>35,'growth'=>12, 'color' => 'sky'],
                ['name'=>'Clothing','revenue'=>8900,'percentage'=>25,'growth'=>8, 'color' => 'purple'],
                ['name'=>'Home & Kitchen','revenue'=>7200,'percentage'=>20,'growth'=>15, 'color' => 'emerald'],
                ['name'=>'Accessories','revenue'=>5400,'percentage'=>15,'growth'=>5, 'color' => 'amber'],
                ['name'=>'Books','revenue'=>1800,'percentage'=>5,'growth'=>-2, 'color' => 'rose']
            ];
            @endphp
            
            @foreach($categories as $c)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-{{ $c['color'] }}-500 rounded-full"></div>
                        <span class="text-sm font-bold text-stone-700">{{ $c['name'] }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-bold text-stone-800">${{ number_format($c['revenue']) }}</span>
                        <span class="text-[10px] font-bold {{ $c['growth']>=0?'text-red-500':'text-rose-500' }}">
                            {{ ($c['growth']>=0?'+':'').$c['growth'] }}%
                        </span>
                    </div>
                </div>
                <div class="w-full bg-stone-100 rounded-full h-2">
                    <div class="bg-{{ $c['color'] }}-500 h-2 rounded-full shadow-sm" style="width: {{ $c['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Metrics -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Key Metrics</h3>

        <div class="grid grid-cols-2 gap-4">
            @php
            $metrics = [
                ['$89.50','Average Order','emerald'],
                ['2.4','Items / Order','sky'],
                ['42%','Repeat Rate','purple'],
                ['18.3%','Cart Abandon','amber'],
                ['2.3%','Refund Rate','rose']
            ];
            @endphp
            
            @foreach($metrics as $m)
            <div class="text-center p-6 bg-stone-50 rounded-2xl border border-stone-100 hover:bg-white hover:shadow-sm transition-all duration-300">
                <p class="text-2xl font-black text-{{ $m[2] }}-500">{{ $m[0] }}</p>
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mt-1">{{ $m[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Recent Activity</h3>
        <div class="space-y-4">
            @php
            $activity = [
                ['icon'=>'file-export','color'=>'emerald','title'=>'Sales Report Exported','time'=>'Today, 9:30 AM','badge'=>'Completed'],
                ['icon'=>'chart-bar','color'=>'sky','title'=>'Analytics Generated','time'=>'Yesterday, 3:15 PM','badge'=>'Processed'],
                ['icon'=>'exclamation-triangle','color'=>'amber','title'=>'Low Stock Alert','time'=>'2 days ago','badge'=>'Warning'],
                ['icon'=>'file-pdf','color'=>'purple','title'=>'Monthly Report PDF','time'=>'3 days ago','badge'=>'Generated']
            ];
            @endphp
            
            @foreach($activity as $a)
            <div class="flex justify-between items-center p-4 bg-stone-50 rounded-2xl border border-stone-100">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-{{ $a['color'] }}-500">
                        <i class="fas fa-{{ $a['icon'] }} text-sm"></i>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-stone-800">{{ $a['title'] }}</p>
                        <p class="text-[10px] font-medium text-stone-400 uppercase tracking-widest">{{ $a['time'] }}</p>
                    </div>
                </div>

                <span class="text-[10px] font-black uppercase tracking-widest text-{{ $a['color'] }}-500 bg-white px-2.5 py-1 rounded-lg shadow-sm border border-stone-50">
                    {{ $a['badge'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white mt-10 rounded-3xl border border-stone-100 shadow-sm p-8">
    <h3 class="text-xl font-bold text-stone-800 mb-6">Quick Actions</h3>

    <div class="flex flex-wrap gap-4">
        <button onclick="generateCustomReport()" class="btn-primary min-w-[180px]">
            <i class="fas fa-plus mr-2 text-xs"></i>Custom Report
        </button>

        <button onclick="scheduleReport()" class="btn-secondary min-w-[180px]">
            <i class="fas fa-clock mr-2 text-xs"></i>Schedule Report
        </button>

        <button onclick="exportAllReports()" class="btn-secondary text-red-600 border-red-100 bg-red-50 hover:bg-red-100 min-w-[150px]">
            <i class="fas fa-file-export mr-2 text-xs"></i>Export All
        </button>

        <button onclick="viewReportHistory()" class="btn-secondary min-w-[150px]">
            <i class="fas fa-history mr-2 text-xs"></i>Report History
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ----- Custom Report Modal ----- */
function generateCustomReport() {
    Swal.fire({
        title: "Generate Custom Report",
        width: 600,
        html: `
            <div class="text-left space-y-5 p-2">
                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Report Type</label>
                    <select class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                        <option>Sales Report</option>
                        <option>Customer Report</option>
                        <option>Product Report</option>
                        <option>Inventory Report</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Start Date</label>
                        <input type="date" class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">End Date</label>
                        <input type="date" class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Export Format</label>
                    <div class="flex gap-6">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="format" checked class="w-4 h-4 text-red-500 focus:ring-red-500 border-stone-300">
                            <span class="ml-2 text-sm font-bold text-stone-700 group-hover:text-stone-900 transition-colors">PDF</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="format" class="w-4 h-4 text-red-500 focus:ring-red-500 border-stone-300">
                            <span class="ml-2 text-sm font-bold text-stone-700 group-hover:text-stone-900 transition-colors">Excel</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="format" class="w-4 h-4 text-red-500 focus:ring-red-500 border-stone-300">
                            <span class="ml-2 text-sm font-bold text-stone-700 group-hover:text-stone-900 transition-colors">CSV</span>
                        </label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Generate Report",
        customClass: { 
            popup: 'rounded-3xl',
            confirmButton: 'btn-primary px-8', 
            cancelButton: 'btn-secondary px-8' 
        }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Custom report generation started!");
    });
}

/* ----- Schedule Modal ----- */
function scheduleReport() {
    Swal.fire({
        title: "Schedule Report",
        width: 550,
        html: `
            <div class="text-left space-y-5 p-2">
                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Report Type</label>
                    <select class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                        <option>Daily Sales</option>
                        <option>Weekly Summary</option>
                        <option>Monthly Overview</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Frequency</label>
                    <select class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                        <option>Daily</option>
                        <option>Weekly</option>
                        <option>Monthly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Email Recipient</label>
                    <input type="email" placeholder="manager@example.com" class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Schedule Now",
        customClass: { 
            popup: 'rounded-3xl',
            confirmButton: 'btn-primary px-8', 
            cancelButton: 'btn-secondary px-8' 
        }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Report scheduled successfully!");
    });
}

/* ----- Export All ----- */
function exportAllReports() {
    Swal.fire({
        title: "Export All Reports",
        text: "This will prepare a ZIP archive containing Sales, Customers, Products, and Inventory reports.",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Start Export",
        customClass: { 
            popup: 'rounded-3xl',
            confirmButton: 'btn-primary px-8 border-red-500 bg-red-500 hover:bg-red-600', 
            cancelButton: 'btn-secondary px-8' 
        }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Bulk export has been initiated!");
    });
}

/* ----- History ----- */ 
function viewReportHistory() {
    Swal.fire({
        title: "Report History",
        width: 600,
        html: `
            <div class="max-h-96 overflow-y-auto space-y-3 p-2 custom-scrollbar">
                ${[1,2,3,4,5].map(i => `
                    <div class="flex justify-between items-center p-4 bg-stone-50 border border-stone-100 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-file-pdf text-rose-500 text-lg"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-stone-800 text-sm">Sales Report Q${i}_2024</p>
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">${i} days ago • 2.4 MB</p>
                            </div>
                        </div>
                        <button class="text-red-600 hover:text-red-800 font-bold text-xs uppercase tracking-widest bg-white px-3 py-1.5 rounded-lg border border-stone-50 shadow-sm transition-all hover:scale-105">Download</button>
                    </div>
                `).join('')}
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        customClass: { 
            popup: 'rounded-3xl'
        }
    });
}
</script>
<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e7e5e4; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d6d3d1; }
</style>
@endpush
