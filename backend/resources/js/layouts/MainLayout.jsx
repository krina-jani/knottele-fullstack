import React from 'react';
import { Outlet } from 'react-router-dom';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';

const MainLayout = () => {
    return (
        <div className="bg-gray-900 min-h-screen text-white font-sans flex flex-col">
            <Navbar />
            
            {/* Main content area */}
            <main className="flex-grow">
                <Outlet />
            </main>
            
            <Footer />
        </div>
    );
};

export default MainLayout;
