@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

<link href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/5.0.1/css/fixedColumns.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

<div class="box-main p-3 bg-white margin-15-b radius8">
  <div class="row justify-content-center mt-4 book-bg">  
    <table id="example" class="stripe row-border order-column nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Sr.No.</th>
          <th>Patient Name</th>
          <th>Plan Type</th>
          <th>Transaction ID</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Transaction Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $payment)
        <tr>
          <td>{{ $loop->iteration }}</td> <!-- Loop index -->
          <td>{{ $payment->first_name }} {{ $payment->last_name }}</td>
          <td>{{ $payment->plan_type ?? 'N/A' }}</td> <!-- Show 'N/A' if plan_type is null -->
          <td>{{ $payment->txn_id }}</td>
          <td>{{'$'}}{{ $payment->txn_amount }}</td>
          <td>
            @if($payment->txn_status == 'succeeded')
              <span class="badge bg-success">Success</span>
            @else
              <span class="badge bg-danger">Failed</span>
            @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($payment->txn_time)->format('M d, Y H:i') }}</td>
          <td>
            <ul class="d-flex actions bg-none">
              <li><a href="{{ route('payment.view', Crypt::encrypt($payment->id)) }}">View</a></li>
            </ul>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>  
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/dataTables.fixedColumns.js"></script>

<script type="text/javascript">
  new DataTable('#example', {
    // fixedColumns: {
    //     start: 1
    // },
    paging: false,
    scrollCollapse: true,
    scrollX: true
  });
</script>

</body>
</html>
@endsection
