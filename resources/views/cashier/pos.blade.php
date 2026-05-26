@use(App\Enums\PaymentMethod)
<x-app-layout layout="layouts.pos">
    <!-- The Alpine.js Brain -->
    <div x-data="posApp()" class="h-screen flex flex-col">

        <!-- Top Bar -->
        <div class="bg-teal-700 text-white px-6 py-2 flex justify-between items-center shadow-md">
            <h1 class="text-lg font-bold tracking-wide">Point of Sale</h1>

            <div class="hidden md:block">
                <div class="flex items-center space-x-3">
                    <div class="w-7 h-7 rounded-full bg-teal-500 flex items-center justify-center text-xs font-bold text-white uppercase">
                        {{ substr(auth()->user()->username ?? auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-teal-200 uppercase tracking-wider font-semibold">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <button type="submit" class="text-teal-300 hover:text-white transition-colors flex items-center" title="Logout">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                        </button>
                    </form>
                    <!-- This button opens Sales History Modal -->
                    <button @click="fetchMySales()" class="flex items-center space-x-1 text-teal-200 hover:text-white transition">
                        <x-heroicon-o-clock class="w-5 h-5" />
                        <span class="text-sm font-medium">My History</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Split Screen Body -->
        <div class="flex-1 grid grid-cols-5 divide-x divide-gray-200 overflow-hidden">

            <!-- LEFT SIDE: Product Grid (Takes up 3/5 space) -->
            <div class="col-span-3 p-4 bg-gray-50 overflow-y-auto relative">
                <!-- Search Bar -->
                <input type="text" x-model="search"
                    placeholder="Search products by name..."
                    class="w-full mb-4 px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">

                <!-- Product Grid -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Alpine loop: filter products based on search input -->
                    <template x-for="product in filteredProducts()" :key="product.id">
                        <button @click="selectedProduct = product"
                            :class="!product.is_active ? 'opacity-50 cursor-not-allowed bg-gray-200' : 'bg-white hover:border-teal-500 hover:shadow-md cursor-pointer'"
                            class="p-3 rounded-lg border border-gray-200 transition-all text-left flex flex-col justify-between h-28">
                            <div>
                                <div class="flex justify-between items-start">
                                    <p class="font-semibold text-sm text-gray-800 truncate" x-text="product.name"></p>
                                    <!-- Badge if inactive -->
                                    <span x-show="!product.is_active" class="text-[10px] font-bold text-red-600 bg-red-100 px-1 rounded">OFF</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate" x-text="product.base_unit"></p>
                            </div>
                            <p class="text-teal-700 font-bold mt-2" x-text="'$' + parseFloat(product.selling_price).toFixed(2)"></p>
                        </button>
                    </template>

                    <!-- Empty State -->
                    <p x-show="filteredProducts().length === 0" class="col-span-3 text-center text-gray-500 py-10">
                        No products found.
                    </p>
                </div>

                <!-- PRODUCT INFO DRAWER -->
                <div x-show="selectedProduct"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-full"
                    class="absolute inset-0 z-20 bg-white shadow-2xl p-6 overflow-y-auto flex flex-col">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Product Details</h2>
                        <button @click="selectedProduct = null" class="text-gray-400 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    <template x-if="selectedProduct">
                        <div class="flex-1">
                            <img :src="selectedProduct.image ? '/storage/' + selectedProduct.image : 'https://via.placeholder.com/400'" class="w-full h-48 object-cover rounded-lg mb-4 bg-gray-100">

                            <h3 class="text-2xl font-bold text-gray-900" x-text="selectedProduct.name"></h3>
                            <p class="text-sm text-gray-500 mb-4" x-text="selectedProduct.description || 'No description provided.'"></p>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-xs text-gray-500">Price</p>
                                    <p class="text-lg font-bold text-teal-700" x-text="'$' + parseFloat(selectedProduct.selling_price).toFixed(2)"></p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-xs text-gray-500">In Stock</p>
                                    <p class="text-lg font-bold" :class="selectedProduct.stock_quantity <= 5 ? 'text-red-600' : 'text-gray-900'" x-text="selectedProduct.stock_quantity + ' ' + selectedProduct.base_unit + 's'"></p>
                                </div>
                            </div>

                            <button @click="addToCart(selectedProduct); selectedProduct = null;"
                                :disabled="!selectedProduct.is_active"
                                class="w-full bg-teal-600 text-white py-3 rounded-lg font-bold hover:bg-teal-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                Add to Cart
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- RIGHT SIDE: Cart (Takes up 2/5 space) -->
            <div class="col-span-2 flex flex-col bg-white">
                <div class="px-4 py-3 border-b font-bold text-gray-700 flex justify-between">
                    <span>Current Sale</span>
                    <span x-text="cartCount() + ' items'"></span>
                </div>

                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    <template x-for="(item, id) in cart" :key="id">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800" x-text="item.name"></p>
                                <p class="text-sm text-gray-500" x-text="'$' + parseFloat(item.price).toFixed(2) + ' each'"></p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2 mx-4">
                                <button @click="updateQty(id, -1)" class="w-7 h-7 rounded bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-bold">-</button>
                                <span class="w-8 text-center font-bold" x-text="item.qty"></span>
                                <button @click="updateQty(id, 1)" class="w-7 h-7 rounded bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-bold">+</button>
                            </div>

                            <p class="w-20 text-right font-bold text-gray-800" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>

                            <!-- Remove Button -->
                            <button @click="removeFromCart(id)" class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <div x-show="Object.keys(cart).length === 0" class="text-center text-gray-400 mt-20">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                        </svg>
                        <p>Cart is empty</p>
                    </div>
                </div>

                <!-- Cart Footer / Total -->
                <div class="border-t p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-bold text-gray-700">TOTAL:</span>
                        <span class="text-3xl font-bold text-teal-700" x-text="'$' + cartTotal().toFixed(2)"></span>
                    </div>
                    <button @click="clearCart()" class="w-full mb-2 bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300 font-medium">Clear Cart</button>

                    <!-- This button opens Confirmation Modal -->
                    <button @click="showConfirmCheckout = true"
                        class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="Object.keys(cart).length === 0">
                        <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                        <span>Checkout</span>
                    </button>

                    <!-- THE CONFIRMATION MODAL -->
                    <div x-show="showConfirmCheckout"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

                        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4"
                            @click.away="showConfirmCheckout = false">
                            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-teal-100 mb-4">
                                <x-heroicon-o-shopping-bag class="w-8 h-8 text-teal-600" />
                            </div>

                            <!-- Inside the Confirmation Modal -->
                            <p class="text-sm text-gray-500 mb-4">Select payment method and finalize:</p>

                            <!-- Payment Method DROPDOWN -->
                            <select x-model="selectedPayment" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm mb-4 focus:ring-teal-500 focus:border-teal-500">
                                @foreach(PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                                @endforeach
                            </select>

                            <p class="text-4xl font-bold text-teal-700 mb-8" x-text="'$' + cartTotal().toFixed(2)"></p>

                            <div class="flex space-x-3">
                                <button @click="showConfirmCheckout = false" class="flex-1 bg-gray-200 text-gray-700 py-2.5 rounded-lg font-medium hover:bg-gray-300">
                                    Cancel
                                </button>
                                <!-- The real processing happens here -->
                                <button @click="processCheckout()"
                                    :disabled="isCheckingOut"
                                    class="flex-1 bg-teal-600 text-white py-2.5 rounded-lg font-bold hover:bg-teal-700 disabled:opacity-50 flex items-center justify-center">
                                    <svg x-show="isCheckingOut" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isCheckingOut ? 'Processing...' : 'Confirm'" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- MY SALES HISTORY MODAL -->
                    <div x-show="showMySales"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col"
                            @click.away="showMySales = false">

                            <div class="p-6 border-b flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-900">My Recent Sales</h3>
                                <button @click="showMySales = false" class="text-gray-400 hover:text-gray-600">
                                    <x-heroicon-o-x-mark class="w-6 h-6" />
                                </button>
                            </div>

                            <div class="flex-1 overflow-y-auto p-6">
                                <div class="space-y-3">
                                    <template x-for="sale in mySales" :key="sale.id">
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                                            <div>
                                                <p class="font-mono text-sm font-bold text-teal-700" x-text="sale.sale_number"></p>
                                                <p class="text-xs text-gray-500 mt-1" x-text="new Date(sale.created_at).toLocaleString()"></p>
                                            </div>

                                            <p class="text-sm text-gray-500 font-medium" x-text="paymentLabels[sale.payment_method] || sale.payment_method"></p>

                                            <span class="text-lg font-bold text-gray-900" x-text="'$' + parseFloat(sale.total_amount).toFixed(2)"></span>
                                        </div>
                                    </template>

                                    <p x-show="mySales.length === 0" class="text-center text-gray-500 py-8">No sales recorded yet.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="showSuccess"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed bottom-6 right-6 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3 z-50"
            style="display: none;"> <x-heroicon-o-check-circle class="w-6 h-6 flex-shrink-0" />

            <div>
                <p class="font-bold">Sale Completed!</p>
                <p class="text-sm text-green-100" x-text="'Receipt #: ' + lastSaleNumber"></p>
            </div>
        </div>
    </div>

    <script>
        function posApp() {
            return {
                search: '',
                cart: @js(session('cart', [])),
                products: @js($products),
                paymentLabels: @js(PaymentMethod::options()),

                // New state variables
                isCheckingOut: false,
                showSuccess: false,
                lastSaleNumber: '',

                selectedProduct: null, // Holds the product object for the drawer
                selectedPayment: '{{ PaymentMethod::Cash->value }}', // Default to cash
                showConfirmCheckout: false, // Toggles the checkout modal
                showMySales: false, // Toggles the sales history modal
                mySales: [], // Holds the fetched sales data

                filteredProducts() {
                    if (!this.search) return this.products;
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                addToCart(product) {
                    if (product.expiration_date && new Date(product.expiration_date) < new Date()) {
                        alert('Cannot sell expired products.');
                        return;
                    }
                    if (!product.is_active) {
                        alert('This product is currently deactivated by admin.');
                        return;
                    }
                    // Send AJAX request to our CartController
                    fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                product_id: product.id,
                                qty: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Update Alpine state with the new cart from the server
                            this.cart = data.cart;
                        });
                },

                removeFromCart(id) {
                    fetch('/cart/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                product_id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.cart = data.cart;
                        });
                },

                updateQty(id, change) {
                    const item = this.cart[id];
                    const newQty = item.qty + change;

                    if (newQty <= 0) {
                        this.removeFromCart(id);
                        return;
                    }

                    // Re-use add logic, passing the new total quantity
                    fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                product_id: id,
                                qty: change // Controller handles += logic
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.cart = data.cart;
                        });
                },

                clearCart() {
                    fetch('/cart/clear', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.cart = data.cart;
                        });
                },

                cartTotal() {
                    return Object.values(this.cart).reduce((total, item) => {
                        return total + (item.price * item.qty);
                    }, 0);
                },

                cartCount() {
                    return Object.values(this.cart).reduce((count, item) => {
                        return count + item.qty;
                    }, 0);
                },

                processCheckout() {
                    this.isCheckingOut = true;

                    fetch('/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                payment_method: this.selectedPayment
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.isCheckingOut = false;
                            this.showConfirmCheckout = false;

                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            if (data.success) {
                                this.lastSaleNumber = data.sale_number;
                                this.showSuccess = true;

                                // 1. Update stock in the UI grid instantly!
                                for (const [id, item] of Object.entries(this.cart)) {
                                    let product = this.products.find(p => p.id == id);
                                    if (product) {
                                        product.stock_quantity -= item.qty;
                                    }
                                }

                                // 2. Clear the local Alpine cart
                                this.cart = {};

                                // 3. Force My Sales to refresh next time they open it
                                this.mySales = [];

                                setTimeout(() => {
                                    this.showSuccess = false;
                                }, 4000);
                            }
                        })
                        .catch(error => {
                            this.isCheckingOut = false;
                            alert('Network error. Please check connection.');
                        });

                },

                fetchMySales() {
                    this.showMySales = true;
                    // Only fetch from server if we haven't already, to save bandwidth
                    if (this.mySales.length === 0) {
                        fetch('/pos/my-sales')
                            .then(response => response.json())
                            .then(data => {
                                this.mySales = data;
                            });
                    }
                }

            }
        }
    </script>
</x-app-layout>