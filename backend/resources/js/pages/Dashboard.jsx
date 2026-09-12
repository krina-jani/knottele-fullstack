import React from 'react';
import Navbar from '../components/Navbar';
import Button from '../components/Button';

const Dashboard = () => {
    return (
        <div className="bg-gray-900 min-h-screen text-white font-sans">
            <Navbar />
            
            <div className="pt-32 pb-20 max-w-7xl mx-auto px-6 lg:px-8">
                <div className="flex justify-between items-center mb-10">
                    <h1 className="text-3xl font-bold">Dashboard</h1>
                    <Button variant="outline">Edit Profile</Button>
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div className="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div className="text-gray-400 text-sm font-medium mb-2">Total Orders</div>
                        <div className="text-4xl font-bold text-white">12</div>
                    </div>
                    <div className="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div className="text-gray-400 text-sm font-medium mb-2">Active Wishlist</div>
                        <div className="text-4xl font-bold text-cyan-400">4</div>
                    </div>
                    <div className="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div className="text-gray-400 text-sm font-medium mb-2">Rewards Balance</div>
                        <div className="text-4xl font-bold text-white">$45.00</div>
                    </div>
                </div>
                
                <h3 className="text-xl font-bold mb-6">Recent Orders</h3>
                <div className="bg-white/5 border border-white/10 rounded-2xl overflow-hidden backdrop-blur-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-white/5 border-b border-white/10 text-sm text-gray-400">
                                <tr>
                                    <th className="px-6 py-4 font-medium">Order ID</th>
                                    <th className="px-6 py-4 font-medium">Date</th>
                                    <th className="px-6 py-4 font-medium">Status</th>
                                    <th className="px-6 py-4 font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-white/10">
                                <tr className="hover:bg-white/5 transition-colors">
                                    <td className="px-6 py-4">#ORD-0921</td>
                                    <td className="px-6 py-4 text-gray-300">Oct 12, 2026</td>
                                    <td className="px-6 py-4"><span className="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium border border-green-500/20">Delivered</span></td>
                                    <td className="px-6 py-4 font-medium">$399.00</td>
                                </tr>
                                <tr className="hover:bg-white/5 transition-colors">
                                    <td className="px-6 py-4">#ORD-0844</td>
                                    <td className="px-6 py-4 text-gray-300">Sep 04, 2026</td>
                                    <td className="px-6 py-4"><span className="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium border border-green-500/20">Delivered</span></td>
                                    <td className="px-6 py-4 font-medium">$249.00</td>
                                </tr>
                                <tr className="hover:bg-white/5 transition-colors">
                                    <td className="px-6 py-4">#ORD-0712</td>
                                    <td className="px-6 py-4 text-gray-300">Aug 21, 2026</td>
                                    <td className="px-6 py-4"><span className="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium border border-blue-500/20">Processing</span></td>
                                    <td className="px-6 py-4 font-medium">$189.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
