import React from 'react';
import { Outlet } from 'react-router-dom';

const AuthLayout = () => {
    return (
        <div className="bg-gray-900 min-h-screen text-white font-sans flex flex-col">
            <main className="flex-grow flex flex-col justify-center">
                <Outlet />
            </main>
        </div>
    );
};

export default AuthLayout;
