<?php
session_start();
require_once 'connect.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT p.*, c.category_name 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.category_id 
                       WHERE p.product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - <?php echo htmlspecialchars($product['product_name']); ?></title>
    
    <!-- Load CSS files first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    
    <!-- Load JavaScript files in correct order -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #232d39;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .main-container {
            padding: 80px 0;
            min-height: calc(100vh - 80px);
        }

        .order-card {
            background: #1a1a1a;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .product-side {
            background: linear-gradient(135deg, #ed563b, #ff8d6b);
            padding: 40px;
            height: 100%;
        }

        .product-image {
            width: 100%;
            height: 350px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #fff;
        }

        .category-badge {
            display: inline-block;
            padding: 8px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .order-side {
            padding: 40px;
        }

        .price-tag {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ed563b;
            margin-bottom: 30px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            background: #2a2a2a;
            padding: 20px;
            border-radius: 15px;
        }

        .qty-btn {
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 12px;
            background: #ed563b;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: #ff8d6b;
            transform: translateY(-2px);
        }

        #quantity {
            width: 80px;
            height: 45px;
            text-align: center;
            border: 2px solid #333;
            border-radius: 12px;
            background: transparent;
            color: white;
            font-size: 1.2rem;
        }

        .order-summary {
            background: #2a2a2a;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .summary-row.total {
            border-top: 2px solid #333;
            margin-top: 15px;
            padding-top: 15px;
            font-size: 1.3rem;
            font-weight: 600;
            color: #ed563b;
        }

        .place-order-btn {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #ed563b, #ff8d6b);
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .place-order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(237, 86, 59, 0.4);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 30px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #ed563b;
            transform: translateX(-5px);
        }

        /* Success Modal */
        .success-modal .modal-content {
            background: #232d39;
            border-radius: 20px;
            color: #fff;
        }

        .success-modal .modal-header {
            border-bottom: 1px solid #333;
        }

        .success-modal .modal-footer {
            border-top: 1px solid #333;
        }

        .success-icon {
            font-size: 5rem;
            color: #ed563b;
            margin-bottom: 20px;
        }

        .payment-method-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option {
            background: #2a2a2a;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .payment-option.selected {
            border-color: #ed563b;
            background: #333;
        }

        .payment-icon {
            font-size: 24px;
            color: #ed563b;
        }

        .check-icon {
            color: #ed563b;
            display: none;
        }

        .payment-option.selected .check-icon {
            display: block;
        }

        .upi-info {
            background: #2a2a2a;
            border: 1px solid #333;
        }

        .text-primary {
            color: #ed563b !important;
        }

        .modal-content {
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ed563b, #ff8d6b);
            border: none;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(237, 86, 59, 0.4);
        }

        .order-summary {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .payment-options {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .payment-option {
            padding: 15px;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            background: #333;
        }

        .payment-option input:checked + label {
            color: #ed563b;
        }

        .order-confirmation {
            background: #2a2a2a;
            padding: 15px;
            border-radius: 10px;
        }

        .form-check-input:checked {
            background-color: #ed563b;
            border-color: #ed563b;
        }

        .place-order-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none !important;
        }
    </style>

    <!-- Add this before closing </head> tag -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <a href="index.php#products" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Products
            </a>

            <div class="order-card">
                <div class="row g-0">
                    <!-- Product Preview Side -->
                    <div class="col-lg-6">
                        <div class="product-side">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            </div>
                            <div class="product-info">
                                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                                <div class="category-badge">
                                    <i class="fas fa-tag"></i>
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Form Side -->
                    <div class="col-lg-6">
                        <div class="order-side">
                            <div class="price-tag">
                                ₹<?php echo number_format($product['price'], 2); ?>
                            </div>

                            <form id="orderForm">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                
                                <div class="quantity-control">
                                    <button type="button" class="qty-btn" onclick="updateQuantity(-1)">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                                    <button type="button" class="qty-btn" onclick="updateQuantity(1)">+</button>
                                </div>

                                <div class="price-details">
                                    <div class="price-row">
                                        <span>Subtotal</span>
                                        <span id="subtotal">₹<?php echo number_format($product['price'], 2); ?></span>
                                    </div>
                                    <div class="price-row">
                                        <span>GST (18%)</span>
                                        <span id="gst">₹<?php echo number_format($product['price'] * 0.18, 2); ?></span>
                                    </div>
                                    <div class="price-row total">
                                        <span>Total</span>
                                        <span id="total">₹<?php echo number_format($product['price'] * 1.18, 2); ?></span>
                                    </div>
                                </div>

                                <!-- Add Payment Method Selection -->
                                <div class="payment-methods mb-4">
                                    <h5>Select Payment Method</h5>
                                    <div class="payment-options">
                                        <div class="form-check payment-option">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cashPayment" value="cash" checked>
                                            <label class="form-check-label" for="cashPayment">
                                                <i class="fas fa-money-bill-wave"></i> Cash Payment
                                                <small class="d-block text-muted">Pay at reception during collection</small>
                                            </label>
                                        </div>
                                        <div class="form-check payment-option mt-3">
                                            <input class="form-check-input" type="radio" name="payment_method" id="razorpayPayment" value="razorpay">
                                            <label class="form-check-label" for="razorpayPayment">
                                                <i class="fas fa-credit-card"></i> Pay Online (Razorpay)
                                                <small class="d-block text-muted">Credit/Debit Card, Net Banking, UPI</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Order Confirmation -->
                                <div class="order-confirmation mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmOrder" required>
                                        <label class="form-check-label" for="confirmOrder">
                                            I confirm this order and agree to pay the total amount
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="place-order-btn" id="submitOrder" disabled>
                                    <i class="fas fa-shopping-cart"></i>
                                    Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade success-modal" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h4 class="mb-3">Thank you for your order!</h4>
                    <p class="mb-0">Your order has been placed successfully.</p>
                </div>
                <div class="modal-footer">
                    <a href="index.php#products" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Select Payment Method</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <div class="payment-method-card mb-3" onclick="selectPaymentMethod('cash')">
                            <div class="d-flex align-items-center p-3 rounded payment-option" data-method="cash">
                                <i class="fas fa-money-bill-wave payment-icon me-3"></i>
                                <div>
                                    <h6 class="mb-1">Cash Payment</h6>
                                    <p class="mb-0 text-muted">Pay at reception during collection</p>
                                </div>
                                <i class="fas fa-check-circle ms-auto check-icon"></i>
                            </div>
                        </div>

                        <div class="payment-method-card" onclick="selectPaymentMethod('upi')">
                            <div class="d-flex align-items-center p-3 rounded payment-option" data-method="upi">
                                <i class="fas fa-qrcode payment-icon me-3"></i>
                                <div>
                                    <h6 class="mb-1">UPI Payment</h6>
                                    <p class="mb-0 text-muted">Pay using any UPI app</p>
                                </div>
                                <i class="fas fa-check-circle ms-auto check-icon"></i>
                            </div>
                        </div>

                        <!-- UPI Section -->
                        <div id="upiSection" class="text-center mt-4" style="display: none;">
                            <div class="upi-info p-4 rounded">
                                <i class="fas fa-mobile-alt fa-3x mb-3 text-primary"></i>
                                <h6 class="mb-3">UPI Payment Details</h6>
                                <p class="mb-2">UPI ID: yourgym@upi</p>
                                <p class="mb-0 text-muted">Open your UPI app and complete the payment</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary mt-4">
                        <h6 class="mb-3">Order Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="modalSubtotal"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>GST (18%):</span>
                            <span id="modalGst"></span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top mt-2">
                            <strong>Total Amount:</strong>
                            <strong id="modalTotal" class="text-primary"></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="confirmOrder()">
                        <i class="fas fa-check-circle me-2"></i>Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productPrice = <?php echo $product['price']; ?>;
            const submitButton = document.getElementById('submitOrder');
            const confirmCheckbox = document.getElementById('confirmOrder');
            const orderForm = document.getElementById('orderForm');

            function updateQuantity(change) {
                const input = document.getElementById('quantity');
                let newValue = parseInt(input.value) + change;
                if (newValue < 1) newValue = 1;
                input.value = newValue;
                updateTotalPrice();
            }

            function updateTotalPrice() {
                const quantity = parseInt(document.getElementById('quantity').value);
                const subtotal = productPrice * quantity;
                const gst = subtotal * 0.18;
                const total = subtotal + gst;

                document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
                document.getElementById('gst').textContent = '₹' + gst.toFixed(2);
                document.getElementById('total').textContent = '₹' + total.toFixed(2);
                return total;
            }

            // Enable/disable submit button based on confirmation
            confirmCheckbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
            });

            function processRazorpayPayment(paymentData) {
                const formData = new FormData(orderForm);
                formData.append('payment_method', 'razorpay');
                formData.append('razorpay_payment_id', paymentData.razorpay_payment_id);
                formData.append('razorpay_order_id', paymentData.razorpay_order_id);
                formData.append('razorpay_signature', paymentData.razorpay_signature);

                return fetch('process_order.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json());
            }

            function processCashPayment() {
                const formData = new FormData(orderForm);
                formData.append('payment_method', 'cash');

                return fetch('process_order.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json());
            }

            // Single form submission handler
            orderForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                
                if (!confirmCheckbox.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmation Required',
                        text: 'Please confirm your order before proceeding.'
                    });
                    return;
                }

                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                try {
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                    const quantity = parseInt(document.getElementById('quantity').value);
                    const total = updateTotalPrice();

                    if (paymentMethod === 'razorpay') {
                        // Create Razorpay order
                        const orderResponse = await fetch('create_razorpay_order.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                amount: total,
                                product_id: <?php echo $product['product_id']; ?>,
                                quantity: quantity
                            })
                        }).then(res => res.json());

                        if (!orderResponse.order_id) {
                            throw new Error('Failed to create payment order');
                        }

                        // Initialize Razorpay
                        const options = {
                            key: "rzp_test_Fur0pLo5d2MztK",
                            amount: total * 100,
                            currency: "INR",
                            name: "Focus Gym",
                            description: "Product Order Payment",
                            order_id: orderResponse.order_id,
                            handler: async function(response) {
                                try {
                                    const result = await processRazorpayPayment(response);
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Payment Successful!',
                                            text: 'Your order has been placed successfully.',
                                            confirmButtonText: 'View My Orders'
                                        }).then(() => {
                                            window.location.href = 'my_orders.php';
                                        });
                                    } else {
                                        throw new Error(result.message || 'Payment processing failed');
                                    }
                                } catch (error) {
                                    throw new Error('Payment processing failed: ' + error.message);
                                }
                            },
                            prefill: {
                                name: "<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>",
                                email: "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>",
                                contact: "<?php echo isset($_SESSION['mobile_no']) ? $_SESSION['mobile_no'] : ''; ?>"
                            },
                            theme: {
                                color: "#ed563b"
                            },
                            modal: {
                                ondismiss: function() {
                                    submitButton.disabled = false;
                                    submitButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Place Order';
                                }
                            }
                        };

                        const rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        // Process cash payment
                        const result = await processCashPayment();
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed Successfully!',
                                html: `
                                    <div class="text-left">
                                        <p><strong>Order Reference:</strong> ${result.order_reference}</p>
                                        <p><strong>Total Amount:</strong> ₹${result.total_price.toFixed(2)}</p>
                                        <p class="text-muted">Please collect your order from the reception</p>
                                    </div>
                                `,
                                confirmButtonText: 'View My Orders'
                            }).then(() => {
                                window.location.href = 'my_orders.php';
                            });
                        } else {
                            throw new Error(result.message || 'Failed to place order');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to process order. Please try again.'
                    });
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Place Order';
                }
            });

            // Initialize price calculation
            document.getElementById('quantity').addEventListener('input', updateTotalPrice);
        });
    </script>

    <!-- Add these hidden fields to your form -->
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</body>
</html> 