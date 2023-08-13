<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="quotations-table">
            <thead>
            <tr>
                <th>Quotation Uuid</th>
                <th>Total Price</th>
                <th>Name</th>
                <th>Date</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quotations as $quotation)
                <tr>
                    <td>{{ $quotation->quotation_uuid }}</td>
                    <td>{{ $quotation->total_price }}</td>
                    <td>{{ $quotation->name }}</td>
                    <td>{{ $quotation->date }}</td>
                    <td>{{ $quotation->created_by }}</td>
                    <td>{{ $quotation->updated_by }}</td>
                    <td>{{ $quotation->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['quotations.destroy', $quotation->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('quotations.show', [$quotation->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('quotations.edit', [$quotation->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $quotations])
        </div>
    </div>
</div>
