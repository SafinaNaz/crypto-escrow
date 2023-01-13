@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
            <h2>Support Tickets</h2>
            <a class="btn btn-primary btn-mbl" href="{{url('support-ticket/create')}}">Create Ticket</a>

        </div>
        <div class="gaps-2x"></div>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th>Last Reply</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($tickets) > 0)
                    @foreach($tickets as $row)
                    <tr>
                        <td>
                            {{$row->subject}}
                        </td>
                        <td>{!!$row->ticketUser->full_name()!!}</td>
                        <td>
                            {!!$row->status()!!}
                        </td>
                        <td>
                            <strong>{{$row->get_date()}}</strong>
                        </td>
                        <td>
                            <a href="{{url('support-ticket/view',['id' => encode($row->id)])}}">View</a>
                        </td>

                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">No record found.</td>
                    </tr>
                    @endif
                </tbody>

            </table>
            <nav class="pull-right">{!! $tickets->links( "pagination::bootstrap-4") !!}</nav>
        </div>
    </div><!-- .user-content -->

    @endsection