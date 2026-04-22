import listavailable from "@/components/inventorylist";
export default function Inventory(){
    return(
        <div className="flex h-screen bg-gray-100">
        {/* Sidebar */}
        <aside className="w-64 bg-white border-r hidden md:block">
            <div className="p-4 font-bold text-xl">My App</div>
            <nav className="mt-4">
            <a href="/dashboard" className="block px-4 py-2 hover:bg-gray-200">Overview</a>
            <a href="/dashboard/invoices" className="block px-4 py-2 hover:bg-gray-200">Invoices</a>
            </nav>
        </aside>

        {/* Main Content Area */}
        <div className="flex-1 flex flex-col overflow-hidden">
            <header className="h-16 bg-white border-b flex items-center px-6">
            <h1 className="text-lg font-semibold">Dashboard</h1>
            </header>
            <main className="flex-1 overflow-y-auto p-6">
            Test
            {listavailable()}
            </main>
        </div>
        </div>
    );
}