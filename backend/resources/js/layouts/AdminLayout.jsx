import React from 'react';
import { Outlet } from 'react-router-dom';

const AdminLayout = () => {
    return (
        <div className="bg-gray-900 min-h-screen text-white font-sans flex">
            {/* Admin Sidebar */}
            <aside className="w-64 bg-gray-950 border-r border-white/10 hidden md:flex flex-col">
                <div className="h-16 flex items-center px-6 border-b border-white/10">
                    <span className="text-xl font-extrabold tracking-tight text-white">NNUI<span className="text-cyan-400">.</span> Admin</span>
                </div>
                <nav className="flex-1 px-4 py-6 space-y-2">
                    <a href="#" className="flex items-center px-4 py-3 bg-white/5 text-cyan-400 rounded-xl font-medium transition-colors">
                        Dashboard
                    </a>
                    <a href="#" className="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition-colors">
                        Products
                    </a>
                    <a href="#" className="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition-colors">
                        Orders
                    </a>
                    <a href="#" className="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition-colors">
                        Customers
                    </a>
                </nav>
                <div className="p-4 border-t border-white/10">
                    <a href="#" className="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl font-medium transition-colors">
                        Logout
                    </a>
                </div>
            </aside>
            
            {/* Admin Main Content Area */}
            <div className="flex-1 flex flex-col h-screen overflow-hidden">
                <header className="h-16 bg-gray-950/50 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6">
                    <h2 className="text-lg font-semibold text-gray-200">Admin Dashboard</h2>
                    <div className="flex items-center gap-3">
                        <div className="w-8 h-8 rounded-full bg-cyan-500 flex items-center justify-center text-sm font-bold">
                            A
                        </div>
                    </div>
                </header>
                
                <main className="flex-1 overflow-y-auto p-6">
                    <Outlet />
                </main>
            </div>
        </div>
    );
};

export default AdminLayout;
