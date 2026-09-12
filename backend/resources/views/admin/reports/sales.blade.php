@extends('admin.layouts.master')

@section('title', 'Sales Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Sales Reports</h2>
            <p class="text-stone-500 font-medium">Analyze your sales performance and trends</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter" onchange="updateSalesData()"
                class="bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm font-bold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">This Year</option>
            </select>

            <button onclick="exportSalesReport()" class="btn-primary">
                <i class="fas fa-file-export mr-2 text-xs"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Sales Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Revenue -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total Revenue</p>
                <p class="text-3xl font-black text-stone-800" id="totalRevenue">$24,568</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i><span id="revenueChange">12.5%</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total Orders</p>
                <p class="text-3xl font-black text-stone-800" id="totalOrders">1,248</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i><span id="ordersChange">8.2%</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Average Order Value -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Avg. Order Value</p>
                <p class="text-3xl font-black text-stone-800" id="avgOrderValue">$89.50</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i><span id="avgOrderChange">5.3%</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Conversion Rate -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Conversion Rate</p>
                <p class="text-3xl font-black text-stone-800" id="conversionRate">3.2%</p>
                <div class="flex items-center mt-3 text-rose-500 font-bold text-xs bg-rose-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-down mr-1.5"></i><span id="conversionChange">0.8%</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-percentage text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts + Top Products -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-bold text-stone-800">Revenue Overview</h3>
            <div class="flex items-center bg-stone-50 rounded-xl p-1 border border-stone-100">
                <button onclick="setChartType('line')" id="lineChartBtn" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all bg-white text-red-600 shadow-sm border border-red-100">
                    <i class="fas fa-chart-line mr-1.5"></i>Line
                </button>
                <button onclick="setChartType('bar')" id="barChartBtn" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all text-stone-400 hover:text-stone-600">
                    <i class="fas fa-chart-bar mr-1.5"></i>Bar
                </button>
            </div>
        </div>
        <div class="h-80 bg-stone-50 rounded-3xl border border-stone-100 p-4 transition-all duration-300 hover:shadow-inner">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Top Selling Products</h3>
        <div class="space-y-4" id="topProductsList">
            <!-- Products will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50">
        <h3 class="text-xl font-bold text-stone-800">Detailed Sales Data</h3>
    </div>

    <div class="p-8">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 mb-8">
            <div class="order-2 sm:order-1 flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" id="searchSalesInput" placeholder="Search by order ID, customer or status..."
                        class="pl-11 pr-4 py-3 rounded-xl border border-stone-200 bg-stone-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all w-full font-medium">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400"></i>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 order-1 sm:order-2">
                <!-- Column Visibility Button -->
                <button id="columnVisibilityBtn" class="btn-secondary">
                    <i class="fas fa-columns mr-2 text-xs"></i>Columns
                </button>
                <!-- Export Dropdown -->
                <div class="relative group">
                    <button id="exportBtn" class="btn-primary">
                        <i class="fas fa-file-export mr-2 text-xs"></i>Export Data
                    </button>
                    <div class="absolute mt-2 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-2 z-50 hidden group-hover:block 
                            right-0 animate-in fade-in slide-in-from-top-2 duration-200">
                        <button data-export="csv" class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-file-csv mr-3 text-stone-400"></i>CSV Format
                        </button>
                        <button data-export="xlsx" class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-file-excel mr-3 text-stone-400"></i>Excel Spreadsheet
                        </button>
                        <button data-export="print" class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-print mr-3 text-stone-400"></i>Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabulator Container -->
        <div id="salesTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sales data
const salesData = [
    { id: 1, date: '2024-01-15', order_id: 'ORD-7891', customer: 'John Doe', products: 3, amount: 125.50, status: 'completed' },
    { id: 2, date: '2024-01-14', order_id: 'ORD-7892', customer: 'Jane Smith', products: 2, amount: 89.99, status: 'completed' },
    { id: 3, date: '2024-01-13', order_id: 'ORD-7893', customer: 'Mike Johnson', products: 1, amount: 45.00, status: 'completed' },
    { id: 4, date: '2024-01-12', order_id: 'ORD-7894', customer: 'Sarah Wilson', products: 4, amount: 189.75, status: 'completed' },
    { id: 5, date: '2024-01-11', order_id: 'ORD-7895', customer: 'David Brown', products: 2, amount: 67.50, status: 'completed' },
    { id: 6, date: '2024-01-10', order_id: 'ORD-7896', customer: 'Emily Davis', products: 3, amount: 134.99, status: 'processing' },
    { id: 7, date: '2024-01-09', order_id: 'ORD-7897', customer: 'Robert Miller', products: 1, amount: 29.99, status: 'completed' },
    { id: 8, date: '2024-01-08', order_id: 'ORD-7898', customer: 'Linda Martinez', products: 2, amount: 78.50, status: 'completed' },
    { id: 9, date: '2024-01-07', order_id: 'ORD-7899', customer: 'James Garcia', products: 5, amount: 245.25, status: 'shipped' },
    { id: 10, date: '2024-01-06', order_id: 'ORD-7900', customer: 'Patricia Rodriguez', products: 2, amount: 92.50, status: 'completed' },
    { id: 11, date: '2024-01-05', order_id: 'ORD-7901', customer: 'Michael Wilson', products: 3, amount: 156.80, status: 'completed' },
    { id: 12, date: '2024-01-04', order_id: 'ORD-7902', customer: 'Jennifer Brown', products: 1, amount: 49.99, status: 'cancelled' },
    { id: 13, date: '2024-01-03', order_id: 'ORD-7903', customer: 'William Taylor', products: 4, amount: 178.40, status: 'completed' },
    { id: 14, date: '2024-01-02', order_id: 'ORD-7904', customer: 'Elizabeth Lee', products: 2, amount: 85.75, status: 'completed' },
    { id: 15, date: '2024-01-01', order_id: 'ORD-7905', customer: 'Christopher Clark', products: 3, amount: 112.30, status: 'completed' }
];

// Top products data
const topProductsData = [
    { id: 1, name: "Wireless Bluetooth Headphones", sold: 175, revenue: 1250, color: 'sky' },
    { id: 2, name: "Smart Fitness Watch", sold: 150, revenue: 1400, color: 'purple' },
    { id: 3, name: "Organic Cotton T-Shirt", sold: 200, revenue: 900, color: 'emerald' },
    { id: 4, name: "Stainless Steel Water Bottle", sold: 125, revenue: 600, color: 'amber' },
    { id: 5, name: "Wireless Phone Charger", sold: 180, revenue: 1100, color: 'rose' }
];

let salesTable;
let revenueChart;
let chartType = 'line';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Tabulator
    initializeSalesTable();
    
    // Initialize charts and top products
    initializeCharts();
    loadTopProducts();
    
    // Initialize controls
    initSalesControls();
});

function initializeSalesTable() {
    salesTable = new Tabulator("#salesTable", {
        data: salesData,
        layout: "fitColumns",
        responsiveLayout: "collapse",
        pagination: "local",
        paginationSize: 10,
        movableColumns: true,
        headerSort: true,
        paginationSizeSelector: [10, 20, 50, 100],
        columns: [
            {
                title: "Date",
                field: "date",
                width: 140,
                sorter: "date",
                headerFilter: "input",
                headerFilterPlaceholder: "Filter...",
                formatter: function(cell) {
                    const date = new Date(cell.getValue());
                    return `<span class="text-stone-500 font-bold text-xs uppercase tracking-wider">${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>`;
                }
            },
            {
                title: "Order ID",
                field: "order_id",
                width: 140,
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Filter...",
                formatter: function(cell) {
                    return `<span class="font-black text-stone-800 tracking-tight">${cell.getValue()}</span>`;
                }
            },
            {
                title: "Customer",
                field: "customer",
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Filter...",
                formatter: function(cell) {
                    return `
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500">
                                <i class="fas fa-user text-[10px]"></i>
                            </div>
                            <span class="font-bold text-stone-700">${cell.getValue()}</span>
                        </div>
                    `;
                }
            },
            {
                title: "Products",
                field: "products",
                width: 100,
                sorter: "number",
                hozAlign: "center",
                formatter: function(cell) {
                    const count = cell.getValue();
                    return `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-stone-50 text-stone-600 border border-stone-100">${count} ITEMS</span>`;
                }
            },
            {
                title: "Amount",
                field: "amount",
                width: 130,
                sorter: "number",
                hozAlign: "right",
                formatter: function(cell) {
                    return `<span class="text-sm font-black text-stone-800">$${cell.getValue().toFixed(2)}</span>`;
                }
            },
            {
                title: "Status",
                field: "status",
                width: 140,
                responsive: 0,
                hozAlign: "center",
                headerFilter: "select",
                headerFilterParams: {
                    values: {
                        "": "All Status",
                        "completed": "Completed",
                        "processing": "Processing",
                        "shipped": "Shipped",
                        "cancelled": "Cancelled"
                    }
                },
                formatter: function(cell) {
                    const status = cell.getValue();
                    let badgeClass, icon;
                    
                    switch(status) {
                        case 'completed':
                            badgeClass = 'bg-red-50 text-red-600 border-red-100';
                            icon = 'fa-check-circle';
                            break;
                        case 'processing':
                            badgeClass = 'bg-red-50 text-red-600 border-red-100';
                            icon = 'fa-spinner fa-spin';
                            break;
                        case 'shipped':
                            badgeClass = 'bg-amber-50 text-amber-600 border-amber-100';
                            icon = 'fa-shipping-fast';
                            break;
                        case 'cancelled':
                            badgeClass = 'bg-rose-50 text-rose-600 border-rose-100';
                            icon = 'fa-times-circle';
                            break;
                        default:
                            badgeClass = 'bg-stone-50 text-stone-600 border-stone-100';
                            icon = 'fa-info-circle';
                    }
                    
                    return `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border ${badgeClass}">
                        <i class="fas ${icon} mr-1.5"></i>
                        ${status}
                    </span>`;
                }
            }
        ],
        footerElement: "<div class='text-stone-400 font-bold uppercase tracking-widest text-[10px] px-4'>Sales Management Table</div>"
    });
}

function initSalesControls() {
    // Search functionality
    const searchInput = document.getElementById('searchSalesInput');
    searchInput.addEventListener('keyup', function() {
        salesTable.setFilter([
            [
                { field: "order_id", type: "like", value: this.value },
                { field: "customer", type: "like", value: this.value },
                { field: "status", type: "like", value: this.value }
            ]
        ]);
    });

    // Column visibility
    const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
    const columnMenu = document.createElement('div');
    columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-2 z-50 hidden right-12 animate-in fade-in slide-in-from-top-2 duration-200';

    const columns = salesTable.getColumnDefinitions();
    columns.forEach((column, index) => {
        const field = column.field;
        const columnBtn = document.createElement('button');
        columnBtn.className = 'w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all';
        columnBtn.innerHTML = `
            <input type="checkbox" class="mr-3 rounded border-stone-300 text-red-500 focus:ring-red-500 h-4 w-4" ${salesTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;
        
        columnBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const col = salesTable.getColumn(field);
            const checkbox = this.querySelector('input');
            col.toggle();
            setTimeout(() => {
                checkbox.checked = col.isVisible();
            }, 10);
        });
        
        columnMenu.appendChild(columnBtn);
    });

    columnVisibilityBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        columnMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
            columnMenu.classList.add('hidden');
        }
    });

    columnVisibilityBtn.parentElement.appendChild(columnMenu);

    // Export functionality
    const exportBtns = document.querySelectorAll('[data-export]');
    exportBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const format = this.getAttribute('data-export');
            switch(format) {
                case 'csv':
                    salesTable.download("csv", "sales_report.csv");
                    break;
                case 'xlsx':
                    salesTable.download("xlsx", "sales_report.xlsx", { sheetName: "Sales Report" });
                    break;
                case 'print':
                    window.print();
                    break;
            }
        });
    });
}

function initializeCharts() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Sample data for the chart
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const data = [12000, 19000, 15000, 22000, 18000, 24568];
    
    revenueChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: data,
                backgroundColor: chartType === 'bar' ? 'rgba(14, 165, 233, 0.2)' : 'rgba(14, 165, 233, 0.1)',
                borderColor: 'rgba(14, 165, 233, 1)',
                borderWidth: 3,
                fill: chartType === 'line',
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(14, 165, 233, 1)',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1c1917',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f5f5f4',
                        drawBorder: false
                    },
                    ticks: {
                        font: { size: 10, weight: 'bold' },
                        color: '#a8a29e',
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 10, weight: 'bold' },
                        color: '#a8a29e'
                    }
                }
            }
        }
    });
}

function setChartType(type) {
    chartType = type;
    
    const lineBtn = document.getElementById('lineChartBtn');
    const barBtn = document.getElementById('barChartBtn');
    
    if (type === 'line') {
        lineBtn.classList.add('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-red-100');
        lineBtn.classList.remove('text-stone-400');
        barBtn.classList.remove('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-red-100');
        barBtn.classList.add('text-stone-400');
    } else {
        barBtn.classList.add('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-red-100');
        barBtn.classList.remove('text-stone-400');
        lineBtn.classList.remove('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-red-100');
        lineBtn.classList.add('text-stone-400');
    }
    
    revenueChart.destroy();
    initializeCharts();
}

function loadTopProducts() {
    const container = document.getElementById('topProductsList');
    container.innerHTML = '';
    
    topProductsData.forEach((product, index) => {
        const productElement = document.createElement('div');
        productElement.className = 'flex justify-between items-center p-5 bg-stone-50 rounded-2xl border border-stone-100 hover:bg-white hover:shadow-md transition-all duration-300 group';
        productElement.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-stone-50 flex items-center justify-center text-${product.color}-500 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-cube text-lg"></i>
                </div>
                <div>
                    <p class="font-black text-stone-800 text-sm tracking-tight">${product.name}</p>
                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mt-0.5">${product.sold} units sold</p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-black text-red-500 text-sm">$${product.revenue.toLocaleString()}</span>
                <p class="text-[9px] font-black text-stone-400 uppercase tracking-tighter">Gross Rev.</p>
            </div>
        `;
        container.appendChild(productElement);
    });
}

function updateSalesData() {
    const period = document.getElementById('periodFilter').value;
    
    Swal.fire({
        title: 'Updating Sales Analysis',
        html: '<div class="text-stone-400 font-medium mt-2">Connecting to data warehouse...</div>',
        allowOutsideClick: false,
        timer: 1000,
        didOpen: () => {
            Swal.showLoading();
            const popup = Swal.getPopup();
            popup.classList.add('rounded-3xl');
        }
    }).then(() => {
        // Update stats based on period
        let revenue, orders, avgOrder, conversion;
        
        switch(period) {
            case '7':
                revenue = 5680; orders = 64; avgOrder = 88.75; conversion = 2.8;
                break;
            case '30':
                revenue = 24568; orders = 1248; avgOrder = 89.50; conversion = 3.2;
                break;
            case '90':
                revenue = 68900; orders = 3125; avgOrder = 91.20; conversion = 3.5;
                break;
            case '365':
                revenue = 285000; orders = 15000; avgOrder = 95.00; conversion = 4.1;
                break;
        }
        
        document.getElementById('totalRevenue').textContent = '$' + revenue.toLocaleString();
        document.getElementById('totalOrders').textContent = orders.toLocaleString();
        document.getElementById('avgOrderValue').textContent = '$' + avgOrder.toFixed(2);
        document.getElementById('conversionRate').textContent = conversion + '%';
        
        const changes = {
            7: { revenue: '2.1%', orders: '1.5%', avgOrder: '1.8%', conversion: '0.3%' },
            30: { revenue: '12.5%', orders: '8.2%', avgOrder: '5.3%', conversion: '0.8%' },
            90: { revenue: '18.2%', orders: '12.5%', avgOrder: '7.8%', conversion: '1.2%' },
            365: { revenue: '25.4%', orders: '18.7%', avgOrder: '10.2%', conversion: '1.8%' }
        };
        
        document.getElementById('revenueChange').textContent = changes[period].revenue;
        document.getElementById('ordersChange').textContent = changes[period].orders;
        document.getElementById('avgOrderChange').textContent = changes[period].avgOrder;
        document.getElementById('conversionChange').textContent = changes[period].conversion;
        
        toastr.success('Sales intelligence updated!');
    });
}

function exportSalesReport() {
    Swal.fire({
        title: 'Sales Report Export',
        width: 550,
        html: `
            <div class="text-left space-y-5 p-2">
                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Report Detail Level</label>
                    <select id="reportType" class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                        <option value="summary">Summary Performance</option>
                        <option value="detailed">Complete Transaction Log</option>
                        <option value="customers">Customer Acquisition Report</option>
                        <option value="products">SKU Level Performance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Export Format</label>
                    <select id="exportFormat" class="w-full border border-stone-200 bg-stone-50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                        <option value="csv">CSV (Comma Separated)</option>
                        <option value="excel">XLSX (Microsoft Excel)</option>
                        <option value="pdf">PDF (Print Ready)</option>
                    </select>
                </div>
                <div class="flex items-center space-x-3 bg-red-50 p-4 rounded-xl border border-red-100">
                    <input type="checkbox" id="includeCharts" class="w-5 h-5 rounded border-red-200 text-red-500 focus:ring-red-500" checked>
                    <label for="includeCharts" class="text-sm font-bold text-red-700">Embed Analytics & Charts</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Initialize Export',
        customClass: {
            popup: 'rounded-3xl',
            confirmButton: 'btn-primary px-8',
            cancelButton: 'btn-secondary px-8'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Exporting Data...',
                text: 'Preparing your high-resolution report',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            setTimeout(() => {
                Swal.close();
                toastr.success(`Sales data exported successfully!`);
                
                if (document.getElementById('exportFormat').value === 'csv') {
                    salesTable.download("csv", "sales_intelligence.csv");
                } else if (document.getElementById('exportFormat').value === 'excel') {
                    salesTable.download("xlsx", "sales_intelligence.xlsx", { sheetName: "Intelligence" });
                }
            }, 1500);
        }
    });
}
</script>

<style>
/* Tabulator Theme Customization */
.tabulator { border: none !important; background: transparent !important; }
.tabulator-header { background-color: #f5f5f4 !important; border-bottom: 2px solid #e7e5e4 !important; border-top: none !important; }
.tabulator-col { background-color: #f5f5f4 !important; border-right: none !important; padding: 12px 4px !important; }
.tabulator-col-title { color: #78716c !important; font-size: 10px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
.tabulator-row { border-bottom: 1px solid #f5f5f4 !important; transition: all 0.2s !important; }
.tabulator-row:hover { background-color: #f0f9ff !important; }
.tabulator-cell { padding: 16px 8px !important; color: #44403c !important; border-right: none !important; }
.tabulator-footer { background-color: white !important; border-top: 1px solid #e7e5e4 !important; padding: 15px !important; }
.tabulator-page { border-radius: 8px !important; border: 1px solid #e7e5e4 !important; padding: 5px 10px !important; margin: 0 2px !important; font-weight: bold !important; font-size: 11px !important; color: #78716c !important; transition: all 0.2s !important; }
.tabulator-page.active { background-color: #0ea5e9 !important; border-color: #0ea5e9 !important; color: white !important; }
.tabulator-page:not(.disabled):hover { background-color: #f5f5f4 !important; color: #0ea5e9 !important; }
</style>
@endpush
