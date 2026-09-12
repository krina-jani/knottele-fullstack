import React from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import ProductCard from '../components/ProductCard';

const Products = () => {
    return (
        <div className="bg-gray-900 min-h-screen text-white font-sans">
            <Navbar />
            
            <div className="pt-32 pb-20 max-w-7xl mx-auto px-6 lg:px-8">
                <div className="text-center max-w-2xl mx-auto mb-16">
                    <h1 className="text-4xl md:text-5xl font-extrabold mb-4">Our Collection</h1>
                    <p className="text-gray-400 text-lg">
                        Explore our full range of premium products, designed to elevate your everyday experience.
                    </p>
                </div>
                
                {/* Filters */}
                <div className="flex flex-wrap gap-4 justify-center mb-12">
                    {['All', 'Electronics', 'Accessories', 'Apparel', 'Home'].map(category => (
                        <button key={category} className="px-5 py-2 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 hover:border-cyan-400 transition-all text-sm font-medium">
                            {category}
                        </button>
                    ))}
                </div>
                
                {/* Product Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard title="Minimalist Watch" price="$249.00" image="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Premium Headphones" price="$399.00" image="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Leather Messenger" price="$189.00" image="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Smart Speaker" price="$299.00" image="https://images.unsplash.com/photo-1543512214-318c7553f230?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Mechanical Keyboard" price="$159.00" image="https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Wireless Mouse" price="$89.00" image="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Desk Mat" price="$45.00" image="https://images.unsplash.com/photo-1616423640778-28d1b53229bd?q=80&w=600&auto=format&fit=crop" />
                    <ProductCard title="Table Lamp" price="$120.00" image="https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=600&auto=format&fit=crop" />
                </div>
            </div>
            
            <Footer />
        </div>
    );
};

export default Products;
