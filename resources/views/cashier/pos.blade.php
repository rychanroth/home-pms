<x-app-layout>
    <!-- The Alpine.js Brain -->
    <div x-data="posApp()" class="h-[calc(100vh-80px)] flex flex-col">

        <!-- Top Bar -->
        <div class="bg-teal-700 text-white px-6 py-3 flex justify-between items-center shadow-md">
            <h1 class="text-xl font-bold tracking-wide">Point of Sale</h1>
            <span class="text-teal-200 text-sm">Cashier: {{ auth()->user()->first_name }}</span>
        </div>

        <!-- Split Screen Body -->
        <div class="flex-1 grid grid-cols-5 divide-x divide-gray-200 overflow-hidden">

            <!-- LEFT SIDE: Product Grid (Takes up 3/5 space) -->
            <div class="col-span-3 p-4 bg-gray-50 overflow-y-auto">
                <!-- Search Bar -->
                <input type="text" x-model="search"
                    placeholder="Search products by name..."
                    class="w-full mb-4 px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">

                <!-- Product Grid -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Alpine loop: filter products based on search input -->
                    <template x-for="product in filteredProducts()" :key="product.id">
                        <button @click="addToCart(product)"
                            class="bg-white p-3 rounded-lg border border-gray-200 hover:border-teal-500 hover:shadow-md transition-all text-left flex flex-col justify-between h-28">
                            <div>
                                <p class="font-semibold text-sm text-gray-800 truncate" x-text="product.name"></p>
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
                    <button @click="processCheckout()"
                        class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="Object.keys(cart).length === 0 || isCheckingOut">

                        <x-heroicon-o-arrow-path x-show="isCheckingOut" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" />

                        <span x-text="isCheckingOut ? 'Processing...' : 'Checkout'"></span>
                    </button>
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

                // New state variables
                isCheckingOut: false,
                showSuccess: false,
                lastSaleNumber: '',

                filteredProducts() {
                    if (!this.search) return this.products;
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                addToCart(product) {
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
                                payment_method: 'cash' // Hardcoded for MVP as per proposal
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.isCheckingOut = false;

                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            // Success!
                            this.lastSaleNumber = data.sale_number;
                            this.showSuccess = true;

                            // Clear the local Alpine cart (Session was already cleared by server)
                            this.cart = {};

                            // Hide success popup after 4 seconds
                            setTimeout(() => {
                                this.showSuccess = false;
                            }, 4000);
                        })
                        .catch(error => {
                            this.isCheckingOut = false;
                            alert('Network error. Please check connection.');
                        });
                }

            }
        }
    </script>
</x-app-layout>