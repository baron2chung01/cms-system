<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="special-promotions-table">
            <thead>
            <tr>
                <th>Special Promotion Uuid</th>
                <th>Code</th>
                <th>Name</th>
                <th>Short Description</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($specialPromotions as $specialPromotion)
                <tr>
                    <td>{{ $specialPromotion->special_promotion_uuid }}</td>
                    <td>{{ $specialPromotion->code }}</td>
                    <td>{{ $specialPromotion->name }}</td>
                    <td>{{ $specialPromotion->short_description }}</td>
                    <td>{{ $specialPromotion->description }}</td>
                    <td>{{ $specialPromotion->status }}</td>
                    <td>{{ $specialPromotion->created_by }}</td>
                    <td>{{ $specialPromotion->updated_by }}</td>
                    <td>{{ $specialPromotion->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['specialPromotions.destroy', $specialPromotion->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('specialPromotions.show', [$specialPromotion->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('specialPromotions.edit', [$specialPromotion->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $specialPromotions])
        </div>
    </div>
</div>
