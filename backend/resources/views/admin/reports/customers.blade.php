@extends('admin.layouts.master')

@section('title', 'Customer Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Customer Reports</h2>
            <p class="text-stone-500 font-medium">Analyze customer behavior and demographics</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter"
                class="bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm font-bold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm">
                <option value="30">Last 30 Days</option>
                <option value="90" selected>Last 90 Days</option>
                <option value="365">This Year</option>
                <option value="all">All Time</option>
            </select>

            <button id="exportReportBtn" class="btn-primary">
                <i class="fas fa-file-export mr-2 text-xs"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Customer Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Customers -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Total Customers</p>
                <p id="totalCustomers" class="text-3xl font-black text-stone-800">5,423</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>15.3%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- New Customers -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">New Customers</p>
                <p id="newCustomers" class="text-3xl font-black text-stone-800">248</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>8.7%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- AOV -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Avg. Order Value</p>
                <p id="avgOrderValue" class="text-3xl font-black text-stone-800">$89.50</p>
                <div class="flex items-center mt-3 text-red-500 font-bold text-xs bg-red-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-up mr-1.5"></i>5.2%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Repeat Rate -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 hover:shadow-md transition-all duration-300 group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-1">Repeat Rate</p>
                <p id="repeatRate" class="text-3xl font-black text-stone-800">42%</p>
                <div class="flex items-center mt-3 text-rose-500 font-bold text-xs bg-rose-50 px-2 py-1 rounded-lg w-fit">
                    <i class="fas fa-arrow-down mr-1.5"></i>2.1%
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-redo text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Customer Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Growth Chart -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Customer Growth</h3>
        <div class="h-80 bg-stone-50 rounded-3xl border border-stone-100 p-4">
            <canvas id="customerGrowthChart"></canvas>
        </div>
    </div>

    <!-- Demographics -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
        <h3 class="text-xl font-bold text-stone-800 mb-8">Demographics</h3>

        <!-- Age Group -->
        <div class="mb-10">
            <div class="flex justify-between text-xs font-bold text-stone-400 uppercase tracking-widest mb-4">
                <span>Age Group</span>
                <span>Distribution</span>
            </div>

            <div class="space-y-6">
                @php
                $ages = [
                    ['18-24', 15, 'sky'],
                    ['25-34', 35, 'purple'],
                    ['35-44', 28, 'emerald'],
                    ['45+', 22, 'amber'],
                ];
                @endphp
                
                @foreach($ages as $a)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-stone-700 min-w-[60px]">{{ $a[0] }}</span>
                        <span class="text-xs font-black text-stone-800">{{ $a[1] }}%</span>
                    </div>
                    <div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-{{ $a[2] }}-500 h-2 rounded-full shadow-sm" style="width: {{ $a[1] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Locations -->
        <div>
            <div class="flex justify-between text-xs font-bold text-stone-400 uppercase tracking-widest mb-4">
                <span>Location</span>
                <span>Customers</span>
            </div>

            <div class="space-y-4">
                @php
                $locations = [
                    ['United States', 3245],
                    ['Canada', 856],
                    ['United Kingdom', 723],
                    ['Australia', 456],
                    ['Other', 143],
                ];
                @endphp
                
                @foreach($locations as $l)
                <div class="flex justify-between items-center p-3 bg-stone-50 rounded-xl border border-stone-100 hover:bg-white hover:shadow-sm transition-all duration-300">
                    <span class="text-sm font-bold text-stone-700 flex items-center">
                        <i class="fas fa-map-marker-alt text-stone-300 mr-2 text-xs"></i>{{ $l[0] }}
                    </span>
                    <span class="text-xs font-black text-stone-800 bg-white px-3 py-1 rounded-lg border border-stone-50 shadow-sm">{{ number_format($l[1]) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Top Customers Table - Tabulator -->
<div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50">
        <h3 class="text-xl font-bold text-stone-800">Top Customers by Lifetime Value</h3>
    </div>

    <div class="p-8">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 mb-8">
            <div class="order-2 sm:order-1 flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by name, email or location..."
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
                        <button data-export="print"
                            class="w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-print mr-3 text-stone-400"></i>Print View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabulator Table -->
        <div id="customersReportTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Static customer reports data
    window.customersReportData = [
        {
            id: 1, rank: 1, name: "John Smith", email: "john.smith@example.com",
            total_orders: 15, total_spent: 1875.50, avg_order_value: 125.03,
            last_order: new Date(Date.now() - 2 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jan 15, 2022", location: "New York, USA", status: "active"
        },
        {
            id: 2, rank: 2, name: "Sarah Johnson", email: "sarah.j@example.com",
            total_orders: 12, total_spent: 1420.00, avg_order_value: 118.33,
            last_order: new Date(Date.now() - 5 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Mar 22, 2022", location: "Los Angeles, USA", status: "active"
        },
        {
            id: 3, rank: 3, name: "Michael Chen", email: "michael.c@example.com",
            total_orders: 10, total_spent: 1250.75, avg_order_value: 125.08,
            last_order: new Date(Date.now() - 1 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Feb 10, 2022", location: "Toronto, Canada", status: "active"
        },
        {
            id: 4, rank: 4, name: "Emma Wilson", email: "emma.w@example.com",
            total_orders: 9, total_spent: 975.25, avg_order_value: 108.36,
            last_order: new Date(Date.now() - 7 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Apr 05, 2022", location: "London, UK", status: "active"
        },
        {
            id: 5, rank: 5, name: "Robert Brown", email: "robert.b@example.com",
            total_orders: 8, total_spent: 825.00, avg_order_value: 103.13,
            last_order: new Date(Date.now() - 14 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "May 18, 2022", location: "Sydney, Australia", status: "active"
        },
        {
            id: 6, rank: 6, name: "Lisa Anderson", email: "lisa.a@example.com",
            total_orders: 7, total_spent: 675.50, avg_order_value: 96.50,
            last_order: new Date(Date.now() - 3 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jun 30, 2022", location: "Chicago, USA", status: "active"
        },
        {
            id: 7, rank: 7, name: "David Miller", email: "david.m@example.com",
            total_orders: 7, total_spent: 625.25, avg_order_value: 89.32,
            last_order: new Date(Date.now() - 10 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jul 12, 2022", location: "Vancouver, Canada", status: "active"
        },
        {
            id: 8, rank: 8, name: "Jennifer Lee", email: "jennifer.l@example.com",
            total_orders: 6, total_spent: 550.00, avg_order_value: 91.67,
            last_order: new Date(Date.now() - 21 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Aug 08, 2022", location: "Manchester, UK", status: "inactive"
        },
        {
            id: 9, rank: 9, name: "Thomas White", email: "thomas.w@example.com",
            total_orders: 5, total_spent: 475.75, avg_order_value: 95.15,
            last_order: new Date(Date.now() - 28 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Sep 25, 2022", location: "Melbourne, Australia", status: "active"
        },
        {
            id: 10, rank: 10, name: "Maria Garcia", email: "maria.g@example.com",
            total_orders: 5, total_spent: 425.50, avg_order_value: 85.10,
            last_order: new Date(Date.now() - 35 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Oct 15, 2022", location: "Miami, USA", status: "active"
        }
    ];

    // Initialize Tabulator
    let customersReportTable;

    document.addEventListener('DOMContentLoaded', function () {
        // Update metrics
        updateMetrics();
        initCustomerGrowthChart(); 
        
        // Create Tabulator
        customersReportTable = new Tabulator("#customersReportTable", {
            data: customersReportData,
            layout: "fitColumns",
            responsiveLayout: "collapse",
            pagination: "local",
            paginationSize: 10,
            movableColumns: true,
            headerSort: true,
            paginationSizeSelector: [10, 20, 50, 100],
            initialSort: [{ column: "rank", dir: "asc" }],
            columns: [
                {
                    title: "Rank",
                    field: "rank",
                    width: 90,
                    sorter: "number",
                    hozAlign: "center",
                    headerFilter: "input",
                    headerFilterPlaceholder: " # ",
                    formatter: function (cell) {
                        const rank = cell.getValue();
                        let badgeClass = "bg-stone-50 text-stone-500 border-stone-100";
                        if (rank === 1) badgeClass = "bg-amber-50 text-amber-600 border-amber-100";
                        else if (rank === 2) badgeClass = "bg-red-50 text-red-600 border-red-100";
                        else if (rank === 3) badgeClass = "bg-red-50 text-red-600 border-red-100";

                        return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-[10px] border ${badgeClass}">
                        #${rank}
                    </span>`;
                    }
                },
                {
                    title: "Customer Intelligence",
                    field: "name",
                    sorter: "string",
                    widthGrow: 2,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Filter...",
                    formatter: function (cell) {
                        const data = cell.getRow().getData();
                        const initials = data.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        return `
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-stone-50 rounded-xl flex items-center justify-center text-stone-400 font-black text-xs border border-stone-100 shadow-sm">
                                ${initials}
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-stone-800 text-sm tracking-tight truncate">${data.name}</p>
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest truncate">${data.email}</p>
                            </div>
                        </div>
                    `;
                    }
                },
                {
                    title: "Volume",
                    field: "total_orders",
                    width: 120,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function (cell) {
                        return `
                            <div class="text-center">
                                <span class="text-sm font-black text-stone-800">${cell.getValue()}</span>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Orders</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Lifetime Value",
                    field: "total_spent",
                    width: 150,
                    sorter: "number",
                    hozAlign: "right",
                    formatter: function (cell) {
                        return `
                            <div class="text-right">
                                <span class="text-sm font-black text-red-500">$${cell.getValue().toLocaleString()}</span>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Spent</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Avg. Purchase",
                    field: "avg_order_value",
                    width: 140,
                    sorter: "number",
                    hozAlign: "right",
                    formatter: function (cell) {
                        return `
                            <div class="text-right">
                                <span class="text-sm font-black text-stone-800">$${cell.getValue().toLocaleString()}</span>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest">per order</p>
                            </div>
                        `;
                    }
                },
                {
                    title: "Status",
                    field: "status",
                    width: 120,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: { values: { "": "All", "active": "Active", "inactive": "Inactive" } },
                    formatter: function (cell) {
                        const status = cell.getValue();
                        const config = status === 'active' 
                            ? { class: 'bg-red-50 text-red-600 border-red-100', icon: 'fa-check-circle' }
                            : { class: 'bg-rose-50 text-rose-600 border-stone-100', icon: 'fa-times-circle' };

                        return `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border ${config.class}">
                        <i class="fas ${config.icon} mr-1.5"></i>
                        ${status}
                    </span>`;
                    }
                },
                {
                    title: "Details",
                    field: "id",
                    width: 120,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function (cell) {
                        return `
                            <button onclick="viewCustomer(${cell.getValue()})" 
                                class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all hover:scale-110 shadow-sm border border-red-100">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </button>
                        `;
                    }
                }
            ],
            footerElement: "<div class='text-stone-400 font-bold uppercase tracking-widest text-[10px] px-4'>Customer Intelligence Analysis</div>"
        });

        customersReportTable.on("tableBuilt", () => {
            initSearch();
            initExport();
            initColumnVisibility();
            initPeriodFilter();
        });

    });

    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function () {
            customersReportTable.setFilter([
                [{ field: "name", type: "like", value: this.value },
                 { field: "email", type: "like", value: this.value },
                 { field: "location", type: "like", value: this.value }]
            ]);
        });
    }

    function initColumnVisibility() {
        const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
        const columnMenu = document.createElement('div');
        columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-2 z-50 hidden right-12 animate-in fade-in slide-in-from-top-2 duration-200';

        const columns = customersReportTable.getColumnDefinitions();
        columns.forEach(column => {
            if (["id"].includes(column.field)) return;
            const field = column.field;
            const columnBtn = document.createElement('button');
            columnBtn.className = 'w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all';
            columnBtn.innerHTML = `
                <input type="checkbox" class="mr-3 rounded border-stone-300 text-red-500 focus:ring-red-500 h-4 w-4" ${customersReportTable.getColumn(field).isVisible() ? 'checked' : ''}>
                ${column.title}
            `;
            columnBtn.addEventListener('click', (e) => {
                e.preventDefault(); e.stopPropagation();
                const col = customersReportTable.getColumn(field);
                col.toggle();
                columnBtn.querySelector('input').checked = col.isVisible();
            });
            columnMenu.appendChild(columnBtn);
        });

        columnVisibilityBtn.addEventListener('click', (e) => { e.stopPropagation(); columnMenu.classList.toggle('hidden'); });
        document.addEventListener('click', () => columnMenu.classList.add('hidden'));
        columnVisibilityBtn.parentElement.appendChild(columnMenu);
    }

    function initExport() {
        const exportBtns = document.querySelectorAll('[data-export]');
        exportBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const format = this.getAttribute('data-export');
                if (format === 'csv') customersReportTable.download("csv", "customer_intelligence.csv");
                else if (format === 'xlsx') customersReportTable.download("xlsx", "customer_intelligence.xlsx", { sheetName: "Intelligence" });
                else if (format === 'print') window.print();
            });
        });

        document.getElementById('exportReportBtn').addEventListener('click', () => {
            Swal.fire({
                title: 'Customer Data Export',
                width: 500,
                html: `
                    <div class="grid grid-cols-2 gap-4 p-2">
                        <button onclick="exportCustomerReport('excel')" class="flex flex-col items-center p-6 bg-stone-50 border border-stone-100 rounded-2xl hover:bg-white hover:shadow-md transition-all group">
                            <i class="fas fa-file-excel text-red-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-black text-stone-800 text-xs uppercase tracking-widest text-center leading-tight">Full Excel<br>Report</span>
                        </button>
                        <button onclick="exportCustomerReport('pdf')" class="flex flex-col items-center p-6 bg-stone-50 border border-stone-100 rounded-2xl hover:bg-white hover:shadow-md transition-all group">
                            <i class="fas fa-file-pdf text-rose-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-black text-stone-800 text-xs uppercase tracking-widest text-center leading-tight">Executive PDF<br>Summary</span>
                        </button>
                    </div>
                `,
                showConfirmButton: false, showCancelButton: true,
                customClass: { popup: 'rounded-3xl' }
            });
        });
    }

    function initPeriodFilter() {
        document.getElementById('periodFilter').addEventListener('change', function () {
            toastr.info(`Syncing data for: ${this.options[this.selectedIndex].text}...`);
            setTimeout(() => { toastr.success('Customer intelligence updated!'); }, 800);
        });
    }

    function updateMetrics() {
        // Mock updates
        toastr.info("Synchronizing customer metrics...");
    }

    function initCustomerGrowthChart() {
        const ctx = document.getElementById('customerGrowthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Direct Growth',
                    data: [4200, 4800, 5100, 4900, 5300, 5423],
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

    function exportCustomerReport(type) {
        Swal.fire({
            title: 'Generating Report', html: '<div class="text-stone-400 font-medium mt-2">Compiling demographics & purchase history...</div>',
            allowOutsideClick: false, timer: 1500, didOpen: () => { Swal.showLoading(); Swal.getPopup().classList.add('rounded-3xl'); }
        }).then(() => {
            toastr.success(`Customer ${type.toUpperCase()} report generated!`);
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
.tabulator-page { border-radius: 8px !important; border: 1px solid #e7e5e4 !important; padding: 5px 10px !important; margin: 0 2px !important; font-weight: bold !important; font-size: 11px !important; color: #78716c !important; }
.tabulator-page.active { background-color: #0ea5e9 !important; border-color: #0ea5e9 !important; color: white !important; }
</style>
@endpush
