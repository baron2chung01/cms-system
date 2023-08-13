<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="qna-categories-table">
            <thead>
            <tr>
                <th>Categories Uuid</th>
                <th>Parents Uuid</th>
                <th>Code</th>
                <th>Name</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($qnaCategories as $qnaCategory)
                <tr>
                    <td>{{ $qnaCategory->categories_uuid }}</td>
                    <td>{{ $qnaCategory->parents_uuid }}</td>
                    <td>{{ $qnaCategory->code }}</td>
                    <td>{{ $qnaCategory->name }}</td>
                    <td>{{ $qnaCategory->status }}</td>
                    <td>{{ $qnaCategory->created_by }}</td>
                    <td>{{ $qnaCategory->updated_by }}</td>
                    <td>{{ $qnaCategory->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['qnaCategories.destroy', $qnaCategory->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('qnaCategories.show', [$qnaCategory->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('qnaCategories.edit', [$qnaCategory->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $qnaCategories])
        </div>
    </div>
</div>
