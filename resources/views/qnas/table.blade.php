<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="qnas-table">
            <thead>
            <tr>
                <th>Qna Uuid</th>
                <th>Qna Categories Uuid</th>
                <th>Title</th>
                <th>Date</th>
                <th>Short Desc</th>
                <th>Description</th>
                <th>Top Blog</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($qnas as $qna)
                <tr>
                    <td>{{ $qna->qna_uuid }}</td>
                    <td>{{ $qna->qna_categories_uuid }}</td>
                    <td>{{ $qna->title }}</td>
                    <td>{{ $qna->date }}</td>
                    <td>{{ $qna->short_desc }}</td>
                    <td>{{ $qna->description }}</td>
                    <td>{{ $qna->top_blog }}</td>
                    <td>{{ $qna->status }}</td>
                    <td>{{ $qna->created_by }}</td>
                    <td>{{ $qna->updated_by }}</td>
                    <td>{{ $qna->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['qnas.destroy', $qna->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('qnas.show', [$qna->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('qnas.edit', [$qna->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $qnas])
        </div>
    </div>
</div>
