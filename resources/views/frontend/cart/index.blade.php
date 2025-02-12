@extends('frontend.layout.app')

@section('title', 'Cart')

@section('content')
    <div class="container mt-5 mb-auto" style="min-height: 70vh; display: flex; flex-direction: column;">
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <a href="{{ url()->previous() }}" style="color: var(--primary-color); text-decoration: none;">
                <i class="fas fa-arrow-left" style="font-size: 1.4rem;"></i>
            </a>
            <h3 class="" style="color: var(--primary-color); text-align: center;">Your Cart</h3>
            <div></div> <!-- Spacer to keep alignment consistent -->
        </div>

        @if ($cart && count($cart) > 0)
            <table class="table mb-auto" style="color: var(--text-color); flex-grow: 1;">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($cart as $id => $item)
                        <tr>
                            <td><img src="{{ asset($item['image']) }}" width="50"></td>
                            <td>{{ $item['name'] }}</td>
                            <td>Rs{{ $item['price'] }}</td>
                            <td>
                                <input type="number" class="form-control update-quantity" style="width: 70px;"
                                    data-id="{{ $id }}" value="{{ $item['quantity'] }}" min="1">
                            </td>
                            <td>Rs{{ $item['price'] * $item['quantity'] }}</td>
                            <td>
                                <button class="btn btn-danger remove-from-cart"
                                    data-id="{{ $id }}">Remove</button>
                            </td>
                        </tr>
                        @php $grandTotal += $item['price'] * $item['quantity']; @endphp
                    @endforeach
                </tbody>
            </table>

            <!-- Add Note Section -->
            <div class="mt-3 mb-3">
                <label for="cartNote" style="color: var(--text-color); background-color: var(--background-color)">Add a
                    Note:</label>
                <textarea id="cartNote" class="form-control" rows="3"
                    style="color: var(--text-color); background-color: var(--background-color)"></textarea>
            </div>

            <h4 class="mt-3 mb-3">Total: Rs{{ $grandTotal }}</h4>

            <!-- Order Review Modal -->

            <form id="orderForm" action="{{ route('menu.order.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="orderReviewModal" tabindex="-1" aria-labelledby="orderReviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: var(--background-color); color: var(--text-color);">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderReviewModalLabel">Order Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h5 style="color: var(--text-color);">Order Summary</h5>
                                <ul class="list-group" style="color: var(--text-color);">
                                    @foreach ($cart as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center"
                                            style="color: var(--text-color); background-color: var(--background-color)">
                                            {{ $item['name'] }} (x{{ $item['quantity'] }})
                                            <span>Rs{{ $item['price'] * $item['quantity'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <h5 class="mt-3" style="color: var(--text-color);">Total: Rs{{ $grandTotal }}</h5>
                                <div class="mt-4">
                                    <label for="paymentMethod" style="color: var(--text-color); background-color: var(--background-color)">Choose Payment Method:</label>
                                    <select id="paymentMethod" name="paymentMethod" class="form-select"
                                        style="color: var(--text-color); background-color: var(--background-color)">
                                        <option value="cash">Cash</option>
                                        <option value="online">Online (eSewa)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="color: var(--text-color);">Close</button>
                                <button type="submit" class="btn btn-primary" id="confirmOrder" style="color: var(--text-color);">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <button type="button" class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#orderReviewModal">
                Proceed to Order
            </button>
        @else
            <div class="d-flex justify-content-center align-items-center" style="height: 70vh; color: var(--text-color);">
                <div class="text-center">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h4>Your cart is empty</h4>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        // Update quantity
        $(document).on('change', '.update-quantity', function() {
            const id = $(this).data('id');
            const quantity = $(this).val();

            $.ajax({
                url: "{{ route('menu.cart.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    quantity: quantity
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        });

        // Remove from cart
        $(document).on('click', '.remove-from-cart', function() {
            const id = $(this).data('id');

            $.ajax({
                url: "{{ route('menu.cart.remove') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        });

        $('#confirmOrder').click(function() {
            const paymentMethod = $('#paymentMethod').val();
            const cartNote = $('#cartNote').val();
            const grandTotal = {{ $grandTotal ?? 0 }};
            const cart = [];

            // Collect cart data from the table rows
            $('table tbody tr').each(function() {
                const id = $(this).find('.update-quantity').data('id');
                const name = $(this).find('td:nth-child(2)').text().trim();
                const price = parseFloat($(this).find('td:nth-child(3)').text().replace('Rs', '').trim());
                const quantity = parseInt($(this).find('.update-quantity').val());
                const image = $(this).find('td:first-child img').attr('src');
                // Only push valid items
                if (id && !isNaN(price) && !isNaN(quantity)) {
                    cart.push({
                        id,
                        name,
                        price,
                        quantity,
                        image
                    });
                }
            });

            if (cart.length === 0) {
                toastr.error('Your cart is empty!');
                return;
            }

            // $.ajax({
            //     url: "{{ route('menu.order.store') }}",
            //     type: "POST",
            //     data: {
            //         _token: "{{ csrf_token() }}",
            //         paymentMethod: paymentMethod,
            //         cart: cart,
            //         grandTotal: grandTotal,
            //         note: cartNote
            //     },
            //     success: function(response) {
            //         toastr.success(response.message + " Order ID: " + response.order_id);
            //         // window.location.href ="{{ route('menu.order.history') }}";
            //     },
            //     error: function(response) {
            //         console.log(response); //
            //         toastr.error('Something went wrong!');
            //         // window.location.reload();
            //     }
            // });
        });
    </script>
@endsection
