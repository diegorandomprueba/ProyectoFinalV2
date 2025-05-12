<nav class="bg-black text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-500">Trendfit</a>
        <ul class="flex space-x-6">
            <li><a href="{{ route('home') }}" class="hover:text-orange-500">Portada</a></li>
            <li><a href="{{ route('about') }}" class="hover:text-orange-500">Quien Somos</a></li>
            <li><a href="{{ route('where') }}" class="hover:text-orange-500">Donde Estamos</a></li>
            @guest
                <li><a href="{{ route('register') }}" class="hover:text-orange-500">Registro</a></li>
            @endguest
            <li><a href="{{ route('shop') }}" class="hover:text-orange-500">Compra</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Contacto</a></li>
        </ul>
        <div class="flex items-center space-x-6">
            <a href="{{ route('cart') }}" class="text-white hover:text-orange-500 relative">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span class="cart-count absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
            </a>
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-white">
                        {{ Auth::user()->name }}
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="{{ route('profile.update') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-500 hover:text-white">Mi Perfil</a>
                        <a href="{{ route('orders') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-500 hover:text-white">Mis Pedidos</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-orange-500 hover:text-white">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="bg-orange-500 px-4 py-2 rounded text-white hover:bg-orange-600 transition duration-200 ease-in-out">Login</a>
            @endauth
        </div>
    </div>
</nav>