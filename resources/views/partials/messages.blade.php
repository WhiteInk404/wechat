@if(count($errors)>0)
<div class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <ul>
    @foreach($errors->all('<li>:message</li>') as $error)
        {!! $error !!}
    @endforeach
    </ul>
</div>
@endif

@if(Session::get('success', false))
    <?php $data = Session::get('success');?>
    @if (is_array($data))
        @foreach ($data as $msg)
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $msg }}
            </div>
        @endforeach
    @else
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $data }}
        </div>
    @endif
@endif
