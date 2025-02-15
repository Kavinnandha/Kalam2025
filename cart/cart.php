<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
    <style>
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .content-wrapper {
            padding-top: 5rem;
            padding-bottom: 6rem;
            min-height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-yellow-50" x-data="{ 
    cartItems: [],
    totalPrice: 0,
    async loadCartItems() {
        try {
            const response = await fetch('get_cart_items.php');
            const result = await response.json();
            if (result.success) {
                this.cartItems = result.data;
                this.calculateTotal();
            }
        } catch (error) {
            console.error('Error loading cart items:', error);
        }
    },
    calculateTotal() {
        this.totalPrice = this.cartItems.reduce((sum, item) => sum + parseFloat(item.registration_fee), 0);
    },
    async removeItem(event, cartItemId) {
        event.stopPropagation();
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            try {
                const response = await fetch('remove_cart_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart_item_id: cartItemId })
                });
                const result = await response.json();
                if (result.success) {
                    await this.loadCartItems();
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        }
    },
    goToEventDetails(eventId) {
        window.location.href = `../categories/event_details.php?id=${eventId}`;
    }
}" x-init="loadCartItems()">
    <?php include '../header/navbar.php'; ?>
    
    <div class="content-wrapper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-yellow-800 mb-8">Your Cart</h1>
            
            <!-- Cart Items -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <template x-if="cartItems.length > 0">
                    <div class="divide-y divide-yellow-100">
                        <template x-for="item in cartItems" :key="item.cart_item_id">
                            <div @click="goToEventDetails(item.event_id)" 
                                 class="p-6 flex items-center space-x-4 hover:bg-yellow-50 transition-colors duration-200 cursor-pointer">
                                <div class="w-24 h-24 bg-green-100 rounded-lg flex items-center justify-center">
                                    <template x-if="item.image_path">
                                        <img :src="item.image_path" :alt="item.event_name" 
                                             class="w-24 h-24 object-cover rounded-lg">
                                    </template>
                                    <template x-if="!item.image_path">
                                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </template>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-yellow-800" x-text="item.event_name"></h3>
                                    <p class="text-sm text-yellow-600 mt-1 text-truncate" x-text="item.event_detail"></p>
                                    <div class="mt-2 flex items-center space-x-4">
                                        <span class="text-sm text-green-700" x-text="'Date: ' + item.event_date"></span>
                                        <span class="text-sm text-green-700" 
                                              x-text="'Time: ' + item.start_time + ' - ' + item.end_time"></span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-sm text-green-700" x-text="'Venue: ' + item.venue"></span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-sm text-green-700" x-text="'Department: ' + item.department_name"></span>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-green-700" 
                                       x-text="'₹' + parseFloat(item.registration_fee).toFixed(2)"></p>
                                    <button @click="removeItem($event, item.cart_item_id)" 
                                            class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-200">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                
                <template x-if="cartItems.length === 0">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-yellow-800">Your cart is empty</h3>
                        <p class="mt-1 text-sm text-yellow-600">Start adding some events to your cart!</p>
                        <a href="../categories/departments.php" 
                           class="mt-6 inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                            Browse Events
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    <!-- Fixed Bottom Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t border-yellow-200" 
         x-show="cartItems.length > 0"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-baseline">
                    <span class="text-sm text-yellow-600 mr-2">Total:</span>
                    <span class="text-2xl font-bold text-yellow-800" x-text="'₹' + totalPrice.toFixed(2)"></span>
                </div>
                <button onclick="window.location.href='../payment/checkout.php'" 
                        class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                    Place Order
                </button>
            </div>
        </div>
    </div>
    
    <?php include '../header/navbar_scripts.php'; ?>
</body>
</html>