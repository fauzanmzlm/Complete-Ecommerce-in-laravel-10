@extends('backend.layouts.master')

@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">Booking Lists</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if (count($orders) > 0)
                    <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Booking No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>S.N.</th>
                                <th>Order No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->user->email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->from_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->to_date)->format('d/m/Y') }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>
                                        @if ($order->status == 'pending')
                                            <span class="badge badge-warning">{{ $order->status }}</span>
                                        @elseif($order->status == 'confirmed')
                                            <span class="badge badge-primary">{{ $order->status }}</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success">{{ $order->status }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->remarks }}</td>
                                    <td>

                                        <a href="{{ route('order.show', $order->id) }}"
                                            class="btn btn-warning btn-sm float-left mr-1"
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                            title="view" data-placement="bottom"><i class="fas fa-eye"
                                                style="margin-left: -3px;"></i></a>
                                        {{-- <a href="{{route('order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a> --}}
                                        <form method="POST" action="{{ route('order.destroy', [$order->id]) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm dltBtn" data-id={{ $order->id }}
                                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                data-placement="bottom" title="Delete"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                        <hr>
                                        <select name="status" id="status" class="form-control"
                                            data-url="{{ route('order.update-status', $order->id) }}">
                                            <option value="pending"
                                                {{ $order->status == 'confirmed' || $order->status == 'completed' || $order->status == 'cancelled' ? 'disabled' : '' }}
                                                {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed"
                                                {{ $order->status == 'completed' || $order->status == 'cancelled' ? 'disabled' : '' }}
                                                {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="completed" {{ $order->status == 'cancelled' ? 'disabled' : '' }}
                                                {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'completed' ? 'disabled' : '' }}
                                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <span style="float:right">{{ $orders->links() }}</span>
                @else
                    <h6 class="text-center">No bookings found!!! Please order some equipments</h6>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
    <script>
        $('#order-dataTable').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [6]
            }]
        });

        // Sweet alert

        function deleteData(id) {

        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.dltBtn').click(function(e) {
                var form = $(this).closest('form');
                var dataID = $(this).data('id');
                // alert(dataID);
                e.preventDefault();
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        } else {
                            swal("Your data is safe!");
                        }
                    });
            })

            // function updateStatusBooking(dataUrl, selectedStatus, remarks = "") {
            //     $.ajax({
            //         url: dataUrl, // Replace with the actual endpoint to update the status
            //         type: 'POST', // Adjust the method accordingly
            //         data: {
            //             _method: 'PUT',
            //             _token: '{{ csrf_token() }}',
            //             status: selectedStatus,
            //             remarks: remarks
            //         },
            //         success: function(response) {
            //           swal(response.message);
            //           console.log('Status updated successfully');
            //         },
            //         error: function(error) {
            //           swal(response.message);
            //           console.error('Error updating status:', error);
            //         }
            //     });
            // }

            // $('#status').on('change', function() {
            //     var selectedStatus = $(this).val();
            //     var dataUrl = $(this).attr("data-url");
            //     if (selectedStatus == "completed" || selectedStatus == "cancelled") {

            //         const remarksElement = document.createElement("div");
            //         $(remarksElement).html("<textarea rows='5' id='remarks' class='form-control'></textarea>");
            //         swal({
            //             title: "Type your remarks here",
            //             content: remarksElement,
            //             buttons: [true, "Confirm"]
            //         }).then(value => {
            //             if (value) {
            //                 var remarksTA = $('#remarks').val();
            //                 console.log(remarksTA);
            //                 updateStatusBooking(dataUrl, selectedStatus, "entah");
            //             } else {
            //                 swal("You cancelled");
            //             }
            //         });
            //     } else {
            //         updateStatusBooking(dataUrl, selectedStatus, "entah");
            //     }
            // });

            $('#status').on('change', function() {
                var selectedStatus = $(this).val();
                var dataUrl = $(this).attr("data-url");
                $.ajax({
                    url: dataUrl, // Replace with the actual endpoint to update the status
                    type: 'POST', // Adjust the method accordingly
                    data: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}',
                        status: selectedStatus,
                    },
                    success: function(response) {
                      swal(response.message);
                      console.log('Status updated successfully');
                    },
                    error: function(error) {
                      swal(response.message);
                      console.error('Error updating status:', error);
                    }
                });
            });


        })
    </script>
@endpush
