import React from 'react';

const Loader = () => {
    return (
        <div className="flex justify-center items-center h-full w-full py-12">
            <div className="relative w-16 h-16">
                <div className="absolute inset-0 border-4 border-white/10 rounded-full"></div>
                <div className="absolute inset-0 border-4 border-cyan-400 rounded-full border-t-transparent animate-spin"></div>
                <div className="absolute inset-0 border-4 border-blue-500 rounded-full border-b-transparent animate-pulse opacity-50"></div>
            </div>
        </div>
    );
};

export default Loader;
