<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="shops-table">
            <thead>
            <tr>
                <th>Shops Uuid</th>
                <th>Regions Uuid</th>
                <th>Name</th>
                <th>Shops Code</th>
                <th>Phone</th>
                <th>Whatsapp</th>
                <th>Contact Person</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Desc</th>
                <th>Remarks</th>
                <th>Payment Methods</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($shops as $shop)
                <tr>
                    <td>{{ $shop->shops_uuid }}</td>
                    <td>{{ $shop->regions_uuid }}</td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->shops_code }}</td>
                    <td>{{ $shop->phone }}</td>
                    <td>{{ $shop->whatsapp }}</td>
                    <td>{{ $shop->contact_person }}</td>
                    <td>{{ $shop->address }}</td>
                    <td>{{ $shop->latitude }}</td>
                    <td>{{ $shop->longitude }}</td>
                    <td>{{ $shop->desc }}</td>
                    <td>{{ $shop->remarks }}</td>
                    <td>{{ $shop->payment_methods }}</td>
                    <td>{{ $shop->created_by }}</td>
                    <td>{{ $shop->updated_by }}</td>
                    <td>{{ $shop->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['shops.destroy', $shop->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('shops.show', [$shop->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('shops.edit', [$shop->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $shops])
        </div>
    </div>
</div>
