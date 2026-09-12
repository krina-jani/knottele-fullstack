@extends('admin.layouts.master')

@section('title', 'Product Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Product Reports</h2>
            <p class="text-stone-500 font-medium">Analyze product performance and inventory trends</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter"
                class="bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm font-bold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm">
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">This Year</option>
                <option value="all">All Time</option>
            </select>

            <button id="exportReportBtn" class="btn-primary">
                <i class="fas fa-file-export mr-2 text-xs"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Product Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Products -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total SKUs</p>
                <p id="totalProducts" class="text-3xl font-black text-stone-800">856</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>12.5%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Products Sold -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Units Sold</p>
                <p id="totalSold" class="text-3xl font-black text-stone-800">12,458</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>18.3%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Out of Stock</p>
                <p id="outOfStock" class="text-3xl font-black text-stone-800">23</p>
                <div class="flex items-center mt-3 text-rose-500 font-bold text-xs bg-rose-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-exclamation-triangle mr-1.5"></i>Critical
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-ban text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Low Stock</p>
                <p id="lowStock" class="text-3xl font-black text-stone-800">45</p>
                <div class="flex items-center mt-3 text-amber-500 font-bold text-xs bg-amber-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-down mr-1.5"></i>Attention
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-box-open text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Product Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Sales Performance -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Performance Over Time</h3>
        <div class="h-80 bg-stone-50 rounded-3xl border border-stone-100 p-4">
            <canvas id="productSalesChart"></canvas>
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Sales by Category</h3>
        <div id="categoryDistribution" class="space-y-6">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Top Products Table - Tabulator -->
<div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50">
        <h3 class="text-xl font-bold text-stone-800">Top Selling Products</h3>
    </div>

    <div class="p-8">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 mb-8">
            <div class="order-2 sm:order-1 flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by name, SKU or category..."
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
                        <button data-export="csv"
                            class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-file-csv mr-3 text-stone-400"></i>CSV Format
                        </button>
                        <button data-export="xlsx"
                            class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-file-excel mr-3 text-stone-400"></i>Excel Spreadsheet
                        </button>
                        <button data-export="pdf"
                            class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-file-pdf mr-3 text-stone-400"></i>PDF Document
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabulator Table -->
        <div id="productsReportTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Static product reports data
    window.productsReportData = [
        {
            id: 1, rank: 1, name: "Wireless Bluetooth Headphones", sku: "ELEC-WBH-001",
            category: "Electronics", units_sold: 1250, revenue: 74500.00,
            stock: 45, stock_status: "in_stock", status: "active", growth: 25
        },
        {
            id: 2, rank: 2, name: "Smart Fitness Watch", sku: "WRB-SFW-002",
            category: "Wearables", units_sold: 980, revenue: 147000.00,
            stock: 23, stock_status: "low_stock", status: "active", growth: 18
        },
        {
            id: 3, rank: 3, name: "Organic Cotton T-Shirt", sku: "CLO-OCT-003",
            category: "Clothing", units_sold: 845, revenue: 21125.00,
            stock: 0, stock_status: "out_of_stock", status: "inactive", growth: 12
        },
        {
            id: 4, rank: 4, name: "Stainless Steel Water Bottle", sku: "ACC-SSW-004",
            category: "Accessories", units_sold: 720, revenue: 17928.00,
            stock: 78, stock_status: "in_stock", status: "active", growth: 32
        },
        {
            id: 5, rank: 5, name: "Wireless Phone Charger", sku: "ELEC-WPC-005",
            category: "Electronics", units_sold: 650, revenue: 19487.50,
            stock: 34, stock_status: "in_stock", status: "active", growth: 15
        },
        {
            id: 6, rank: 6, name: "Yoga Mat Premium", sku: "FIT-YMP-006",
            category: "Fitness", units_sold: 520, revenue: 20748.00,
            stock: 12, stock_status: "low_stock", status: "active", growth: 28
        }
    ];

    // Category distribution data
    window.categoryDistributionData = [
        { name: "Electronics", sales: 12500, percentage: 35, color: 'sky' },
        { name: "Clothing", sales: 8900, percentage: 25, color: 'purple' },
        { name: "Home & Kitchen", sales: 7200, percentage: 20, color: 'emerald' },
        { name: "Accessories", sales: 5400, percentage: 15, color: 'amber' },
        { name: "Others", sales: 1800, percentage: 5, color: 'stone' }
    ];

    let productsReportTable;

    document.addEventListener('DOMContentLoaded', function () {
        initCategoryDistribution();
        updateMetrics();
        
        productsReportTable = new Tabulator("#productsReportTable", {
            data: productsReportData,
            layout: "fitColumns",
            responsiveLayout: "collapse",
            pagination: "local",
            paginationSize: 10,
            movableColumns: true,
            headerSort: true,
            initialSort: [{ column: "rank", dir: "asc" }],
            columns: [
                {
                    title: "Rank",
                    field: "rank",
                    width: 90,
                    hozAlign: "center",
                    formatter: function (cell) {
                        const rank = cell.getValue();
                        let badgeClass = "bg-stone-50 text-stone-500 border-stone-100";
                        if (rank === 1) badgeClass = "bg-amber-50 text-amber-600 border-amber-100";
                        else if (rank === 2) badgeClass = "bg-red-50 text-red-600 border-red-100";
                        else if (rank === 3) badgeClass = "bg-red-50 text-red-600 border-red-100";
                        return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-[10px] border ${badgeClass}">#${rank}</span>`;
                    }
                },
                {
                    title: "Product Identity",
                    field: "name",
                    widthGrow: 2,
                    headerFilter: "input",
                    formatter: function (cell) {
                        const data = cell.getRow().getData();
                        return `
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-stone-50 rounded-xl flex items-center justify-center text-stone-400 border border-stone-100 shadow-sm transition-transform group-hover:scale-110">
                                    <i class="fas fa-cube text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-stone-800 text-sm tracking-tight truncate">${data.name}</p>
                                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest truncate">${data.sku}</p>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Category",
                    field: "category",
                    width: 140,
                    hozAlign: "center",
                    formatter: function (cell) {
                        return `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-stone-50 text-stone-500 border border-stone-100">${cell.getValue()}</span>`;
                    }
                },
                {
                    title: "Volume",
                    field: "units_sold",
                    width: 120,
                    hozAlign: "center",
                    formatter: function (cell) {
                        return `
                            <div class="text-center">
                                <span class="text-sm font-black text-stone-800">${cell.getValue().toLocaleString()}</span>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Units Sold</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Gross Revenue",
                    field: "revenue",
                    width: 150,
                    hozAlign: "right",
                    formatter: function (cell) {
                        return `
                            <div class="text-right">
                                <span class="text-sm font-black text-red-500">$${cell.getValue().toLocaleString()}</span>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Revenue</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Inventory",
                    field: "stock_status",
                    width: 120,
                    hozAlign: "center",
                    formatter: function (cell) {
                        const rowData = cell.getRow().getData();
                        const status = cell.getValue();
                        const config = status === 'in_stock' 
                            ? { class: 'text-red-500', text: 'IN STOCK' }
                            : (status === 'low_stock' ? { class: 'text-amber-500', text: 'LOW STOCK' } : { class: 'text-rose-500', text: 'OUT OF STOCK' });
                        return `
                            <div class="text-center">
                                <span class="text-xs font-black ${config.class}">${rowData.stock}</span>
                                <p class="text-[9px] font-black opacity-60 uppercase tracking-widest">${config.text}</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Action",
                    field: "id",
                    width: 100,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function (cell) {
                        return `<button onclick="viewReport(${cell.getValue()})" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl border border-red-100 hover:bg-red-100 transition-all shadow-sm"><i class="fas fa-chart-pie text-xs"></i></button>`;
                    }
                }
            ],
            footerElement: "<div class='text-stone-400 font-bold uppercase tracking-widest text-[10px] px-4'>Stock & Performance Intelligence</div>"
        });

        productsReportTable.on("tableBuilt", () => {
            initSearch();
            initExport();
            initColumnVisibility();
            initProductSalesChart();
        });
    });

    function initSearch() {
        document.getElementById('searchInput').addEventListener('keyup', function () {
            productsReportTable.setFilter([
                [{ field: "name", type: "like", value: this.value },
                 { field: "sku", type: "like", value: this.value },
                 { field: "category", type: "like", value: this.value }]
            ]);
        });
    }

    function initCategoryDistribution() {
        const container = document.getElementById('categoryDistribution');
        container.innerHTML = '';
        window.categoryDistributionData.forEach(cat => {
            const div = document.createElement('div');
            div.className = 'group';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-stone-700">${cat.name}</span>
                    <span class="text-xs font-black text-stone-800">${cat.percentage}%</span>
                </div>
                <div class="w-full bg-stone-100 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-${cat.color}-500 h-full rounded-full shadow-sm group-hover:scale-x-105 transition-transform origin-left duration-500" style="width: ${cat.percentage}%"></div>
                </div>
            `;
            container.appendChild(div);
        });
    }

    function initProductSalesChart() {
        const ctx = document.getElementById('productSalesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                datasets: [{
                    label: "Movements",
                    data: [1200, 1800, 1500, 2000, 1750, 2300, 2500],
                    borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 3, fill: true, tension: 0.4,
                    pointBackgroundColor: '#fff', pointBorderColor: '#0ea5e9', pointRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f5f5f4', drawBorder: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#a8a29e' } },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#a8a29e' } }
                }
            }
        });
    }

    function initExport() {
        const btns = document.querySelectorAll('[data-export]');
        btns.forEach(btn => {
            btn.addEventListener('click', function () {
                const format = this.getAttribute('data-export');
                if (format === 'csv') productsReportTable.download("csv", "product_intelligence.csv");
                else if (format === 'xlsx') productsReportTable.download("xlsx", "product_intelligence.xlsx", { sheetName: "Intelligence" });
            });
        });
        document.getElementById('exportReportBtn').addEventListener('click', () => {
            Swal.fire({
                title: 'Data Extraction',
                html: '<div class="text-stone-400 font-medium p-4">Select format to export SKU data...</div>',
                showCancelButton: true, confirmButtonText: 'Export Now',
                customClass: { popup: 'rounded-3xl', confirmButton: 'btn-primary px-8' }
            });
        });
    }

    function initColumnVisibility() {
        const btn = document.getElementById('columnVisibilityBtn');
        const menu = document.createElement('div');
        menu.className = 'absolute mt-12 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-2 z-50 hidden right-12 animate-in fade-in slide-in-from-top-2 duration-200';
        productsReportTable.getColumnDefinitions().forEach(column => {
            if (column.field === "id") return;
            const item = document.createElement('div');
            item.className = 'px-4 py-2 hover:bg-stone-50 transition-all cursor-pointer flex items-center text-sm font-medium text-stone-600';
            item.innerHTML = `<input type="checkbox" class="mr-3 rounded text-red-500" ${column.visible !== false ? 'checked' : ''}> ${column.title}`;
            item.onclick = (e) => { e.stopPropagation(); const col = productsReportTable.getColumn(column.field); col.toggle(); item.querySelector('input').checked = col.isVisible(); };
            menu.appendChild(item);
        });
        btn.onclick = (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); };
        document.onclick = () => menu.classList.add('hidden');
        btn.parentElement.appendChild(menu);
    }
    
    function updateMetrics() {
        toastr.info("Synchronizing inventory intelligence...");
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
.tabulator-page { border-radius: 8px !important; border: 1px solid #e7e5e4 !important; padding: 5px 10px !important; margin: 0 2px !important; font-weight: bold !important; font-size: 11px !important; color: #78716c !important; }
.tabulator-page.active { background-color: #0ea5e9 !important; border-color: #0ea5e9 !important; color: white !important; }
</style>
@endpush
