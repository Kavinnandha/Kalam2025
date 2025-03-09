<?php session_start(); ?>
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

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Mobile order summary page styles */
        .mobile-order-summary {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: white;
            z-index: 60;
            overflow-y: auto;

            padding-bottom: 5rem;
        }
        
        @media (min-width: 640px) {
            .mobile-order-summary {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-yellow-50" x-data="{ 
    cartItems: [],
    totalPrice: 0,
    showOrderModal: false,
    showMobileOrderSummary: false,
    orderSummary: {
        userData: {},
        items: [],
        subtotal: 0,
        generalFee: 0,
        totalAmount: 0,
        hasExistingOrder: false
    },
    isMobile: window.innerWidth < 640,
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
        window.location.href = `../categories/event_details.php?event_id=${eventId}`;
    },
    async openOrderSummary() {
        try {
            const response = await fetch('get_order_summary.php');
            const result = await response.json();
            
            if (result.success) {
                this.orderSummary = result;
                // Check screen size and show appropriate view
                if (this.isMobile) {
                    this.showMobileOrderSummary = true;
                } else {
                    this.showOrderModal = true;
                }
            } else {
                alert('Could not load order details. Please try again.');
            }
        } catch (error) {
            console.error('Error loading order summary:', error);
            alert('An error occurred. Please try again.');
        }
    },
    proceedToPayment() {
        window.location.href = '../payment/cashfree_initiate.php';
    },
    closeMobileOrderSummary() {
        this.showMobileOrderSummary = false;
    },
    checkMobile() {
        this.isMobile = window.innerWidth < 640;
    }
}" x-init="loadCartItems(); checkMobile(); window.addEventListener('resize', checkMobile)">
    <?php include '../header/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-yellow-800 mb-8">Your Cart</h1>

            <!-- Cart Items -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <template x-if="cartItems.length > 0">
                    <div class="divide-y divide-yellow-100">
                        <template x-for="item in cartItems" :key="item.cart_item_id">
                            <!-- Desktop view (original layout) and mobile view (new layout) -->
                            <div @click="goToEventDetails(item.event_id)"
                                class="hover:bg-yellow-50 transition-colors duration-200 cursor-pointer">

                                <!-- Desktop layout (hidden on small screens) -->
                                <div class="hidden sm:flex p-6 items-center space-x-4">
                                    <div class="w-24 h-24 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <template x-if="item.image_path">
                                            <img :src="item.image_path" :alt="item.event_name"
                                                class="w-24 h-24 object-cover rounded-lg">
                                        </template>
                                        <template x-if="!item.image_path">
                                            <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </template>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-yellow-800" x-text="item.event_name"></h3>
                                        <p class="text-sm text-yellow-600 mt-1 text-truncate"
                                            x-text="item.event_detail"></p>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <span class="text-sm text-orange-700"
                                                x-text="'Date: ' + item.event_date"></span>
                                            <span class="text-sm text-orange-700"
                                                x-text="'Time: ' + item.start_time + ' - ' + item.end_time"></span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="text-sm text-orange-700"
                                                x-text="'Venue: ' + item.venue"></span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="text-sm text-orange-700"
                                                x-text="'Department: ' + item.department_name"></span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-orange-700"
                                            x-text="'₹' + parseFloat(item.registration_fee).toFixed(2)"></p>
                                        <button @click="removeItem($event, item.cart_item_id)"
                                            class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-200">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <!-- Mobile layout (visible only on small screens) -->
                                <div class="sm:hidden p-4">
                                    <!-- First row: Image | (Event Name and Price on separate lines) -->
                                    <div class="flex items-start space-x-3 mb-3">
                                        <!-- Image column -->
                                        <div
                                            class="w-20 h-20 bg-orange-100 rounded-lg flex-shrink-0 flex items-center justify-center">
                                            <template x-if="item.image_path">
                                                <img :src="item.image_path" :alt="item.event_name"
                                                    class="w-20 h-20 object-cover rounded-lg">
                                            </template>
                                            <template x-if="!item.image_path">
                                                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </template>
                                        </div>

                                        <!-- Name and price column (stacked vertically) -->
                                        <div class="flex-1 min-w-0 flex flex-col">
                                            <!-- Event name with 2-line limit -->
                                            <h3 class="text-base font-semibold text-yellow-800 line-clamp-2 leading-tight"
                                                x-text="item.event_name"></h3>
                                            <!-- Price on separate line -->
                                            <p class="text-base font-semibold text-orange-700 mt-1"
                                                x-text="'₹' + parseFloat(item.registration_fee).toFixed(2)"></p>
                                        </div>
                                    </div>

                                    <!-- Second row: All other details -->
                                    <div class="pl-2">
                                        <p class="text-xs text-yellow-600 mb-2 line-clamp-2" x-text="item.event_detail">
                                        </p>

                                        <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-xs">
                                            <div class="text-orange-700">
                                                <span x-text="'Date: ' + item.event_date"></span>
                                            </div>
                                            <div class="text-orange-700">
                                                <span
                                                    x-text="'Time: ' + item.start_time + ' - ' + item.end_time"></span>
                                            </div>
                                            <div class="text-orange-700 col-span-2">
                                                <span x-text="'Venue: ' + item.venue"></span>
                                            </div>
                                            <div class="text-orange-700 col-span-2">
                                                <span x-text="'Department: ' + item.department_name"></span>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-right">
                                            <button @click="removeItem($event, item.cart_item_id)"
                                                class="text-red-600 hover:text-red-800 text-xs font-medium transition-colors duration-200">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="cartItems.length === 0">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-yellow-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-yellow-800">Your cart is empty</h3>
                        <p class="mt-1 text-sm text-yellow-600">Start adding some events to your cart!</p>
                        <a href="../categories/departments.php"
                            class="mt-6 inline-block px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors duration-200">
                            Browse Events
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Fixed Bottom Bar with Mobile Adjustments -->
    <div class="fixed left-0 right-0 bg-white shadow-lg border-t border-yellow-200 sm:bottom-0 bottom-14"
        x-show="cartItems.length > 0" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-full"
        x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-baseline">
                    <span class="text-sm text-yellow-600 mr-2">Total:</span>
                    <span class="text-2xl font-bold text-yellow-800" x-text="'₹' + totalPrice.toFixed(2)"></span>
                </div>
                <button @click="openOrderSummary()"
                    class="px-8 py-3 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-200">
                    Place Order
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Order Summary Modal (shown only on larger screens) -->
    <div class="modal-backdrop hidden sm:flex" x-show="showOrderModal" x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="modal-content" @click.outside="showOrderModal = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-yellow-800 mb-4">Order Summary</h2>

                <!-- User Details -->
                <div class="mb-6 bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Customer Information</h3>
                    <p class="text-sm text-yellow-700"><span class="font-medium">Name:</span> <span
                            x-text="orderSummary.userData.name"></span></p>
                    <p class="text-sm text-yellow-700 mt-1"><span class="font-medium">Email:</span> <span
                            x-text="orderSummary.userData.email"></span></p>
                    <p class="text-sm text-yellow-700 mt-1"><span class="font-medium">Phone:</span> <span
                            x-text="orderSummary.userData.phone"></span></p>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Order Items</h3>
                    <div class="border border-yellow-200 rounded-lg overflow-hidden">
                        <div class="divide-y divide-yellow-100">
                            <template x-for="(item, index) in orderSummary.items" :key="index">
                                <div class="p-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-yellow-800" x-text="item.event_name"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-orange-700"
                                            x-text="'₹' + parseFloat(item.registration_fee).toFixed(2)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Cost Summary -->
                <div class="mb-6 bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Cost Summary</h3>
                    <div class="flex justify-between mb-2">
                        <span class="text-yellow-700">Subtotal</span>
                        <span class="font-medium text-yellow-800"
                            x-text="'₹' + orderSummary.subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between mb-2" x-show="orderSummary.generalFee > 0">
                        <span class="text-yellow-700">General Fee</span>
                        <span class="font-medium text-yellow-800"
                            x-text="'₹' + orderSummary.generalFee.toFixed(2)"></span>
                    </div>
                    <div class="border-t border-yellow-200 pt-2 mt-2 flex justify-between">
                        <span class="font-bold text-yellow-800">Total</span>
                        <span class="font-bold text-orange-700"
                            x-text="'₹' + orderSummary.totalAmount.toFixed(2)"></span>
                    </div>
                    <div class="mt-2 text-xs text-yellow-600" x-show="orderSummary.generalFee > 0">
                        <p>* General fee is applied for first-time orders.</p>
                        <p>* All general events can be participated.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button @click="showOrderModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                    <button @click="proceedToPayment()"
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors duration-200">
                        Continue to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Order Summary Page (full-screen instead of modal) -->
    <div class="mb-10 mobile-order-summary" x-show="showMobileOrderSummary" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-full"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-full">
        
        <!-- Mobile header with back button -->
        <div class="sticky top-0 bg-white shadow-sm px-4 py-4 flex items-center z-10">
            <button @click="closeMobileOrderSummary()" class="mr-2 text-yellow-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-xl font-bold text-yellow-800">Order Summary</h1>
        </div>
        
        <div class="px-4 py">
            <!-- User Details -->
            <div class="mb-6 bg-yellow-50 p-4 rounded-lg mt-2">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Customer Information</h3>
                <p class="text-sm text-yellow-700"><span class="font-medium">Name:</span> <span
                        x-text="orderSummary.userData.name"></span></p>
                <p class="text-sm text-yellow-700 mt-1"><span class="font-medium">Email:</span> <span
                        x-text="orderSummary.userData.email"></span></p>
                <p class="text-sm text-yellow-700 mt-1"><span class="font-medium">Phone:</span> <span
                        x-text="orderSummary.userData.phone"></span></p>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Order Items</h3>
                <div class="border border-yellow-200 rounded-lg overflow-hidden">
                    <div class="divide-y divide-yellow-100">
                        <template x-for="(item, index) in orderSummary.items" :key="index">
                            <div class="p-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 pr-4">
                                        <p class="font-medium text-yellow-800" x-text="item.event_name"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-orange-700"
                                            x-text="'₹' + parseFloat(item.registration_fee).toFixed(2)"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Cost Summary -->
            <div class="mb-6 bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Cost Summary</h3>
                <div class="flex justify-between mb-2">
                    <span class="text-yellow-700">Subtotal</span>
                    <span class="font-medium text-yellow-800"
                        x-text="'₹' + orderSummary.subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between mb-2" x-show="orderSummary.generalFee > 0">
                    <span class="text-yellow-700">General Fee</span>
                    <span class="font-medium text-yellow-800"
                        x-text="'₹' + orderSummary.generalFee.toFixed(2)"></span>
                </div>
                <div class="border-t border-yellow-200 pt-2 mt-2 flex justify-between">
                    <span class="font-bold text-yellow-800">Total</span>
                    <span class="font-bold text-orange-700"
                        x-text="'₹' + orderSummary.totalAmount.toFixed(2)"></span>
                </div>
                <div class="mt-2 text-xs text-yellow-600" x-show="orderSummary.generalFee > 0">
                    <p>* General fee is applied for first-time orders.</p>
                    <p>* All general events can be participated.</p>
                </div>
            </div>
        </div>
        
        <!-- Fixed bottom action buttons for mobile -->
        <div class="fixed bottom-15 left-0 right-0 bg-white border-t border-yellow-200 p-4">
            <button @click="proceedToPayment()"
                class="w-full py-3 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors duration-200">
                Continue to Payment
            </button>
        </div>
    </div>

</body>

</html>